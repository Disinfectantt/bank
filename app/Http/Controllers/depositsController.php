<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\deposit;
use App\Events\depositChange;
use Illuminate\Support\Facades\Auth;

class depositsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Auth::check() && Auth::user()->role >= 5 ? deposit::paginate(10)->onEachSide(2) : response()->json(['status' => false], 401);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'surname' => 'required|max:50|min:4',
            'name' => 'required|max:50|min:4',
            'patronymic' => 'required|max:50|min:4',
            'period' => 'required|integer|between:1,72',
            'sum' => 'required|integer|between:1,500000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors(),
            ], 400);
        }
        if(Auth::check()){
            $user_id=Auth::user()->id;
        }else{
            $user_id = null;
        }
        $data = deposit::create([
            'surname' => $request->surname,
            'name' => $request->name,
            'patronymic' => $request->patronymic,
            'period' => $request->period,
            'sum' => $request->sum,
            'user_id' => $user_id
        ]);

        broadcast(new depositChange($data, 'new'));

        return response()->json([
            'status' => true,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (Auth::check() && Auth::user()->id != $id){
            return response()->json(['status' => false], 401);
        }

        return deposit::where('user_id', '=', $id)->get();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (Auth::check() && Auth::user()->role >= 5) {
            $data = [
                'surname' => $request->surname,
                'name' => $request->name,
                'patronymic' => $request->patronymic,
                'period' => $request->period,
                'sum' => $request->sum
            ];
            deposit::where('id', $id)->update($data);
            $data = deposit::where('id', $id)->get();
            broadcast(new depositChange($data, 'update'));
        } else {
            return response()->json(['status' => false], 401);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (Auth::check() && Auth::user()->role >= 5) {
            deposit::destroy($id);
            broadcast(new depositChange($id, 'destroy'))->toOthers();
        } else {
            return response()->json(['status' => false], 401);
        }
    }
}

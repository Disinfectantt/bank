<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\credit;
use App\Events\creditChange;

class creditsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Auth::check() && Auth::user()->role >= 5 ? credit::paginate(10)->onEachSide(2) : response()->json(['status' => false], 401);
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
            'region' => 'required',
            'surname' => 'required|max:50|min:4',
            'name' => 'required|max:50|min:4',
            'patronymic' => 'required|max:50|min:4',
            'period' => 'required|integer|between:1,36',
            'sum' => 'required|integer|between:1,500000',
            'passport' => 'required|max:10|min:10',
            'insurance' => 'string|in:true,false',
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
        $data = credit::create([
            'region' => $request->region,
            'surname' => $request->surname,
            'name' => $request->name,
            'patronymic' => $request->patronymic,
            'passport' => $request->passport,
            'period' => $request->period,
            'sum' => $request->sum,
            'insurance' => $request->insurance,
            'user_id' => $user_id
        ]);

        broadcast(new creditChange($data, 'new'));

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

        return credit::where('user_id', '=', $id)->get();
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
                'region' => $request->region,
                'surname' => $request->surname,
                'name' => $request->name,
                'patronymic' => $request->patronymic,
                'passport' => $request->passport,
                'period' => $request->period,
                'sum' => $request->sum,
                'insurance' => $request->insurance
            ];
            credit::where('id', $id)->update($data);
            $feedback = credit::where('id', $id)->get();
            broadcast(new creditChange($feedback, 'update'));
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
            credit::destroy($id);
            broadcast(new creditChange($id, 'destroy'))->toOthers();
        } else {
            return response()->json(['status' => false], 401);
        }
    }
}

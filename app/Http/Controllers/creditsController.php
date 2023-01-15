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
        return Auth::check() && Auth::user()->role == 'admin' ? credit::all() : response()->json(['status' => false], 401);
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
            'reg' => 'required',
            'surname' => 'required|max:50|min:4',
            'name' => 'required|max:50|min:4',
            'otc' => 'required|max:50|min:4',
            'date' => 'required|integer|between:1,36',
            'sum' => 'required|integer|between:1,500000',
            'document' => 'required|max:10|min:10',
            'insurance' => 'string|in:true,false',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors(),
            ], 400);
        }

        $data = credit::create([
            'region' => $request->reg,
            'surname' => $request->surname,
            'name' => $request->name,
            'patronymic' => $request->otc,
            'passport' => $request->document,
            'period' => $request->date,
            'sum' => $request->sum,
            'insurance' => $request->insurance,
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
        //
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
        if (Auth::check() && Auth::user()->role == 'admin') {
            $data = [
                'region' => $request->reg,
                'surname' => $request->surname,
                'name' => $request->name,
                'patronymic' => $request->otc,
                'passport' => $request->document,
                'period' => $request->date,
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
        if (Auth::check() && Auth::user()->role == 'admin') {
            credit::destroy($id);
            broadcast(new creditChange($id, 'destroy'))->toOthers();
        } else {
            return response()->json(['status' => false], 401);
        }
    }
}

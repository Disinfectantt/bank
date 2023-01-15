<?php

namespace App\Http\Controllers;

use App\Models\feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Events\feedbackChange;

class feedbackController extends Controller
{
    /**
     * Display a listing of the resource.
     * 
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Auth::check() && Auth::user()->role == 'admin' ? feedback::all() : response()->json(['status' => false], 401);
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
            'tel' => 'required|digits_between:11,11',
            'surname' => 'required|max:50|min:4',
            'name' => 'required|max:50|min:4',
            'otc' => 'required|max:50|min:4'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors(),
            ], 400);
        }

        $feedback = feedback::create([
            'tel' => $request->tel,
            'region' => $request->reg,
            'surname' => $request->surname,
            'name' => $request->name,
            'patronymic' => $request->otc
        ]);

        broadcast(new feedbackChange($feedback, 'new'));

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
                'tel' => $request->tel,
                'region' => $request->reg,
                'surname' => $request->surname,
                'name' => $request->name,
                'patronymic' => $request->otc
            ];
            feedback::where('id', $id)->update($data);
            $feedback = feedback::where('id', $id)->get();
            broadcast(new feedbackChange($feedback, 'update'));
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
            feedback::destroy($id);
            broadcast(new feedbackChange($id, 'destroy'))->toOthers();
        } else {
            return response()->json(['status' => false], 401);
        }
    }
}

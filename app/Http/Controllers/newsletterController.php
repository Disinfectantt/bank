<?php

namespace App\Http\Controllers;

use App\Models\newsletters;
use App\Mail\newsletter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Events\newsletterChange;

class newsletterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Auth::check() && Auth::user()->role >= 5 ? newsletters::paginate(10)->onEachSide(2) : response()->json(['status' => false], 401);
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
            'email' => 'required|email'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors(),
            ], 400);
        }

        Mail::to($request->email)->send(new newsletter());
        if(Auth::check()){
            $user_id=Auth::user()->id;
        }else{
            $user_id = null;
        }
        $data = newsletters::create([
            'email' => $request->email,
            'user_id' => $user_id
        ]);

        broadcast(new newsletterChange($data, 'new'));

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

        return newsletters::where('user_id', '=', $id)->get();
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
                'email' => $request->email
            ];
            newsletters::where('id', $id)->update($data);
            $data = newsletters::where('id', $id)->get();
            broadcast(new newsletterChange($data, 'update'));
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
            newsletters::destroy($id);
            broadcast(new newsletterChange($id, 'destroy'))->toOthers();
        } else {
            return response()->json(['status' => false], 401);
        }
    }
}

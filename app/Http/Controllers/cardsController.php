<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\card;
use App\Events\cardChange;
use Illuminate\Support\Facades\Auth;

class cardsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Auth::check() && Auth::user()->role == 'admin' ? card::all() : response()->json(['status' => false], 401);
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
            'otc' => 'required|max:50|min:4',
            'card' => 'required',
            'tel' => 'required|digits_between:11,11',
            'credit' => 'required|integer|between:0,100000',
            'document' => 'required|max:10|min:10',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors(),
            ], 400);
        }

        $data = card::create([
            'surname' => $request->surname,
            'name' => $request->name,
            'patronymic' => $request->otc,
            'passport' => $request->document,
            'card' => $request->card,
            'tel' => $request->tel,
            'card_limit' => $request->credit,
        ]);

        broadcast(new cardChange($data, 'new'));

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
        if (Auth::check() && Auth::user()->role == '1') {
            $data = [
                'surname' => $request->surname,
                'name' => $request->name,
                'patronymic' => $request->otc,
                'passport' => $request->document,
                'card' => $request->card,
                'tel' => $request->tel,
                'card_limit' => $request->credit
            ];
            card::where('id', $id)->update($data);
            $data = card::where('id', $id)->get();
            broadcast(new cardChange($data, 'update'));
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
        if (Auth::check() && Auth::user()->role == '1') {
            card::destroy($id);
            broadcast(new cardChange($id, 'destroy'))->toOthers();
        } else {
            return response()->json(['status' => false], 401);
        }
    }
}

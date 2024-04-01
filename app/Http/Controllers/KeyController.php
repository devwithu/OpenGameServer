<?php

namespace App\Http\Controllers;

use App\Models\Key;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class KeyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $key = new Key;
        $key->priv_key = Str::orderedUuid();
        $key->pub_key = Str::orderedUuid();
        $key->phone = $request->phone;

        $key->save();
        $key->setHidden(['id','updated_at','created_at']);

        return response()->json(
            array(
                'status' => "Success",
                'code' => 200,
                'message' => 'OK',
                'data' => $key
            ), 200, [], JSON_UNESCAPED_UNICODE
        );


    }

    /**
     * Display the specified resource.
     */
    public function show(Key $key)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Key $key)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Key $key)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Key $key)
    {
        //
    }
}

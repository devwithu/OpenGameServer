<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Key;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CouponController extends Controller
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
        $key = Key::where('priv_key', $request-> priv_key)->first(); // model or null
        if (!$key) {
            return response()->json(
                array(
                    'status' => "Error",
                    'code' => 404,
                    'message' => 'Key Not Found',
                    'data' => null
                ), 404, [], JSON_UNESCAPED_UNICODE
            );
        }

        $coupon = new Coupon;
        $coupon->key_id = $key->id;
        $coupon->coupon_no = strtoupper(Str::random(4) . '-' . Str::random(4) . '-' . Str::random(4));
        $coupon->product_id = $request->product_id;
        $coupon->permanent = $request->permanent;

        $coupon->save();
        $coupon->setHidden(['id','updated_at','created_at','key_id']);

        return response()->json(
            array(
                'status' => "Success",
                'code' => 200,
                'message' => 'OK',
                'coupon_no' => $coupon->coupon_no
            ), 200, [], JSON_UNESCAPED_UNICODE
        );

    }


    public function useCoupon(Request $request)
    {
        $key = Key::where('priv_key', $request-> priv_key)->first(); // model or null
        if (!$key) {
            return response()->json(
                array(
                    'status' => "Error",
                    'code' => 200,
                    'message' => 'Key Not Found',
                    'data' => null
                ), 200, [], JSON_UNESCAPED_UNICODE
            );
        }

        $coupon = Coupon::where('key_id', $key->id)->where( 'coupon_no', strtoupper($request->coupon_no) )->first(); // model or null
        if (!$coupon) {
            return response()->json(
                array(
                    'status' => "Error",
                    'code' => 200,
                    'message' => 'Coupon Not Found',
                    'data' => null
                ), 200, [], JSON_UNESCAPED_UNICODE
            );
        }

        $key->count = $key->count + 1;
        $key->update();

        if( ! $coupon->permanent)
            $coupon->delete();

        $coupon->setHidden(['id','updated_at','created_at','key_id', 'coupon_id']);

        return response()->json(
            array(
                'status' => "Success",
                'code' => 200,
                'message' => 'OK',
                'product_id' => $coupon->product_id
            ), 200, [], JSON_UNESCAPED_UNICODE
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Coupon $coupon)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Coupon $coupon)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Coupon $coupon)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Coupon $coupon)
    {
        //
    }
}

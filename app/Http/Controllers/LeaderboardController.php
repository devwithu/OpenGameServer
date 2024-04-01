<?php

namespace App\Http\Controllers;

use App\Models\Leaderboard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;

class LeaderboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($priv_key, $start, $end)
    {
        //Route::get('lb/gets/{priv_key}/{start}/{end}', 'App\Http\Controllers\LeaderboardController@index');

        if($end - $start +1 > 100) {
            $end = $start + 99;
        }
        $results = Redis::zrevrange($priv_key, $start, $end, true);
        return response()->json(
            array(
                'status' => "Success",
                'code' => 200,
                'message' => 'OK',
                'data' => $results
            ), 200, [], JSON_UNESCAPED_UNICODE
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $leaderboard = new Leaderboard;
        $leaderboard->priv_key = Str::orderedUuid();
        $leaderboard->pub_key = Str::orderedUuid();

        $leaderboard->save();
        $leaderboard->setHidden(['id','updated_at','created_at','pub_key']);

        return response()->json(
            array(
                'status' => "Success",
                'code' => 200,
                'message' => 'OK',
                'data' => $leaderboard
            ), 200, [], JSON_UNESCAPED_UNICODE
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($pub_Key,$user_id)
    {
        //Route::get('lb/get/{pub_Key}/{user_id}', 'App\Http\Controllers\LeaderboardController@show');

        $score = Redis::zScore($pub_Key, $user_id);

        if ($score > 0) {
            return response()->json(
                array(
                    'status' => "Success",
                    'code' => 200,
                    'message' => 'OK',
                    'data' => $score
                ), 200, [], JSON_UNESCAPED_UNICODE
            );


        }

        return response()->json(
            array(
                'status' => "Error",
                'code' => 404,
                'message' => 'There is no data',
                'data' => null
            ), 404, [], JSON_UNESCAPED_UNICODE
        );

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Leaderboard $leaderboard)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($priv_key, $user_id, $score)
    {
        //Route::get('lb/{priv_key}/set/{user_id}/{score}', 'App\Http\Controllers\LeaderboardController@update');

        $leaderboard = Leaderboard::where('priv_key', $priv_key)->first();

        if (null == $leaderboard) {
            return response()->json(
                array(
                    'status' => "Error",
                    'code' => 404,
                    'message' => 'There is no priv_key',
                    'data' => null
                ), 404, [], JSON_UNESCAPED_UNICODE
            );
        }

        $leaderboard->touch(); // for updated_at

        //$user_id = Str::of($user_id)->limit(100);
        $user_id = Str::limit($user_id, 100);


        if ($score > Redis::zScore($priv_key, $user_id))
            Redis::zAdd($priv_key, $score, $user_id);

        return response()->json(
            array(
                'status' => "Success",
                'code' => 200,
                'message' => 'OK',
                'data' => $score
            ), 200, [], JSON_UNESCAPED_UNICODE
        );

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($priv_key, $user_id)
    {
        //Route::get('lb/del/{priv_key}/{user_id}', 'App\Http\Controllers\LeaderboardController@destroy');

        $count = Redis::zRem($priv_key, $user_id);

        if ($count > 0) {
            return response()->json(
                array(
                    'status' => "Success",
                    'code' => 200,
                    'message' => 'OK',
                    'data' => $count
                ), 200, [], JSON_UNESCAPED_UNICODE
            );
        }

        return response()->json(
            array(
                'status' => "Fail",
                'code' => 404,
                'message' => 'There is no data',
                'data' => null
            ), 404, [], JSON_UNESCAPED_UNICODE
        );


    }


    public function remove($priv_key)
    {
        $deletedRows = Leaderboard::where('priv_key', $priv_key)->delete();

        if ($deletedRows > 0){
            return response()->json(
                array(
                    'status' => "Success",
                    'code' => 200,
                    'message' => 'OK',
                    'data' => null
                ), 200, [], JSON_UNESCAPED_UNICODE
            );
        }

        return response()->json(
            array(
                'status' => "Fail",
                'code' => 404,
                'message' => 'There is no data for delete',
                'data' => null
            ), 404, [], JSON_UNESCAPED_UNICODE
        );

    }


    public function clear($priv_key)
    {
        //Route::get('lb/clear/{priv_key}', 'App\Http\Controllers\LeaderboardController@clear');

        $count = Redis::zRem($priv_key, '*');

        if ($count > 0) {
            return response()->json(
                array(
                    'status' => "Success",
                    'code' => 200,
                    'message' => 'OK',
                    'data' => $count
                ), 200, [], JSON_UNESCAPED_UNICODE
            );
        }

        return response()->json(
            array(
                'status' => "Fail",
                'code' => 404,
                'message' => 'There is no data',
                'data' => null
            ), 404, [], JSON_UNESCAPED_UNICODE
        );

    }
}

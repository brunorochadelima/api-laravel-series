<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Series;
use App\Http\Controllers\Api\SeriesController;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

/* |-------------------------------------------------------------------------- | API Routes |-------------------------------------------------------------------------- | | Here is where you can register API routes for your application. These | routes are loaded by the RouteServiceProvider within a group which | is assigned the "api" middleware group. Enjoy building your API! | */

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// rota manual
Route::get('/series', [SeriesController::class , 'index']);

// rotas automáticas
Route::apiResources([
    '/series' => SeriesController::class]);

//Login
// Route::post('/login', function (Request $request) {
//     $credentials = $request->only(['email', 'password']);

//     if (Auth::attempt($credentials) === false) {
//         return response()->json('Unauthorized', 401);
//     }

//     $user = Auth::user();
//      dd($user);

// });

//Login
Route::post('/login', function (Request $request) {

    $credentials = $request->only(['email', 'password']);
    
    $user = User::where('email', $credentials['email'])->first();
    $password = User::where('password', $credentials['password'])->first();

    // if(Auth::attempt($credentials) === false) {
    //     return response()->json('Unauthorized', 401);
    // }
    
    if(!$user || !$password) {
        return response()->json('Unauthorized', 401);
    }
        
    dd($user);


 

});
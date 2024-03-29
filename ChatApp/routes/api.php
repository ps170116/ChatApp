<?php

use App\Models\friend;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/sanctum/token', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $user = User::where('email', $request->email)->first();

    if (! $user || ! Hash::check($request->password, $user->password)) {
        throw ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect.'],
        ]);
    }

    return $user->createToken('authToken')->plainTextToken;
});

Route::middleware('auth:sanctum')->get('/getfriends', function (Request $request) {
    $user = $request->user();
    $friends = friend::where([['user_id', '=', $user->id]])->get();
    return $friends;
});

Route::middleware('auth:sanctum')->post('/chat', function (Request $request) {
    $request->validate([
        'chat_id' => 'required',        
    ]);
    $chat = Task::where([['project_id' , '=' , $request->chat_id]])->get();
    return $chat;
   
});

Route::post('/getname', function (Request $request) {
    $request->validate([
        'user_id' => 'required',        
    ]);
    $user = User::where([['id', '=', $request->user_id]])->first();
    return $user->name;
});
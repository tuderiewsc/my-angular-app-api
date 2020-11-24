<?php

namespace App\Http\Controllers;

use App\User;
use Validator;
use Auth;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function login()
    {
        $validator = Validator::make(request()->all(), [
            'email' => 'required',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response(['status' => 'error', 'message' => $validator->errors()], 402);
        }

        if (\Auth::validate(['email' => request('email'), 'password' => request('password')])) {
            return User::where('email', request('email'))->first();
        }

        return response(['status' => 'error', 'message' => 'password or email not validate'], 404);
    }

    public function register()
    {
        $validator = Validator::make(request()->all(), [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
           'password' => 'required|min:6|confirmed'
        ]);

        if ($validator->fails()) {
            return response(['status' => 'error', 'message' => $validator->errors()], 400);
        }

        $user = User::create([
            'name' => request('name'),
            'email' => request('email'),
            'password' => bcrypt(request('password')),
        ]);

        return $user;
    }


    public function update_user(Request $request, $id){
        //$user = User::findOrFail($request->user_id);
        $user = User::findOrFail($id);

        $array = [];
        $res = $user->update(array_merge($array, [
            'name'=> $request->name,
            'gender'=> $request->gender,
            'profilepic'=> $request->profilepic
        ]));
        if ($res){
            return response()->json(['result' => 'update success']);
        }else{
            return response()->json(['result' => 'update error']);
        }

    }
	
    public function get_user($id){
        $user = User::find($id);
		return $user;
    }
	
    public function auth_api(Request $request){
		return $request->user();
    }
	
	
	
	
	
	
	
}

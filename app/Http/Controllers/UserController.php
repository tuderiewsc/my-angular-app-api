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
            $user = User::where('email', request('email'))->first();
            $id = $user->id;
            $encrypted_id = $this->encrypt_decrypt('encrypt', $id);

            $info = array(
                'uid' => $encrypted_id
            );

            // setcookie("userToken", serialize($info), time()+1800 , '/', '' , true , true );  /* expire in 30 mins */

            // return response()->json([
            //     'token_name' => 'userToken',
            //     'token_value' => serialize($info),
            //     'token_type' => 'Bearer'
            // ]);

            return User::where('email', request('email'))->first();
        }

        return response(['status' => 'error', 'message' => 'password or email not validate'], 404);
    }

    function encrypt_decrypt($action, $string) {
        $output = false;
        $encrypt_method = "AES-256-CBC";
        $secret_key = 'H+MbQeThWmYq3t6w9z$C&F)J@NcRfUjXn2r5u7x!A%D*G-KaPdSgVkYp3s6v9y/B';
        $secret_iv = 'RfUjXn2r5u8x/A?D(G-KaPdSgVkYp3s6v9y$B&E)H@MbQeThWmZq4t7w!z%C*F-J';
        $key = hash('sha256', $secret_key);
    // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
        $iv = substr(hash('sha256', $secret_iv), 0, 16);
        if ( $action == 'encrypt' ) {
            $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
            $output = base64_encode($output);
        } else if( $action == 'decrypt' ) {
            $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        }
        return $output;
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

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Notifications\LoginNeedsVerification;

class LoginController extends Controller
{
    //
    public function submit(Request $request)
    {
        //validate phone number regex:/^([0-9\s\-\+\(\)]*)$/
        //1|99r7lNbiLKAHqr0ybpHUXqI3ii4gUqLPy3iMyNWX1d94a58b
        $request->validate([
            'phone' => 'required|numeric|min:10'
        ]);

        //find or create a user model
        $phone = $request->phone;
        $phone = "+52" . $phone;
        $user = User::firstOrCreate([
            'phone' => $phone
        ]);

        if(!$user){
            return response()->json([
                'message' => 'Could not process a user with that phone number.'
            ], 401);
        }

        //send user a one time  user code
        $user->notify(new LoginNeedsVerification());


        //return a response
        return response()->json([
            'message' => 'A one time code has been sent to your phone number.'
        ], 200);
    }


    public function verify(Request $request)
    {

        //validate the incomming request.
        $request->validate([
            'phone' => 'required|numeric|min:10',
            'login_code' => 'required|numeric|between:111111,999999'
        ]);

        $phone = $request->phone;
        $phone = "+52" . $phone;

        //find the user
        $user = User::where('phone', $phone)->where('login_code', $request->login_code)->first();

        //is the code provided the same as the one in the database?
        //if yes, return a token
        if ($user){

            $user->update([
                'login_code' => null
            ]);


            return $user->createToken($request->login_code)->plainTextToken;
        }
        //if no, return an error message
        return response()->json([
            'message' => 'Could not verify the code provided.'
        ], 401);
    }

}

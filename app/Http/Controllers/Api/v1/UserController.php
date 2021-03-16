<?php


namespace App\Http\Controllers\Api\v1;


use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'string|required',
            'last_name' => 'string|required',
            'phone' => 'string|required|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|string'
        ]);

        if($validator->fails())
        {
            return response()->json(['error' => $validator->errors()], 422);
        }else{
            try{
                $hashed_password = app('hash')->make($request->password);
                User::create(
                    [
                        'first_name' => $request->first_name,
                        'last_name' => $request->last_name,
                        'email' => $request->email,
                        'phone' => $request->phone,
                        'password' => $hashed_password,
                    ]
                );
                return response()->json(['success' => $request->first_name.' '.$request->last_name.' created!'], 200);
            }catch (\Exception $e)
            {
                return response()->json(['error' => $e->getMessage()], 422);
            }
        }
    }

    public function authenticate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required'
        ]);

        if($validator->fails())
        {
            return response()->json(['error' => $validator->errors()], 422);
        }else{
            $user = User::where('email', $request->email)->first();

            if(Hash::check($request->password, $user->password))
            {
                $apikey = base64_encode(str_random(40));
                User::where('email', $request->email)->update(['api_key' => $apikey]);;
                return response()->json(['status' => 'success','api_key' => $apikey]);
            }else{
                return response()->json(['status' => 'fail'],401);
            }
        }
    }

    public function recoverPassword(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if($user)
        {
            if($user->api_key == $request->header('Authorization'))
            {
                $new_password = str_random();
                $hashed_password = app('hash')->make($new_password);
                $user->update(['password' => $hashed_password]);

                return response()->json(['success' => 'New password: '. $new_password.' has been set for '.$user->first_name.' '.$user->last_name], 200);
            }else{
                return response()->json(['error' => 'wrong email'], 422);
            }
        }else{
            return response()->json(['error' => 'wrong email'], 422);
        }
    }
}

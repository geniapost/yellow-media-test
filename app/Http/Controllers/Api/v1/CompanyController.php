<?php


namespace App\Http\Controllers\Api\v1;


use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CompanyController extends Controller
{
    public function show(Request $request)
    {
        $user = User::where('api_key', $request->header('Authorization'))->first();
        if($user)
        {
            $validator = Validator::make($request->all(), [
                'title' => 'string|required',
                'phone' => 'string|required',
                'description' => 'string|required'
            ]);

            if($validator->fails())
            {
                return response()->json(['error' => $validator->errors()], 422);
            }else{
                $user_companies = $user->companies->toArray();

                return response()->json(['success' => $user_companies], 200);
            }
        }else{
            return response()->json(['error' => 'Authorization error'], 401);
        }
    }

    public function store(Request $request)
    {
        $user = User::where('api_key', $request->header('Authorization'))->first();
        if($user)
        {
            $validator = Validator::make($request->all(), [
                'title' => 'string|required',
                'phone' => 'string|required',
                'description' => 'string|required'
            ]);

            if($validator->fails())
            {
                return response()->json(['error' => $validator->errors()], 422);
            }else{
                $new_company = Company::create([
                    'title' => $request->title,
                    'phone' => $request->phone,
                    'description' => $request->description
                ]);
                $user->companies()->save($new_company);

                return response()->json(['success' => 'Company '.$new_company->title.' added to user '.$user->first_name.' '.$user->last_name], 200);
            }
        }else{
            return response()->json(['error' => 'Authorization error'], 401);
        }    }
}

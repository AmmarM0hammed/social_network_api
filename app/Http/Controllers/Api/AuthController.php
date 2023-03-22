<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\User;
use Validator;

class AuthController extends Controller
{

    public function user()
    {
        $responce = [
            "success"=>true,
            'user'=> auth()->user()
        ];
        return response()->json($responce,200);
    }
    // =========== Register ===========
    public function register(Request $request){
        $validated = Validator::make($request->all(),[
            'name' => 'required|max:255',
            'email' => 'required|unique:users|max:255|email',
            "password"=>"required|regex:/[a-z]/|regex:/[A-Z]/|regex:/[0-9]/|regex:/[@$!%*#?&]/",
            "confirm_password"=>"required|same:password",
        ]);

        if($validated->fails()){
            $responce = [
                "success"=>false,
                'message'=> $validated->errors()
            ];
            return response()->json($responce,400);
        }

       $user = User::create([
            'name'=> $request->name,
            'email'=> $request->email,
            'password'=> Hash::make($request->password),
        ]);
        $token = $user->createToken('secret')->plainTextToken;
        $responce = [
            'success'=>true,
            'token'=>$token,
            'user'=>$user,
            'message'=> 'Register success'
        ];
        return response()->json($responce,200);

    }

     // =========== Login ===========
    public function login(Request $request){
        $form_data = [
            'email'=>$request->email ,
            'password'=>$request->password
        ];

        if(Auth::attempt($form_data)){
            $user = Auth::user();
            $token = $user->createToken('secret')->plainTextToken;
            $responce = [
                'success'=>true,
                'token'=>$token,
                'user'=>$user,
                'message'=> 'Login success'
            ];
            return response()->json($responce,200);
        }

        else{
            $responce = [
                'success'=>false,
                'message'=> 'User Login Faild'
            ];
            return response()->json($responce,400);

        }
    }

    public function logout(){
       auth()->user()->tokens()->delete();
        $responce = [
            'message'=> 'Logout Success'
        ];
        return response()->json($responce,200);
    }
    public function update(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'phone' => 'numeric|min:13',
            'address' => 'string|max:255',
            'photo'=>"required|image|mimes:png,jpg,jpeg|max:500000",
        ]);
        if ($validated->fails()) {
            $responce = [
                "success" => false,
                'message' => $validated->errors()
            ];
            return response()->json($responce, 400);
        }

        
        $image = $request->file('photo');
        $new_name = Str::ulid() . "." . $image->getClientOriginalExtension();
        $image->move(public_path('/profile'), $new_name);

        $user =Auth::user();
        $user->name = $request->name;
        $user->photo = $new_name;
        $user->phone = $request->phone;
        $user->address = $request->address;
        $user->save();

        return response([
            'message' => 'User updated.',
            'user' => auth()->user()
        ], 200);
    }
}

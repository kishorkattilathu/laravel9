<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\Customers;


class Customer_controller extends Controller
{
    public function index(){
        return view("login");
    }
    public function register(){
        return view("registration");
    }
    public function signup(Request $request){
        
      $rules = [
        'name' => 'required|string|max:255',
        'email' => 'unique:customers|required|email',
        'password' => 'required|min:3|string',
     ];

     $validators = Validator::make($request->all(),$rules);
     if ($validators->fails()) {
        return response()->json(['status'=>false,'errors'=>$validators->errors()],422);
     }
     $return_array = ['status'=>false,'message'=>''];

     $name = $request->input('name');
     $email = $request->input('email');
     $password = $request->input('password');
     $confirm = $request->input('password_confirmation');

     $checkexist = Customers::where([['email',$email]])->count();

     if ($checkexist) {
        $return_array['message'] = 'Email Already Exist';
     }else{
       if ($password == $confirm) {
        $hashpassword = Hash::make($password);
        $customer = new Customers();
        $customer->name = $name;
        $customer->email = $email;
        $customer->password = $hashpassword;
        $is_created = $customer->save();
        if ($is_created) {
           $return_array['status'] = true;
           $return_array['message'] ='Registered Successfully';
           $return_array['redirect_url'] = url('/dashboard');
           $request->session()->put('customers',$customer->toArray());

        }else{
           $return_array['message'] = 'Failed registration';
        }

       }else{
        $return_array['message'] = 'Password does not match';
       }
     }
     return response()->json($return_array);
    }

    public function dashboard(){
        // $customerData = session('customers')['name'];
        // dd($customerData);
        // echo"<pre>";print_r($customerData);echo"</pre>";die('end');
        return view('index');
    }
}

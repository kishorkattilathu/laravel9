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

    public function get_all_admin_donation_data(Request $request)
   {
      $columns = ['id', 'category_id', 'account_type', 'amount', 'phone', 'email', 'name', 'pan', 'status','created_at'];
      $limit = $request->input('length');
      $start = $request->input('start');
      $order = $columns[$request->input('order.0.column')];
      $dir = $request->input('order.0.dir');
  
      // Total number of records
      $totalData = Donation::count();
      $totalFiltered = $totalData;
  
      // Query the donations
      $query = Donation::select(['donation.id', 'donation_categories.category_name', 'donation.account_type', 'donation.amount', 'donation.phone', 'donation.email', 'donation.name', 'donation.pan', 'donation.status','donation.created_at'])->join('donation_categories','donation.category_id','=','donation_categories.id');
      // Apply search filter if present
      if (!empty($request->input('search.value'))) {
          $search = $request->input('search.value');
          $query->where(function($q) use ($search) {
              $q->where('donation.id', 'LIKE', "%{$search}%")
                ->orWhere('donation_categories.category_name', 'LIKE', "%{$search}%")
                ->orWhere('donation.account_type', 'LIKE', "%{$search}%")
                ->orWhere('donation.amount', 'LIKE', "%{$search}%")
                ->orWhere('donation.phone', 'LIKE', "%{$search}%")
                ->orWhere('donation.email', 'LIKE', "%{$search}%")
                ->orWhere('donation.name', 'LIKE', "%{$search}%")
                ->orWhere('donation.pan', 'LIKE', "%{$search}%")
                ->orWhere('donation.created_at', 'LIKE', "%{$search}%")
                ->orWhere('donation.status', 'LIKE', "%{$search}%");
          });
  
          // Update filtered data count
          $totalFiltered = $query->count();
      }
  
      // Apply ordering, limit, and offset
      $donations = $query->orderBy($order, $dir)
                         ->offset($start)
                         ->limit($limit)
                         ->get();
      // Prepare data for DataTables
      $data = [];
      foreach ($donations as $donation) {
          $nestedData['id'] = $donation->id;
          $nestedData['category_id'] = $donation->category_name;
          $nestedData['account_type'] = $donation->account_type;
          $nestedData['amount'] = $donation->amount;
          $nestedData['phone'] = $donation->phone;
          $nestedData['email'] = $donation->email;
          $nestedData['name'] = $donation->name;
          $nestedData['pan'] = $donation->pan;
          $nestedData['status'] = $donation->status == 0 ? 'Pending': 'Paid';
          $nestedData['created_at'] = $donation->created_at->format('d-m-y H:i');
  
          // Example action column
          $nestedData['action'] = '<a target="_blank" class="btn btn-soft-secondary-base btn-icon btn-circle btn-sm hov-svg-white mt-2 mt-sm-0" href="'.url('view_donation_receipt/'.$donation->id).'" title="View Invoice">
                            <i class="las la-eye"></i>
                        </a>';
        if($donation->status){
            $nestedData['action'] .=  '<a class="btn btn-soft-secondary-base btn-icon btn-circle btn-sm hov-svg-white mt-2 mt-sm-0" href="'.url('donation_receipt/'.$donation->id).'" title="Download Invoice">
            <i class="las la-download" style="color: #f3af3d;">
            </a>';
        }               
       
          
          $data[] = $nestedData;
      }
      // Return response in JSON format
      $json_data = [
          "draw" => intval($request->input('draw')),
          "recordsTotal" => intval($totalData),
          "recordsFiltered" => intval($totalFiltered),
          "data" => $data
      ];
  
      return response()->json($json_data);

   }
}

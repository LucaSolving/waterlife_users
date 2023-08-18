<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Services\ProductService;
use App\Services\DeliveryService;
use App\Services\OrderService;
use App\Services\CommissionService;
use App\Mail\RegisterConfirmation;
use App\Mail\RegisterNewConsultorConfirmation;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Mail\ConfirmationNewConsultor;
use Validator;
use Illuminate\Validation\Rules\Password;


class RegisterController extends Controller
{
    public $deliveryService, $productService, $orderService, $commissionService;

    public function __construct(ProductService $productService, DeliveryService $deliveryService, OrderService $orderService, CommissionService $commissionService)
    {
        $this->productService = $productService;
        $this->deliveryService = $deliveryService;
        $this->orderService = $orderService;
        $this->commissionService = $commissionService;
    }

    public function show(){
        return view('auth.register');
    }

    public function success(){
        return view('auth.confirmation');
    }

    public function register(UserRequest $request){
        $rules = [
            'password' =>   [
                                'required',
                                'string',
                                Password::min(8)

                            ],
        ];
        $messages = [
            'password.required'         => 'El campo es requerido',
            'password.min'              => 'La contraseña debe tener al menos 8 caracteres.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ( $validator->fails() ) {
            $messages = $validator->messages();
            return redirect('/register')->withErrors($validator);
        }else{
            $user = new User;
            $user->id_sponsor = Auth::user()->id;
            $email_sponsor = Auth::user()->email;

            $user->firts_name = $request->firts_name;
            $user->last_name = $request->last_name;
            $user->mother_last_name = $request->mother_last_name;
            $user->type_doc = $request->type_doc;
            $user->num_doc = $request->num_doc;
            $user->username = $request->num_doc;
            $user->email = $request->email;
            $user->send_email_sponsor = $request->send_email_sponsor;
            $user->reg_token = bcrypt($request->email);
            $user->setPasswordAttribute($request->password);
            $user->status = 'P';
            $user->type_user = 'S';
            $user->save();

            $accessToken = $user->createToken('authToken')->accessToken;

            //Create record for commissions            
            $request2['partner_id'] = Auth::user()->id;
            $request2['amp'] = 0;
            $request2['personal_discount'] = 0;
            $request2['partner_name'] = $request->firts_name.' '.$request->last_name.' '.$request->mother_last_name;
            $request2['range'] = '';
            
            $commission = $this->commissionService->post('/commissions_users_exists/'.$request2['partner_id'],$request2);
            //End create record for commissions

            $data = [
                'firts_name' => $user->firts_name,
                'reg_token' => $user->reg_token,
            ];

            if($user->send_email_sponsor==1){
                Mail::to($email_sponsor)
                    ->send(new RegisterConfirmation($data));

            }
            Mail::to($user->email)->send(new RegisterConfirmation($data));

            return view('auth.confirmation', ['email' => $request->email]);

        }

            

    }

    public function registerconfirmation(Request $request){

        $data = User::where('reg_token', $request->reg_token)
                        ->where('status','=','P')
                        ->first();
        //return $data;
        if($data!=''){
            $sponsor = User::where('id', $data->id_sponsor)->first();

            $name_sponsor = $sponsor->firts_name.' '.$sponsor->last_name.' '.$sponsor->mother_last_name;

            $departments = $this->deliveryService->get('/departments');

            return view('auth.register_confirmation', compact('data','name_sponsor','departments'));
        }else{
            return view('auth.register_confirmation', compact('data'));
        }
    }

    public function register_confirmation_ok(Request $request){

        $rules = [
            'birth_date' => 'required',
            'gender' => 'required',
            'phone' => 'required',
            'phone_operator' => 'required',
            'department' => 'required',
            'province' => 'required',
            'district' => 'required',
            'address' => 'required',
        ];

        $this->validate($request, $rules);

        $data = User::findOrFail($request->txt);

        $data->birth_date = $request->birth_date;
        $data->gender = $request->gender;
        $data->phone = $request->phone;
        $data->phone_operator = $request->phone_operator;
        $data->department = $request->department;
        $data->province = $request->province;
        $data->district = $request->district;
        $data->address = $request->address;
        $data->address_reference = $request->address_reference;
        $data->bank = $request->bank;
        $data->type_account = $request->type_account;
        $data->nro_account = $request->nro_account;
        $data->cci_account = $request->cci_account;
        $data->method_payment = $request->method_payment;
        $data->affiliation_date = Carbon::now();

        //For Product Price != 0
        //$data->status = 'I';
        /*$data->update();

        $product_affiliation = $this->productService->get('/product_affiliation');
        foreach ($product_affiliation as $item) {
            $product_affiliation_price = $item["price"];
            $product_affiliation_points = $item["points"];
        }

        $orders_affiliation = $this->orderService->post('/validar_transactions_confirmation_ok/',$request->all());

        if($data->method_payment==1){
            return view('auth.payment_confirmation', compact('data'));
        }else{
            return view('auth.payment_confirmation_transferencia_bancaria', compact('data','product_affiliation_price','product_affiliation_points'));
        }*/

        //End For Product Price != 0



        //For Product Price = 0
        $data->status = 'I';
        $data->update();

        $product_affiliation = $this->productService->get('/product_affiliation');
        foreach ($product_affiliation as $item) {
            $request["total_amount"] = $item["price"];
            $request["total_points"] = $item["points"];
        }

        $request["partner_id"] = $data->id;
        $request["partner_name"] = $data->firts_name.' '.$data->last_name;

        $orders_affiliation = $this->orderService->post('/partner_network_pre_ok2/',$request->all());
        
        if($orders_affiliation!=''){

            $partner_name = $data->firts_name.' '.$data->last_name;
            $data_email = $data->email;

            Mail::to($data_email)->send(new ConfirmationNewConsultor($data));
        }

        $request['amp'] = $request['total_points'];
        $request['personal_discount'] = 0;

        $commission = $this->commissionService->post('/commissions_users_exists/'.$request['partner_id'],$request->all());

        return view('auth.membership_confirmation_free', compact('partner_name'));
        //End For Product Price = 0
    }

    public function duplic_email(Request $request){

        $order_register_voucher = $this->orderService->post('/register_voucher_ok',$request->all());
        $id_order = $request->id;

        if($order_register_voucher == 1) {
            Session::flash('message', 'Haz ingresado un número de operación que ya ha sido usado.');
            Session::flash('class', 'danger');
            return view('home.register_voucher_ok_error', compact('id_order'));
        }else{
            return  view('home.register_voucher_ok', compact('id_order'));
        }

        //$data = User::findOrFail($request->email);
        $data = User::where('email', '=', $request->email)
                ->where(function($query) {
                    $query->where('status', '=', 'A')
                        ->where('status', '=', 'I');
                })
                ->count();
        return $data;

        /*$data->confirm_membership = date('Y-m-d H:i:s');
        $data->update();

        return view('auth.payment_confirmation_ok', compact('data'));*/
    }

    public function payment_confirmation_ok(Request $request){

        $data = User::findOrFail($request->txt);

        $data->confirm_membership = date('Y-m-d H:i:s');
        $data->update();

        return view('auth.payment_confirmation_ok', compact('data'));
    }

    public function transactions_confirmation_ok(Request $request){

        $orders_affiliation = $this->orderService->post('/validar_transactions_confirmation_ok/',$request->all());

        if($orders_affiliation==1){
            Session::flash('message', 'Haz ingresado un número de operación que ya ha sido usado.');
            Session::flash('class', 'danger');

            return view('auth.payment_confirmation_transferencia_bancaria_error');

        }else{
            $orders_affiliation = $this->orderService->post('/orders_affiliation/',$request->all());

            $partner_name = $orders_affiliation["partner_name"];

            return view('auth.payment_confirmation_transferencia_bancaria_ok', compact('partner_name'));
        }

    }



    public function provinces($id_department){
        $provinces = $this->deliveryService->get('/provinces/'.$id_department);
        return response()->json($provinces);
    }

    public function districts($id_province){
        $districts = '';
        $districts = $this->deliveryService->get('/districts/'.$id_province);
        return response()->json($districts);
    }


    public function usersprueba(){

        $user_1_level = User::select(array('id','firts_name','last_name','level','id_sponsor','status','image','phone','email'))
                            ->get();

        return $user_1_level;
    }
}

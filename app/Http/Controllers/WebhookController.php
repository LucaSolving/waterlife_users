<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\Services\ProductService;
use App\Services\DeliveryService;
use App\Services\OrderService;
use App\Services\CommissionService;
use Carbon\Carbon;
use App\Models\User;
use Hash;
use Illuminate\Support\Facades\Log;
use Validator;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Mail;
use App\Mail\ShopConfirmation;
use App\Mail\ProductStockNotification;

class WebhookController extends Controller
{
    public $productService, $orderService, $deliveryService, $commissionService;

    public function __construct(ProductService $productService, OrderService $orderService, DeliveryService $deliveryService, CommissionService $commissionService)
    {
        $this->orderService = $orderService;
        $this->deliveryService = $deliveryService;
        $this->commissionService = $commissionService;
        $this->productService = $productService;
    }

    public function culqi_update_order(Request $request){
        Log::info($request);
        //return $request;

        /* Recuperar el cuerpo de la solicitud y parsearlo como JSON */
        $input = $request->all();

        $input_json = file_get_contents("php://input");
        //$event_json = json_decode($input);
        $data = array();


        // Escribir el Webhook en mi archivo "log/log-webhooks.json" de ejemplo
        $myfile = fopen("log-webhooks.text", "w") or die("Imposible abrir el archivo.");
        fwrite($myfile, $input_json);

        /* Reconocer tipo de evento recibido */
        if($input['type'] == 'order.status.changed') {

            // Obtener objeto Order
            $objectOrder = json_decode($input['data'], true);

            // Parametros
            $state = trim($objectOrder['state']);
            $id = trim($objectOrder['id']);
            $payment_code = trim($objectOrder['payment_code']);
            $order_number = trim($objectOrder['order_number']);
            $amount = trim($objectOrder['amount']);
            //$id_order = 'ord_live_7obvMm63Qf0afRru';
            $id_order = trim($objectOrder['order_number']);

            // Orden pagada
            if($state == 'paid') {

                $data_d['payment_status'] = 'Pagado - Pago Efectivo';
                $data_d['payment_date'] = Carbon::now(); //->subHours(10)
                $data_buy['first_buy'] = 'yes';
                /* $data['total'] = $amount; */
                //$data['token_culqi'] = $payment_code;

                //codigo_cip = CIP de culqi para pago efectivo
                $orders_codigo_cip = $this->orderService->get('/orders_consult_codigo_cip/'.$payment_code);
                //return $orders_codigo_cip;
                foreach ($orders_codigo_cip as $item_codigo_cip) {
                    $id_order = $item_codigo_cip["id"];
                    $partner_id = $item_codigo_cip["partner_id"];
                    $partner_name = $item_codigo_cip["partner_name"];
                    $total_points = $item_codigo_cip["total_points"];
                    $discount_applied = $item_codigo_cip["discount_applied"];
                    $type_member = $item_codigo_cip["type_member"];

                    //Data to Email
                    $id_order = $item_codigo_cip["id"];
                    $order_date = $item_codigo_cip["order_date"];
                    $department = $item_codigo_cip["department"];
                    $province = $item_codigo_cip["province"];
                    $district = $item_codigo_cip["district"];
                    $address = $item_codigo_cip["address"];
                    $total_amount = $item_codigo_cip["total_amount"];
                    $email_d = $item_codigo_cip["email"];                    
                }

                if($partner_id != 1 ){

                    //Commissions Insert Data
                    $request2['id_order'] = $id_order;
                    $request2['partner_id'] = $partner_id;
                    $request2['partner_name'] = $partner_name;
                    $request2['amp'] = $total_points;
                    $request2['personal_discount'] = $discount_applied;

                    $request2['type'] = $type_member;
                    $request2['amount'] = $total_amount;
                    $request2['points'] = $total_points;
                    
                    $order = $this->orderService->post('/orders_payment_webhook_success/'.$request2['id_order'], $data_d);
                    
                    //For Me
                    $commission = $this->commissionService->post('/commissions_users_exists/'.$request2['partner_id'],$request2);
                    
                    foreach ($commission as $item) {
                        $commission_id = $item["id"];
                    }
                    //return $commission_id;

                    $commission_detail = $this->commissionService->post('/commission_detail/'.$commission_id,$request2);

                    $first_buy = $this->orderService->get('/orders_first_buy/'.$request2['partner_id']);
                    if($first_buy==0){
                        $this->orderService->post('/orders_update_orders_first_buy/'.$id_order, $data_buy);
                    }
                }

                //Descontar Stock
                $order_products = $this->orderService->get('/list_stock_products/'.$id_order);
                
                $email_admin = 'mcollantes@hidroteklatino.com.pe,rmontalvan@hidroteklatino.com.pe';

                foreach ($order_products as $item) {
                    $id_product = $item["id_product"];
                    $request['quantity'] = $item["quantity"];

                    $products = $this->productService->post('/update_stockproduct/'.$id_product, $request->all());

                    //Script for Stock Notification
                    $stockproduct = $this->productService->get('/stockproduct/'.$id_product);
                    
                    foreach ($stockproduct as $item2) {
                        $sku = $item2["sku"];
                        $stock = $item2["stock"];
                        $product = $item2["product"];
                        $stock_notification = $item2["stock_notification"];

                        $data_stock = [
                            'sku' => $sku,
                            'stock' => $stock,
                            'product' => $product,
                        ];

                        if($stock_notification>=$stock){
                            Mail::to($email_admin)->send(new ProductStockNotification($data_stock));
                        }
                    }
                    //End script for Stock Notification
                }
                //End Descontar Stock

                $users = User::select(array('id','status'))
                        ->where('id', '=', $partner_id)
                        ->get();
                
                foreach ($users as $item_users) {
                    $id = $item_users["id"];
                    $user_status = $item_users["status"];               
                }

                $commission = $this->commissionService->get('/commissions_users/'.$partner_id);

                if($commission!=0){
                    foreach ($commission as $item) {
                        $commission['amp'] = $item["amp"];
                    }
                }

                if(($user_status == 'I' ) && $commission['amp']>=75){
                    $user = User::findOrFail($id);
                    $user->status = 'A';
                    $user->update();
                }
        
                //Data to Email
                $data = [
                    'firts_name' => $partner_name,
                    'last_name' => '',
                    'email' => $email_d,
                    'id' => $id_order,
                    'order_date' => $order_date,
                    'department' => $department,
                    'province' => $province,
                    'district' => $district,
                    'address' => $address,
                    'total_amount' => $total_amount,
                ];
        
                Mail::to($email_d)->send(new ShopConfirmation($data));
                //End data to Email

            }

            // Orden expirada
            if($state == 'expired') {
                $data_d['payment_status'] = 'Expirado - Pago Efectivo';
                /* $data['total'] = $amount;
                $data['token_culqi'] = $payment_code; */

                //$order = $this->orderService->post('/orders_payment_pending/'.$id_order, $data);

            }

            // Orden eliminada
            if($state == 'deleted') {

                $data_d['payment_status'] = 'Eliminado - Pago Efectivo';
                /* $data['total'] = $amount; */
                //$data['token_culqi'] = $payment_code;


                // Aquì cambiar estado de la orden en tu sistema ...
                /* $array = array(
                "response" => "Webhook de Culqi $state ",
                'id' =>  $id,
                'payment_code' => $payment_code,
                'order_number' =>$order_number,
                'amount' => $amount
                );
                $this->load->model('Matriculacion_model');
                $datos['tx_estatus'] = 'Eliminado';
                $this->Matriculacion_model->editar_x_order(intval($order_number), $datos);
                    $this->load->library('Libnotificaciones');
                    $param_padre['nombre'] = $matricula->name;
                    $param_padre['correo'] =  $matricula->email;
                    $param_padre['fe_pago'] =  date('d-m-Y');
                    $param_padre['tx_operacion'] =
                    "Su pago con Culqi Efectivo N° $payment_code ha cambiado a estado Expirado";
                    $param_padre['nu_monto'] =  $amount/100;
                $this->libnotificaciones->envio_pago_padre($param_padre); */
            }

        }

        $order = $this->orderService->post('/update_status_webhook/'.$id_order, $data_d);

        //Respuesta a Culqi
        //http_response_code(200);
        return response()->json($order);
    }


    public function tarjeta_update_shop($order_number){

        /* Activación de compra de tarjeta de forma manual por postman */
        if(isset($order_number)) {

            $id_order = trim($order_number);


            $data_d['payment_status'] = 'Pagado';
            $data_buy['first_buy'] = 'yes';

            //codigo_cip = CIP de culqi para pago efectivo
            $orders_codigo_cip = $this->orderService->get('/orders_consult_tarjeta_token/'.$id_order);
            
            foreach ($orders_codigo_cip as $item_codigo_cip) {
                $id_order = $item_codigo_cip["id"];
                $partner_id = $item_codigo_cip["partner_id"];
                $partner_name = $item_codigo_cip["partner_name"];
                $total_points = $item_codigo_cip["total_points"];
                $discount_applied = $item_codigo_cip["discount_applied"];
                $type_member = $item_codigo_cip["type_member"];

                //Data to Email
                $id_order = $item_codigo_cip["id"];
                $order_date = $item_codigo_cip["order_date"];
                $department = $item_codigo_cip["department"];
                $province = $item_codigo_cip["province"];
                $district = $item_codigo_cip["district"];
                $address = $item_codigo_cip["address"];
                $total_amount = $item_codigo_cip["total_amount"];
                $email_d = $item_codigo_cip["email"];                    
            }

            if($partner_id != 1 ){

                //Commissions Insert Data
                $request2['id_order'] = $id_order;
                $request2['partner_id'] = $partner_id;
                $request2['partner_name'] = $partner_name;
                $request2['amp'] = $total_points;
                $request2['personal_discount'] = $discount_applied;

                $request2['type'] = $type_member;
                $request2['amount'] = $total_amount;
                $request2['points'] = $total_points;
                
                $order = $this->orderService->post('/orders_payment_webhook_success/'.$request2['id_order'], $data_d);
                
                //For Me
                $commission = $this->commissionService->post('/commissions_users_exists/'.$request2['partner_id'],$request2);
                
                foreach ($commission as $item) {
                    $commission_id = $item["id"];
                }
                //return $commission_id;

                $commission_detail = $this->commissionService->post('/commission_detail/'.$commission_id,$request2);

                $first_buy = $this->orderService->get('/orders_first_buy/'.$request2['partner_id']);
                if($first_buy==0){
                    $this->orderService->post('/orders_update_orders_first_buy/'.$id_order, $data_buy);
                }
            }

            //Descontar Stock
            $order_products = $this->orderService->get('/list_stock_products/'.$id_order);
                
            $email_admin = 'mcollantes@hidroteklatino.com.pe,rmontalvan@hidroteklatino.com.pe';

            foreach ($order_products as $item) {
                $id_product = $item["id_product"];
                $request['quantity'] = $item["quantity"];

                //$products = $this->productService->post('/update_stockproduct/'.$id_product, $request->all());

                //Script for Stock Notification
                $stockproduct = $this->productService->get('/stockproduct/'.$id_product);
                
                foreach ($stockproduct as $item2) {
                    $sku = $item2["sku"];
                    $stock = $item2["stock"];
                    $product = $item2["product"];
                    $stock_notification = $item2["stock_notification"];

                    $data_stock = [
                        'sku' => $sku,
                        'stock' => $stock,
                        'product' => $product,
                    ];

                    if($stock_notification>=$stock){
                        Mail::to($email_admin)->send(new ProductStockNotification($data_stock));
                    }
                }
                //End script for Stock Notification
            }
            //End Descontar Stock

            $users = User::select(array('id','status'))
                    ->where('id', '=', $partner_id)
                    ->get();
            
            foreach ($users as $item_users) {
                $id = $item_users["id"];
                $user_status = $item_users["status"];               
            }

            $commission = $this->commissionService->get('/commissions_users/'.$partner_id);

            if($commission!=0){
                foreach ($commission as $item) {
                    $commission['amp'] = $item["amp"];
                }
            }

            if(($user_status == 'I' ) && $commission['amp']>=75){
                $user = User::findOrFail($id);
                $user->status = 'A';
                $user->update();
            }
    
            //Data to Email
            $data = [
                'firts_name' => $partner_name,
                'last_name' => '',
                'email' => $email_d,
                'id' => $id_order,
                'order_date' => $order_date,
                'department' => $department,
                'province' => $province,
                'district' => $district,
                'address' => $address,
                'total_amount' => $total_amount,
            ];
    
            Mail::to($email_d)->send(new ShopConfirmation($data));
            //End data to Email

        }

        $order = $this->orderService->post('/update_status_webhook/'.$id_order, $data_d);

        return response()->json($order);
    }


    //Start - Section Profile
    public function profile($id){
        if(auth()->user()->id == $id){
            $users = User::find($id);

            $departments = $this->deliveryService->get('/departments');

            return view('home.profile', compact('users','departments'));
        }else{
            //return view('home.index');
            return redirect('/profile/'.auth()->user()->id);
        }
    }




}

<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\ProductService;
use App\Services\DeliveryService;
use App\Services\OrderService;
use App\Services\CommissionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use Culqi;
use Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\ShopConfirmation;
use App\Mail\ProductStockNotification;

class ShopController extends Controller
{
    public $productService, $deliveryService, $orderService, $commissionService;

    public function __construct(ProductService $productService, DeliveryService $deliveryService, OrderService $orderService, CommissionService $commissionService)
    {
        $this->productService = $productService;
        $this->deliveryService = $deliveryService;
        $this->orderService = $orderService;
        $this->commissionService = $commissionService;
    }

    public function stockproduct_test($id, Request $request){

        $stockproduct_test = $this->productService->get('/stockproduct/'.$id);

        foreach ($stockproduct_test as $item2) {
            $product['id_product'] = $item2["id"];
            $product['product'] = $item2["product"];
               
        }
        return response()->json($product);
    }

    public function show(){

        $partner_id = auth()->user()->id;

        $products = $this->productService->get('/products');
        $departments = $this->deliveryService->get('/departments');
        $commission = $this->commissionService->get('/commissions_users/'.$partner_id);
        $commission_past = $this->commissionService->get('/commissions_users_1Mpast/'.$partner_id);

        if($commission_past!=0){
            foreach ($commission_past as $item_past) {
                $range_past = $item_past["range"];
            }
        }else{
            $range_past = 0;
        }
        

        //Data Client For Report Facturation
        $data_department = $this->deliveryService->get('/show_department/'.auth()->user()->department);
        foreach ($data_department as $item_department) {
            $name_department = $item_department["department"];
        }

        $data_province = $this->deliveryService->get('/show_province/'.auth()->user()->province);
        foreach ($data_province as $item_province) {
            $name_province = $item_province["province"];
        }

        $data_district = $this->deliveryService->get('/show_district/'.auth()->user()->district);
        foreach ($data_district as $item_district) {
            $name_district = $item_district["district"];
        }
        

        //if(isset($commission)){
        if($commission!=0){
            foreach ($commission as $item) {
                $commission['amp'] = $item["amp"];
                //$commission['personal_discount'] = $item["personal_discount"];
                if($item["personal_discount"]==0){
                    $commission['personal_discount'] = $item["personal_discount_history"];
                }else{
                    $commission['personal_discount'] = $item["personal_discount"];
                }
                $commission['amp_history'] = $item["amp_history"];
                $commission['personal_discount_history'] = $item["personal_discount_history"];
            }

            //if(isset($commission['amp'])){
            //    $commission['amp'] = $commission['amp'];
            if(isset($commission['amp_history'])){
                $commission['amp_month'] = $commission['amp'];
                //$commission['amp'] = $commission['amp_history'];
                $commission['amp'] = $commission['amp'];
            }else{
                $commission['amp'] = 0;
            }

            if(($range_past==0) || ($range_past=='Waterlife')){
                if($commission['amp']<75){

                    $commission['amp1'] = 75-$commission['amp'];
                    $commission['amp2'] = 125-$commission['amp'];
                    $commission['amp3'] = 200-$commission['amp'];
                    $commission['amp4'] = 350-$commission['amp'];
                    $commission['amp5'] = 500-$commission['amp'];
                    
                    $commission['points_alert_message'] = "Te falta ".$commission['amp1']." puntos para llegar al 15%<br>
                                                        Te falta ".$commission['amp2']." puntos para llegar al 20%<br>
                                                        Te falta ".$commission['amp3']." puntos para llegar al 25%<br>
                                                        Te falta ".$commission['amp4']." puntos para llegar al 30%<br>
                                                        Te falta ".$commission['amp5']." puntos para llegar al 35%";
                    

                }elseif(($commission['amp']>=75)&&($commission['amp']<125)){

                    $commission['amp2'] = 125-$commission['amp'];
                    $commission['amp3'] = 200-$commission['amp'];
                    $commission['amp4'] = 350-$commission['amp'];
                    $commission['amp5'] = 500-$commission['amp'];

                    $commission['points_alert_message'] = "<span class=text-success>Ya tienes el 15% de descuento</span><br>
                                                        Te falta ".$commission['amp2']." puntos para llegar al 20%<br>
                                                        Te falta ".$commission['amp3']." puntos para llegar al 25%<br>
                                                        Te falta ".$commission['amp4']." puntos para llegar al 30%<br>
                                                        Te falta ".$commission['amp5']." puntos para llegar al 35%";
                    
                }elseif(($commission['amp']>=125)&&($commission['amp']<200)){

                    $commission['amp3'] = 200-$commission['amp'];
                    $commission['amp4'] = 350-$commission['amp'];
                    $commission['amp5'] = 500-$commission['amp'];

                    $commission['points_alert_message'] = "<span class=text-success>Ya tienes el 15% de descuento</span><br>
                                                        <span class=text-success>Ya tienes el 20% de descuento</span><br>
                                                        Te falta ".$commission['amp3']." puntos para llegar al 25%<br>
                                                        Te falta ".$commission['amp4']." puntos para llegar al 30%<br>
                                                        Te falta ".$commission['amp5']." puntos para llegar al 35%";
                    
                }elseif(($commission['amp']>=200)&&($commission['amp']<350)){

                    $commission['amp4'] = 350-$commission['amp'];
                    $commission['amp5'] = 500-$commission['amp'];

                    $commission['points_alert_message'] = "<span class=text-success>Ya tienes el 15% de descuento</span><br>
                                                        <span class=text-success>Ya tienes el 20% de descuento</span><br>
                                                        <span class=text-success>Ya tienes el 25% de descuento</span><br>
                                                        Te falta ".$commission['amp4']." puntos para llegar al 30%<br>
                                                        Te falta ".$commission['amp5']." puntos para llegar al 35%";
                    
                }elseif(($commission['amp']>=350)&&($commission['amp']<500)){

                    $commission['amp5'] = 500-$commission['amp'];

                    $commission['points_alert_message'] = "<span class=text-success>Ya tienes el 15% de descuento</span><br>
                                                        <span class=text-success>Ya tienes el 20% de descuento</span><br>
                                                        <span class=text-success>Ya tienes el 25% de descuento</span><br>
                                                        <span class=text-success>Ya tienes el 30% de descuento</span><br>
                                                        Te falta ".$commission['amp5']." puntos para llegar al 35%";
                    
                }elseif($commission['amp']>=500){

                    $commission['points_alert_message'] = "<span class=text-success>Ya tienes el 15% de descuento</span><br>
                                                        <span class=text-success>Ya tienes el 20% de descuento</span><br>
                                                        <span class=text-success>Ya tienes el 25% de descuento</span><br>
                                                        <span class=text-success>Ya tienes el 30% de descuento</span><br>
                                                        <span class=text-success>Ya tienes el 35% de descuento</span>";
                    
                }
            }else{
                if($commission['amp']<75){

                    $commission['amp1'] = 75-$commission['amp'];
                    $commission['amp2'] = 125-$commission['amp'];
                    $commission['amp3'] = 200-$commission['amp'];
                    $commission['amp4'] = 350-$commission['amp'];
                    
                    $commission['points_alert_message'] = "Te falta ".$commission['amp1']." puntos para llegar al 20%<br>
                                                        Te falta ".$commission['amp2']." puntos para llegar al 25%<br>
                                                        Te falta ".$commission['amp3']." puntos para llegar al 30%<br>
                                                        Te falta ".$commission['amp4']." puntos para llegar al 35%";
                    

                }elseif(($commission['amp']>=75)&&($commission['amp']<125)){

                    $commission['amp2'] = 125-$commission['amp'];
                    $commission['amp3'] = 200-$commission['amp'];
                    $commission['amp4'] = 350-$commission['amp'];

                    $commission['points_alert_message'] = "<span class=text-success>Ya tienes el 20% de descuento</span><br>
                                                        Te falta ".$commission['amp2']." puntos para llegar al 25%<br>
                                                        Te falta ".$commission['amp3']." puntos para llegar al 30%<br>
                                                        Te falta ".$commission['amp4']." puntos para llegar al 35%";
                    
                }elseif(($commission['amp']>=125)&&($commission['amp']<200)){

                    $commission['amp3'] = 200-$commission['amp'];
                    $commission['amp4'] = 350-$commission['amp'];

                    $commission['points_alert_message'] = "<span class=text-success>Ya tienes el 20% de descuento</span><br>
                                                        <span class=text-success>Ya tienes el 25% de descuento</span><br>
                                                        Te falta ".$commission['amp3']." puntos para llegar al 30%<br>
                                                        Te falta ".$commission['amp4']." puntos para llegar al 35%";
                    
                }elseif(($commission['amp']>=200)&&($commission['amp']<350)){

                    $commission['amp4'] = 350-$commission['amp'];

                    $commission['points_alert_message'] = "<span class=text-success>Ya tienes el 20% de descuento</span><br>
                                                        <span class=text-success>Ya tienes el 25% de descuento</span><br>
                                                        <span class=text-success>Ya tienes el 30% de descuento</span><br>
                                                        Te falta ".$commission['amp4']." puntos para llegar al 35%";
                    
                }elseif($commission['amp']>=350){

                    $commission['points_alert_message'] = "<span class=text-success>Ya tienes el 20% de descuento</span><br>
                                                        <span class=text-success>Ya tienes el 25% de descuento</span><br>
                                                        <span class=text-success>Ya tienes el 30% de descuento</span><br>
                                                        <span class=text-success>Ya tienes el 35% de descuento</span>";
                    
                }
            }

            
            //session()->increment('total_points_initial', $commission['amp']);
        }

        
        if(session()->exists('session_id_order')) {

            $orders = $this->orderService->get('/orders/'.session('session_id_order'));            
            $orders_detail_products = $this->orderService->get('/orders_pending_detail_products/'.session('session_id_order'));           
            return view('home.shop', compact('products','departments','orders','orders_detail_products','commission','name_department','name_province','name_district'));
            //return 'SESSION ACTIVA'.session('session_id_order');
        }else{
            $orders = null;
            $orders_detail_products = null;
            return view('home.shop', compact('products','departments','orders','orders_detail_products','commission','name_department','name_province','name_district'));
        }
    }
    
    public function shop_clear(){
        
        session()->forget('session_id_order');
        session()->forget('subtotal');
        session()->forget('discount_amount');
        session()->forget('total_points');
        session()->forget('total_points_initial');
        session()->forget('total_amount');
        session()->forget('total');

        return redirect('shop');
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

    public function deliverycost(Request $request){
        $cant_prod_cart = $this->orderService->get('/orders_quantity_total/'.session('session_id_order'));
        
        if($cant_prod_cart == 1){
            
            $products = $this->orderService->get('/orders_product_shop/'.session('session_id_order'));

            foreach ($products as $item) {
                $request['sku'] = $item["sku"];
            }
            
            $deliverycost = $this->deliveryService->post('/deliverycost_new/',$request->all());
            
            foreach ($deliverycost as $cost) {
                $delivery["price"] = number_format($cost["tariff"],2);
                $delivery["name"] = $cost["path_classification"];
            }

            return $delivery;
            
        }else{
            
            if(($request['department'] == 'Lima')&&($request['province'] == 'Lima')){
            //if($request['department'] == 'Lima'){

                $delivery_cla = $this->deliveryService->post('/deliverycost_clasification/',$request->all());        
                
                foreach ($delivery_cla as $item) {
                    $request["classification"] = $item["path_classification"];
                }            
                
                $kilo_aditional_a = $this->deliveryService->post('/deliverycost_aditional_lima/',$request->all());
                
                foreach ($kilo_aditional_a as $item2) {
                    $kilo_aditional = $item2["additional_kilogram"];
                }    
                
                
                //Traer todos los sku del carrito
                $products = $this->orderService->get('/orders_product_shop/'.session('session_id_order'));
                
                foreach ($products as $item) {
                    $sku[] = $item["sku"];
                }
                $request['sku'] = $sku;
                
                //Traer el producto con mayor precio base
                $deliverycost = $this->deliveryService->post('/deliverycost_new_products_all/',$request->all());
                
                foreach ($deliverycost as $cost) {
                    $product_base = $cost["product"];
                    $tariff = $cost["tariff"];
                    $path_classification = $cost["path_classification"];
                }

                //Todos los productos restantes
                $products = $this->orderService->get('/orders_product_shop_all/'.session('session_id_order'));
                
                foreach ($products as $item) {
                    if($item["sku"]==$product_base){
                        $subtotal[] = ($item["quantity"]-1)*$item["weight"];
                    }else{
                        $subtotal[] = $item["quantity"]*$item["weight"];
                    }
                }
                
                $request2['subtotal'] = array_sum($subtotal);

                $cant_price_aditional = $request2['subtotal'] * $kilo_aditional;

                $delivery["price"] = number_format(($tariff + $cant_price_aditional),2);
                $delivery["name"] = $path_classification;
                return $delivery;
        
            }else{ //para provincia                

                $deliverycost_ap = $this->deliveryService->post('/deliverycost_aditional_provinces/',$request->all());
                
                foreach ($deliverycost_ap as $cost) {
                    $additional_fee = $cost["additional_fee"];
                }

                //Traer todos los sku del carrito
                $products = $this->orderService->get('/orders_product_shop/'.session('session_id_order'));
                
                foreach ($products as $item) {
                    $sku[] = $item["sku"];
                }
                $request['sku'] = $sku;

                //Traer el producto con mayor precio base
                $deliverycost = $this->deliveryService->post('/deliverycost_new_products_all/',$request->all());
                
                foreach ($deliverycost as $cost) {
                    $product_base = $cost["product"];
                    $tariff = $cost["tariff"];
                    $path_classification = $cost["path_classification"];
                }
                
                //Todos los productos restantes
                $products = $this->orderService->get('/orders_product_shop_all/'.session('session_id_order'));
                
                foreach ($products as $item) {
                    if($item["sku"]==$product_base){
                        $subtotal[] = ($item["quantity"]-1)*$item["weight"];
                    }else{
                        $subtotal[] = $item["quantity"]*$item["weight"];
                    }
                }

                $request2['subtotal'] = array_sum($subtotal);

                $cant_price_aditional = $request2['subtotal'] * $additional_fee;
                
                $delivery["price"] = number_format(($tariff + $cant_price_aditional),2);
                $delivery["name"] = $path_classification;

                return $delivery;
                
            }        
        }
    }

    public function orden_remove_product($id, Request $request){
        
        $partner_id = auth()->user()->id;
        $request_product['id_product'] = $id;
        $order['id_order'] = session('session_id_order');
        $department = $request->department;
        
        $commission_data = $this->commissionService->get('/commissions_users/'.$partner_id);        
        $commission_past = $this->commissionService->get('/commissions_users_1Mpast/'.$partner_id);

        if($commission_past!=0){
            foreach ($commission_past as $item_past) {
                $range_past = $item_past["range"];
            }
        }else{
            $range_past = 0;
        }

        //return $commission_data;
        //return $commission_data;
        if($commission_data!=0){
            foreach ($commission_data as $item) {
                $commission['amp'] = $item["amp"];
                $commission['personal_discount'] = $item["personal_discount"];
                $commission['amp_history'] = $item["amp_history"];
                $commission['personal_discount_history'] = $item["personal_discount_history"];
            }
        }

        /*if(isset($commission['amp'])){
            $commission['amp'] = $commission['amp'];
        }else{
            $commission['amp'] = 0;
        }*/

        if(isset($commission['amp_history'])){
            $commission['amp_month'] = $commission['amp'];
            //$commission['amp'] = $commission['amp_history'];
            $commission['amp'] = $commission['amp'];
        }else{
            $commission['amp'] = 0;
        }
        
        //consulto el producto existente para que me traiga el campo points y total_import = quantity * price
        $product_row = $this->orderService->post('/get_product_order/'.session('session_id_order'),$request_product);
        
        foreach ($product_row as $item) {
            $total_import_db = $item["total_import"];
            $points_db = $item["points"];
        }
        
        //Elimino el producto de mi tabla orders_detail
        $product_delete = $this->orderService->post('/orden_remove_product/'.$id,$order);

        //consulto los datos de la orden existente
        $orders_now = $this->orderService->get('/orders/'.session('session_id_order'));
        //return $product_row;

        $subtotal_new = session('subtotal')-$total_import_db;
        $total_amount_new = session('total_amount')-$total_import_db;      
        $points_new = $commission['amp']+(session('total_points')-$points_db);
        $points_new2 = (session('total_points')-$points_db);
        
        if(($range_past==0) || ($range_past=='Waterlife')){
            if($points_new<75){

                $product['discount_applied'] = 0;
                $product['discount_amount'] = 0;
                $product['total'] = $subtotal_new;
                $product['amp1'] = 75-$points_new;
                $product['amp2'] = 125-$points_new;
                $product['amp3'] = 200-$points_new;
                $product['amp4'] = 350-$points_new;
                $product['amp5'] = 500-$points_new;
                
                $product['points_alert_message'] = "Te falta ".$product['amp1']." puntos para llegar al 15%<br>
                                                    Te falta ".$product['amp2']." puntos para llegar al 20%<br>
                                                    Te falta ".$product['amp3']." puntos para llegar al 25%<br>
                                                    Te falta ".$product['amp4']." puntos para llegar al 30%<br>
                                                    Te falta ".$product['amp5']." puntos para llegar al 35%";

            }elseif(($points_new>=75)&&($points_new<125)){            

                $product['discount_applied'] = 15;
                $product['discount_amount'] = $subtotal_new*($product['discount_applied']/100);
                $product['total'] = $total_amount_new-($total_amount_new*($product['discount_applied']/100));
                $product['amp2'] = 125-$points_new;
                $product['amp3'] = 200-$points_new;
                $product['amp4'] = 350-$points_new;
                $product['amp5'] = 500-$points_new;

                $product['points_alert_message'] = "<span class=text-success>Ya tienes el 15% de descuento</span><br>
                                                    Te falta ".$product['amp2']." puntos para llegar al 20%<br>
                                                    Te falta ".$product['amp3']." puntos para llegar al 25%<br>
                                                    Te falta ".$product['amp4']." puntos para llegar al 30%<br>
                                                    Te falta ".$product['amp5']." puntos para llegar al 35%";
                
            }elseif(($points_new>=125)&&($points_new<200)){

                $product['discount_applied'] = 20;
                $product['discount_amount'] = $subtotal_new*($product['discount_applied']/100);
                $product['total'] = $total_amount_new-($total_amount_new*($product['discount_applied']/100));     
                $product['amp3'] = 200-$points_new;
                $product['amp4'] = 350-$points_new;
                $product['amp5'] = 500-$points_new;

                $product['points_alert_message'] = "<span class=text-success>Ya tienes el 15% de descuento</span><br>
                                                    <span class=text-success>Ya tienes el 20% de descuento</span><br>
                                                    Te falta ".$product['amp3']." puntos para llegar al 25%<br>
                                                    Te falta ".$product['amp4']." puntos para llegar al 30%<br>
                                                    Te falta ".$product['amp5']." puntos para llegar al 35%";
                
            }elseif(($points_new>=200)&&($points_new<350)){

                $product['discount_applied'] = 25;
                $product['discount_amount'] = $subtotal_new*($product['discount_applied']/100);
                $product['total'] = $total_amount_new-($total_amount_new*($product['discount_applied']/100));
                $product['amp4'] = 350-$points_new;
                $product['amp5'] = 500-$points_new;
                $product['points_alert_message'] = "<span class=text-success>Ya tienes el 15% de descuento</span><br>
                                                    <span class=text-success>Ya tienes el 20% de descuento</span><br>
                                                    <span class=text-success>Ya tienes el 25% de descuento</span><br>
                                                    Te falta ".$product['amp4']." puntos para llegar al 30%<br>
                                                    Te falta ".$product['amp5']." puntos para llegar al 35%";
                
            }elseif(($points_new>=350)&&($points_new<500)){

                $product['discount_applied'] = 30;
                $product['discount_amount'] = $subtotal_new*($product['discount_applied']/100);
                $product['total'] = $total_amount_new-($total_amount_new*($product['discount_applied']/100));
                $product['amp5'] = 500-$product['total_points'];
                $product['points_alert_message'] = "<span class=text-success>Ya tienes el 15% de descuento</span><br>
                                                    <span class=text-success>Ya tienes el 20% de descuento</span><br>
                                                    <span class=text-success>Ya tienes el 25% de descuento</span><br>
                                                    <span class=text-success>Ya tienes el 30% de descuento</span><br>
                                                    Te falta ".$product['amp5']." puntos para llegar al 35%";
                
            }elseif($points_new>=500){
            //}elseif($points_new>499){
            //}else{

                $product['discount_applied'] = 35;
                $product['discount_amount'] = $subtotal_new*($product['discount_applied']/100);
                $product['total'] = $total_amount_new-($total_amount_new*($product['discount_applied']/100));
                $product['points_alert_message'] = "<span class=text-success>Ya tienes el 15% de descuento</span><br>
                                                    <span class=text-success>Ya tienes el 20% de descuento</span><br>
                                                    <span class=text-success>Ya tienes el 25% de descuento</span><br>
                                                    <span class=text-success>Ya tienes el 30% de descuento</span><br>
                                                    <span class=text-success>Ya tienes el 35% de descuento</span>";
                
            }
        }else{
            if($points_new<75){

                $product['discount_applied'] = 0;
                $product['discount_amount'] = 0;
                $product['total'] = $subtotal_new;
                $product['amp1'] = 75-$points_new;
                $product['amp2'] = 125-$points_new;
                $product['amp3'] = 200-$points_new;
                $product['amp4'] = 350-$points_new;
                
                $product['points_alert_message'] = "Te falta ".$product['amp1']." puntos para llegar al 20%<br>
                                                    Te falta ".$product['amp2']." puntos para llegar al 25%<br>
                                                    Te falta ".$product['amp3']." puntos para llegar al 30%<br>
                                                    Te falta ".$product['amp4']." puntos para llegar al 35%";
    
            }elseif(($points_new>=75)&&($points_new<125)){            
    
                $product['discount_applied'] = 20;
                $product['discount_amount'] = $subtotal_new*($product['discount_applied']/100);
                $product['total'] = $total_amount_new-($total_amount_new*($product['discount_applied']/100));
                $product['amp2'] = 125-$points_new;
                $product['amp3'] = 200-$points_new;
                $product['amp4'] = 350-$points_new;
    
                $product['points_alert_message'] = "<span class=text-success>Ya tienes el 20% de descuento</span><br>
                                                    Te falta ".$product['amp2']." puntos para llegar al 25%<br>
                                                    Te falta ".$product['amp3']." puntos para llegar al 30%<br>
                                                    Te falta ".$product['amp4']." puntos para llegar al 35%";
                
            }elseif(($points_new>=125)&&($points_new<200)){
    
                $product['discount_applied'] = 25;
                $product['discount_amount'] = $subtotal_new*($product['discount_applied']/100);
                $product['total'] = $total_amount_new-($total_amount_new*($product['discount_applied']/100));     
                $product['amp3'] = 200-$points_new;
                $product['amp4'] = 350-$points_new;
    
                $product['points_alert_message'] = "<span class=text-success>Ya tienes el 20% de descuento</span><br>
                                                    <span class=text-success>Ya tienes el 25% de descuento</span><br>
                                                    Te falta ".$product['amp3']." puntos para llegar al 30%<br>
                                                    Te falta ".$product['amp4']." puntos para llegar al 35%";
                
            }elseif(($points_new>=200)&&($points_new<350)){
    
                $product['discount_applied'] = 30;
                $product['discount_amount'] = $subtotal_new*($product['discount_applied']/100);
                $product['total'] = $total_amount_new-($total_amount_new*($product['discount_applied']/100));
                $product['amp4'] = 350-$points_new;
                
                $product['points_alert_message'] = "<span class=text-success>Ya tienes el 20% de descuento</span><br>
                                                    <span class=text-success>Ya tienes el 25% de descuento</span><br>
                                                    <span class=text-success>Ya tienes el 30% de descuento</span><br>
                                                    Te falta ".$product['amp4']." puntos para llegar al 35%";
                
            }elseif($points_new>=350){
    
                $product['discount_applied'] = 35;
                $product['discount_amount'] = $subtotal_new*($product['discount_applied']/100);
                $product['total'] = $total_amount_new-($total_amount_new*($product['discount_applied']/100));
                
                $product['points_alert_message'] = "<span class=text-success>Ya tienes el 20% de descuento</span><br>
                                                    <span class=text-success>Ya tienes el 25% de descuento</span><br>
                                                    <span class=text-success>Ya tienes el 30% de descuento</span><br>
                                                    <span class=text-success>Ya tienes el 35% de descuento</span>";
                
            }
        }

        if($points_new<$commission['amp_history']){
            $product['discount_applied'] = $commission['personal_discount_history'];
            $product['discount_amount'] = $subtotal_new*($product['discount_applied']/100);
            $product['total'] = $total_amount_new-($total_amount_new*($product['discount_applied']/100));
        }
        
        
        //Recalcular Delivery
        if($department!='Departamento'){

            $cant_prod_cart = $this->orderService->get('/orders_quantity_total/'.session('session_id_order'));
            
            if($cant_prod_cart == 1){
                
                $products_recalcular = $this->orderService->get('/orders_product_shop/'.session('session_id_order'));
                
                foreach ($products_recalcular as $item) {
                    $request['sku'] = $item["sku"];
                }
                
                $deliverycost = $this->deliveryService->post('/deliverycost_new/',$request->all());
                //return $deliverycost;
                foreach ($deliverycost as $cost) {
                    $product["price"] = number_format($cost["tariff"],2);
                    $product["name"] = $cost["path_classification"];
                }

                //return response()->json($product);
                
            }else{
                
                //if($request['department'] == 'Lima'){
                if(($request['department'] == 'Lima')&&($request['province'] == 'Lima')){
                    
                    $delivery_cla = $this->deliveryService->post('/deliverycost_clasification/',$request->all());        
                    
                    foreach ($delivery_cla as $item) {
                        $request["classification"] = $item["path_classification"];
                    }

                    $kilo_aditional_a = $this->deliveryService->post('/deliverycost_aditional_lima/',$request->all());
                    
                    foreach ($kilo_aditional_a as $item2) {
                        $kilo_aditional = $item2["additional_kilogram"];
                    }
                    
                    //Traer todos los sku del carrito
                    $products = $this->orderService->get('/orders_product_shop/'.session('session_id_order'));
                    
                    foreach ($products as $item) {
                        $sku[] = $item["sku"];
                    }
                    $request['sku'] = $sku;
                    
                    
                    //Traer el producto con mayor precio base
                    $deliverycost = $this->deliveryService->post('/deliverycost_new_products_all/',$request->all());
                    
                    foreach ($deliverycost as $cost) {
                        $product_base = $cost["product"];
                        $tariff = $cost["tariff"];
                        $path_classification = $cost["path_classification"];
                    }

                    //Todos los productos restantes
                    
                    $products_recalcular = $this->orderService->get('/orders_product_shop_all/'.session('session_id_order'));
                    
                    foreach ($products_recalcular as $item) {
                        
                        if($item["sku"]==$product_base){
                            $subtotal_recalcular[] = ($item["quantity"]-1)*$item["weight"];
                        }else{
                            $subtotal_recalcular[] = $item["quantity"]*$item["weight"];
                        }
                    }
                    
                    $request2['subtotal'] = array_sum($subtotal_recalcular);
                    

                    $cant_price_aditional = $request2['subtotal'] * $kilo_aditional;

                    $product["price"] = number_format(($tariff + $cant_price_aditional),2);
                    $product["name"] = $path_classification;
                    //return response()->json($product);
                    //return $delivery;
            
                }else{ //para provincia                

                    $deliverycost_ap = $this->deliveryService->post('/deliverycost_aditional_provinces/',$request->all());
                    
                    foreach ($deliverycost_ap as $cost) {
                        $additional_fee = $cost["additional_fee"];
                    }

                    //Traer todos los sku del carrito
                    $products = $this->orderService->get('/orders_product_shop/'.session('session_id_order'));
                    
                    foreach ($products as $item) {
                        $sku[] = $item["sku"];
                    }
                    $request['sku'] = $sku;

                    //Traer el producto con mayor precio base
                    $deliverycost = $this->deliveryService->post('/deliverycost_new_products_all/',$request->all());
                    
                    foreach ($deliverycost as $cost) {
                        $product_base = $cost["product"];
                        $tariff = $cost["tariff"];
                        $path_classification = $cost["path_classification"];
                    }
                    
                    //Todos los productos restantes
                    $products_recalcular = $this->orderService->get('/orders_product_shop_all/'.session('session_id_order'));
                    
                    foreach ($products_recalcular as $item) {
                        if($item["sku"]==$product_base){
                            $subtotal_recalcular[] = ($item["quantity"]-1)*$item["weight"];
                        }else{
                            $subtotal_recalcular[] = $item["quantity"]*$item["weight"];
                        }
                    }

                    $request2['subtotal'] = array_sum($subtotal);

                    $cant_price_aditional = $request2['subtotal'] * $additional_fee;
                    
                    $product["price"] = number_format(($tariff + $cant_price_aditional),2);
                    $product["name"] = $path_classification;

                    //return response()->json($product);
                    
                }        
            }

        }else{
            $product["price"] = 0;
            $product["name"] = '';
            //return response()->json($product);
        }


        //Resto el total_import de ese producto y actualizo mi tabla orders//for session
        session()->forget('subtotal');
        session()->forget('discount_amount');
        session()->forget('discount_applied');
        session()->forget('total_amount');
        session()->forget('total_points');
        session()->forget('total');        
        
        $product['subtotal'] = session()->increment('subtotal', $subtotal_new);
        $product['discount_amount'] = session()->increment('discount_amount', $product['discount_amount']);
        $product['discount_applied'] = session()->increment('discount_applied', $product['discount_applied']);
        $product['total_amount'] = session()->increment('total_amount', $subtotal_new);
        $product['total_points'] = session()->increment('total_points', $points_new2);
        $product['total'] = session()->increment('total', $product['total']);     
        
        $orders_update = $this->orderService->post('/orders_update/'.session('session_id_order'),$product);
        
        return response()->json($product);

    }

    public function stockproduct($id, Request $request){
        $partner_id = auth()->user()->id;
        $quantity = $request->quantity;
        $department = $request->department;        
        $stockproduct = $this->productService->get('/stockproduct/'.$id);
        
        $commission_data = $this->commissionService->get('/commissions_users/'.$partner_id);
        $commission_past = $this->commissionService->get('/commissions_users_1Mpast/'.$partner_id);

        if($commission_past!=0){
            foreach ($commission_past as $item_past) {
                $range_past = $item_past["range"];
            }
        }else{
            $range_past = 0;
        }

        foreach ($stockproduct as $dem) {
            $product['stock'] = $dem["stock"];
        }

        if($quantity>$product['stock']){
            return 1;
        }

        //return $commission_data;
        if($commission_data!=0){
            foreach ($commission_data as $item) {
                $commission['amp'] = $item["amp"];
                $commission['personal_discount'] = $item["personal_discount"];
                $commission['amp_history'] = $item["amp_history"];
                $commission['personal_discount_history'] = $item["personal_discount_history"];
            }
        }

        

        if(isset($commission['amp_history'])){
            $commission['amp_month'] = $commission['amp'];
            //$commission['amp'] = $commission['amp_history'];
            $commission['amp'] = $commission['amp'];
        }else{
            $commission['amp'] = 0;
        }
        
        if(session()->exists('session_id_order')) {
            $request['id_product'] = $id;
            //$id_order = session('session_id_order');
            $product2 = $this->orderService->post('/get_product_order/'.session('session_id_order'),$request->all());
            //$product = session('session_id_order').$id;

            if($product2){

                //$product['product2'] = 'SI HAY - UPDATE';

                foreach ($product2 as $item) {
                    $id_detail = $item["id_detail"];
                    $quantity_db = $item["quantity"];
                    $total_import_db = $item["total_import"];
                    $total_points_db = $item["points"];
                }

                foreach ($stockproduct as $item2) {
                    $product['id_order'] = session('session_id_order');
                    $product['id_product'] = $item2["id"];
                    $product['product'] = $item2["product"];
                    $product['weight'] = $item2["weight"];
                    $product['stock'] = $item2["stock"];                    
                    $total_quantity = $quantity + $quantity_db;
                    $product['quantity'] = $quantity + $quantity_db;
                    $product['unit_price'] = $item2["price"];
                    $product['total_import'] = $item2["price"]*$total_quantity;
                    $product['points'] = $item2["points"]*$total_quantity;

                    //for session
                    $subtotal = $product['total_import']-$total_import_db;
                    $total_amount = $product['total_import']-$total_import_db;
                    $total_points = $product['points']-$total_points_db;                    
                }
                
                $product['subtotal'] = $request->session()->increment('subtotal', $subtotal);
                $product['total_amount'] = $request->session()->increment('total_amount', $total_amount);
                $product['total_points'] = $request->session()->increment('total_points', $total_points);
                
                $product['total_points_cal'] = $product['total_points'] + $commission['amp'];

                /*if($product['total_points_cal']>$commission['amp_history']){
                    $product['total_points_cal'] = $product['total_points_cal'];
                }else{
                    $product['total_points_cal'] = $product['total_points'];
                }*/
                
                if(($range_past==0) || ($range_past=='Waterlife')){
                    if($product['total_points_cal']<75){

                        $product['discount_applied'] = 0;
                        $product['discount_amount'] = 0;
                        $product['total'] = $product['subtotal'];
                        $product['amp1'] = 75-$product['total_points_cal'];
                        $product['amp2'] = 125-$product['total_points_cal'];
                        $product['amp3'] = 200-$product['total_points_cal'];
                        $product['amp4'] = 350-$product['total_points_cal'];
                        $product['amp5'] = 500-$product['total_points_cal'];
                        
                        $product['points_alert_message'] = "Te falta ".$product['amp1']." puntos para llegar al 15%<br>
                                                            Te falta ".$product['amp2']." puntos para llegar al 20%<br>
                                                            Te falta ".$product['amp3']." puntos para llegar al 25%<br>
                                                            Te falta ".$product['amp4']." puntos para llegar al 30%<br>
                                                            Te falta ".$product['amp5']." puntos para llegar al 35%";

                    }elseif(($product['total_points_cal']>=75)&&($product['total_points_cal']<125)){

                        $product['discount_applied'] = 15;
                        $product['discount_amount'] = $product['subtotal']*($product['discount_applied']/100);
                        $product['total'] = $product['total_amount']-($product['total_amount']*($product['discount_applied']/100));
                        $product['amp2'] = 125-$product['total_points_cal'];
                        $product['amp3'] = 200-$product['total_points_cal'];
                        $product['amp4'] = 350-$product['total_points_cal'];
                        $product['amp5'] = 500-$product['total_points_cal'];

                        $product['points_alert_message'] = "<span class=text-success>Ya tienes el 15% de descuento</span><br>
                                                            Te falta ".$product['amp2']." puntos para llegar al 20%<br>
                                                            Te falta ".$product['amp3']." puntos para llegar al 25%<br>
                                                            Te falta ".$product['amp4']." puntos para llegar al 30%<br>
                                                            Te falta ".$product['amp5']." puntos para llegar al 35%";
                        
                    }elseif(($product['total_points_cal']>=125)&&($product['total_points_cal']<200)){

                        $product['discount_applied'] = 20;
                        $product['discount_amount'] = $product['subtotal']*($product['discount_applied']/100);
                        $product['total'] = $product['total_amount']-($product['total_amount']*($product['discount_applied']/100));     
                        $product['amp3'] = 200-$product['total_points_cal'];
                        $product['amp4'] = 350-$product['total_points_cal'];
                        $product['amp5'] = 500-$product['total_points_cal'];

                        $product['points_alert_message'] = "<span class=text-success>Ya tienes el 15% de descuento</span><br>
                                                            <span class=text-success>Ya tienes el 20% de descuento</span><br>
                                                            Te falta ".$product['amp3']." puntos para llegar al 25%<br>
                                                            Te falta ".$product['amp4']." puntos para llegar al 30%<br>
                                                            Te falta ".$product['amp5']." puntos para llegar al 35%";
                        
                    }elseif(($product['total_points_cal']>=200)&&($product['total_points_cal']<350)){

                        $product['discount_applied'] = 25;
                        $product['discount_amount'] = $product['subtotal']*($product['discount_applied']/100);
                        $product['total'] = $product['total_amount']-($product['total_amount']*($product['discount_applied']/100));
                        $product['amp4'] = 350-$product['total_points_cal'];
                        $product['amp5'] = 500-$product['total_points_cal'];
                        $product['points_alert_message'] = "<span class=text-success>Ya tienes el 15% de descuento</span><br>
                                                            <span class=text-success>Ya tienes el 20% de descuento</span><br>
                                                            <span class=text-success>Ya tienes el 25% de descuento</span><br>
                                                            Te falta ".$product['amp4']." puntos para llegar al 30%<br>
                                                            Te falta ".$product['amp5']." puntos para llegar al 35%";
                        
                    }elseif(($product['total_points_cal']>=350)&&($product['total_points_cal']<500)){

                        $product['discount_applied'] = 30;
                        $product['discount_amount'] = $product['subtotal']*($product['discount_applied']/100);
                        $product['total'] = $product['total_amount']-($product['total_amount']*($product['discount_applied']/100));
                        $product['amp5'] = 500-$product['total_points_cal'];
                        $product['points_alert_message'] = "<span class=text-success>Ya tienes el 15% de descuento</span><br>
                                                            <span class=text-success>Ya tienes el 20% de descuento</span><br>
                                                            <span class=text-success>Ya tienes el 25% de descuento</span><br>
                                                            <span class=text-success>Ya tienes el 30% de descuento</span><br>
                                                            Te falta ".$product['amp5']." puntos para llegar al 35%";
                        
                    }elseif($product['total_points_cal']>=500){

                        $product['discount_applied'] = 35;
                        $product['discount_amount'] = $product['subtotal']*($product['discount_applied']/100);
                        $product['total'] = $product['total_amount']-($product['total_amount']*($product['discount_applied']/100));
                        $product['points_alert_message'] = "<span class=text-success>Ya tienes el 15% de descuento</span><br>
                                                            <span class=text-success>Ya tienes el 20% de descuento</span><br>
                                                            <span class=text-success>Ya tienes el 25% de descuento</span><br>
                                                            <span class=text-success>Ya tienes el 30% de descuento</span><br>
                                                            <span class=text-success>Ya tienes el 35% de descuento</span>";
                        
                    }
                }else{
                    if($product['total_points_cal']<75){

                        $product['discount_applied'] = 0;
                        $product['discount_amount'] = 0;
                        $product['total'] = $product['subtotal'];
                        $product['amp1'] = 75-$product['total_points_cal'];
                        $product['amp2'] = 125-$product['total_points_cal'];
                        $product['amp3'] = 200-$product['total_points_cal'];
                        $product['amp4'] = 350-$product['total_points_cal'];
                        
                        $product['points_alert_message'] = "Te falta ".$product['amp1']." puntos para llegar al 20%<br>
                                                            Te falta ".$product['amp2']." puntos para llegar al 25%<br>
                                                            Te falta ".$product['amp3']." puntos para llegar al 30%<br>
                                                            Te falta ".$product['amp4']." puntos para llegar al 35%";
    
                    }elseif(($product['total_points_cal']>=75)&&($product['total_points_cal']<125)){
    
                        $product['discount_applied'] = 20;
                        $product['discount_amount'] = $product['subtotal']*($product['discount_applied']/100);
                        $product['total'] = $product['total_amount']-($product['total_amount']*($product['discount_applied']/100));
                        $product['amp2'] = 125-$product['total_points_cal'];
                        $product['amp3'] = 200-$product['total_points_cal'];
                        $product['amp4'] = 350-$product['total_points_cal'];
    
                        $product['points_alert_message'] = "<span class=text-success>Ya tienes el 20% de descuento</span><br>
                                                            Te falta ".$product['amp2']." puntos para llegar al 25%<br>
                                                            Te falta ".$product['amp3']." puntos para llegar al 30%<br>
                                                            Te falta ".$product['amp4']." puntos para llegar al 35%";
                        
                    }elseif(($product['total_points_cal']>=125)&&($product['total_points_cal']<200)){
    
                        $product['discount_applied'] = 25;
                        $product['discount_amount'] = $product['subtotal']*($product['discount_applied']/100);
                        $product['total'] = $product['total_amount']-($product['total_amount']*($product['discount_applied']/100));     
                        $product['amp3'] = 200-$product['total_points_cal'];
                        $product['amp4'] = 350-$product['total_points_cal'];
    
                        $product['points_alert_message'] = "<span class=text-success>Ya tienes el 20% de descuento</span><br>
                                                            <span class=text-success>Ya tienes el 25% de descuento</span><br>
                                                            Te falta ".$product['amp3']." puntos para llegar al 30%<br>
                                                            Te falta ".$product['amp4']." puntos para llegar al 35%";
                        
                    }elseif(($product['total_points_cal']>=200)&&($product['total_points_cal']<350)){
    
                        $product['discount_applied'] = 30;
                        $product['discount_amount'] = $product['subtotal']*($product['discount_applied']/100);
                        $product['total'] = $product['total_amount']-($product['total_amount']*($product['discount_applied']/100));
                        $product['amp4'] = 350-$product['total_points_cal'];
                        
                        $product['points_alert_message'] = "<span class=text-success>Ya tienes el 20% de descuento</span><br>
                                                            <span class=text-success>Ya tienes el 25% de descuento</span><br>
                                                            <span class=text-success>Ya tienes el 30% de descuento</span><br>
                                                            Te falta ".$product['amp4']." puntos para llegar al 35%";
                        
                    }elseif($product['total_points_cal']>=350){
    
                        $product['discount_applied'] = 35;
                        $product['discount_amount'] = $product['subtotal']*($product['discount_applied']/100);
                        $product['total'] = $product['total_amount']-($product['total_amount']*($product['discount_applied']/100));
                        
                        $product['points_alert_message'] = "<span class=text-success>Ya tienes el 20% de descuento</span><br>
                                                            <span class=text-success>Ya tienes el 25% de descuento</span><br>
                                                            <span class=text-success>Ya tienes el 30% de descuento</span><br>
                                                            <span class=text-success>Ya tienes el 35% de descuento</span>";
                        
                    }
                }

                if($product['total_points_cal']<$commission['amp_history']){
                    $product['discount_applied'] = $commission['personal_discount_history'];
                    $product['discount_amount'] = $product['subtotal']*($product['discount_applied']/100);
                    $product['total'] = $product['total_amount']-($product['total_amount']*($product['discount_applied']/100));
                }
                
                session()->forget('discount_amount');                
                session()->forget('total');

                $product['discount_amount'] = $request->session()->increment('discount_amount', $product['discount_amount']);
                $product['total'] = $request->session()->increment('total', $product['total']);
            
                $orders_update['subtotal'] = $product['subtotal'];
                $orders_update['discount_amount'] = $product['discount_amount'];
                $orders_update['total_amount'] = $product['total_amount'];
                $orders_update['total'] = $product['total'];
                $orders_update['discount_applied'] = $product['discount_applied'];
                $orders_update['total_points'] = $product['total_points'];

                $orders_detail = $this->orderService->post('/orders_detail_update/'.$id_detail,$product);
                $orders_update = $this->orderService->post('/orders_update/'.$product['id_order'],$orders_update);
                
                
                //Recalcular Delivery
                if($department!='Departamento'){

                    $cant_prod_cart = $this->orderService->get('/orders_quantity_total/'.session('session_id_order'));
                    
                    if($cant_prod_cart == 1){
                        
                        $products_recalcular = $this->orderService->get('/orders_product_shop/'.session('session_id_order'));
                        
                        foreach ($products_recalcular as $item) {
                            $request['sku'] = $item["sku"];
                        }
                        
                        $deliverycost = $this->deliveryService->post('/deliverycost_new/',$request->all());
                        //return $deliverycost;
                        foreach ($deliverycost as $cost) {
                            $product["price"] = number_format($cost["tariff"],2);
                            $product["name"] = $cost["path_classification"];
                        }

                        return response()->json($product);
                        
                    }else{
                        
                        //if($request['department'] == 'Lima'){
                        if(($request['department'] == 'Lima')&&($request['province'] == 'Lima')){
                            
                            $delivery_cla = $this->deliveryService->post('/deliverycost_clasification/',$request->all());        
                            
                            foreach ($delivery_cla as $item) {
                                $request["classification"] = $item["path_classification"];
                            }

                            $kilo_aditional_a = $this->deliveryService->post('/deliverycost_aditional_lima/',$request->all());
                            
                            foreach ($kilo_aditional_a as $item2) {
                                $kilo_aditional = $item2["additional_kilogram"];
                            }
                            
                            //Traer todos los sku del carrito
                            $products = $this->orderService->get('/orders_product_shop/'.session('session_id_order'));
                            
                            foreach ($products as $item) {
                                $sku[] = $item["sku"];
                            }
                            $request['sku'] = $sku;
                            
                            
                            //Traer el producto con mayor precio base
                            $deliverycost = $this->deliveryService->post('/deliverycost_new_products_all/',$request->all());
                            
                            foreach ($deliverycost as $cost) {
                                $product_base = $cost["product"];
                                $tariff = $cost["tariff"];
                                $path_classification = $cost["path_classification"];
                            }

                            //Todos los productos restantes
                            
                            $products_recalcular = $this->orderService->get('/orders_product_shop_all/'.session('session_id_order'));
                            
                            foreach ($products_recalcular as $item) {
                                
                                if($item["sku"]==$product_base){
                                    $subtotal_recalcular[] = ($item["quantity"]-1)*$item["weight"];
                                }else{
                                    $subtotal_recalcular[] = $item["quantity"]*$item["weight"];
                                }
                            }
                            
                            $request2['subtotal'] = array_sum($subtotal_recalcular);
                            

                            $cant_price_aditional = $request2['subtotal'] * $kilo_aditional;

                            $product["price"] = number_format(($tariff + $cant_price_aditional),2);
                            $product["name"] = $path_classification;
                            return response()->json($product);
                            //return $delivery;
                    
                        }else{ //para provincia                

                            $deliverycost_ap = $this->deliveryService->post('/deliverycost_aditional_provinces/',$request->all());
                            
                            foreach ($deliverycost_ap as $cost) {
                                $additional_fee = $cost["additional_fee"];
                            }

                            //Traer todos los sku del carrito
                            $products = $this->orderService->get('/orders_product_shop/'.session('session_id_order'));
                            
                            foreach ($products as $item) {
                                $sku[] = $item["sku"];
                            }
                            $request['sku'] = $sku;

                            //Traer el producto con mayor precio base
                            $deliverycost = $this->deliveryService->post('/deliverycost_new_products_all/',$request->all());
                            
                            foreach ($deliverycost as $cost) {
                                $product_base = $cost["product"];
                                $tariff = $cost["tariff"];
                                $path_classification = $cost["path_classification"];
                            }
                            
                            //Todos los productos restantes
                            $products_recalcular = $this->orderService->get('/orders_product_shop_all/'.session('session_id_order'));
                            
                            foreach ($products_recalcular as $item) {
                                if($item["sku"]==$product_base){
                                    $subtotal_recalcular[] = ($item["quantity"]-1)*$item["weight"];
                                }else{
                                    $subtotal_recalcular[] = $item["quantity"]*$item["weight"];
                                }
                            }

                            $request2['subtotal'] = array_sum($subtotal);

                            $cant_price_aditional = $request2['subtotal'] * $additional_fee;
                            
                            $product["price"] = number_format(($tariff + $cant_price_aditional),2);
                            $product["name"] = $path_classification;

                            return response()->json($product);
                            
                        }        
                    }

                }else{
                    $product["price"] = 0;
                    $product["name"] = '';
                    return response()->json($product);
                }                

            }else{
                //$product['product2'] = 'NO HAY';
                

                foreach ($stockproduct as $item) {
                    $product['id_order'] = session('session_id_order');
                    $product['sku'] = $item["sku"];
                    $product['product'] = $item["product"];
                    $product['weight'] = $item["weight"];
                    $product['stock'] = $item["stock"];
                    $product['quantity'] = $quantity;
                    $product['unit_price'] = $item["price"];
                    $product['total_import'] = $item["price"]*$quantity;
                    //$product[] = $item["stock"];
                    $product['points'] = $item["points"]*$quantity;
                    $product['id_product'] = $item["id"];
                }

                $product['subtotal'] = $request->session()->increment('subtotal', $product['total_import']);
                $product['total_amount'] = $request->session()->increment('total_amount', $product['total_import']);
                $product['total_points'] = $request->session()->increment('total_points', $product['points']);

                //return $product['total_points'];

                $product['total_points_cal'] = $product['total_points'] + $commission['amp'];
                //Add March 2023
                /*if($product['total_points_cal']>$commission['amp_history']){
                    $product['total_points_cal'] = $product['total_points_cal'];
                }else{
                    $product['total_points_cal'] = $commission['amp'];
                }*/

                

                if(($range_past==0) || ($range_past=='Waterlife')){
                    if($product['total_points_cal']<75){

                        $product['discount_applied'] = 0;
                        $product['discount_amount'] = 0;
                        $product['total'] = $product['subtotal'];
                        $product['amp1'] = 75-$product['total_points_cal'];
                        $product['amp2'] = 125-$product['total_points_cal'];
                        $product['amp3'] = 200-$product['total_points_cal'];
                        $product['amp4'] = 350-$product['total_points_cal'];
                        $product['amp5'] = 500-$product['total_points_cal'];
                        
                        $product['points_alert_message'] = "Te falta ".$product['amp1']." puntos para llegar al 15%<br>
                                                            Te falta ".$product['amp2']." puntos para llegar al 20%<br>
                                                            Te falta ".$product['amp3']." puntos para llegar al 25%<br>
                                                            Te falta ".$product['amp4']." puntos para llegar al 30%<br>
                                                            Te falta ".$product['amp5']." puntos para llegar al 35%";

                    }elseif(($product['total_points_cal']>=75)&&($product['total_points_cal']<125)){

                        $product['discount_applied'] = 15;
                        $product['discount_amount'] = $product['subtotal']*($product['discount_applied']/100);
                        $product['total'] = $product['total_amount']-($product['total_amount']*($product['discount_applied']/100));
                        $product['amp2'] = 125-$product['total_points_cal'];
                        $product['amp3'] = 200-$product['total_points_cal'];
                        $product['amp4'] = 350-$product['total_points_cal'];
                        $product['amp5'] = 500-$product['total_points_cal'];

                        $product['points_alert_message'] = "<span class=text-success>Ya tienes el 15% de descuento</span><br>
                                                            Te falta ".$product['amp2']." puntos para llegar al 20%<br>
                                                            Te falta ".$product['amp3']." puntos para llegar al 25%<br>
                                                            Te falta ".$product['amp4']." puntos para llegar al 30%<br>
                                                            Te falta ".$product['amp5']." puntos para llegar al 35%";
                        
                    }elseif(($product['total_points_cal']>=125)&&($product['total_points_cal']<200)){

                        $product['discount_applied'] = 20;
                        $product['discount_amount'] = $product['subtotal']*($product['discount_applied']/100);
                        $product['total'] = $product['total_amount']-($product['total_amount']*($product['discount_applied']/100));     
                        $product['amp3'] = 200-$product['total_points_cal'];
                        $product['amp4'] = 350-$product['total_points_cal'];
                        $product['amp5'] = 500-$product['total_points_cal'];

                        $product['points_alert_message'] = "<span class=text-success>Ya tienes el 15% de descuento</span><br>
                                                            <span class=text-success>Ya tienes el 20% de descuento</span><br>
                                                            Te falta ".$product['amp3']." puntos para llegar al 25%<br>
                                                            Te falta ".$product['amp4']." puntos para llegar al 30%<br>
                                                            Te falta ".$product['amp5']." puntos para llegar al 35%";
                        
                    }elseif(($product['total_points_cal']>=200)&&($product['total_points_cal']<350)){

                        $product['discount_applied'] = 25;
                        $product['discount_amount'] = $product['subtotal']*($product['discount_applied']/100);
                        $product['total'] = $product['total_amount']-($product['total_amount']*($product['discount_applied']/100));
                        $product['amp4'] = 350-$product['total_points_cal'];
                        $product['amp5'] = 500-$product['total_points_cal'];
                        $product['points_alert_message'] = "<span class=text-success>Ya tienes el 15% de descuento</span><br>
                                                            <span class=text-success>Ya tienes el 20% de descuento</span><br>
                                                            <span class=text-success>Ya tienes el 25% de descuento</span><br>
                                                            Te falta ".$product['amp4']." puntos para llegar al 30%<br>
                                                            Te falta ".$product['amp5']." puntos para llegar al 35%";
                        
                    }elseif(($product['total_points_cal']>=350)&&($product['total_points_cal']<500)){

                        $product['discount_applied'] = 30;
                        $product['discount_amount'] = $product['subtotal']*($product['discount_applied']/100);
                        $product['total'] = $product['total_amount']-($product['total_amount']*($product['discount_applied']/100));
                        $product['amp5'] = 500-$product['total_points_cal'];
                        $product['points_alert_message'] = "<span class=text-success>Ya tienes el 15% de descuento</span><br>
                                                            <span class=text-success>Ya tienes el 20% de descuento</span><br>
                                                            <span class=text-success>Ya tienes el 25% de descuento</span><br>
                                                            <span class=text-success>Ya tienes el 30% de descuento</span><br>
                                                            Te falta ".$product['amp5']." puntos para llegar al 35%";
                        
                    }elseif($product['total_points_cal']>=500){

                        $product['discount_applied'] = 35;
                        $product['discount_amount'] = $product['subtotal']*($product['discount_applied']/100);
                        $product['total'] = $product['total_amount']-($product['total_amount']*($product['discount_applied']/100));
                        $product['points_alert_message'] = "<span class=text-success>Ya tienes el 15% de descuento</span><br>
                                                            <span class=text-success>Ya tienes el 20% de descuento</span><br>
                                                            <span class=text-success>Ya tienes el 25% de descuento</span><br>
                                                            <span class=text-success>Ya tienes el 30% de descuento</span><br>
                                                            <span class=text-success>Ya tienes el 35% de descuento</span>";
                        
                    }
                }else{
                    if($product['total_points_cal']<75){

                        $product['discount_applied'] = 0;
                        $product['discount_amount'] = 0;
                        $product['total'] = $product['subtotal'];
                        $product['amp1'] = 75-$product['total_points_cal'];
                        $product['amp2'] = 125-$product['total_points_cal'];
                        $product['amp3'] = 200-$product['total_points_cal'];
                        $product['amp4'] = 350-$product['total_points_cal'];
                        
                        $product['points_alert_message'] = "Te falta ".$product['amp1']." puntos para llegar al 20%<br>
                                                            Te falta ".$product['amp2']." puntos para llegar al 25%<br>
                                                            Te falta ".$product['amp3']." puntos para llegar al 30%<br>
                                                            Te falta ".$product['amp4']." puntos para llegar al 35%";
    
                    }elseif(($product['total_points_cal']>=75)&&($product['total_points_cal']<125)){
    
                        $product['discount_applied'] = 20;
                        $product['discount_amount'] = $product['subtotal']*($product['discount_applied']/100);
                        $product['total'] = $product['total_amount']-($product['total_amount']*($product['discount_applied']/100));
                        $product['amp2'] = 125-$product['total_points_cal'];
                        $product['amp3'] = 200-$product['total_points_cal'];
                        $product['amp4'] = 350-$product['total_points_cal'];
    
                        $product['points_alert_message'] = "<span class=text-success>Ya tienes el 20% de descuento</span><br>
                                                            Te falta ".$product['amp2']." puntos para llegar al 25%<br>
                                                            Te falta ".$product['amp3']." puntos para llegar al 30%<br>
                                                            Te falta ".$product['amp4']." puntos para llegar al 35%";
                        
                    }elseif(($product['total_points_cal']>=125)&&($product['total_points_cal']<200)){
    
                        $product['discount_applied'] = 25;
                        $product['discount_amount'] = $product['subtotal']*($product['discount_applied']/100);
                        $product['total'] = $product['total_amount']-($product['total_amount']*($product['discount_applied']/100));     
                        $product['amp3'] = 200-$product['total_points_cal'];
                        $product['amp4'] = 350-$product['total_points_cal'];
    
                        $product['points_alert_message'] = "<span class=text-success>Ya tienes el 20% de descuento</span><br>
                                                            <span class=text-success>Ya tienes el 25% de descuento</span><br>
                                                            Te falta ".$product['amp3']." puntos para llegar al 30%<br>
                                                            Te falta ".$product['amp4']." puntos para llegar al 35%";
                        
                    }elseif(($product['total_points_cal']>=200)&&($product['total_points_cal']<350)){
    
                        $product['discount_applied'] = 30;
                        $product['discount_amount'] = $product['subtotal']*($product['discount_applied']/100);
                        $product['total'] = $product['total_amount']-($product['total_amount']*($product['discount_applied']/100));
                        $product['amp4'] = 350-$product['total_points_cal'];
                        
                        $product['points_alert_message'] = "<span class=text-success>Ya tienes el 20% de descuento</span><br>
                                                            <span class=text-success>Ya tienes el 25% de descuento</span><br>
                                                            <span class=text-success>Ya tienes el 30% de descuento</span><br>
                                                            Te falta ".$product['amp4']." puntos para llegar al 35%";
                        
                    }elseif($product['total_points_cal']>=350){
    
                        $product['discount_applied'] = 35;
                        $product['discount_amount'] = $product['subtotal']*($product['discount_applied']/100);
                        $product['total'] = $product['total_amount']-($product['total_amount']*($product['discount_applied']/100));
                        
                        $product['points_alert_message'] = "<span class=text-success>Ya tienes el 20% de descuento</span><br>
                                                            <span class=text-success>Ya tienes el 25% de descuento</span><br>
                                                            <span class=text-success>Ya tienes el 30% de descuento</span><br>
                                                            <span class=text-success>Ya tienes el 35% de descuento</span>";
                        
                    }
                }

                if($product['total_points_cal']<$commission['amp_history']){
                    $product['discount_applied'] = $commission['personal_discount_history'];
                    $product['discount_amount'] = $product['subtotal']*($product['discount_applied']/100);
                    $product['total'] = $product['total_amount']-($product['total_amount']*($product['discount_applied']/100));
                }
                
                session()->forget('discount_amount');  
                session()->forget('total');
                $product['discount_amount'] = $request->session()->increment('discount_amount', $product['discount_amount']);
                $product['total'] = $request->session()->increment('total', $product['total']);
                
                $orders_update['subtotal'] = $product['subtotal'];
                $orders_update['discount_amount'] = $product['discount_amount'];
                $orders_update['total_amount'] = $product['total_amount'];
                $orders_update['total'] = $product['total'];
                $orders_update['discount_applied'] = $product['discount_applied'];
                $orders_update['total_points'] = $product['total_points'];

                $orders_detail = $this->orderService->post('/orders_detail/'.$product['id_order'],$product);
                $orders_update = $this->orderService->post('/orders_update/'.$product['id_order'],$orders_update);

                //Recalcular Delivery
                if($department!='Departamento'){

                    $cant_prod_cart = $this->orderService->get('/orders_quantity_total/'.session('session_id_order'));
                    
                    if($cant_prod_cart == 1){
                        
                        $products_recalcular = $this->orderService->get('/orders_product_shop/'.session('session_id_order'));
                        
                        foreach ($products_recalcular as $item) {
                            $request['sku'] = $item["sku"];
                        }
                        
                        $deliverycost = $this->deliveryService->post('/deliverycost_new/',$request->all());
                        //return $deliverycost;
                        foreach ($deliverycost as $cost) {
                            $product["price"] = number_format($cost["tariff"],2);
                            $product["name"] = $cost["path_classification"];
                        }

                        return response()->json($product);
                        
                    }else{
                        
                        //if($request['department'] == 'Lima'){
                        if(($request['department'] == 'Lima')&&($request['province'] == 'Lima')){
                            
                            $delivery_cla = $this->deliveryService->post('/deliverycost_clasification/',$request->all());        
                            
                            foreach ($delivery_cla as $item) {
                                $request["classification"] = $item["path_classification"];
                            }         

                            $kilo_aditional_a = $this->deliveryService->post('/deliverycost_aditional_lima/',$request->all());
                            
                            foreach ($kilo_aditional_a as $item2) {
                                $kilo_aditional = $item2["additional_kilogram"];
                            }
                            
                            //Traer todos los sku del carrito
                            $products = $this->orderService->get('/orders_product_shop/'.session('session_id_order'));
                            
                            foreach ($products as $item) {
                                $sku[] = $item["sku"];
                            }
                            $request['sku'] = $sku;
                            
                            
                            //Traer el producto con mayor precio base
                            $deliverycost = $this->deliveryService->post('/deliverycost_new_products_all/',$request->all());
                            
                            foreach ($deliverycost as $cost) {
                                $product_base = $cost["product"];
                                $tariff = $cost["tariff"];
                                $path_classification = $cost["path_classification"];
                            }

                            //Todos los productos restantes
                            
                            $products_recalcular = $this->orderService->get('/orders_product_shop_all/'.session('session_id_order'));
                            
                            foreach ($products_recalcular as $item) {
                                
                                if($item["sku"]==$product_base){
                                    $subtotal_recalcular[] = ($item["quantity"]-1)*$item["weight"];
                                }else{
                                    $subtotal_recalcular[] = $item["quantity"]*$item["weight"];
                                }
                            }
                            
                            $request2['subtotal'] = array_sum($subtotal_recalcular);
                            

                            $cant_price_aditional = $request2['subtotal'] * $kilo_aditional;

                            $product["price"] = number_format(($tariff + $cant_price_aditional),2);
                            $product["name"] = $path_classification;
                            return response()->json($product);
                            //return $delivery;
                    
                        }else{ //para provincia                

                            $deliverycost_ap = $this->deliveryService->post('/deliverycost_aditional_provinces/',$request->all());
                            
                            foreach ($deliverycost_ap as $cost) {
                                $additional_fee = $cost["additional_fee"];
                            }

                            //Traer todos los sku del carrito
                            $products = $this->orderService->get('/orders_product_shop/'.session('session_id_order'));
                            
                            foreach ($products as $item) {
                                $sku[] = $item["sku"];
                            }
                            $request['sku'] = $sku;

                            //Traer el producto con mayor precio base
                            $deliverycost = $this->deliveryService->post('/deliverycost_new_products_all/',$request->all());
                            
                            foreach ($deliverycost as $cost) {
                                $product_base = $cost["product"];
                                $tariff = $cost["tariff"];
                                $path_classification = $cost["path_classification"];
                            }
                            
                            //Todos los productos restantes
                            $products_recalcular = $this->orderService->get('/orders_product_shop_all/'.session('session_id_order'));
                            
                            foreach ($products_recalcular as $item) {
                                if($item["sku"]==$product_base){
                                    $subtotal_recalcular[] = ($item["quantity"]-1)*$item["weight"];
                                }else{
                                    $subtotal_recalcular[] = $item["quantity"]*$item["weight"];
                                }
                            }

                            $request2['subtotal'] = array_sum($subtotal);

                            $cant_price_aditional = $request2['subtotal'] * $additional_fee;
                            
                            $product["price"] = number_format(($tariff + $cant_price_aditional),2);
                            $product["name"] = $path_classification;

                            return response()->json($product);
                            
                        }        
                    }

                }else{
                    $product["price"] = 0;
                    $product["name"] = '';
                    return response()->json($product);
                }  
            }

        }else{
            //INSERTAR la nueva orden
                
            $request['order_date'] = Carbon::now(); // '2022-07-02 11:53:05';                                     
            $request['partner_id'] = auth()->user()->id;
            $request['partner_name'] = auth()->user()->firts_name.' '.auth()->user()->last_name;
            $request['payment_status'] = 'Pendiente';
            $request['delivery_status'] = 'Pendiente';
            $request['order_session'] = 'Yes';

            $orderdata = $this->orderService->post('/orders/',$request->all());
            

            session()->put('session_id_order', $orderdata['id']);            
            
            foreach ($stockproduct as $item) {
                $product['id_order'] = session('session_id_order');
                $product['sku'] = $item["sku"];
                $product['product'] = $item["product"];
                $product['weight'] = $item["weight"];
                $product['stock'] = $item["stock"];
                $product['quantity'] = $quantity;
                $product['unit_price'] = $item["price"];
                $product['total_import'] = $item["price"]*$quantity;
                $product['points'] = $item["points"]*$quantity;
                $product['id_product'] = $item["id"];
            }
            
            
            //$product['csrf-token'] = $request["csrf-token"];
            $product['subtotal'] = $request->session()->increment('subtotal', $product['total_import']);
            $product['total_amount'] = $request->session()->increment('total_amount', $product['total_import']);
            $product['total_points'] = $request->session()->increment('total_points', $product['points']);
            
            
            if($commission_data!=0){
                
            //if($commission!=''){

            //return $commission;
            //if($commission!='[]'){
                     
                //$commission['amp'] = 0;
                $product['total_points_cal'] = $product['total_points'] + $commission['amp'];
                
                //Add March 2023
                /*if($product['total_points_cal']>$commission['amp_history']){
                    $product['total_points_cal'] = $product['total_points_cal'];
                }else{
                    $product['total_points_cal'] = $product['total_points'];
                }*/

                if(($range_past==0) || ($range_past=='Waterlife')){
                    if($product['total_points_cal']<75){

                        $product['discount_applied'] = 0;
                        $product['discount_amount'] = 0;
                        $product['total'] = $product['subtotal'];
                        $product['amp1'] = 75-$product['total_points_cal'];
                        $product['amp2'] = 125-$product['total_points_cal'];
                        $product['amp3'] = 200-$product['total_points_cal'];
                        $product['amp4'] = 350-$product['total_points_cal'];
                        $product['amp5'] = 500-$product['total_points_cal'];
                        
                        $product['points_alert_message'] = "Te falta ".$product['amp1']." puntos para llegar al 15%<br>
                                                            Te falta ".$product['amp2']." puntos para llegar al 20%<br>
                                                            Te falta ".$product['amp3']." puntos para llegar al 25%<br>
                                                            Te falta ".$product['amp4']." puntos para llegar al 30%<br>
                                                            Te falta ".$product['amp5']." puntos para llegar al 35%";

                    }elseif(($product['total_points_cal']>=75)&&($product['total_points_cal']<125)){

                        $product['discount_applied'] = 15;
                        $product['discount_amount'] = $product['subtotal']*($product['discount_applied']/100);
                        $product['total'] = $product['total_amount']-($product['total_amount']*($product['discount_applied']/100));
                        $product['amp2'] = 125-$product['total_points_cal'];
                        $product['amp3'] = 200-$product['total_points_cal'];
                        $product['amp4'] = 350-$product['total_points_cal'];
                        $product['amp5'] = 500-$product['total_points_cal'];

                        $product['points_alert_message'] = "<span class=text-success>Ya tienes el 15% de descuento</span><br>
                                                            Te falta ".$product['amp2']." puntos para llegar al 20%<br>
                                                            Te falta ".$product['amp3']." puntos para llegar al 25%<br>
                                                            Te falta ".$product['amp4']." puntos para llegar al 30%<br>
                                                            Te falta ".$product['amp5']." puntos para llegar al 35%";
                        
                    }elseif(($product['total_points_cal']>=125)&&($product['total_points_cal']<200)){

                        $product['discount_applied'] = 20;
                        $product['discount_amount'] = $product['subtotal']*($product['discount_applied']/100);
                        $product['total'] = $product['total_amount']-($product['total_amount']*($product['discount_applied']/100));     
                        $product['amp3'] = 200-$product['total_points_cal'];
                        $product['amp4'] = 350-$product['total_points_cal'];
                        $product['amp5'] = 500-$product['total_points_cal'];

                        $product['points_alert_message'] = "<span class=text-success>Ya tienes el 15% de descuento</span><br>
                                                            <span class=text-success>Ya tienes el 20% de descuento</span><br>
                                                            Te falta ".$product['amp3']." puntos para llegar al 25%<br>
                                                            Te falta ".$product['amp4']." puntos para llegar al 30%<br>
                                                            Te falta ".$product['amp5']." puntos para llegar al 35%";
                        
                    }elseif(($product['total_points_cal']>=200)&&($product['total_points_cal']<350)){

                        $product['discount_applied'] = 25;
                        $product['discount_amount'] = $product['subtotal']*($product['discount_applied']/100);
                        $product['total'] = $product['total_amount']-($product['total_amount']*($product['discount_applied']/100));
                        $product['amp4'] = 350-$product['total_points_cal'];
                        $product['amp5'] = 500-$product['total_points_cal'];
                        $product['points_alert_message'] = "<span class=text-success>Ya tienes el 15% de descuento</span><br>
                                                            <span class=text-success>Ya tienes el 20% de descuento</span><br>
                                                            <span class=text-success>Ya tienes el 25% de descuento</span><br>
                                                            Te falta ".$product['amp4']." puntos para llegar al 30%<br>
                                                            Te falta ".$product['amp5']." puntos para llegar al 35%";
                        
                    }elseif(($product['total_points_cal']>=350)&&($product['total_points_cal']<500)){

                        $product['discount_applied'] = 30;
                        $product['discount_amount'] = $product['subtotal']*($product['discount_applied']/100);
                        $product['total'] = $product['total_amount']-($product['total_amount']*($product['discount_applied']/100));
                        $product['amp5'] = 500-$product['total_points_cal'];
                        $product['points_alert_message'] = "<span class=text-success>Ya tienes el 15% de descuento</span><br>
                                                            <span class=text-success>Ya tienes el 20% de descuento</span><br>
                                                            <span class=text-success>Ya tienes el 25% de descuento</span><br>
                                                            <span class=text-success>Ya tienes el 30% de descuento</span><br>
                                                            Te falta ".$product['amp5']." puntos para llegar al 35%";
                        
                    }elseif($product['total_points_cal']>=500){

                        $product['discount_applied'] = 35;
                        $product['discount_amount'] = $product['subtotal']*($product['discount_applied']/100);
                        $product['total'] = $product['total_amount']-($product['total_amount']*($product['discount_applied']/100));
                        $product['points_alert_message'] = "<span class=text-success>Ya tienes el 15% de descuento</span><br>
                                                            <span class=text-success>Ya tienes el 20% de descuento</span><br>
                                                            <span class=text-success>Ya tienes el 25% de descuento</span><br>
                                                            <span class=text-success>Ya tienes el 30% de descuento</span><br>
                                                            <span class=text-success>Ya tienes el 35% de descuento</span>";
                        
                    }
                }else{
                    if($product['total_points_cal']<75){

                        $product['discount_applied'] = 0;
                        $product['discount_amount'] = 0;
                        $product['total'] = $product['subtotal'];
                        $product['amp1'] = 75-$product['total_points_cal'];
                        $product['amp2'] = 125-$product['total_points_cal'];
                        $product['amp3'] = 200-$product['total_points_cal'];
                        $product['amp4'] = 350-$product['total_points_cal'];
                        
                        $product['points_alert_message'] = "Te falta ".$product['amp1']." puntos para llegar al 20%<br>
                                                            Te falta ".$product['amp2']." puntos para llegar al 25%<br>
                                                            Te falta ".$product['amp3']." puntos para llegar al 30%<br>
                                                            Te falta ".$product['amp4']." puntos para llegar al 35%";
    
                    }elseif(($product['total_points_cal']>=75)&&($product['total_points_cal']<125)){
    
                        $product['discount_applied'] = 20;
                        $product['discount_amount'] = $product['subtotal']*($product['discount_applied']/100);
                        $product['total'] = $product['total_amount']-($product['total_amount']*($product['discount_applied']/100));
                        $product['amp2'] = 125-$product['total_points_cal'];
                        $product['amp3'] = 200-$product['total_points_cal'];
                        $product['amp4'] = 350-$product['total_points_cal'];
    
                        $product['points_alert_message'] = "<span class=text-success>Ya tienes el 20% de descuento</span><br>
                                                            Te falta ".$product['amp2']." puntos para llegar al 25%<br>
                                                            Te falta ".$product['amp3']." puntos para llegar al 30%<br>
                                                            Te falta ".$product['amp4']." puntos para llegar al 35%";
                        
                    }elseif(($product['total_points_cal']>=125)&&($product['total_points_cal']<200)){
    
                        $product['discount_applied'] = 25;
                        $product['discount_amount'] = $product['subtotal']*($product['discount_applied']/100);
                        $product['total'] = $product['total_amount']-($product['total_amount']*($product['discount_applied']/100));     
                        $product['amp3'] = 200-$product['total_points_cal'];
                        $product['amp4'] = 350-$product['total_points_cal'];
    
                        $product['points_alert_message'] = "<span class=text-success>Ya tienes el 20% de descuento</span><br>
                                                            <span class=text-success>Ya tienes el 25% de descuento</span><br>
                                                            Te falta ".$product['amp3']." puntos para llegar al 30%<br>
                                                            Te falta ".$product['amp4']." puntos para llegar al 35%";
                        
                    }elseif(($product['total_points_cal']>=200)&&($product['total_points_cal']<350)){
    
                        $product['discount_applied'] = 30;
                        $product['discount_amount'] = $product['subtotal']*($product['discount_applied']/100);
                        $product['total'] = $product['total_amount']-($product['total_amount']*($product['discount_applied']/100));
                        $product['amp4'] = 350-$product['total_points_cal'];
                        
                        $product['points_alert_message'] = "<span class=text-success>Ya tienes el 20% de descuento</span><br>
                                                            <span class=text-success>Ya tienes el 25% de descuento</span><br>
                                                            <span class=text-success>Ya tienes el 30% de descuento</span><br>
                                                            Te falta ".$product['amp4']." puntos para llegar al 35%";
                        
                    }elseif($product['total_points_cal']>=350){
    
                        $product['discount_applied'] = 35;
                        $product['discount_amount'] = $product['subtotal']*($product['discount_applied']/100);
                        $product['total'] = $product['total_amount']-($product['total_amount']*($product['discount_applied']/100));
                        
                        $product['points_alert_message'] = "<span class=text-success>Ya tienes el 20% de descuento</span><br>
                                                            <span class=text-success>Ya tienes el 25% de descuento</span><br>
                                                            <span class=text-success>Ya tienes el 30% de descuento</span><br>
                                                            <span class=text-success>Ya tienes el 35% de descuento</span>";
                        
                    }
                }

                if($product['total_points_cal']<$commission['amp_history']){
                    $product['discount_applied'] = $commission['personal_discount_history'];
                    $product['discount_amount'] = $product['subtotal']*($product['discount_applied']/100);
                    $product['total'] = $product['total_amount']-($product['total_amount']*($product['discount_applied']/100));
                }

            }else{
                
                if(($range_past==0) || ($range_past=='Waterlife')){
                    if($product['total_points']<75){

                        $product['discount_applied'] = 0;
                        $product['discount_amount'] = 0;
                        $product['total'] = $product['subtotal'];
                        $product['amp1'] = 75-$product['total_points'];
                        $product['amp2'] = 125-$product['total_points'];
                        $product['amp3'] = 200-$product['total_points'];
                        $product['amp4'] = 350-$product['total_points'];
                        $product['amp5'] = 500-$product['total_points'];
                        
                        $product['points_alert_message'] = "Te falta ".$product['amp1']." puntos para llegar al 15%<br>
                                                            Te falta ".$product['amp2']." puntos para llegar al 20%<br>
                                                            Te falta ".$product['amp3']." puntos para llegar al 25%<br>
                                                            Te falta ".$product['amp4']." puntos para llegar al 30%<br>
                                                            Te falta ".$product['amp5']." puntos para llegar al 35%";

                    }elseif(($product['total_points']>=75)&&($product['total_points']<125)){

                        $product['discount_applied'] = 15;
                        $product['discount_amount'] = $product['subtotal']*($product['discount_applied']/100);
                        $product['total'] = $product['total_amount']-($product['total_amount']*($product['discount_applied']/100));
                        $product['amp2'] = 125-$product['total_points'];
                        $product['amp3'] = 200-$product['total_points'];
                        $product['amp4'] = 350-$product['total_points'];
                        $product['amp5'] = 500-$product['total_points'];

                        $product['points_alert_message'] = "<span class=text-success>Ya tienes el 15% de descuento</span><br>
                                                            Te falta ".$product['amp2']." puntos para llegar al 20%<br>
                                                            Te falta ".$product['amp3']." puntos para llegar al 25%<br>
                                                            Te falta ".$product['amp4']." puntos para llegar al 30%<br>
                                                            Te falta ".$product['amp5']." puntos para llegar al 35%";
                        
                    }elseif(($product['total_points']>=125)&&($product['total_points']<200)){

                        $product['discount_applied'] = 20;
                        $product['discount_amount'] = $product['subtotal']*($product['discount_applied']/100);
                        $product['total'] = $product['total_amount']-($product['total_amount']*($product['discount_applied']/100));     
                        $product['amp3'] = 200-$product['total_points'];
                        $product['amp4'] = 350-$product['total_points'];
                        $product['amp5'] = 500-$product['total_points'];

                        $product['points_alert_message'] = "<span class=text-success>Ya tienes el 15% de descuento</span><br>
                                                            <span class=text-success>Ya tienes el 20% de descuento</span><br>
                                                            Te falta ".$product['amp3']." puntos para llegar al 25%<br>
                                                            Te falta ".$product['amp4']." puntos para llegar al 30%<br>
                                                            Te falta ".$product['amp5']." puntos para llegar al 35%";
                        
                    }elseif(($product['total_points']>=200)&&($product['total_points']<350)){

                        $product['discount_applied'] = 25;
                        $product['discount_amount'] = $product['subtotal']*($product['discount_applied']/100);
                        $product['total'] = $product['total_amount']-($product['total_amount']*($product['discount_applied']/100));
                        $product['amp4'] = 350-$product['total_points'];
                        $product['amp5'] = 500-$product['total_points'];
                        $product['points_alert_message'] = "<span class=text-success>Ya tienes el 15% de descuento</span><br>
                                                            <span class=text-success>Ya tienes el 20% de descuento</span><br>
                                                            <span class=text-success>Ya tienes el 25% de descuento</span><br>
                                                            Te falta ".$product['amp4']." puntos para llegar al 30%<br>
                                                            Te falta ".$product['amp5']." puntos para llegar al 35%";
                        
                    }elseif(($product['total_points']>=350)&&($product['total_points']<500)){

                        $product['discount_applied'] = 30;
                        $product['discount_amount'] = $product['subtotal']*($product['discount_applied']/100);
                        $product['total'] = $product['total_amount']-($product['total_amount']*($product['discount_applied']/100));
                        $product['amp5'] = 500-$product['total_points'];
                        $product['points_alert_message'] = "<span class=text-success>Ya tienes el 15% de descuento</span><br>
                                                            <span class=text-success>Ya tienes el 20% de descuento</span><br>
                                                            <span class=text-success>Ya tienes el 25% de descuento</span><br>
                                                            <span class=text-success>Ya tienes el 30% de descuento</span><br>
                                                            Te falta ".$product['amp5']." puntos para llegar al 35%";
                        
                    }elseif($product['total_points']>=500){

                        $product['discount_applied'] = 35;
                        $product['discount_amount'] = $product['subtotal']*($product['discount_applied']/100);
                        $product['total'] = $product['total_amount']-($product['total_amount']*($product['discount_applied']/100));
                        $product['points_alert_message'] = "<span class=text-success>Ya tienes el 15% de descuento</span><br>
                                                            <span class=text-success>Ya tienes el 20% de descuento</span><br>
                                                            <span class=text-success>Ya tienes el 25% de descuento</span><br>
                                                            <span class=text-success>Ya tienes el 30% de descuento</span><br>
                                                            <span class=text-success>Ya tienes el 35% de descuento</span>";
                        
                    }
                }else{
                    if($product['total_points']<75){

                        $product['discount_applied'] = 0;
                        $product['discount_amount'] = 0;
                        $product['total'] = $product['subtotal'];
                        $product['amp1'] = 75-$product['total_points'];
                        $product['amp2'] = 125-$product['total_points'];
                        $product['amp3'] = 200-$product['total_points'];
                        $product['amp4'] = 350-$product['total_points'];
                        
                        $product['points_alert_message'] = "Te falta ".$product['amp1']." puntos para llegar al 20%<br>
                                                            Te falta ".$product['amp2']." puntos para llegar al 25%<br>
                                                            Te falta ".$product['amp3']." puntos para llegar al 30%<br>
                                                            Te falta ".$product['amp4']." puntos para llegar al 35%";
    
                    }elseif(($product['total_points']>=75)&&($product['total_points']<125)){
    
                        $product['discount_applied'] = 20;
                        $product['discount_amount'] = $product['subtotal']*($product['discount_applied']/100);
                        $product['total'] = $product['total_amount']-($product['total_amount']*($product['discount_applied']/100));
                        $product['amp2'] = 125-$product['total_points'];
                        $product['amp3'] = 200-$product['total_points'];
                        $product['amp4'] = 350-$product['total_points'];
    
                        $product['points_alert_message'] = "<span class=text-success>Ya tienes el 20% de descuento</span><br>
                                                            Te falta ".$product['amp2']." puntos para llegar al 25%<br>
                                                            Te falta ".$product['amp3']." puntos para llegar al 30%<br>
                                                            Te falta ".$product['amp4']." puntos para llegar al 35%";
                        
                    }elseif(($product['total_points']>=125)&&($product['total_points']<200)){
    
                        $product['discount_applied'] = 25;
                        $product['discount_amount'] = $product['subtotal']*($product['discount_applied']/100);
                        $product['total'] = $product['total_amount']-($product['total_amount']*($product['discount_applied']/100));     
                        $product['amp3'] = 200-$product['total_points'];
                        $product['amp4'] = 350-$product['total_points'];
    
                        $product['points_alert_message'] = "<span class=text-success>Ya tienes el 20% de descuento</span><br>
                                                            <span class=text-success>Ya tienes el 25% de descuento</span><br>
                                                            Te falta ".$product['amp3']." puntos para llegar al 30%<br>
                                                            Te falta ".$product['amp4']." puntos para llegar al 35%";
                        
                    }elseif(($product['total_points']>=200)&&($product['total_points']<350)){
    
                        $product['discount_applied'] = 30;
                        $product['discount_amount'] = $product['subtotal']*($product['discount_applied']/100);
                        $product['total'] = $product['total_amount']-($product['total_amount']*($product['discount_applied']/100));
                        $product['amp4'] = 350-$product['total_points'];
                        
                        $product['points_alert_message'] = "<span class=text-success>Ya tienes el 20% de descuento</span><br>
                                                            <span class=text-success>Ya tienes el 25% de descuento</span><br>
                                                            <span class=text-success>Ya tienes el 30% de descuento</span><br>
                                                            Te falta ".$product['amp4']." puntos para llegar al 35%";
                        
                    }elseif($product['total_points']>=350){
    
                        $product['discount_applied'] = 35;
                        $product['discount_amount'] = $product['subtotal']*($product['discount_applied']/100);
                        $product['total'] = $product['total_amount']-($product['total_amount']*($product['discount_applied']/100));
                        
                        $product['points_alert_message'] = "<span class=text-success>Ya tienes el 20% de descuento</span><br>
                                                            <span class=text-success>Ya tienes el 25% de descuento</span><br>
                                                            <span class=text-success>Ya tienes el 30% de descuento</span><br>
                                                            <span class=text-success>Ya tienes el 35% de descuento</span>";
                        
                    }
                }
            }

            $product['discount_amount'] = $request->session()->increment('discount_amount', $product['discount_amount']);
            $product['total'] = $request->session()->increment('total', $product['total']);
            
            $orders_update['subtotal'] = $product['subtotal'].'anthony';
            $orders_update['discount_amount'] = $product['discount_amount'];
            $orders_update['total_amount'] = $product['total_amount'];
            $orders_update['total'] = $product['total'];
            $orders_update['discount_applied'] = $product['discount_applied'];
            $orders_update['total_points'] = $product['total_points'];

            $orders_detail = $this->orderService->post('/orders_detail/'.$product['id_order'],$product);
            $orders_update = $this->orderService->post('/orders_update/'.$product['id_order'],$orders_update);
            
            //Recalcular Delivery
            if($department!='Departamento'){

                $cant_prod_cart = $this->orderService->get('/orders_quantity_total/'.session('session_id_order'));
                
                if($cant_prod_cart == 1){
                    
                    $products_recalcular = $this->orderService->get('/orders_product_shop/'.session('session_id_order'));
                    
                    foreach ($products_recalcular as $item) {
                        $request['sku'] = $item["sku"];
                    }
                    
                    $deliverycost = $this->deliveryService->post('/deliverycost_new/',$request->all());
                    //return $deliverycost;
                    foreach ($deliverycost as $cost) {
                        $product["price"] = number_format($cost["tariff"],2);
                        $product["name"] = $cost["path_classification"];
                    }

                    return response()->json($product);
                    
                }else{
                    
                    //if($request['department'] == 'Lima'){
                    if(($request['department'] == 'Lima')&&($request['province'] == 'Lima')){
                        
                        $delivery_cla = $this->deliveryService->post('/deliverycost_clasification/',$request->all());        
                        
                        foreach ($delivery_cla as $item) {
                            $request["classification"] = $item["path_classification"];
                        }         

                        $kilo_aditional_a = $this->deliveryService->post('/deliverycost_aditional_lima/',$request->all());
                        
                        foreach ($kilo_aditional_a as $item2) {
                            $kilo_aditional = $item2["additional_kilogram"];
                        }
                        
                        //Traer todos los sku del carrito
                        $products = $this->orderService->get('/orders_product_shop/'.session('session_id_order'));
                        
                        foreach ($products as $item) {
                            $sku[] = $item["sku"];
                        }
                        $request['sku'] = $sku;
                        
                        
                        //Traer el producto con mayor precio base
                        $deliverycost = $this->deliveryService->post('/deliverycost_new_products_all/',$request->all());
                        
                        foreach ($deliverycost as $cost) {
                            $product_base = $cost["product"];
                            $tariff = $cost["tariff"];
                            $path_classification = $cost["path_classification"];
                        }

                        //Todos los productos restantes
                        
                        $products_recalcular = $this->orderService->get('/orders_product_shop_all/'.session('session_id_order'));
                        
                        foreach ($products_recalcular as $item) {
                            
                            if($item["sku"]==$product_base){
                                $subtotal_recalcular[] = ($item["quantity"]-1)*$item["weight"];
                            }else{
                                $subtotal_recalcular[] = $item["quantity"]*$item["weight"];
                            }
                        }
                        
                        $request2['subtotal'] = array_sum($subtotal_recalcular);
                        

                        $cant_price_aditional = $request2['subtotal'] * $kilo_aditional;

                        $product["price"] = number_format(($tariff + $cant_price_aditional),2);
                        $product["name"] = $path_classification;
                        return response()->json($product);
                        //return $delivery;
                
                    }else{ //para provincia                

                        $deliverycost_ap = $this->deliveryService->post('/deliverycost_aditional_provinces/',$request->all());
                        
                        foreach ($deliverycost_ap as $cost) {
                            $additional_fee = $cost["additional_fee"];
                        }

                        //Traer todos los sku del carrito
                        $products = $this->orderService->get('/orders_product_shop/'.session('session_id_order'));
                        
                        foreach ($products as $item) {
                            $sku[] = $item["sku"];
                        }
                        $request['sku'] = $sku;

                        //Traer el producto con mayor precio base
                        $deliverycost = $this->deliveryService->post('/deliverycost_new_products_all/',$request->all());
                        
                        foreach ($deliverycost as $cost) {
                            $product_base = $cost["product"];
                            $tariff = $cost["tariff"];
                            $path_classification = $cost["path_classification"];
                        }
                        
                        //Todos los productos restantes
                        $products_recalcular = $this->orderService->get('/orders_product_shop_all/'.session('session_id_order'));
                        
                        foreach ($products_recalcular as $item) {
                            if($item["sku"]==$product_base){
                                $subtotal_recalcular[] = ($item["quantity"]-1)*$item["weight"];
                            }else{
                                $subtotal_recalcular[] = $item["quantity"]*$item["weight"];
                            }
                        }

                        $request2['subtotal'] = array_sum($subtotal);

                        $cant_price_aditional = $request2['subtotal'] * $additional_fee;
                        
                        $product["price"] = number_format(($tariff + $cant_price_aditional),2);
                        $product["name"] = $path_classification;

                        return response()->json($product);
                        
                    }        
                }

            }else{
                $product["price"] = 0;
                $product["name"] = '';
                return response()->json($product);
            }  
            
        }
    }

    public function createOrder(Request $request){
        
        //$SECRET_KEY = "sk_test_04aff21ada451a4c"; //key culqi
        //$SECRET_KEY = "sk_test_lRRwFYxWA7IBjDCu"; //key dev waterlife
        $SECRET_KEY = "sk_live_ed5fb3c067744c55"; //key prod waterlife
        
        
        $culqi = new Culqi\Culqi(array('api_key' => $SECRET_KEY));

        $order = $culqi->Orders->create(
            array(
              "amount" => $request->total_amount,
              "currency_code" => "PEN",
              "description" => 'Waterlife',
              "order_number" => $request->session_order,
              "client_details" => array(
                  "first_name"=> auth()->user()->firts_name,
                  "last_name" => auth()->user()->last_name,
                  "email" => auth()->user()->email,
                  "phone_number" => auth()->user()->phone
               ),
              "expiration_date" => time() + 24*60*60,   // Orden con un dia de validez
              //"expiration_date" => time() + 60,   // Orden con una hora de validez
              "confirm"=> false,
            )
        );

        //return $order;

        $order = json_decode(json_encode($order), true);

        return response()->json($order['id']);

    }

    public function show_tarjeta(Request $request){
        $id_order = session('session_id_order');
        // Configurar tu API Key y autenticación
        //$SECRET_KEY = "sk_test_04aff21ada451a4c"; //key culqi
        //$SECRET_KEY = "sk_test_lRRwFYxWA7IBjDCu"; //key dev waterlife
        $SECRET_KEY = "sk_live_ed5fb3c067744c55"; //key prod waterlife
        
        $culqi = new Culqi\Culqi(array('api_key' => $SECRET_KEY));
        
        $token_id = $request['tkstr'];
        $type_member = $request['type_member'];
        if($type_member==1){
            $total_amount_cart = $request['total_amount_cart_in'];
        }else{
            $total_amount_cart = $request['total_amount_cart_in2'];
            $request['total_cart_in'] = $request['subtotal'];
            $request['total_amount_cart_in'] = $request['total_amount_cart_in2'];
        }

        // Creamos Cargo a una tarjeta
        $total_amount = $total_amount_cart*100;
        try {
            $charge = $culqi->Charges->create(
                array(
                "amount" => $total_amount,
                "capture" => true,
                "currency_code" => "PEN",
                "description" => "Waterlife",
                "email" => auth()->user()->email,
                "installments" => 0,
                "antifraud_details" => array(
                    "first_name"=> auth()->user()->firts_name,
                    "last_name" => auth()->user()->last_name,
                    "email" => auth()->user()->email,
                    "phone_number" => auth()->user()->phone
                ),
                "source_id" => "{$token_id}"
                )
            );

        } catch (\Throwable $th) {

            $charge = json_decode($th->getMessage());
        }

        $charge = json_decode(json_encode($charge), true);

        $data = $request->all();

        $merchant_message = '';
        $type = '';

        if ($charge['object'] != 'error') {
            if (is_array($charge) && array_key_exists('outcome', $charge)) {
                if($charge['outcome']['type'] == 'venta_exitosa'){
                    $data['payment_status'] = 'Pagado';
                    $data['token_culqi'] = $charge['id'];
                    $data_buy['first_buy'] = 'yes';
                    $merchant_message = $charge['outcome']['merchant_message'];
                    $type = 'venta_exitosa';
                    //$sumarAmp = $this->commissionService->post('/sum-amp/'.auth()->user()->id, $data);

                    if(auth()->user()->id != 1 ){

                        //Commissions Insert Data
                        $request2['id_order'] = session('session_id_order');
                        $request2['partner_id'] = auth()->user()->id;
                        $request2['partner_name'] = auth()->user()->firts_name.' '.auth()->user()->last_name;
                        $request2['amp'] = $request['amp'];
                        $request2['personal_discount'] = $request['discount_applied_cart_in'];

                        $request2['type'] = $request['type_member'];
                        $request2['amount'] = $total_amount_cart;
                        $request2['points'] = $request['total_points'];                        
                                                
                        //For Me
                        $commission = $this->commissionService->post('/commissions_users_exists/'.$request2['partner_id'],$request2);

                        foreach ($commission as $item) {
                            $commission_id = $item["id"];
                            $commission_amp = $item["amp"];
                        }

                        $commission_detail = $this->commissionService->post('/commission_detail/'.$commission_id,$request2);
                    }

                    if(auth()->user()->id != 1 ){
                        $order[] = $this->orderService->post('/orders_payment_tarjeta/'.$id_order, $data);

                        $first_buy = $this->orderService->get('/orders_first_buy/'.$request2['partner_id']);
                        if($first_buy==0){
                            $this->orderService->post('/orders_update_orders_first_buy/'.$id_order, $data_buy);
                        }
                    }
            
                    
                    if((auth()->user()->status == 'I' ) && $commission_amp>=75){
                        $user = User::findOrFail(auth()->user()->id);
                        $user->status = 'A';
                        $user->update();
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
            
                    //Data to Email
                    foreach ($order as $item) {
                        $id = $item["id"];
                        $order_date = $item["order_date"];
                        $department = $item["department"];
                        $province = $item["province"];
                        $district = $item["district"];
                        $address = $item["address"];
                        $total_amount = $item["total_amount"];
                    }
            
                    $data = [
                        'firts_name' => auth()->user()->firts_name,
                        'last_name' => auth()->user()->last_name,
                        'email' => auth()->user()->email,
                        'id' => $id,
                        'order_date' => $order_date,
                        'department' => $department,
                        'province' => $province,
                        'district' => $district,
                        'address' => $address,
                        'total_amount' => $total_amount,
                    ];
            
                    Mail::to(auth()->user()->email)->send(new ShopConfirmation($data));
                    //End data to Email
                }
            }
        }

        if ($charge['object'] == 'error') {
            if (array_key_exists('charge_id', $charge)) {
                $data['payment_status'] = 'Pendiente';
                $data['token_culqi'] = $charge['charge_id'];
                $merchant_message = $charge['merchant_message'];
                $type = 'error';
            }
        }

        session()->forget('session_id_order');
        session()->forget('subtotal');
        session()->forget('discount_amount');
        session()->forget('total_points');
        session()->forget('total_points_initial');
        session()->forget('total_amount');
        session()->forget('total');  
        
        return view('home.shop_tarjeta', compact('merchant_message','type','charge'));
    }

    public function shop_admin_approve(Request $request){

        $data = $request->all();
        $data['payment_status'] = 'Pagado';

        $id_order = session('session_id_order');
        
        $type_member = $request['type_member'];

        if($type_member==1){
            $total_amount_cart = $request['total_amount_cart_in'];
        }else{
            $total_amount_cart = $request['total_amount_cart_in2'];
            $request['total_cart_in'] = $request['subtotal'];
            $request['total_amount_cart_in'] = $request['total_amount_cart_in2'];
        }


        if(auth()->user()->id != 1 ){

            //Commissions Insert Data
            $request2['id_order'] = session('session_id_order');
            $request2['partner_id'] = auth()->user()->id;
            $request2['partner_name'] = auth()->user()->firts_name.' '.auth()->user()->last_name;
            $request2['amp'] = $request['amp'];
            $request2['personal_discount'] = $request['discount_applied_cart_in'];

            $request2['type'] = $request['type_member'];
            $request2['amount'] = $total_amount_cart;
            $request2['points'] = $request['total_points'];                 
                                    
            //For Me
            $commission = $this->commissionService->post('/commissions_users_exists/'.$request2['partner_id'],$request2);
            
            foreach ($commission as $item) {
                $commission_id = $item["id"];
                $commission_amp = $item["amp"];
            }

            $commission_detail = $this->commissionService->post('/commission_detail/'.$commission_id,$request2);
        }

        if(auth()->user()->id != 1 ){
            $order[] = $this->orderService->post('/orders_payment_pending/'.$id_order, $data);

            $first_buy = $this->orderService->get('/orders_first_buy/'.$request2['partner_id']);
            if($first_buy==0){
                $this->orderService->post('/orders_update_orders_first_buy/'.$id_order, $data_buy);
            }
        }

        
        if((auth()->user()->status == 'I' ) && $commission_amp>=75){
            $user = User::findOrFail(auth()->user()->id);
            $user->status = 'A';
            $user->update();
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

        //Data to Email
        foreach ($order as $item) {
            $id = $item["id"];
            $order_date = $item["order_date"];
            $department = $item["department"];
            $province = $item["province"];
            $district = $item["district"];
            $address = $item["address"];
            $total_amount = $item["total_amount"];
        }

        $data = [
            'firts_name' => auth()->user()->firts_name,
            'last_name' => auth()->user()->last_name,
            'email' => auth()->user()->email,
            'id' => $id,
            'order_date' => $order_date,
            'department' => $department,
            'province' => $province,
            'district' => $district,
            'address' => $address,
            'total_amount' => $total_amount,
        ];

        Mail::to(auth()->user()->email)->send(new ShopConfirmation($data));
        //End data to Email


        session()->forget('session_id_order');
        session()->forget('subtotal');
        session()->forget('discount_amount');
        session()->forget('total_points');
        session()->forget('total_points_initial');
        session()->forget('total_amount');
        session()->forget('total');  
        
        return view('home.shop_admin_approve_ok');
    }

    

    public function update_order(Request $request){
        $id_order = session('session_id_order');
        
        if(auth()->user()->id != 1 ){
            $order[] = $this->orderService->post('/orders_payment_pending/'.$id_order, $request->all());

            /*foreach ($order as $item) {
                $codigo_cip = $item["codigo_cip"];
            }*/
        }
        $codigo_cip = $request->codigo_cip;

        

        session()->forget('session_id_order');
        session()->forget('subtotal');
        session()->forget('discount_amount');
        session()->forget('total_points');
        session()->forget('total_points_initial');
        session()->forget('total_amount');
        session()->forget('total');
        
        return response()->json($codigo_cip);
    }

    public function shop_pago_efectivo($codigo_cip){
        
        return view('home.shop_pago_efectivo',compact('codigo_cip'));
    }

    public function show_transferencia(Request $request){
        
        $id_order = session('session_id_order');        

        if((auth()->user()->id)!=1){
            $order = $this->orderService->post('/orders_payment_pending/'.$id_order,$request->all());

            /***
            //Commissions Insert Data
            $request2['partner_id'] = auth()->user()->id;
            $request2['partner_name'] = auth()->user()->firts_name.' '.auth()->user()->last_name;
            $request2['amp'] = $request->amp;
            $request2['personal_discount'] = $request->discount_applied_cart_in;
            $request2['range'] = auth()->user()->level;
            
            //For Me
            $commission = $this->commissionService->post('/commissions_users_exists/'.$request2['partner_id'],$request2);
                       

            //Commission for Sponsor 1er Level
            $id_sponsor = auth()->user()->id_sponsor;
            $commission_1_level['id_sponsor'] = auth()->user()->id_sponsor;

            if(($commission_1_level['id_sponsor'])!=1){
                
                if((auth()->user()->level)=='M'){

                    $profit_1_level = 10;
                    $commission_1_level['profit_1_level'] = ($request->subtotal*($profit_1_level/100));

                }elseif((auth()->user()->level)=='S'){

                    $profit_1_level = 7.5;
                    $commission_1_level['profit_1_level'] = ($request->subtotal*($profit_1_level/100));

                }elseif((auth()->user()->level)=='J'){

                    $profit_1_level = 5;
                    $commission_1_level['profit_1_level'] = ($request->subtotal*($profit_1_level/100));
                }

                $commission = $this->commissionService->post('/commissions_users_exists/'.$commission_1_level['id_sponsor'],$commission_1_level);
                foreach ($commission as $item) {
                    $commission_id = $item["id"];
                }

                //For Table Commissions_Detail
                $request_detail['type'] = $request->type_member;
                $request_detail['partner_id'] = auth()->user()->id;
                $request_detail['partner_name'] = auth()->user()->firts_name.' '.auth()->user()->last_name;
                $request_detail['level'] = auth()->user()->level;
                $request_detail['id_order'] = $id_order;
                $request_detail['date_commission'] = date("Y-m-d H:i:s");
                $request_detail['amount'] = $request->subtotal;
                $request_detail['points'] = $request->total_points;
                $request_detail['percentage'] = $request->discount_applied_cart_in;
                $request_detail['commissions_points'] = ($request->total_points*($profit_1_level/100));
                $request_detail['commissions_money'] = ($request->subtotal*($profit_1_level/100));

                $comission_detail = $this->commissionService->post('/commissions_detail/'.$commission_id,$request_detail);
            }
            //End --- Commission for Sponsor 1er Level

            //Commission for Sponsor 2do Level
            $sponsor_2_level = User::select('id_sponsor')
                    ->where('id', '=', $id_sponsor)
                    ->get();

            foreach ($sponsor_2_level as $item) {
                $id_sponsor_2_level = $item["id_sponsor"];
            }

            if(($id_sponsor_2_level)!=1){
                
                if((auth()->user()->level)=='M'){

                    $profit_2_level = 5;
                    $commission_2_level['profit_2_level'] = ($request->subtotal*($profit_2_level/100));

                }elseif((auth()->user()->level)=='S'){

                    $profit_2_level = 2.5;
                    $commission_2_level['profit_2_level'] = ($request->subtotal*($profit_2_level/100));

                }

                //For Table Commissions_Detail
                $request_detail['type'] = $request->type_member;
                $request_detail['partner_id'] = auth()->user()->id;
                $request_detail['partner_name'] = auth()->user()->firts_name.' '.auth()->user()->last_name;
                $request_detail['level'] = auth()->user()->level;
                $request_detail['id_order'] = $id_order;
                $request_detail['date_commission'] = date("Y-m-d H:i:s");
                $request_detail['amount'] = $request->subtotal;
                $request_detail['points'] = $request->total_points;
                $request_detail['percentage'] = $request->discount_applied_cart_in;
                $request_detail['commissions_points'] = ($request->total_points*($profit_2_level/100));
                $request_detail['commissions_money'] = ($request->subtotal*($profit_2_level/100));
                
                if(($id_sponsor_2_level)!=1){
                    $commission = $this->commissionService->post('/commissions_detail/'.$commission_id,$request_detail);
                }
                //End --- Commission for Sponsor 2do Level
            }
            **/
        }

        session()->forget('session_id_order');
        session()->forget('subtotal');
        session()->forget('discount_amount');
        session()->forget('total_points');
        session()->forget('total_points_initial');
        session()->forget('total_amount');
        session()->forget('total');  

        return view('home.shop_transferencia');
    }

    public function register_voucher(){

        $partner_id = auth()->user()->id;

        $orderlist = $this->orderService->get('/orders_user/'.$partner_id);

        return view('home.register_voucher', compact('orderlist'));
    }

    public function register_voucher_ok(Request $request){
        
        $order_register_voucher = $this->orderService->post('/register_voucher_ok',$request->all());
        $id_order = $request->id;

        if($order_register_voucher == 1) {
            Session::flash('message', 'Haz ingresado un número de operación que ya ha sido usado.');
            Session::flash('class', 'danger');
            return view('home.register_voucher_ok_error', compact('id_order'));
        }else{
            return  view('home.register_voucher_ok', compact('id_order'));
        }
    }

    public function register_voucher_del($id){

        $orderlist = $this->orderService->delete('/orders/'.$id);

        return redirect('register_voucher');
    }




    public function show_mauricio(){

        $partner_id = auth()->user()->id;

        $products = $this->productService->get('/products');
        $departments = $this->deliveryService->get('/departments');
        $commission = $this->commissionService->get('/commissions_users/'.$partner_id);

        //if(isset($commission)){
        if($commission!=0){
            foreach ($commission as $item) {
                $commission['amp'] = $item["amp"];
                $commission['personal_discount'] = $item["personal_discount"];
            }

            if(isset($commission['amp'])){
                $commission['amp'] = $commission['amp'];
            }else{
                $commission['amp'] = 0;
            }

            
            if($commission['amp']<75){

                $commission['amp1'] = 75-$commission['amp'];
                $commission['amp2'] = 125-$commission['amp'];
                $commission['amp3'] = 200-$commission['amp'];
                $commission['amp4'] = 350-$commission['amp'];
                $commission['amp5'] = 500-$commission['amp'];
                
                $commission['points_alert_message'] = "Te falta ".$commission['amp1']." puntos para llegar al 15%<br>
                                                    Te falta ".$commission['amp2']." puntos para llegar al 20%<br>
                                                    Te falta ".$commission['amp3']." puntos para llegar al 25%<br>
                                                    Te falta ".$commission['amp4']." puntos para llegar al 30%<br>
                                                    Te falta ".$commission['amp5']." puntos para llegar al 35%";
                

            }elseif(($commission['amp']>=75)&&($commission['amp']<125)){

                $commission['amp2'] = 125-$commission['amp'];
                $commission['amp3'] = 200-$commission['amp'];
                $commission['amp4'] = 350-$commission['amp'];
                $commission['amp5'] = 500-$commission['amp'];

                $commission['points_alert_message'] = "<span class=text-success>Ya tienes el 15% de descuento</span><br>
                                                    Te falta ".$commission['amp2']." puntos para llegar al 20%<br>
                                                    Te falta ".$commission['amp3']." puntos para llegar al 25%<br>
                                                    Te falta ".$commission['amp4']." puntos para llegar al 30%<br>
                                                    Te falta ".$commission['amp5']." puntos para llegar al 35%";
                
            }elseif(($commission['amp']>=125)&&($commission['amp']<200)){

                $commission['amp3'] = 200-$commission['amp'];
                $commission['amp4'] = 350-$commission['amp'];
                $commission['amp5'] = 500-$commission['amp'];

                $commission['points_alert_message'] = "<span class=text-success>Ya tienes el 15% de descuento</span><br>
                                                    <span class=text-success>Ya tienes el 20% de descuento</span><br>
                                                    Te falta ".$commission['amp3']." puntos para llegar al 25%<br>
                                                    Te falta ".$commission['amp4']." puntos para llegar al 30%<br>
                                                    Te falta ".$commission['amp5']." puntos para llegar al 35%";
                
            }elseif(($commission['amp']>=200)&&($commission['amp']<350)){

                $commission['amp4'] = 350-$commission['amp'];
                $commission['amp5'] = 500-$commission['amp'];

                $commission['points_alert_message'] = "<span class=text-success>Ya tienes el 15% de descuento</span><br>
                                                    <span class=text-success>Ya tienes el 20% de descuento</span><br>
                                                    <span class=text-success>Ya tienes el 25% de descuento</span><br>
                                                    Te falta ".$commission['amp4']." puntos para llegar al 30%<br>
                                                    Te falta ".$commission['amp5']." puntos para llegar al 35%";
                
            }elseif(($commission['amp']>=350)&&($commission['amp']<500)){

                $commission['amp5'] = 500-$commission['amp'];

                $commission['points_alert_message'] = "<span class=text-success>Ya tienes el 15% de descuento</span><br>
                                                    <span class=text-success>Ya tienes el 20% de descuento</span><br>
                                                    <span class=text-success>Ya tienes el 25% de descuento</span><br>
                                                    <span class=text-success>Ya tienes el 30% de descuento</span><br>
                                                    Te falta ".$commission['amp5']." puntos para llegar al 35%";
                
            }elseif($commission['amp']>=500){

                $commission['points_alert_message'] = "<span class=text-success>Ya tienes el 15% de descuento</span><br>
                                                    <span class=text-success>Ya tienes el 20% de descuento</span><br>
                                                    <span class=text-success>Ya tienes el 25% de descuento</span><br>
                                                    <span class=text-success>Ya tienes el 30% de descuento</span><br>
                                                    <span class=text-success>Ya tienes el 35% de descuento</span>";
                
            }

            
            //session()->increment('total_points_initial', $commission['amp']);
        }

        
        if(session()->exists('session_id_order')) {

            $orders = $this->orderService->get('/orders/'.session('session_id_order'));            
            $orders_detail_products = $this->orderService->get('/orders_pending_detail_products/'.session('session_id_order'));           
            return view('home.shop_mauricio', compact('products','departments','orders','orders_detail_products','commission'));
            //return 'SESSION ACTIVA'.session('session_id_order');
        }else{
            $orders = null;
            $orders_detail_products = null;
            return view('home.shop_mauricio', compact('products','departments','orders','orders_detail_products','commission'));
        }
    }
    

}

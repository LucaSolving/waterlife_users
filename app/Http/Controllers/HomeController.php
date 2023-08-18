<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\Services\DeliveryService;
use App\Services\OrderService;
use App\Services\CommissionService;
use Carbon\Carbon;
use App\Models\User;
use Hash;
use Validator;
use Illuminate\Validation\Rules\Password;

set_time_limit(300000);

class HomeController extends Controller
{
    public $orderService, $deliveryService, $commissionService;

    public function __construct(OrderService $orderService, DeliveryService $deliveryService, CommissionService $commissionService)
    {
        $this->orderService = $orderService;
        $this->deliveryService = $deliveryService;
        $this->commissionService = $commissionService;
    }

    public function show(){
        return redirect('/dashboard');
        /*if(Auth::check()){
            return view('home.index');
        }else{
            return redirect('/login');
        }*/
    }

    public function users_cierre_mes(){
        $users = User::select(array('id','firts_name','last_name','affiliation_date'))
                            ->where([['status', '!=', 'D'],['status', '!=', 'P'],])
                            ->where('type_user','=','S')
                            //->where('id', '!=', '225')
                            ->orderBy('id')
                            ->get();

        return response()->json($users);
    }

    public function users_cierre_mes_inactive_status(){

        $update = User::where('status', '=', 'A')
                        ->update(array('status' => 'I'));

        return response()->json($update);

    }

    public function count_directos_activos($partner_id){
        //Nro Consultores directos
        $user_1_level = User::select('id')
                ->where('id_sponsor', '=', $partner_id)
                ->where('status', '=', 'A')
                ->orderBy('id')
                ->count();
        
        return response()->json($user_1_level);
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

    public function profileSaveEdit(Request $request, $id){
        $this->validate( $request,[
            'firts_name'                => 'required|string',
            'last_name'                 => 'required|string',
            'mother_last_name'          => 'required|string',
            'birth_date'                => 'required|string',
            'gender'                    => 'required|string',
            'email'                     => 'required|string',
            'phone'                     => 'required|string',
            'phone_operator'            => 'required|string',
            'department'                => 'required|string',
            'province'                  => 'required|string',
            'district'                  => 'required|string',
            'address'                   => 'required|string',
        ]);

        $users              =  User::profileSaveEdit($request, $id);
        if($users->save()){
            Session::flash('message', 'Los Datos han sido Actualizado Correctamente.');
            Session::flash('class', 'success');
        return redirect('/profile/'.$request->id);
        } else {
            Session::flash('message', 'Error al actualizar los datos.');
            Session::flash('class', 'danger');
        return redirect('/profile/'.$request->id);
        }
    }

    public function profileEditBank(Request $request, $id){

        $this->validate( $request,[

        ]);

        $users = User::profileEditBank($request, $id);
        if($users->save()){
            Session::flash('message', 'Los Datos han sido Actualizado Correctamente.');
            Session::flash('class', 'success');
        return redirect('/profile/'.$request->id);
        } else {
            Session::flash('message', 'Error al actualizar los datos.');
            Session::flash('class', 'danger');
        return redirect('/profile/'.$request->id);
        }
    }

    public function updatePassword(Request $request, $id){
        $rules = [
            'password_now'           => 'required',
            'password' =>   [
                                'required',
                                'string',
                                'confirmed',
                                Password::min(8)

                            ],
        ];
        $messages = [
            'password_now.required'     => 'El campo es requerido',
            'password.required'         => 'El campo es requerido',
            'password.confirmed'        => 'Disculpe, La Nueva contraseña no coinciden.',
            'password.min'              => 'La contraseña debe tener al menos 8 caracteres.',
        ];
            $validator = Validator::make($request->all(), $rules, $messages);
            if ( $validator->fails() ) {
                $messages = $validator->messages();
                return redirect('/profile/'.$id)->withErrors($validator);
            }else{
                if(Hash::check($request->password_now, Auth::user()->password)){
                    $user = new User;
                    $user->where('password', '=', Auth::user()->password)
                    ->update(['password' => bcrypt($request->password)]);
                    Session::flash('message', 'La contraseña se ha actualizado con éxito.');
                    Session::flash('class', 'success');
                return redirect('/profile/'.$id);
                } else {
                    Session::flash('message', 'Disculpe, La contraseña no se ha podido actualizar.');
                    Session::flash('class', 'danger');
                return redirect('/profile/'.$request->id);
                }

            }
    }

    public function createPhoto(Request $request, $id){
        $this->validate( $request, [
            'image'             => 'nullable|mimes:jpeg,jpg,png,gif,svg',
        ]);
            $news               = User::createPhoto($request, $id);
            if($news->save()){
                Session::flash('message', 'La Imagen se ha agregado correctamente.');
                Session::flash('class', 'success');
                return redirect('/profile/'.$id)->with('message', 'Imagen ha sido cambiada con éxito');
            } else {
                Session::flash('message', 'Error al agregar la imagen.');
                Session::flash('class', 'danger');
                return redirect('/profile/'.$id)->with('message', 'La Imagen no pudo ser cambiada con éxito.');
            }
    }

    //End - Section Profile
    
    public function profile_update(Request $request){
        $user = Auth::user();
        $user->password_now = $request->password_now;

    }

    public function my_community(){

        $id = auth()->user()->id;
        
        $my_data = User::find($id);
        //Data de Mi Red Arbol Volumen Personal y Grupal
        $commission = $this->commissionService->get('/commissions_users/'.$id);
                
        if($commission!=0){
            foreach ($commission as $item) {
                $commission['amp'] = $item["amp"];
            }

            if(isset($commission['amp'])){
                $commission['amp'] = $commission['amp'];
            }else{
                $commission['amp'] = 0;
            }
        }else{
            $commission = [];
            $commission['amp'] = 0;
        }
        
        $user_1_level = User::select(array('id','firts_name','last_name','level','id_sponsor','status','image','phone','email'))
                            ->where ('id_sponsor', '=', $id)
                            ->where([['status', '!=', 'D'],['status', '!=', 'P'],])
                            ->orderBy('id')
                            ->get();

        // New Code
        $count_level1 = 0; //Nro de Hijos - (Nivel 1)
        $count_level2 = 0; //Nro de Nietos - (Nivel 2)
        //return $user_1_level;
        if($user_1_level!='[]'){
            foreach ($user_1_level as $item) {
                $id_1_level = $item["id"];
                $users_1_level_ids[] = $item["id"];
                $id_1_level_status = $item["status"];

                $user_2_level = User::select(array('id','firts_name','last_name','level','id_sponsor','status','image','phone','email'))
                                        ->where ('id_sponsor', '=', $id_1_level)
                                        ->where([['status', '!=', 'D'],['status', '!=', 'P'],])->get();

                
                if($user_2_level!='[]'){

                    $user_20_level[] = $user_2_level;                   
                    
                    foreach ($user_2_level as $it2) {
                        $id_2_level = $it2["id"];
                        $users_2_level_ids[] = $it2["id"];
                        $users_2A_level_ids[] = $it2["id"];
                        $id_2_level_status = $it2["status"];
                        
                        /********* 3 NIVEL  ******/
                        $user_3_level = User::select(array('id'))
                                        ->where ('id_sponsor', '=', $id_2_level)
                                        ->where([['status', '!=', 'D'],['status', '!=', 'P'],])->get();

                        if($user_3_level!='[]'){
                            foreach ($user_3_level as $it3) {
                                $id_3_level = $it3["id"];
                                $users_3_level_ids[] = $it3["id"];
                                $users_3A_level_ids[] = $it3["id"];
                                
                                /********* 4 NIVEL  ******/
                                $user_4_level = User::select(array('id'))
                                                ->where ('id_sponsor', '=', $id_3_level)
                                                ->where([['status', '!=', 'D'],['status', '!=', 'P'],])->get();

                                if($user_4_level!='[]'){
                                    foreach ($user_4_level as $it4) {
                                        $users_4_level_ids[] = $it4["id"];
                                    }
                                }else{
                                    $users_4_level_ids[] = '';
                                }
                                /******* END 4 NIVEL  ******/                                
                            }
                            // Simulación 1er Nivel
                            $users_1_2_level_ids['users_1_level_ids'] = implode(",",$users_3A_level_ids);
                            $simulation_commissions_1_2 = $this->commissionService->post('/simulation_commissions',$users_1_2_level_ids);

                            // Simulación 2er Nivel
                            $users_2_2_level_ids['users_2_level_ids'] = implode(",",$users_4_level_ids);
                            $simulation_commissions_2_2 = $this->commissionService->post('/simulation_commissions_2',$users_2_2_level_ids);
                            
                            //Volumen Grupal
                            $group_volume_2[] = $simulation_commissions_1_2 + $simulation_commissions_2_2;

                            unset($users_3A_level_ids);
                            unset($users_4_level_ids);
                            unset($users_1_2_level_ids['users_1_level_ids']);
                            unset($users_2_2_level_ids['users_2_level_ids']);
                            unset($simulation_commissions_1_2);
                            unset($simulation_commissions_2_2);

                        }else{
                            $users_3_level_ids[] = '';
                            $users_3A_level_ids[] = '';
                            $users_4_level_ids[] = '';
                            $users_1_2_level_ids['users_1_level_ids'] = '';
                            $users_2_2_level_ids['users_2_level_ids'] = '';
                            $simulation_commissions_1_2 = 0;
                            $simulation_commissions_2_2 = 0;
                            $group_volume_2[] = 0;
                        }
                        /******* END 3 NIVEL  ******/
                        //return $user_4_level;
                        $commission_2_level[] = $this->commissionService->get('/commissions_users_2/'.$id_2_level);
                        
                        
                        
                        if($id_2_level_status=='A'){
                            $count_level2++;
                        }
                    }
                              
                    
                    //$count_level2++;
                    // Simulación 1er Nivel
                    $users_1_1_level_ids['users_1_level_ids'] = implode(",",$users_2_level_ids);
                    $simulation_commissions_1_1 = $this->commissionService->post('/simulation_commissions',$users_1_1_level_ids);
                    
                    // Simulación 2er Nivel
                    $users_2_1_level_ids['users_2_level_ids'] = implode(",",$users_3_level_ids);
                    $simulation_commissions_2_1 = $this->commissionService->post('/simulation_commissions_2',$users_2_1_level_ids);
                    
                    //Volumen Grupal
                    $group_volume_1[] = $simulation_commissions_1_1 + $simulation_commissions_2_1;
                    
                    unset($users_2_level_ids);
                    unset($users_3_level_ids);
                    unset($users_1_1_level_ids['users_1_level_ids']);
                    unset($users_2_1_level_ids['users_2_level_ids']);
                    unset($simulation_commissions_1_1);
                    unset($simulation_commissions_2_1);
                    
                    
                }else{
                    $users_2_level_ids[] = '';
                    $users_3_level_ids[] = '';
                    $users_1_1_level_ids['users_1_level_ids'] = '';
                    $users_2_1_level_ids['users_2_level_ids'] = '';
                    $simulation_commissions_1_1 = 0;
                    $simulation_commissions_2_1 = 0;
                    $group_volume_1[] = 0;
                    //$users_2A_level_ids[] = '';
                    /*$user_20_level[] = '[]';
                    $commission_2_level[] = '[]';
                    $group_volume_2[] = '[]';*/

                    
                    //$count_level2 = 0;
                    //$user_2_level = '';
                }
                //return $user_20_level;
                $commission_1_level[] = $this->commissionService->get('/commissions_users_2/'.$id_1_level);
                
                                
                if($id_1_level_status=='A'){
                    $count_level1++;
                }

                
            }
        }else{
            $count_level1 = 0;
        }

        //echo $user_2_level;
        if(!isset($users_1_level_ids)){
            
            $user_20_level = '';
            $commission_1_level = '';
            $group_volume_1 = '';
            $simulation_commissions = 0;
            $commission_2_level = '';
            $group_volume_2 = '';

            // Simulación 2er Nivel
            //$users_2_level_ids['users_2_level_ids'] = implode(",",$users_2A_level_ids);
            $simulation_commissions_2 = 0;
            
        }elseif(!isset($users_2A_level_ids)){
            
            $user_20_level = '';
            $commission_2_level = '';
            $group_volume_2 = '';
            // Simulación 1er Nivel
            $users_1_level_ids['users_1_level_ids'] = implode(",",$users_1_level_ids);
            $simulation_commissions = $this->commissionService->post('/simulation_commissions',$users_1_level_ids);

            // Simulación 2er Nivel
            //$users_2_level_ids['users_2_level_ids'] = implode(",",$users_2A_level_ids);
            $simulation_commissions_2 = 0;
            
        }else{
            // Simulación 1er Nivel
            $users_1_level_ids['users_1_level_ids'] = implode(",",$users_1_level_ids);
            $simulation_commissions = $this->commissionService->post('/simulation_commissions',$users_1_level_ids);

            // Simulación 2er Nivel
            $users_2_level_ids['users_2_level_ids'] = implode(",",$users_2A_level_ids);
            $simulation_commissions_2 = $this->commissionService->post('/simulation_commissions_2',$users_2_level_ids);
        }
        //return $users_2A_level_ids;

        

        //Volumen Grupal
        $group_volume = $simulation_commissions + $simulation_commissions_2;
        
        
        return view('home.my_community', compact('my_data','count_level1','count_level2','commission','simulation_commissions','simulation_commissions_2','user_1_level','commission_1_level','group_volume','group_volume_1','user_20_level','commission_2_level','group_volume_2'));
        
    }

    public function network_partners_pre_del($id){
        
        $network_partners_pre_del = User::where('id','=',$id)
        ->delete();
        return $network_partners_pre_del;
    }

    

    public function network_partners(){
        $network_partners = User::select(array('id','firts_name','last_name','mother_last_name','id_sponsor', 'created_at','phone','email', 'level','status','affiliation_date','address','department','province','district'))
        ->where([
            ['status', '!=', 'D'],
            ['status', '!=', 'P'],
        ])
        ->where ('type_user', '=', 'S')
        ->orderBy('id', 'ASC')
        //->paginate(10);
        ->get();

        $ca = array();
        foreach ($network_partners as $index2) {
            $id_department = $index2['department'];
            $data['id_province'] = $index2['province'];
            $data['id_district'] = $index2['district'];

            $users['id'] = $index2['id'];
            $users['firts_name'] = $index2['firts_name'];
            $users['last_name'] = $index2['last_name'];
            $users['mother_last_name'] = $index2['mother_last_name'];
            $users['id_sponsor'] = $index2['id_sponsor'];
            $users['created_at'] = $index2['created_at'];
            $users['phone'] = $index2['phone'];
            $users['email'] = $index2['email'];
            $users['level'] = $index2['level'];
            $users['status'] = $index2['status'];
            
            $users['affiliation_date'] = date("Y-m-d H:i:s", strtotime($index2['affiliation_date']));
            $users['address'] = $index2['address'];
            
            $department[] = $this->deliveryService->post('/show_department2/'.$id_department,$data);
            foreach ($department as $index3) {
                $users['department'] = $index3['department'];
                $users['province'] = $index3['province'];
                $users['district'] = $index3['district'];
            }   
            /*$users['province'] = $this->deliveryService->get('/show_province2/'.$id_province); 
            $users['district'] = $this->deliveryService->get('/show_district2/'.$id_district); */
            
            array_push($ca ,$users);
        }
        return response()->json($ca);
    }

    public function partner_network($id){

        $my_data        = User::find($id);
        $user_1_level   = User::select(array('id','firts_name','last_name','level','id_sponsor','status','image','phone','email'))
                                ->where ('id_sponsor', '=', $id)
                                ->where([['status', '!=', 'D'],['status', '!=', 'P'],])
                                ->orderBy('id')
                                ->get();

        foreach ($user_1_level as $item) {
            $id_1_level = $item["id"];

            $user_2_level = User::select(array('id','firts_name','last_name','level','id_sponsor','status','phone','email','image'))
                                    ->where ('id_sponsor', '=', $id_1_level)
                                    ->where([['status', '!=', 'D'],['status', '!=', 'P'],])->get();
            $user_20_level[] = $user_2_level;
        }

        return response()->json(['my_data' => $my_data, 'user_1_level' => $user_1_level, 'user_20_level' => $user_20_level] );
    }

    public function network_partners_pre(){
        $network_partners = User::select(array('id','firts_name','last_name','mother_last_name','id_sponsor', 'created_at','email'))
        ->where('status', '=', 'P')
        ->orderBy('id', 'ASC')
        ->get();

        //echo $network_partners->created_at->format('d-m-Y');

        return response()->json($network_partners);
    }

    public function my_shopping(){

        $partner_id = auth()->user()->id;

        $orderlist = $this->orderService->get('/orders_user_paid/'.$partner_id);
        return view('home.my_shopping', compact('orderlist'));
    }

    public function dataMyShoping()
    {
        $partner_id = auth()->user()->id;

        $orderlist = $this->orderService->get('/orders_user_paid/'.$partner_id);

        setlocale(LC_TIME, 'es_ES', 'esp');
        date_default_timezone_set('America/Lima');

        $data = array_map(function($item){
            $item['periodo'] = ucfirst(strftime('%B de %Y', strtotime($item["order_date"])));
            //$item['fecha'] = date('Y-m-d', strtotime($item["order_date"]));
            return $item;
        }, $orderlist);

        return $data;
    }

    public function dataMyShopingExcel($id)
    {
        $partner_id = $id;

        $orderlist = $this->orderService->get('/orders_user_paid/'.$partner_id);

        setlocale(LC_TIME, 'es_ES', 'esp');
        date_default_timezone_set('America/Lima');

        $data = array_map(function($item){
            $item['periodo'] = ucfirst(strftime('%B de %Y', strtotime($item["order_date"])));
            $item['fecha'] = date('Y-m-d', strtotime($item["order_date"]));
            return $item;
        }, $orderlist);

        return $data;
    }

    
    public function my_shopping_detail($id){     
        $products = $this->orderService->get('/orders_pending_detail_products/'.$id);

        foreach ($products as $product) {
            /*foreach ($item as $product_detail) {
                $product[] = $product_detail["product"];
                $product[] = $product_detail["quantity"];
            }*/
        }

        //var_dump($product);

        return response()->json($products);
    }

    public function my_shopping_red(){
        return view('home.my_shopping_red');
    }

    public function data_my_shopping_red()
    {
        $partner_id = auth()->user()->id;

        /* LOS IDS DE MI RED (NIVEL 1 Y 2) */
        $user_1_level   = User::select('id')
                                ->where ('id_sponsor', '=', $partner_id)
                                ->where([['status', '!=', 'D'],['status', '!=', 'P'],])
                                ->orderBy('id')
                                ->get();

        foreach ($user_1_level as $item) {
            $id_1_level = $item["id"];
            $red_ids[] = $item["id"];

            $user_2_level = User::select('id')
                                    ->where ('id_sponsor', '=', $id_1_level)
                                    ->where([['status', '!=', 'D'],['status', '!=', 'P'],])
                                    ->orderBy('id')
                                    ->get();
            foreach ($user_2_level as $item2) {
                $red_ids[] = $item2["id"];
            }
            
        }
        //return $red_ids;
        /* END LOS IDS DE MI RED (NIVEL 1 Y 2) */
        $red_ids['red_ids'] = implode(",",$red_ids);
        $orderlist = $this->orderService->post('/orders_user_paid_by_sponsor',$red_ids);

        setlocale(LC_TIME, 'es_ES', 'esp');
        date_default_timezone_set('America/Lima');

        $data = array_map(function($item){
            $item['periodo'] = ucfirst(strftime('%B de %Y', strtotime($item["order_date"])));
            //$item['fecha'] = date('Y-m-d', strtotime($item["order_date"]));
            return $item;
        }, $orderlist);

        return $data;
    }

    public function data_my_shopping_red_excel($id)
    {
        $partner_id = $id;

        $orderlist = $this->orderService->get('/orders_user_paid_by_sponsor/'.$partner_id);

        setlocale(LC_TIME, 'es_ES', 'esp');
        date_default_timezone_set('America/Lima');

        $data = array_map(function($item){
            $item['periodo'] = ucfirst(strftime('%B de %Y', strtotime($item["order_date"])));
            $item['fecha'] = date('Y-m-d', strtotime($item["order_date"]));
            return $item;
        }, $orderlist);

        return $data;
    }

    public function my_commissions(){

        $partner_id = auth()->user()->id;

        $commissions_user_history = $this->commissionService->get('/commissions_user_history/'.$partner_id);
        
        return view('home.my_commissions', compact('commissions_user_history'));
    }

    public function dataMyCommissions()
    {
        $partner_id = auth()->user()->id;

        $commissions_user_history = $this->commissionService->get('/commissions_user_history/'.$partner_id);

        $data = array_map(function($item){
            $item['total_commissions'] = $item["commissions_1_level"] + $item["commissions_2_level"] + $item["sales_commissions"];
            return $item;
        }, $commissions_user_history);

        return $data;
    }

    public function my_commissions_detail($id){
        $commissions_user_history_detail = $this->commissionService->get('/commissions_user_history_detail/'.$id);
        $commissions_user_history_detail2 = $this->commissionService->get('/commissions_user_history_detail2/'.$id);
        
        
        return view('home.my_commissions_detail', compact('commissions_user_history_detail','commissions_user_history_detail2'));
    }

    public function my_pre_registration(){
        $partner_id = auth()->user()->id;

        $registrations = User::select(array('id','firts_name','last_name','id_sponsor','email','status'))
            ->where([['id_sponsor', '=', $partner_id],])
            ->where([['status', '=', 'P'],])
            ->orderBy('id', 'DESC')
            ->get();

        return view('home.my_pre_registration', compact('registrations'));
    }

    public function active_membership($id){
        $user = User::find($id);
        $user->status = 'A';

        $user->save();
        return response()->json($user);
    }

    public function show_user($id){
        $user = User::find($id);
        return response()->json($user);
    }

    public function update_range_user(Request $request, $partner_id){

        //return 'REPS';
        $range = $request->range;
        $status = 'I';

        $update_range_user = User::where('id', $partner_id)
                        ->update(array('level' => $range, 'status' => $status));

        return response()->json($update_range_user);
        
    }


    //Static Page
    //Term and Conditions
    public function condiciones(){
        return view('home.terms');
    }


    //Optimization
    public function network_partners_new_count(Request $request){
        $socio_a = $request->query('socio');
        $socio = strtolower($socio_a);
        $status = $request->query('status');

        $network_partners = User::select(array('id','firts_name','last_name','mother_last_name','id_sponsor', 'created_at','phone','email', 'level','status','affiliation_date','address','department','province','district'))        
            ->where([
                ['status', '!=', 'D'],
                ['status', '!=', 'P'],
                ['type_user', '=', 'S'],
            ]);

        $network_partners->when(!is_numeric($socio), function ($query) use ($socio) {
            return $query->where(function ($query) use ($socio) {
                $query->whereRaw('LOWER(firts_name) LIKE ?', ['%'.$socio.'%'])
                    ->orWhereRaw('LOWER(last_name) LIKE ?', ['%'.$socio.'%'])
                    ->orWhereRaw('LOWER(mother_last_name) LIKE ?', ['%'.$socio.'%']);
            });
        });

        $network_partners->when(is_numeric($socio), function ($query) use ($socio) {
            return $query->where('id', '=', $socio);
        });

        $network_partners->when($status, function ($query, $status) {
            return $query->whereRaw('status LIKE ?', ["%$status%"]);
        });

        $network_partners->orderBy('id', 'ASC');
        $count = $network_partners->count();

        return response()->json($count);
    }

    public function network_partners_new(Request $request){

        // Get the search query parameters from the request
        $socio_a = $request->query('socio');
        $socio = strtolower($socio_a);
        $status = $request->query('status');

        // Get the current page number from the query parameters or set to 1 by default
        $page = $request->query('page', 1);

        // Set the number of items to fetch per page
        $perPage = 10;

        $query = User::select(array('id','firts_name','last_name','mother_last_name','id_sponsor', 'created_at','phone','email', 'level','status','affiliation_date','address','department','province','district'))        
            ->where([
                ['status', '!=', 'D'],
                ['status', '!=', 'P'],
                ['type_user', '=', 'S'],
            ]);

        $query->when(!is_numeric($socio), function ($query) use ($socio) {
            return $query->where(function ($query) use ($socio) {
                $query->whereRaw('LOWER(firts_name) LIKE ?', ['%'.$socio.'%'])
                    ->orWhereRaw('LOWER(last_name) LIKE ?', ['%'.$socio.'%'])
                    ->orWhereRaw('LOWER(mother_last_name) LIKE ?', ['%'.$socio.'%']);
            });
        });

        $query->when(is_numeric($socio), function ($query) use ($socio) {
            return $query->where('id', '=', $socio);
        });

        $query->when($status, function ($query, $status) {
            return $query->whereRaw('status LIKE ?', ["%$status%"]);
        });

        
        $query->orderBy('id', 'ASC');

        // Get the total count of orders based on the search parameters
        $totalCount = $query->count();

        // Calculate the total number of pages
        $totalPages = ceil($totalCount / $perPage);

        // Fetch paginated orders based on the search parameters
        $orders = $query->offset(($page - 1) * $perPage)->limit($perPage)->get();

        $ca = array();
        foreach ($orders as $index2) {
            $id_department = $index2['department'];
            $data['id_province'] = $index2['province'];
            $data['id_district'] = $index2['district'];

            $users['id'] = $index2['id'];
            $users['firts_name'] = $index2['firts_name'];
            $users['last_name'] = $index2['last_name'];
            $users['mother_last_name'] = $index2['mother_last_name'];
            $users['id_sponsor'] = $index2['id_sponsor'];
            $users['created_at'] = $index2['created_at'];
            $users['phone'] = $index2['phone'];
            $users['email'] = $index2['email'];
            $users['level'] = $index2['level'];
            $users['status'] = $index2['status'];
            
            $users['affiliation_date'] = date("Y-m-d H:i:s", strtotime($index2['affiliation_date']));
            $users['address'] = $index2['address'];
            
            $department[] = $this->deliveryService->post('/show_department2/'.$id_department,$data);
            foreach ($department as $index3) {
                $users['department'] = $index3['department'];
                $users['province'] = $index3['province'];
                $users['district'] = $index3['district'];
            }
            
            array_push($ca ,$users);
        }

        // Return the paginated orders as JSON response
        return response()->json([
            'data' => $ca,
            'total' => $totalCount,
        ]);

    }

}

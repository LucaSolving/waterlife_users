<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\DeliveriesController;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\WebhookControllerTest;
use Illuminate\Support\Facades\Auth;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
/*Route::get('/welcome', function () {
    return view('welcome');
});*/

//Las rutas libre del middleware
Auth::routes();
//LoginController:
Route::get('/', function () {
    return redirect('/login');
});
// Route::post('/login',                        [LoginController::class, 'login']);
Route::post('/login',                           [LoginController::class, 'login']);
Route::post('/login_recovery',                  [LoginController::class, 'login_recovery']);
Route::get('/login_recovery_ok',                [LoginController::class, 'login_recovery_ok']);
Route::get('/login_recovery_mail',              [LoginController::class, 'login_recovery_mail']);
Route::post('/login_recovery_mail_ok',          [LoginController::class, 'login_recovery_mail_ok']);
Route::get('/logout',                           [LogoutController::class, 'perform']);

Route::get('/registerconfirmation',         [RegisterController::class, 'registerconfirmation']);
Route::post('/register_confirmation_ok',    [RegisterController::class, 'register_confirmation_ok']);
Route::post('/transactions_confirmation_ok',    [RegisterController::class, 'transactions_confirmation_ok']);

Route::get('/provincesservices/{id_department}', [ShopController::class, 'provinces']);
Route::get('/districtsservices/{id_province}', [ShopController::class, 'districts']);

//FALTA crear el middleware e insertar el token del admin no debería estar desprotegido esta url
Route::get('/active_membership/{id}',       [HomeController::class, 'active_membership']);
Route::get('/show_user/{id}',       [HomeController::class, 'show_user']);


//For Culqi Webhooks
Route::post('/culqi_update_order',       [WebhookController::class, 'culqi_update_order']);
Route::get('/culqi_update_order2',       [WebhookController::class, 'culqi_update_order']);
Route::get('/tarjeta_update_shop/{order_number}', [WebhookController::class, 'tarjeta_update_shop']);
//End for Culqi Webhooks

Route::get('/data_my_shopping_excel/{id}',                  [HomeController::class, 'dataMyShopingExcel']);
Route::get('/data_my_shopping_red_excel/{id}',                  [HomeController::class, 'data_my_shopping_red_excel']);

//las rutas protegidas por el middleware.
Route::group(['middleware' => 'auth'], function () {
    Route::get('/dashboard',                    [DashboardController::class, 'show']);
//RegisterController:
    Route::get('/register',                     [RegisterController::class, 'show']);
    Route::post('/register',                    [RegisterController::class, 'register']);
    Route::post('/duplic_email',                [RegisterController::class, 'duplic_email']);
    
    //Route::get('/registerok',                 [RegisterController::class, 'success']);
    Route::post('/payment_confirmation_ok',     [RegisterController::class, 'payment_confirmation_ok']);
//HomeController:
    Route::get('/home',                         [HomeController::class, 'show']);
    // -------------------------Edit Profil-----------------------------------//
    Route::get('/profile/{id}',                 [HomeController::class, 'profile']);
    Route::post('/profile/edit/{id}',           [HomeController::class, 'profileSaveEdit']);
    Route::post('/profile/edit/bank/{id}',      [HomeController::class, 'profileEditBank']);
    Route::post('profile/updatepassword/{id}',  [HomeController::class,  'updatePassword']);
    Route::post('profile/updateImagen/{id}',    [HomeController::class,  'createPhoto']);
    // -------------------------End Profil-----------------------------------//
    // Section Shop
    Route::get('/shop', [ShopController::class, 'show']);
    Route::post('/shop_tarjeta', [ShopController::class, 'show_tarjeta']);
    Route::post('/shop_admin_approve', [ShopController::class, 'shop_admin_approve']);
    Route::post('/create-order', [ShopController::class, 'createOrder']);
    Route::post('/update_order', [ShopController::class, 'update_order']);
    Route::get('/shop_pago_efectivo/{codigo_cip}', [ShopController::class, 'shop_pago_efectivo']);
    
    // End  Section Shop
    //Services
    Route::get('/productsservices', [ProductsController::class, 'productsservices']);

    
    Route::post('/deliverycost', [ShopController::class, 'deliverycost']);
    Route::post('/stockproduct/{id}', [ShopController::class, 'stockproduct']);
    Route::post('/orders_detail/{id_order}', [ShopController::class, 'stockproduct']);
    Route::post('/orderproduct/{id_order}', [ShopController::class, 'stockproduct']);
    Route::post('/orden_remove_product/{id}', [ShopController::class, 'orden_remove_product']);

    Route::get('/shop_clear', [ShopController::class, 'shop_clear']);

    Route::post('/shop_transferencia', [ShopController::class, 'show_transferencia']);
        
    Route::get('/my_community',                 [HomeController::class, 'my_community']);
    Route::get('/my_shopping_red',              [HomeController::class, 'my_shopping_red']);
    Route::get('/data_my_shopping_red',              [HomeController::class, 'data_my_shopping_red']);
    Route::get('/my_commissions',               [HomeController::class, 'my_commissions']);
    Route::get('/data_my_commissions',          [HomeController::class, 'dataMyCommissions']);
    Route::get('/my_commissions_detail/{id}',        [HomeController::class, 'my_commissions_detail']);
    Route::get('/my_pre_registration',          [HomeController::class, 'my_pre_registration']);
    Route::get('/my_shopping',                  [HomeController::class, 'my_shopping']);
    Route::get('/data_my_shopping',                  [HomeController::class, 'dataMyShoping']);
    Route::get('/my_shopping_detail/{id}',      [HomeController::class, 'my_shopping_detail']);
//ProductsController:
    Route::get('/productsservices',             [ProductsController::class, 'productsservices']);
//Services Admin
    
    Route::get('/partner_network/{id}',         [HomeController::class, 'partner_network']);
    Route::get('/partner_network_pre/{id}',         [HomeController::class, 'partner_network_pre']);
    // Register Voucher
    Route::get('/register_voucher',             [ShopController::class, 'register_voucher']);
    Route::post('/register_voucher_ok',         [ShopController::class, 'register_voucher_ok']);
    Route::get('/register_voucher_del/{id}',    [ShopController::class, 'register_voucher_del']);
    // End Register Voucher

    //Route::post('/validar_transactions_confirmation_ok', [RegisterController::class, 'validar_transactions_confirmation_ok']);
    
    //ProductsController
    Route::get('/products', [ProductsController::class, 'show']);
    //DeliveriesController
    Route::get('/deliveries', [DeliveriesController::class, 'show']);

    //Download PDF
    Route::get('/order_detail_print/{id_order}', [AdminController::class, 'order_detail_print']);
});

//For Admin - My community
Route::get('/my_data/{id}',            [AdminController::class, 'my_data']);
Route::get('/my_sponsor_nivel_1/{id}', [AdminController::class, 'my_sponsor_nivel_1']);
Route::get('/my_sponsor_nivel_2/{id}', [AdminController::class, 'my_sponsor_nivel_2']);
Route::get('/my_sponsor_nivel_3/{id}', [AdminController::class, 'my_sponsor_nivel_3']);
Route::get('/my_sponsor_nivel_4/{id}', [AdminController::class, 'my_sponsor_nivel_4']);

Route::get('/network_partners',        [HomeController::class, 'network_partners']);
Route::get('/network_partners_pre',    [HomeController::class, 'network_partners_pre']);
Route::get('/network_partners_pre_del/{id}', [HomeController::class, 'network_partners_pre_del']);


Route::get('/usersprueba',             [RegisterController::class, 'usersprueba']);
Route::post('/stockproduct_test/{id}', [ShopController::class, 'stockproduct_test']);

//Static Page
//Term and Conditions
Route::get('/condiciones',                 [HomeController::class, 'condiciones']);
Route::get('/term_privacidad',             [HomeController::class, 'term_privacidad']);
Route::get('/unsuscribe',                  [HomeController::class, 'unsuscribe']);

//Cierre de Mes
Route::get('/users_cierre_mes',            [HomeController::class, 'users_cierre_mes']);
Route::get('/users_cierre_mes_inactive_status',            [HomeController::class, 'users_cierre_mes_inactive_status']);

Route::get('/count_directos_activos/{partner_id}', [HomeController::class, 'count_directos_activos']);
Route::get('/my_sponsor_nivel_1_cronjob/{id}', [AdminController::class, 'my_sponsor_nivel_1_cronjob']);
Route::get('/my_sponsor_nivel_2_cronjob/{id}', [AdminController::class, 'my_sponsor_nivel_2_cronjob']);

Route::post('/update_range_user/{partner_id}', [HomeController::class, 'update_range_user']);

// For Dashboard - Admin
Route::get('/new_members_month/{period}',           [AdminController::class, 'new_members_month']);
Route::get('/pre_registers_users/{period}',         [AdminController::class, 'pre_registers_users']);
Route::get('/total_users',                          [AdminController::class, 'total_users']);
Route::get('/total_users_actives',                  [AdminController::class, 'total_users_actives']);


//Borrar - files para test
Route::post('/culqi_update_order_test',       [WebhookControllerTest::class, 'culqi_update_order']);



//Optimization
Route::get('/network_partners_new_count',  [HomeController::class, 'network_partners_new_count']);
Route::get('/network_partners_new',         [HomeController::class, 'network_partners_new']);




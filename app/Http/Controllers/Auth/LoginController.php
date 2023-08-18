<?php

namespace App\Http\Controllers\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\RecoveryPassword;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/login';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }


    public function login(LoginRequest $request) {
        $credentials = $request->getCredentials();

        if(!Auth::validate($credentials)):
           return redirect()->to('login')->withErrors(trans('auth.failed'));
        endif;

        $user = Auth::getProvider()->retrieveByCredentials($credentials);
        Auth::login($user);

        // $accessToken = auth()->user()->createToken('authToken')->accessToken;

        return $this->authenticated($request, $user);
    }


    protected function authenticated(Request $request, $user)
    {
        if (($user->status == 'A')||($user->status == 'I')) {
            return redirect('/dashboard');
        } else {
            $this->logout($request);
        }
        Session::flash('message', 'Disculpe, Usted no tiene acceso al sistema.');
        Session::flash('class', 'danger');
        return redirect($this->redirectTo);

    }

    public function login_recovery(Request $request){

        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)->first();

        if($user){
            $mail_token = bcrypt($request->email);

            $data = User::findOrFail($user->id);
            $data->mail_token = $mail_token;
            $data->update();

            $data = [
                'firts_name' => $user->firts_name,
                'mail_token' => $data->mail_token,
            ];

            Mail::to($request->email)
                ->send(new RecoveryPassword($data));
            return redirect('/login_recovery_ok');
        }else{
            return redirect('/login');
        }
    }

    public function login_recovery_ok(){
        return view('auth.login_recovery');
    }

    public function login_recovery_mail(Request $request){

        $mail_token = $request->mail_token;
        //return $mail_token;
        return view('auth.login_recovery_mail', compact('mail_token'));
    }

    public function login_recovery_mail_ok(Request $request){
        $request->validate([
            'password'          => 'required|min:8',
            'password_confirm'  => 'required|min:8|same:password',
        ]);
        $user = User::where('mail_token', $request->mail_token)->first();
        //$user->password = Hash::make($request->password);
        $user->setPasswordAttribute($request->password);
        $user->save();
        return redirect('/');
    }
}

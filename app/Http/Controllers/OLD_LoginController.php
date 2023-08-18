<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\RecoveryPassword;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function show(){
        if(Auth::check()){
            return redirect('/dashboard');
        }
        return view('auth.login');
    }

    public function login(LoginRequest $request) {
        $credentials = $request->getCredentials();

        if(!Auth::validate($credentials)):
           return redirect()->to('login')->withErrors(trans('auth.failed'));
        endif;
        $user = Auth::getProvider()->retrieveByCredentials($credentials);        

        Auth::login($user);

        $accessToken = auth()->user()->createToken('authToken')->accessToken;

        return $this->authenticated($request, $user);
    }
    
    protected function authenticated(Request $request, $user) 
    {
        return redirect('/dashboard');
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
            'password' => 'required|min:8',
            'password_confirm' => 'required|min:8|same:password',
        ]);
        $user = User::where('mail_token', $request->mail_token)->first();
        //$user->password = Hash::make($request->password);
        $user->setPasswordAttribute($request->password);
        $user->save();
        return redirect('/');
    }
}

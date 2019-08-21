<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\DB;
use Session;
use Socialite;
use Auth;
use App\User;
use Alert;
use DateTime;

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
    protected $redirectTo = '/principal';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //print(json_encode('xxxxx'));
        $this->middleware('guest')->except('logout');
    }

     public function index()
    {
        return view("welcome");
    }

    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function handleProviderCallback($provider)
    {
        $user = Socialite::driver($provider)->stateless()->user();
        $val = str_contains($user->email,'@flesan.com.pe');
        $value = str_contains($user->email,'@dvc.com.pe');
        if($val != false || $value != false){
            $correo = User::where('username',$user->email)
            ->where('id_aplicacion','=','4')->get();
                //print(json_encode($correo));
            if(count($correo) != 0){
                $authApp = DB::connection('pgsql')->select('select id_aplicacion from seguridadapp.aplicacion_usuario where username =\''.$user->email.'\' and id_aplicacion=4 and estado_sesion=1 and estado_validacion = 0 and (provider_id is null)');
                if(count($authApp) == 0){
                    $authUser = User::where('provider_id',$user->id)
                    ->where('id_aplicacion','=','4')->where('estado_sesion','=','1')
                    ->first();
                    if($authUser){
                        $authUser;
                    }else{
                        Alert::error('Este usuario '.$user->email.' no se encuentra activado en el sistema','Error');
                        return redirect('/');
                    }
                }elseif(count($authApp) == 1){
                    $authUser = $this->findOrCreateUser($user,$provider);
                }
                request()->session()->push('avatar',$user->avatar);
                Auth::login($authUser,true);
                return redirect($this->redirectTo);
            }else if(count($correo) == 0){
                Alert::error('Este usuario '.$user->email.' no se encuentra registrado en el sistema','Error');
                return redirect('/');
            }

        }else{
           Alert::error('Este usuario '.$user->email.' no es corporativo del Grupo Flesan','Error');
           return redirect('/');
        }
    }

    public function findOrCreateUser($user, $provider){

        $dia = new DateTime();
        $authUser = User::where('username',$user->email)
        ->where('id_aplicacion','=','4')
        ->where('estado_validacion','=','0')
        ->first();

        //print(json_encode($authUser));
        $authUser->name = $user->name;
        $authUser->fecha_validacion = $dia->format('d-m-y');
        $authUser->provider = strtoupper($provider);
        $authUser->provider_id = $user->id;
        $authUser->estado_validacion = 1;
        $authUser->save();

        if($authUser){
            return $authUser;
        }
    }

    public function sendFailedLogin($user){
        $errors = [$this->$user->email => trans('auth.failed')];
        if($user->expectsJson()){
            return response()->json($errors,422);
        }

        return redirect()->back()
            ->withInput($user->only($this->username(),'remember'))
            ->withErrors($errors);
    }
}

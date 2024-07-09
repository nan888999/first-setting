<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\Attendance;
use App\Models\User;
use App\Models\BreakTime;
use Carbon\Carbon;

class UserController extends Controller
{
    public function index(){
    if (Auth::check()) {
        $user = Auth::user();

        $workStart = Attendance::where('user_id', $user->id)->whereDate('created_at', Carbon::today()->toDateString())->get();

        $workEnd = Attendance::where('user_id', $user->id)->whereDate('updated_at', Carbon::today()->toDateString())->get();

        $breakStart = BreakTime::where('user_id', $user->id)->whereDate('created_at', Carbon::today()->toDateString())->get();

        $breakEnd = BreakTime::where('user_id', $user->id)->whereDate('updated_at', Carbon::today())->get();

        return view('index', compact('workStart', 'workEnd', 'breakStart', 'breakEnd'));
        } else {
        return redirect('/login');
        }
    }

    public function showRegister()
    {
        return view('register');
    }

    public function register(UserRequest $request)
    {
        // ユーザーの作成
        $user = User::create([
            'name'=>$request['name'],
            'email'=>$request['email'],
            'password'=>Hash::make($request['password'])
        ]);

        // ユーザーをログインさせる
        Auth::login($user);
        return redirect()->route('index');
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }

    public function showLogin()
    {
        return view('login');
    }

    public function login(LoginRequest $request) {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' =>['required'],
        ]);

        // メールアドレスが存在するか確認
        $user = User::where('email', $credentials['email'])->first();

        if($user){
            if(Auth::attempt($credentials)){
                $request->session()->regenerate();
                return redirect()->intended('/');
            }
            return back()->withErrors([
                'password' => 'パスワードが違います'
            ]);
        } else {
            return back()->withError([
                'email' => 'メールアドレスが登録されていません'
            ]);
        }
    }
}

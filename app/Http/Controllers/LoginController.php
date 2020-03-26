<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class LoginController extends Controller
{
    public function index()
    {
        $loginView = env('THEME').'.login';
       // return view('login.index');

        return view($loginView)->with('title', 'Вход на сайт');
    }

    public function login(Request $request)
    {
       // dd($request->all());
        if(auth()->attempt(['login' => $request->login, 'password' => $request->password])){
           return redirect()->route('admin.adminIndex');
          //  return redirect('/admin');
        }else{
            return redirect()->back();
        }
    }
}


<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['only' => ['home']]);
        parent::__construct();
    }

    public function index(){
        return view('index');
    }

    public function home()
    {
        return view('home');
    }

    public function locale()
    {
        $cookie = cookie()->forever('locale__myProject', request('locale'));

        cookie()->queue($cookie);

        // dd(request());
        // exit;
        return ($return = request('return')) 
            ? redirect(urldecode($return))->withCookie($cookie) 
            : redirect(route('home'))->withCookie($cookie);
    }
      
}

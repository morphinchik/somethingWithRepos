<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Gate;

class IndexController extends AdminController
{
    //
    public function __construct()
    {
    	//dd(Auth::user());
    	parent::__construct();


    	$this->template = env('THEME').'.admin.index';
       // $this->template = 'pink.admin.index';
    }

    public function index()
    {

        if (Gate::denies('VIEW_ADMIN')) {
            abort(403);
        }

    	$this->title = 'Панель администратора';

    	return $this->renderOutput();
    }

  /*  public function index()
    {
        return view('home.index');
    }

    public function admin()
    {
        return view('admin.index');
    }
    */

}

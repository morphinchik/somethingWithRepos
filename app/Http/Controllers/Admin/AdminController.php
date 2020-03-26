<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Auth;
use Menu;

class AdminController extends Controller
{
    //
    protected $p_rep;

    protected $a_rep;

    protected $user;

    protected $template;

    protected $content = FALSE;

    protected $title;

    protected $vars;

    public function __construct()
    {

    	//$this->middleware = 'auth';
    	/*$this->user = Auth::user();

    	if (!$this->user) {
    		abort(403);
    	}
        */
    }

    public function renderOutput()
    {
    	$this->vars = Arr::add($this->vars,'title',$this->title);

    	$menu = $this->getMenu();

    	$navigation = view(env('THEME').'.admin.navigation')->with('menu', $menu)->render();
    	$this->vars = Arr::add($this->vars,'navigation',$navigation);

    	if ($this->content) {
    		$this->vars = Arr::add($this->vars,'content',$this->content);
    	}

    	$footer = view(env('THEME').'.admin.footer')->render();
    	$this->vars = Arr::add($this->vars,'footer',$footer);

    	return view($this->template)->with($this->vars);
    }

    public function getMenu()
    {
    	return Menu::make('adminMenu', function($menu) {

    		$menu->add('Статьи', ['route' => 'admin.articles.index']); //admin.articles.index
    		$menu->add('Портфолио', ['route' => 'home']);
    		$menu->add('Меню', ['route' => 'admin.menus.index']);
    		$menu->add('Пользователи', ['route' => 'admin.users.index']);
    		$menu->add('Привелегии', ['route' => 'admin.permissions.index']);

    	});
    }
}

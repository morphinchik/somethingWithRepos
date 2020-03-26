<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\RolesRepository;
use App\Http\Requests\UsersRequest;
//use App\Repositories\UsersRepository;
use App\Repositories\ReposUs;

use App\User;
use Gate;
use DB;

class UsersController extends AdminController
{
    protected $us_rep;
    protected $rol_rep;
    public function __construct(RolesRepository $rol_rep, ReposUs $us_rep)
    {
        // 
        parent::__construct();
        $this->rol_rep = $rol_rep;
        $this->us_rep = $us_rep;
        $this->template = env('THEME').'.admin.users';
        /*
         if (Gate::denies('EDIT_USERS')) {
            abort(403);
        }*/
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        if (Gate::denies('EDIT_USERS')) {
            abort(403);
        }
        $users = $this->us_rep->get(); 
        //$users = User::all();
        //dd($users->roles->implode('name', ', '));
        $this->content = view(env('THEME').'.admin.users_content')->with(['users' => $users])->render();
        return $this->renderOutput();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $this->title = 'Новый пользователь';
        $roles = $this->getRoles()->reduce( function($returnRoles, $role) {
            $returnRoles[$role->id] = $role->name;
            return $returnRoles;
        }, []);
        $this->content = view(env('THEME').'.admin.users_create_content')->with('roles', $roles)->render();
        return $this->renderOutput();
    }
    public function getRoles()
    {
        return \App\Role::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UsersRequest $request)//UserRequest
    {
        //
        $result = $this->m_rep->addUser($request);
        if (is_array($result) && !empty($result['error'])) {
            return back()->with($result);
        }

        return redirect('/admin')->with($result);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $this->title = 'Редактирование пользователя - '.$user->name;
        $roles = $this->getRoles()->reduce( function($returnRoles, $role) {
            $returnRoles[$role->id] = $role->name;
            return $returnRoles;
        }, []);
        $this->content = view(env('THEME').'.admin.users_create_content')->with(['roles' => $roles, 'user' => $user])->render();
        return $this->renderOutput();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UsersRequest $request, User $user)
    {
        //
        $result = $this->us_rep->updateUser($request, $user);
        if (is_array($result) && !empty($result['error'])) {
            return back()->with($result);
        }

        return redirect('/admin')->with($result);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
        $result = $this->us_rep->deleteUser($user);
        if (is_array($result) && !empty($result['error'])) {
            return back()->with($result);
        }

        return redirect('/admin')->with($result);
    }
}

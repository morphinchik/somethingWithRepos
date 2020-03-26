<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use App\Repositories\MenusRepository;
use App\Repositories\ArticleRepository;
use App\Repositories\PortfolioRepository;
use App\Http\Requests\MenusRequest;
use App\Category;
use App\Filter;
use Gate;
use Menu;

class MenusController extends AdminController
{
    protected $m_rep;
    public function __construct(MenusRepository $m_rep, ArticleRepository $a_rep, PortfolioRepository $p_rep)
    {
        // 
        parent::__construct();
        $this->p_rep = $p_rep;
        $this->a_rep = $a_rep;
        $this->m_rep = $m_rep;
        $this->template = env('THEME').'.admin.menus';
        /*
         if (Gate::denies('VIEW_ADMIN_MENU')) {
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
        if (Gate::denies('VIEW_ADMIN_MENU')) {
            abort(403);
        }
        $this->title = 'Менеджер разделов меню';
        $menu = $this->getMenus();

        $this->content = view(env('THEME').'.admin.menus_content')->with('menus', $menu)->render();

        return $this->renderOutput();
    }
    
    public function getMenus()
    {
        //
        $menu = $this->m_rep->get();
        if ($menu->isEmpty()) {
            return FALSE;
        }
        return Menu::make('forMenuPart', function($m) use($menu){
            foreach ($menu as $item) {
                if ($item->parent == 0) {
                    $m->add($item->title, $item->path)->id($item->id);
                } else {
                    if ($m->find($item->parent)) {
                        $m->find($item->parent)->add($item->title, $item->path)->id($item->id);
                    }
                }
            }
        });
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $this->title = 'Новый пункт меню';
        $tmp = $this->getMenus()->roots();// return parent items
        $menus = $tmp->reduce( function($returnMenus, $menu) {
            $returnMenus[$menu->id] = $menu->title;
            return $returnMenus;
        }, ['0' => 'Родительский пункт меню']);// на первой итерации попадет 2 параметр функции
        $categories = Category::select(['title', 'alias', 'parent_id', 'id'])->get();
        $list = [];
        $list = Arr::add($list,'0','Не используется');
        $list = Arr::add($list,'parent','Раздел блог');
        foreach ($categories as $category) {
            if ($category->parent_id == 0) {
                $list[$category->title] = [];
            } else {
                $list[$categories->where('id', $category->parent_id)->first()->title][$category->alias] = $category->title;
            }
        }
        $articles = $this->a_rep->get(['id', 'title', 'alias']);
        $articles = $articles->reduce( function($returnArticles, $article) {
            $returnArticles[$article->alias] = $article->title;
            return $returnArticles;
        }, ['0' => 'Не используется']);
        $filters = Filter::select(['title', 'alias', 'id'])->get()->reduce( function($returnFilters, $filter) {
            $returnFilters[$filter->alias] = $filter->title;
            return $returnFilters;
        }, ['parent' => 'Раздел портфолио']);
        $portfolios = $this->p_rep->get(['title', 'alias', 'id'])->reduce( function($returnPortfolios, $portfolio) {
            $returnPortfolios[$portfolio->alias] = $portfolio->title;
            return $returnPortfolios;
        }, ['0' => 'Не используется']);
        $filters = Arr::prepend($filters, 'Не используется');
        $this->content = view(env('THEME').'.admin.menus_create_content')->with(['menus' => $menus, 'categories' => $list, 'articles' => $articles, 'filters' => $filters, 'portfolios' => $portfolios])->render();

        return $this->renderOutput();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MenusRequest $request)
    {
        //
        $result = $this->m_rep->addMenu($request);
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
    public function edit(\App\Menu $menu)
    {
       // $menu = Menu::where('id', $id)->first();
        $this->title = 'Редактирование ссылки '.$menu->title;
        $type = FALSE;
        $option = FALSE;
        $route = app('router')->getRoutes()->match(app('request')->create($menu->path));
        $aliasRoute = $route->getName();
        $parameters = $route->parameters();

        if ($aliasRoute == 'articles.index' || $aliasRoute == 'articlesCat') {
            $type = 'blogLink';
            $option = isset($parameters['cat_alias']) ? $parameters['cat_alias'] : 'parent';
        } elseif ($aliasRoute == 'articles.show') {
            $type = 'blogLink';
            $option = isset($parameters['alias']) ? $parameters['alias'] : '';
        } elseif ($aliasRoute == 'portfolios.index') {
            $type = 'portfolioLink';
            $option = 'parent';
        } elseif ($aliasRoute == 'portfolios.show') {
            $type = 'portfolioLink';
            $option = isset($parameters['alias']) ? $parameters['alias'] : '';
        } else{
            $type = 'customLink';
        }

        $tmp = $this->getMenus()->roots();// return parent items
        $menus = $tmp->reduce( function($returnMenus, $menu) {
            $returnMenus[$menu->id] = $menu->title;
            return $returnMenus;
        }, ['0' => 'Родительский пункт меню']);// на первой итерации попадет 2 параметр функции
        $categories = Category::select(['title', 'alias', 'parent_id', 'id'])->get();
        $list = [];
        $list = Arr::add($list,'0','Не используется');
        $list = Arr::add($list,'parent','Раздел блог');
        foreach ($categories as $category) {
            if ($category->parent_id == 0) {
                $list[$category->title] = [];
            } else {
                $list[$categories->where('id', $category->parent_id)->first()->title][$category->alias] = $category->title;
            }
        }
        $articles = $this->a_rep->get(['id', 'title', 'alias']);
        $articles = $articles->reduce( function($returnArticles, $article) {
            $returnArticles[$article->alias] = $article->title;
            return $returnArticles;
        }, ['0' => 'Не используется']);
        $filters = Filter::select(['title', 'alias', 'id'])->get()->reduce( function($returnFilters, $filter) {
            $returnFilters[$filter->alias] = $filter->title;
            return $returnFilters;
        }, ['parent' => 'Раздел портфолио']);
        $portfolios = $this->p_rep->get(['title', 'alias', 'id'])->reduce( function($returnPortfolios, $portfolio) {
            $returnPortfolios[$portfolio->alias] = $portfolio->title;
            return $returnPortfolios;
        }, ['0' => 'Не используется']);
        $filters = Arr::prepend($filters, 'Не используется');
        $this->content = view(env('THEME').'.admin.menus_create_content')->with(['menu' => $menu, 'option' => $option, 'type' => $type, 'menus' => $menus, 'categories' => $list, 'articles' => $articles, 'filters' => $filters, 'portfolios' => $portfolios])->render();

        return $this->renderOutput();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, \App\Menu $menu)
    {
        //
        $result = $this->m_rep->updateMenu($request, $menu);
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
    public function destroy(\App\Menu $menu)
    {
        //
        $result = $this->m_rep->deleteMenu($menu);
        if (is_array($result) && !empty($result['error'])) {
            return back()->with($result);
        }

        return redirect('/admin')->with($result);
    }
}

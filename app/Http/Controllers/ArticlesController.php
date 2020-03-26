<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Arr;

use App\Repositories\PortfolioRepository;

use App\Repositories\ArticleRepository;

use App\Repositories\CommentsRepository;
use Config;

use App\Category;


class ArticlesController extends SiteController
{

	public function __construct(PortfolioRepository $p_rep, ArticleRepository $a_rep, CommentsRepository $c_rep)
    {
        parent::__construct(new \App\Repositories\MenusRepository(new \App\Menu));


        $this->p_rep = $p_rep;
        $this->a_rep = $a_rep;
        $this->c_rep = $c_rep;

        // так как на главное странице есть правый сайд бар
        $this->bar = 'right';
        $this->template = env('THEME').'.articles';
    }

    //
	public function index($cat_alias = FALSE)
    {
        //
    	$articles = $this->getArticles($cat_alias);


    	$this->title = 'Блог';
    	$this->keywords = 'String';
    	$this->meta_desc = 'String';
    	

    	$content = view(env('THEME').'.articles_content')->with('articles', $articles)->render();

    	$this->vars = Arr::add($this->vars,'content',$content);

    	$comments = $this->getComments(Config::get('settings.recent_comments'));
    	$portfolios = $this->getPortfolios(Config::get('settings.recent_portfolios'));


    	$this->contentRightBar = view(env('THEME').'.articlesBar')->with(['comments' => $comments, 'portfolios' => $portfolios])->render();

        return $this->renderOutput();
    }

    protected function getComments($take)
    {
    	$comments = $this->c_rep->get(['text','name','email', 'site', 'article_id', 'user_id'], $take);

    	if ($comments) {
        	$comments->load('article', 'user');
        }

    	return $comments;
    }

   	protected function getPortfolios($take)
    {
    	$portfolios = $this->p_rep->get(['title','text','alias', 'customer', 'img', 'filter_alias'], $take);

    	return $portfolios;
    }

    protected function getArticles($alias = FALSE)
    {

    	$where = FALSE;

    	if ($alias) {
    		$id = Category::select('id')->where('alias', $alias)->first()->id;

    		$where = ['category_id', $id];

    	}

        $articles = $this->a_rep->get(['id','title','created_at', 'img', 'alias', 'desc', 'user_id', 'category_id', 'keywords', 'meta_desc'], FALSE, TRUE, $where);

        if ($articles) {
        	$articles->load('user', 'category', 'comments');
        }

       // dd($articles);

        return $articles;
    }

   	public function show($alias = FALSE)
    {
    	$article = $this->a_rep->one($alias, ['comments' => TRUE]);

    	if ($article) {
    		$article->img = json_decode($article->img);
    	}

        if (isset($article->id)) {
            $this->title = $article->title;
            $this->keywords = $article->keywords;
            $this->meta_desc = $article->meta_desc;
        }

    	
    	//dd($article->comments->groupBy('parent_id'));

    	$content = view(env('THEME').'.article_content')->with(['article' => $article])->render();

    	$this->vars = Arr::add($this->vars,'content',$content);

    	$comments = $this->getComments(Config::get('settings.recent_comments'));
    	$portfolios = $this->getPortfolios(Config::get('settings.recent_portfolios'));


    	$this->contentRightBar = view(env('THEME').'.articlesBar')->with(['comments' => $comments, 'portfolios' => $portfolios])->render();

    	return $this->renderOutput();
    }
}

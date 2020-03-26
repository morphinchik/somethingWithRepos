<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;

use App\Http\Requests;

use App\Repositories\MenusRepository;

use Menu;

class SiteController extends Controller
{
    //
	//для хранения объекта класса Портфолио репозиторий 
	//Логика работы с портфолио
    protected $p_rep;
    // slider репозиторий
    protected $s_rep;
    // article репозиторий Статьи блога
    protected $a_rep;
	// menus репозиторий Статьи блога
    protected $m_rep;
    // имя шаблона для отображения информации


    protected $keywords;
    protected $meta_desc;
    protected $title;




    protected $template;
    // массив передаваемых переменных в шаблон
    protected $vars = array();


    // даные правого и левого сайт баров
    protected $contentRightBar = FALSE;
    protected $contentLeftBar = FALSE;
    // показывает есть ли сайт бар
    protected $bar = 'no';

    public function __construct(MenusRepository $m_rep)
    {
        $this->m_rep = $m_rep;
    }

    //возвращает конкретный отработанный вид
    protected function renderOutput()
    {

        $menu = $this->getMenu();

        //dd($menu);
                                                        // переводит содержимое в строку
        $navigation = view(env('THEME').'.navigation')->with('menu', $menu)->render();
        $this->vars = Arr::add($this->vars,'navigation',$navigation);

        if ($this->contentRightBar) {
            $rightBar = view(env('THEME').'.rightBar')->with('content_rightBar', $this->contentRightBar)->render();
            $this->vars = Arr::add($this->vars,'rightBar',$rightBar);
        }

        if ($this->contentLeftBar) {
            $leftBar = view(env('THEME').'.leftBar')->with('content_leftBar', $this->contentLeftBar)->render();
            $this->vars = Arr::add($this->vars,'leftBar',$leftBar);
        }

        $this->vars = Arr::add($this->vars,'bar', $this->bar);

        $footer = view(env('THEME').'.footer')->render();

        $this->vars = Arr::add($this->vars,'footer', $footer);

        $this->vars = Arr::add($this->vars,'keywords', $this->keywords);
        $this->vars = Arr::add($this->vars,'meta_desc', $this->meta_desc);
        $this->vars = Arr::add($this->vars,'title', $this->title);

    	// with параметры передаваемые во вью
    	return view($this->template)->with($this->vars);
    }

    public function getMenu()
    {
        $menu = $this->m_rep->get();
                                    // для кол бек функции предоставляем доступ к меню
        // $m - объект класса билдера которыйи возвращается
        //* для формирования пунктов меню
        $mBuilder = Menu::make('MyNav', function($m) use ($menu){

            foreach ($menu as $item) {
                if ($item->parent == 0) 
                {
                    // добавляем новый пункт меню назначаем идентификатор
                    $m->add($item->title, $item->path)->id($item->id);
                }
                else
                {
                    // если существует пункт с данным идентификатором то добавим дочерный пункт меню
                    if ($m->find($item->parent)) {
                          // файнд возвращает объект и дальше добавляем дочерний
                        $m->find($item->parent)->add($item->title, $item->path)->id($item->id);
                    }
                }
            }

        });

        //dd($mBuilder);

        return $mBuilder;
    }
}

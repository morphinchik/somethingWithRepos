<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Exception
     */
    public function render($request, Exception $e)
    {

        if ($this->isHttpException($e)) {
            
            $statusCode = $e->getStatusCode();

            switch ($statusCode) {
                case '404':

                    $obj = new \App\Http\Controllers\SiteController(new \App\Repositories\MenusRepository(new \App\Menu));

                    $navigation = view(env('THEME').'.navigation')->with('menu', $obj->getMenu())->render();

                    \Log::alert('Page not found - '.$request->url());

                    return response()->view(env('THEME').'.404', ['bar' => 'no', 'title' => 'Страница не найдена', 'navigation' => $navigation]);
            }
        }
        return parent::render($request, $e);
    }
}

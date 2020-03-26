<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Route::get('/', function () { return view('welcome');});

//Auth::routes();

//Route::get('/home', 'HomeController@index')->name('home');

Route::resource('/','IndexController',['only'=>['index'],
									'names'=>[
											'index'=>'home'
									]
									]);

Route::resource('portfolios','PortfolioController', [
														'parameters' => [
																			'portfolios' => 'alias'
																		]



													]);

/*Route::resource('articles','ArticlesController', [
														'parameters' => [
																			'articles' => 'alias'
																		]



													]);*/
//, 'as' => 'articlesCat'
Route::get('articles/cat/{cat_alias?}',['uses'=> 'ArticlesController@index', 'as' => 'articlesCat'])->where('cat_alias','[\w-]+');

Route::resource('comment', 'CommentController',['only' => ['store']]);

Route::match(['get', 'post'], '/contacts', ['uses'=> 'ContactsController@index', 'as' => 'contacts']);

//Route::get('login', 'Auth\LoginController@showLoginForm');
//Route::post('login', 'Auth\LoginController@login');
//Route::get('logout', 'Auth\LoginController@logout');

Route::get('login', 'LoginController@index')->name('login');
Route::post('login', 'LoginController@login');

Route::group(['prefix' => 'admin', 'middleware' => 'auth', 'as' => 'admin.'], function(){

    Route::get('/', 'Admin\IndexController@index')->name('adminIndex');
    Route::resource('/articles', 'Admin\ArticlesController');
    Route::resource('/permissions', 'Admin\PermissionsController');
    Route::resource('/users', 'Admin\UsersController');
    Route::resource('/menus', 'Admin\MenusController');

});

Route::resource('articles','ArticlesController', [
														'parameters' => [
																			'articles' => 'alias'
																		]



													]);

Route::get('logout', function(){
    auth()->logout();
    Session()->flush();
    return Redirect::to('/');
});

//Route::get('admin', 'AdminController@index')->name('admin');

/*
Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function() {


	Route::get('/', ['uses' => 'Admin\IndexController@index', 'as' => 'adminIndex']);
	Route::resource('/articles', 'Admin\ArticlesController@index');

});
*/


/*
Route::group(['prefix'=>'admin', 'middleware'=> 'auth' ], function () {

    
	Route::get('/', ['uses' => 'Admin\IndexController@index', 'as' => 'adminIndex']);
	//Route::resource('/articles', 'Admin\ArticlesController@index');

}); 
*/
// !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
/*Route::group(['middleware' => 'auth'], function(){

    Route::get('/admin', 'Admin\IndexController@index')->name('adminIndex');

});

Route::get('logout', function(){
    auth()->logout();
    Session()->flush();
    return Redirect::to('/');
});*/


/*
Route::prefix('admin')->middleware('auth')->group(function () {
    
	Route::get('/', ['uses' => 'Admin\IndexController@index', 'as' => 'adminIndex']);
	Route::resource('/articles', 'Admin\ArticlesController@index');

});

*/

/*Route::middleware('auth')->group(['prefix' => 'admin'], function() {


	Route::get('/', ['uses' => 'Admin\IndexController@index'])->name('adminIndex');
	Route::resource('/articles', 'Admin\ArticlesController@index');

});*/

//Route::auth();

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/getcontacts', 'ContactsController@GetContacts');

Route::put('/updatecontact', 'ContactsController@UpdateContact');

Route::post('/savecontact', 'ContactsController@SaveContact');

Route::delete('/removecontact', 'ContactsController@RemoveContact');

Route::get('/getproducts', 'ProductsController@GetAllProducts');

Route::post('/getconditionalproducts', 'ProductsController@GetConditionalProducts');

Route::get('/getcategories', 'ProductsController@GetCategories');

Route::post('/addcategory', 'ProductsController@AddCategory');

Route::post('/addtocategory', 'ProductsController@AddProductToCategory');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

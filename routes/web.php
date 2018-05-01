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

Auth::routes();

Route::get('/', 'SiteController@index')->name('index');
Route::get('/importar', 'SiteController@importar')->name('import');
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/inscricoes', 'HomeController@inscricoes')->name('inscricoes');
Route::post('/inscricoes/modalidade', 'HomeController@inscricoes_modaliade')->name('inscricoes.modalidade');
Route::get('/relacao', 'HomeController@relacao')->name('relacao');

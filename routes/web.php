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
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/inscricoes', 'HomeController@inscricoes')->name('inscricoes');
Route::get('/inscricoes/modalidade', 'HomeController@inscricoes_modalidade')->name('inscricoes.modalidade');
Route::post('/inscricoes/adicionar', 'HomeController@inscricoes_adicionar')->name('inscricoes.adicionar');
Route::get('/relacao', 'HomeController@relacao')->name('relacao');
Route::get('/importar', 'HomeController@importar')->name('importar');
Route::post('/importar', 'HomeController@processar_importacao');

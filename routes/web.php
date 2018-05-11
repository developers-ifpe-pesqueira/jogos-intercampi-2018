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
Route::post('/inscricoes/modalidade', 'HomeController@inscricoes_modalidade')->name('inscricoes.modalidade');
Route::get('/inscricoes/modalidade/{campus}/{modalidade}', 'HomeController@inscricoes_modalidade_v')->name('inscricoes.modalidade.v');
Route::post('/inscricoes/modalidade/{campus}/{modalidade}/adicionar', 'HomeController@inscricoes_adicionar')->name('inscricoes.adicionar');
Route::delete('/inscricoes/modalidade/{campus}/{modalidade}/remover', 'HomeController@inscricoes_remover')->name('inscricoes.remover');
Route::get('/relacao/campus', 'HomeController@relacao_campus')->name('relacao.campus');
Route::post('/relacao/campus', 'HomeController@relacao_campus_pdf');
Route::get('/relacao/modalidade', 'HomeController@relacao_modalidade')->name('relacao.modalidade');
Route::post('/relacao/modalidade', 'HomeController@relacao_modalidade_pdf');
Route::get('/importar', 'HomeController@importar')->name('importar');
Route::post('/importar', 'HomeController@processar_importacao');

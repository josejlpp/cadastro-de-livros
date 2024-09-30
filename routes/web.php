<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/livro', 'App\Http\Controllers\LivroController@index')->name('livro.index');
Route::post('/livro', 'App\Http\Controllers\LivroController@store')->name('livro.store');
Route::put('/livro/{id}', 'App\Http\Controllers\LivroController@update')->name('livro.update');
Route::delete('/livro/{id}', 'App\Http\Controllers\LivroController@destroy')->name('livro.destroy');
Route::get('/livro/create', 'App\Http\Controllers\LivroController@create')->name('livro.create');
Route::get('/livro/{id}/edit', 'App\Http\Controllers\LivroController@edit')->name('livro.edit');

Route::get('/autor', 'App\Http\Controllers\AutorController@index')->name('autor.index');
Route::post('/autor', 'App\Http\Controllers\AutorController@store')->name('autor.store');
Route::put('/autor/{id}', 'App\Http\Controllers\AutorController@update')->name('autor.update');
Route::delete('/autor/{id}', 'App\Http\Controllers\AutorController@destroy')->name('autor.destroy');
Route::get('/autor/create', 'App\Http\Controllers\AutorController@create')->name('autor.create');
Route::get('/autor/{id}/edit', 'App\Http\Controllers\AutorController@edit')->name('autor.edit');

Route::get('/assunto', 'App\Http\Controllers\AssuntoController@index')->name('assunto.index');
Route::post('/assunto', 'App\Http\Controllers\AssuntoController@store')->name('assunto.store');
Route::put('/assunto/{id}', 'App\Http\Controllers\AssuntoController@update')->name('assunto.update');
Route::delete('/assunto/{id}', 'App\Http\Controllers\AssuntoController@destroy')->name('assunto.destroy');
Route::get('/assunto/create', 'App\Http\Controllers\AssuntoController@create')->name('assunto.create');
Route::get('/assunto/{id}/edit', 'App\Http\Controllers\AssuntoController@edit')->name('assunto.edit');

Route::get('/report', 'App\Http\Controllers\ReportController@index')->name('report.index');
Route::get('/report/create', 'App\Http\Controllers\ReportController@create')->name('report.create');
Route::get('/report/download/{fileName}', 'App\Http\Controllers\ReportController@download')->name('report.download');
Route::delete('/report/delete/{report}', 'App\Http\Controllers\ReportController@delete')->name('report.delete');

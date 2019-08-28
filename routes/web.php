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

//LOGIN
Route::get('/','Auth\LoginController@index')->name('login');
//LOGIN GOOGLE
Route::get('auth/{provider}','Auth\LoginController@redirectToProvider');
Route::get('auth/{provider}/callback','Auth\LoginController@handleProviderCallback');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

//MODULO DE GESTION DE USUARIOS
Route::get('/gestion_usuarios','UsuarioController@usuariosListing')->name('gestion_user');
Route::get("/addNewUsuario","UsuarioController@addNewUsuario")->name('addNewUsuario');
Route::get("/modulo_usuarios/{id}/setEstado","UsuarioController@setEstado")->name('states_usuarios');
Route::get("/usuarios_informacion_carga/{email}","UsuarioController@cargaUser")->name('carga_user');
Route::get("/addUsuario","UsuarioController@addUsuario")->name('addUsuario');
Route::get("/editOldUsuario/{id}","UsuarioController@editOldUsuario")->name('editOldUsuario');
Route::get("/editUsuario","UsuarioController@editUsuario")->name('editUsuario');

//MODULO PRINCIPAL
Route::get('/principal', 'AbastecimientoController@index')->name('principal');
Route::post("/searchListSemanal","AbastecimientoController@searchAbastecimiento")->name('searchAbastecimiento');

//MODULO DE PREGUNTAS
Route::get('/preguntas_checklist','PreguntasController@generateQuestions')->name('questions');
Route::get("/addNewPregunta","PreguntasController@addNewPregunta")->name('addNewPregunta');
Route::post("/addPregunta","PreguntasController@addPregunta")->name('addPregunta');
Route::get("/editOldPregunta/{id}","PreguntasController@editOldPregunta")->name('editOldPregunta');
Route::post("/editPregunta","PreguntasController@editPregunta")->name('editPregunta');
Route::get("/preguntas_checklist/{id}/setEstado","PreguntasController@setEstado")->name('states_preguntas');

//BODEGAS
Route::get('/newalmacen','AbastecimientoController@bodega')->name('almacen');
Route::post('/savealmacen','AbastecimientoController@saveCheckList')->name('save_almacen');
Route::post('/verificateAlmacenWeek','AbastecimientoController@verificateWeekB')->name('verificateAlmacen');
Route::get('/editOldAlmacen/{array}','AbastecimientoController@editOldBodega')->name('editOldAlmacen');
Route::get('/editAlmacen','AbastecimientoController@editBodega')->name('editAlmacen');
Route::post('/savealmacenItem','AbastecimientoController@saveCheckListBodegaItem')->name('save_balmacen_item');
Route::post('/verificateAlmacenWeekItem','AbastecimientoController@verificateWeekBItem')->name('verificateAlmacenItem');
Route::post('/validateAlmacenWeekItem','AbastecimientoController@validateWeekBodega')->name('validateAlmacen');


//VISITAS
Route::get('/newvisita','AbastecimientoController@visita')->name('visita');
Route::post('/savevisita','AbastecimientoController@saveCheckListVisita')->name('save_visita');
Route::post('/verificateVisitaWeek','AbastecimientoController@verificateWeekV')->name('verificateVisita');
Route::get('/editOldVisita/{array}','AbastecimientoController@editOldVisita')->name('editOldVisita');
Route::get('/editVisita','AbastecimientoController@editVisita')->name('editVisita');
Route::post('/savevisitaItem','AbastecimientoController@saveCheckListVisitaItem')->name('save_visita_item');
Route::post('/verificateVisitaWeekItem','AbastecimientoController@verificateWeekVItem')->name('verificateVisitaItem');
Route::post('/validateVisitaWeekItem','AbastecimientoController@validateWeekVisita')->name('validateVisita');

//KPIS
Route::get('/reportesweek','AbastecimientoController@reportWeek')->name('reports');


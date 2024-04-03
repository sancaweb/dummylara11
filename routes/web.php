<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return redirect()->route("home");
})->name("root");

Route::view('/user','user.index');

Auth::routes();

Route::group(['middleware'=>['auth']],function(){

});
Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::group(['middleware' => ['auth', 'permission:user read']], function () {
    Route::get('/user', [UserController::class, 'index'])->name('user');
    Route::get('/user/datatable', [UserController::class, 'datatable'])->name('user.datatable');
});

Route::post('/user', [UserController::class, 'store'])->name('user.store')->middleware(['auth', 'permission:user create']);

Route::group(['middleware' => ['auth', 'role:super admin']], function () {
     //User trash
     Route::get('/user/trash', [UserController::class, 'trash'])->name('user.trash');
     Route::post('/user/datatabletrash', [UserController::class, 'datatableTrash'])->name('user.trashDatatable');
     Route::post('/user/{id}/restore', [UserController::class, 'restore'])->name('user.restore');
     Route::delete('/user/{id}/destroy', [UserController::class, 'destroy'])->name('user.destroy');
});


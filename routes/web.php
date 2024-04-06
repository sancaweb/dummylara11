<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RolePermissionController;

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
    Route::post('/user/datatable', [UserController::class, 'datatable'])->name('user.datatable');
});

Route::post('/user', [UserController::class, 'store'])->name('user.store')->middleware(['auth', 'permission:user create']);

Route::group(['middleware' => ['auth', 'permission:user update']], function () {
    Route::get('/user/{id}/edit', [UserController::class, 'edit'])->name('user.edit');
    Route::patch('/user/{id}/update', [UserController::class, 'update'])->name('user.update');
});

Route::delete('user/{id}/delete', [UserController::class, 'delete'])->name('user.delete')->middleware(['auth', 'permission:user delete']);

Route::group(['middleware' => ['auth', 'role:super admin']], function () {
     //User trash
     Route::get('/user/trash', [UserController::class, 'trash'])->name('user.trash');
     Route::post('/user/datatabletrash', [UserController::class, 'datatableTrash'])->name('user.trashDatatable');
     Route::post('/user/{id}/restore', [UserController::class, 'restore'])->name('user.restore');
     Route::delete('/user/{id}/destroy', [UserController::class, 'destroy'])->name('user.destroy');

     /**
      * Roles & Permissions
      */

      Route::get('/rolepermission', [RolePermissionController::class, 'index'])->name('rolepermission');

      /**
     * ROLE PROCESS
     */
    Route::post('/role', [RolePermissionController::class, 'storeRole'])->name('role.store');
    Route::get('/role/{id}/edit', [RolePermissionController::class, 'editRole'])->name('role.edit');
    Route::patch('/role/{id}/update', [RolePermissionController::class, 'updateRole'])->name('role.update');
    Route::delete('/role/{id}/delete', [RolePermissionController::class, 'deleteRole'])->name('role.delete');
    Route::post('/role/datatable', [RolePermissionController::class, 'datatableRoles'])->name('role.datatable');


    /** PERMISSIONS PROCESS */
    Route::post('/permission', [RolePermissionController::class, 'storePermission'])->name('permission.store');
    Route::get('/permission/{id}/edit', [RolePermissionController::class, 'editPermission'])->name('permission.edit');
    Route::patch('/permission/{id}/update', [RolePermissionController::class, 'updatePermission'])->name('permission.update');
    Route::delete('/permission/{id}/delete', [RolePermissionController::class, 'deletePermission'])->name('permission.delete');
    Route::post('/permission/datatable', [RolePermissionController::class, 'datatablePermissions'])->name('permission.datatable');


    /** ASSIGN PROCESS */
    Route::get('/assignpermission', [RolePermissionController::class, 'assign'])->name('assignPermission.assign');
    Route::get('/assignpermission/{id}/viewpermission', [RolePermissionController::class, 'viewPermissions'])->name('assignPermission.viewPermissions');
    Route::post('/assignpermission', [RolePermissionController::class, 'storeAssign'])->name('assignPermission.store');
    Route::post('/assignpermission/datatable', [RolePermissionController::class, 'datatableAssign'])->name('assignPermission.datatable');

});


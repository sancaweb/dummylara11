<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormat;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // if (auth()->user()) {
        //     $roles = Role::all()->pluck('name');
        // } else {
        //     $roles = Role::whereNotIn('name', ['super admin'])->pluck('name');
        // }

        $dataPage = [
            'pageTitle' => "User Management",
            'page' => 'user',
            // 'roles' => $roles,
            'action' => route('user.store')
        ];

        return view('pages.users.index', $dataPage);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $dataValidate['foto'] = ['image', 'mimes:jpeg,jpg,png,gif,svg', 'max:5000'];
        $dataValidate['nama'] = ['required', 'max:255'];
        $dataValidate['username'] = ['required', 'max:255', 'unique:users,username'];
        $dataValidate['email'] = ['required', 'email', 'unique:users,email'];
        $dataValidate['password'] = ['required_with:password_confirmation', 'same:password_confirmation', 'min:6'];
        $dataValidate['password_confirmation'] = ['min:6'];
        $dataValidate['role'] = ['required'];


        $validator = Validator::make($request->all(), $dataValidate);



        if ($validator->fails()) {
            return ResponseFormat::error([
                'errorValidator' => $validator->messages(),
            ], 'Error Validator', 402);
        }

        $isSuperAdmin = auth()->user()->hasRole('super admin');

        if (!$isSuperAdmin && $request->role == 'super admin') {
            return ResponseFormat::error([
                'error' => "User does not have the right roles."
            ], "User does not have the right roles.", 403);
        }

        try {
            DB::beginTransaction();

            if ($request->hasFile('foto')) {
                $file = $request->file('foto');
                $foto = $file->store("images/user");
            } else {
                $foto = null;
            }


            $user = User::create([
                'name' => ucwords($request->nama),
                'foto' => $foto,
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);

            $user->assignRole($request->role);
            activity('user_management')->withProperties($user)->performedOn($user)->log('Create User');

            DB::commit();

            if (auth()->user()->id == $user->id) {
                $self = true;
            } else {
                $self = false;
            }


            return ResponseFormat::success([
                'user' => $user,
                'self' => $self

            ], 'Data User berhasil ditambahkan');
        } catch (Exception $error) {
            DB::rollBack();
            return ResponseFormat::error([
                'error' => $error->getMessage()
            ], 'Error Penambahan user', 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormat;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (auth()->user()) {
            $roles = Role::all()->pluck('name');
        } else {
            $roles = Role::whereNotIn('name', ['super admin'])->pluck('name');
        }

        $dataPage = [
            'pageTitle' => "User Management",
            'page' => 'user',
            'roles' => $roles,
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
                'self' => $self,
                'foto'=>$foto

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
    public function edit($id)
    {
        $user =   User::find($id);

        if ($user) {

            $isSuperAdmin = auth()->user()->hasRole('super admin');

            if (!$isSuperAdmin && $user->hasRole('super admin')) {
                return ResponseFormat::error([
                    'error' => "User does not have the right roles."
                ], "User does not have the right roles.", 403);
            }

            $dataUser = [
                'foto' => $user->takeImage(),
                'name' => $user->name,
                'username' => $user->username,
                'email' => $user->email,
                'role' => $user->getRoleNames()
            ];

            return ResponseFormat::success([
                'user' => $dataUser,
                'action' => route('user.update', $id)
            ], 'Data user ditemukan');
        } else {
            return ResponseFormat::error([
                'error' => "Data user tidak ditemukan"
            ], "Not found", 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $dataValidate['foto'] = ['image', 'mimes:jpeg,jpg,png,gif,svg', 'max:5000'];
        $dataValidate['nama'] = ['required', 'max:255'];
        $dataValidate['username'] = ['required', 'max:255', 'unique:users,username,' . $id];
        $dataValidate['email'] = ['required', 'email', 'unique:users,email,' . $id];
        $dataValidate['password'] = ['same:password_confirmation'];
        $dataValidate['password_confirmation'] = ['same:password'];
        $dataValidate['role'] = ['required'];

        $validator = Validator::make($request->all(), $dataValidate);

        if ($validator->fails()) {
            return ResponseFormat::error([
                'errorValidator' => $validator->messages(),
            ], 'Error Validator', 402);
        }



        try {

            DB::beginTransaction();
            $user = User::find($id);

            if ($user) {

                $isSuperAdmin = auth()->user()->hasRole('super admin');

                if (!$isSuperAdmin && $request->role == 'super admin') {

                    return ResponseFormat::error([
                        'error' => "User does not have the right roles."
                    ], "User does not have the right roles.", 403);
                }

                if (!$isSuperAdmin && $user->hasRole('super admin')) {
                    return ResponseFormat::error([
                        'error' => "User does not have the right roles."
                    ], "User does not have the right roles.", 403);
                }


                if ($request->hasFile('foto')) {
                    $file = $request->file('foto');
                    $foto = $file->store("images/user");
                    Storage::delete($user->foto);
                    $user->foto = $foto;
                }

                $user->name = ucwords($request->nama);
                $user->username = $request->username;
                $user->email = $request->email;

                if (!empty($request->password)) {
                    $user->password = Hash::make($request->password);
                }

                $user->save();

                $user->syncRoles($request->role);


                activity('user_management')->withProperties($user)->performedOn($user)->log('Update User');
            } else {
                throw new Exception("Data User tidak ditemukan");
            }

            DB::commit();

            if (auth()->user()->id == $user->id) {
                $self = true;
            } else {
                $self = false;
            }

            return ResponseFormat::success([
                'user' => $user,
                'self' => $self
            ], 'User Updated');
        } catch (Exception $error) {
            DB::rollBack();

            return ResponseFormat::error([
                'error' => $error->getMessage()
            ], "Update User Error", 400);
        }
    }


    public function delete(Request $request, $id)
    {
        $user = User::find($id);

        if ($user) {

            $loggedIn = auth()->user()->id;
            $userId = $user->id;

            if ($loggedIn == $userId) {
                return ResponseFormat::error([
                    'error' => "User yang sedang login tidak bisa dihapus"
                ], "Delete Failed", 403);
            }

            DB::beginTransaction();

            try {

                activity('user_management')->withProperties($user)->performedOn($user)->log('Delete User');

                $user->delete();

                DB::commit();
                return ResponseFormat::success([
                    'message' => "Data berhasil dihapus",
                ], "User Deleted");
            } catch (Exception $error) {
                DB::rollBack();

                return ResponseFormat::error([
                    'error' => $error->getMessage()
                ], "Something went wrong", 400);
            }
        } else {
            return ResponseFormat::error([
                'error' => "User tidak ditemukan"

            ], "Delete Failed", 404);
        }
    }

    /**
     * TRASHED USER PAGE
     */
    public function trash()
    {
        $dataPage = [
            'pageTitle' => "User Trash",
            'page' => 'userTrash'
        ];

        return view('pages.users.trash', $dataPage);
    }

    /**
     * Restore Trashed User
     */

     public function restore(Request $request, $id)
    {
        $user = User::onlyTrashed()->find($id);

        if ($user) {
            $user->restore();
            activity('user_management')->withProperties($user)->performedOn($user)->log('Restore User');

            return ResponseFormat::success([
                'message' => 'Data User Restored'
            ], "Data User Restored");
        } else {

            return ResponseFormat::error([
                'error' => "Data User not found"
            ], "Data User not found", 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {
        $user = User::onlyTrashed()->find($id);

        if ($user) {
            DB::beginTransaction();
            try {
                $foto = $user->foto;
                $user->roles()->detach();
                $user->forceDelete();

                Storage::delete($foto);
                activity('user_management')->withProperties($user)->performedOn($user)->log('Destroy User');
                DB::commit();
                return ResponseFormat::success([
                    'message' => "Data User Destroyed"
                ], "Data User Destroyed");
            } catch (Exception $error) {
                DB::rollBack();
                return ResponseFormat::error([
                    'error' => $error->getMessage()
                ], "Something went wrong", 400);
            }
        } else {
            return ResponseFormat::error([
                'error' => "Data User not found"
            ], "Data User not found", 404);
        }
    }

    public function datatable(Request $request)
    {
        $columns = array(
            0 => 'id',
            1 => 'foto',
            2 => 'name',
            3 => 'email',
            4 => 'username',
            5 => 'role',
            6 => 'created_at'
        );

        $totalData = User::count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');


        // query
        $search = $request->input('search.value');
        $filter = false;

        $canDelete = auth()->user()->can('user delete');


        if ($canDelete) {

            $getUsers = User::leftJoin('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
                ->leftJoin('roles', 'model_has_roles.role_id', '=', 'roles.id')
                ->select('users.*', 'roles.name as rolename');
        } else {
            $getUsers = User::leftJoin('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
                ->leftJoin('roles', 'model_has_roles.role_id', '=', 'roles.id')
                ->select('users.*', 'roles.name as rolename')->whereDoesntHave('roles', function ($query) {
                    $query->where('roles.name', 'super admin');
                });
        }


        //filter - filter
        if (!empty($search)) {
            $getUsers->where(function ($query) use ($search) {
                $query->where('roles.name', 'LIKE', "%{$search}%")
                    ->orWhere('users.name', 'LIKE', "%{$search}%")
                    ->orWhere('username', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%");
            });


            $filter = true;
        }

        //getData
        if ($request->input('order.0.column') == 5) {
            $users = $getUsers->offset($start)
                ->limit($limit)
                ->orderBy('roles.name', $dir)
                ->get();
        } else {
            $users = $getUsers->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        }

        if ($filter == true) {
            $totalFiltered = $getUsers->count();
        }


        $data = array();


        if (!empty($users)) {
            $no = $start;
            foreach ($users as $user) {

                $action = '<button data-id="' . $user->id . '" class="btn btn-primary btn-flat btn-edit">
                        <i class="fas fa-edit"></i>
                    </button>';

                if ($canDelete) {
                    $action .= '<button type="button" data-id="' . $user->id . '" class="btn btn-danger btn-flat btn-delete">
                            <i class="fas fa-trash"></i>
                        </button>';
                }

                $no++;
                $nestedData['no'] = $no;
                $nestedData['foto'] = '
                        <a href="' . $user->takeImage() . '" data-lightbox="' . $user->name . $user->id . '" data-title="User Foto ' . $user->name . '">
                                    <img src="' . $user->takeImage() . '" alt="Image Foto" style="width: 150px;object-fit:cover;object-position:center;" class="img-thumbnail img-fluid">
                                </a>
                ';

                $nestedData['name'] = $user->name;
                $nestedData['email'] = $user->email;
                $nestedData['username'] = $user->username;
                $nestedData['role'] = $user->rolename;
                // $nestedData['role'] = $user->getRoleNames();
                $nestedData['created_at'] = $user->created_at->diffForHumans();

                $nestedData['action'] = $action;

                $data[] = $nestedData;
            }
        }

        $json_data = array(
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data,
            "order"           => $order,
            "dir" => $dir
        );

        return response()->json($json_data, 200);
    }

    public function datatableTrash(Request $request)
    {
        $columns = array(
            0 => 'id',
            1 => 'foto',
            2 => 'name',
            3 => 'email',
            4 => 'username',
            5 => 'role',
        );

        $totalData = User::onlyTrashed()->count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        //query
        $search = $request->input('search.value');
        $filter = false;

        $getUsers = User::onlyTrashed()->join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
            ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->select('users.*', 'roles.name as rolename');


        //filter-filter

        if (!empty($search)) {
            $getUsers->where(function ($query) use ($search) {
                $query->where('roles.name', 'LIKE', "%{$search}%")
                    ->orWhere('users.name', 'LIKE', "%{$search}%")
                    ->orWhere('username', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%");
            });

            $filter = true;
        }



        //getData
        if ($request->input('order.0.column') == 5) {
            $users = $getUsers->offset($start)
                ->limit($limit)
                ->orderBy('roles.name', $dir)
                ->get();
        } else {
            $users = $getUsers->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        }

        if ($filter == true) {
            $totalFiltered = $getUsers->count();
        }


        $data = array();

        if (!empty($users)) {
            $no = $start;
            foreach ($users as $user) {
                $no++;
                $nestedData['no'] = $no;
                $nestedData['foto'] = '
                        <a href="' . $user->takeImage() . '" data-lightbox="' . $user->name . $user->id . '" data-title="User Foto ' . $user->name . '">
                                    <img src="' . $user->takeImage() . '" alt="Image Foto" style="width: 150px;object-fit:cover;object-position:center;" class="img-thumbnail img-fluid">
                                </a>
                ';
                $nestedData['name'] = $user->name;
                $nestedData['email'] = $user->email;
                $nestedData['username'] = $user->username;
                $nestedData['role'] = $user->getRoleNames();
                // $nestedData['role'] = $user->rolename;

                $nestedData['action'] = '<button data-id="' . $user->id . '" class="btn btn-warning btn-flat btn-restore" title="Restore User">
                <i class="fas fa-trash-restore"></i>
                            </button>
                            <button type="button" data-id="' . $user->id . '" class="btn btn-danger btn-flat btn-destroy" title="Permanent Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                          ';

                $data[] = $nestedData;
            }
        }

        $json_data = array(
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data,
            "order"           => $order,
            "dir" => $dir
        );

        return response()->json($json_data, 200);
    }



}

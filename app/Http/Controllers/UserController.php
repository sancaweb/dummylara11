<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormat;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
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

    // public function datatable()
    // {
    //     return DataTables::of(User::query())->toJson();
    // }
}

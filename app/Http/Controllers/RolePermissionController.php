<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseFormat;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\{Permission, Role};

class RolePermissionController extends Controller
{
    /**
     * ROLES METHODS
     */
    public function index()
    {
        $dataPage = [
            'pageTitle' => "Roles & Permissions",
            'page' => "rolePermission"
        ];

        return view('pages.rolesPermissions.index', $dataPage);
    }

    public function storeRole(Request $request)
    {
        $dataValidate = [
            'roleName' => ['required', 'max:255'],
        ];

        $validator = Validator::make($request->all(), $dataValidate);

        if ($validator->fails()) {
            return ResponseFormat::error([
                'errorValidator' =>  $validator->messages()
            ], 'Error Validator', 402);
        }

        DB::beginTransaction();

        try {
            $role = Role::create(['name' => strtolower($request->roleName)]);

            activity('role_management')->withProperties($role)->performedOn($role)->log('Create Role');


            DB::commit();
            return ResponseFormat::success([
                'message' => "Role Created"
            ], "Role Created");
        } catch (Exception $error) {
            DB::rollBack();
            return ResponseFormat::error([
                'error' => $error->getMessage()
            ], "Something went wrong", 400);
        }
    }

    public function editRole($id)
    {
        $role = Role::find($id);

        if ($role) {
            return ResponseFormat::success([
                'role' => $role,
                'action' => route('role.update', $id)
            ], "Data Role Ditemukan");
        } else {
            return ResponseFormat::error([
                'error' => "Role Not Found"
            ], "Role Not Found", 404);
        }
    }

    public function updateRole(Request $request, $id)
    {
        $validator = Validator::make($request->all(), ['roleName' => ['required', 'max:255']]);

        if ($validator->fails()) {
            return ResponseFormat::error([
                'error' => $validator->messages()
            ], "Error Validator", 402);
        }

        $role = Role::find($id);

        if ($role) {
            DB::beginTransaction();
            try {
                $role->name = strtolower($request->roleName);
                $role->save();

                activity('role_management')->withProperties($role)->performedOn($role)->log('Update Role');


                DB::commit();
                return ResponseFormat::success([
                    'message' => "Role Updated"
                ], "Role Updated");
            } catch (Exception $error) {
                DB::rollBack();
                return ResponseFormat::error([
                    'error' => $error->getMessage()
                ], "Something went wrong", 400);
            }
        } else {
            return ResponseFormat::error([
                'error' => "Role Not Found"
            ], "Role Not Found", 404);
        }
    }

    public function deleteRole(Request $request, $id)
    {
        $role = Role::find($id);

        if ($role) {
            DB::beginTransaction();
            try {
                activity('role_management')->withProperties($role)->performedOn($role)->log('Delete Role');
                $role->delete();
                DB::commit();
                return ResponseFormat::success([
                    'message' => "Role deleted"
                ], "Role Deleted");
            } catch (Exception $error) {
                DB::rollBack();
                return ResponseFormat::error([
                    'error' => $error->getMessage()
                ], "Something weng wrong", 400);
            }
        } else {
            return ResponseFormat::error([
                'error' => "Role Not Found"
            ], "Role Not Found", 404);
        }
    }


    public function datatableRoles(Request $request)
    {
        $columns = array(
            0 => 'id',
            1 => 'name',
            2 => 'created_at'
        );

        $totalData = Role::count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');


        // query
        $search = $request->input('search.value');
        $filter = false;

        $getRoles = new Role();


        //filter - filter
        if (!empty($search)) {
            $getRoles = Role::where('name', 'ILIKE', "%{$search}%")->orWhere('created_at', 'ILIKE', "%{$search}%");
            $filter = true;
        }

        //getData
        $roles = $getRoles->offset($start)
            ->limit($limit)
            ->orderBy($order, $dir)
            ->get();

        if ($filter == true) {
            $totalFiltered = $getRoles->count();
        }


        $data = array();

        if (!empty($roles)) {
            $no = $start;
            foreach ($roles as $role) {
                $no++;
                $nestedData['no'] = $no;
                $nestedData['roleName'] = ucwords($role->name);
                $nestedData['created_at'] = $role->created_at->diffForHumans();

                $nestedData['action'] = '<button data-id="' . $role->id . '" class="btn btn-primary btn-flat btn-edit">
                              <i class="fas fa-edit"></i>
                          </button>
                          <button type="button" data-id="' . $role->id . '" class="btn btn-danger btn-flat btn-delete">
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

    /**
     * ./END ROLES METHODS
     */




    /**
     * PERMISSIONS METHODS
     */

    public function storePermission(Request $request)
    {
        $dataValidate = [
            'permissionName' => ['required', 'max:255'],
        ];

        $validator = Validator::make($request->all(), $dataValidate);

        if ($validator->fails()) {
            return ResponseFormat::error([
                'errorValidator' =>  $validator->messages()
            ], 'Error Validator', 402);
        }

        DB::beginTransaction();

        try {
            $permission = Permission::create(['name' => strtolower($request->permissionName)]);

            activity('permission_management')->withProperties($permission)->performedOn($permission)->log('Create Permission');


            DB::commit();
            return ResponseFormat::success([
                'message' => "Permission Created"
            ], "Permission Created");
        } catch (Exception $error) {
            DB::rollBack();
            return ResponseFormat::error([
                'error' => $error->getMessage()
            ], "Something went wrong", 400);
        }
    }

    public function editPermission($id)
    {
        $permission = Permission::find($id);

        if ($permission) {
            return ResponseFormat::success([
                'permission' => $permission,
                'action' => route('permission.update', $id)
            ], "Data Permission Ditemukan");
        } else {
            return ResponseFormat::error([
                'error' => "Permission Not Found"
            ], "Permission Not Found", 404);
        }
    }

    public function updatePermission(Request $request, $id)
    {
        $validator = Validator::make($request->all(), ['permissionName' => ['required', 'max:255']]);

        if ($validator->fails()) {
            return ResponseFormat::error([
                'error' => $validator->messages()
            ], "Error Validator", 402);
        }

        $permission = Permission::find($id);

        if ($permission) {
            DB::beginTransaction();
            try {
                $permission->name = strtolower($request->permissionName);
                $permission->save();

                activity('permission_management')->withProperties($permission)->performedOn($permission)->log('Update Permission');


                DB::commit();
                return ResponseFormat::success([
                    'message' => "Permission Updated"
                ], "Permission Updated");
            } catch (Exception $error) {
                DB::rollBack();
                return ResponseFormat::error([
                    'error' => $error->getMessage()
                ], "Something went wrong", 400);
            }
        } else {
            return ResponseFormat::error([
                'error' => "Permission Not Found"
            ], "Permission Not Found", 404);
        }
    }

    public function deletePermission(Request $request, $id)
    {
        $permission = Permission::find($id);

        if ($permission) {
            DB::beginTransaction();
            try {
                activity('permission_management')->withProperties($permission)->performedOn($permission)->log('Delete Permission');
                $permission->delete();
                DB::commit();
                return ResponseFormat::success([
                    'message' => "Permission deleted"
                ], "Permission Deleted");
            } catch (Exception $error) {
                DB::rollBack();
                return ResponseFormat::error([
                    'error' => $error->getMessage()
                ], "Something weng wrong", 400);
            }
        } else {
            return ResponseFormat::error([
                'error' => "Permission Not Found"
            ], "Permission Not Found", 404);
        }
    }

    public function datatablePermissions(Request $request)
    {
        $columns = array(
            0 => 'id',
            1 => 'name',
            2 => 'created_at'
        );

        $totalData = Permission::count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');


        // query
        $search = $request->input('search.value');
        $filter = false;

        $getPermissions = new Permission();


        //filter - filter
        if (!empty($search)) {
            $getPermissions = Permission::where('name', 'ILIKE', "%{$search}%")->orWhere('created_at', 'ILIKE', "%{$search}%");
            $filter = true;
        }

        //getData
        $permissions = $getPermissions->offset($start)
            ->limit($limit)
            ->orderBy($order, $dir)
            ->get();

        if ($filter == true) {
            $totalFiltered = $getPermissions->count();
        }


        $data = array();

        if (!empty($permissions)) {
            $no = $start;
            foreach ($permissions as $permission) {
                $no++;
                $nestedData['no'] = $no;
                $nestedData['permissionName'] = ucwords($permission->name);
                $nestedData['created_at'] = $permission->created_at->diffForHumans();

                $nestedData['action'] = '<button data-id="' . $permission->id . '" class="btn btn-primary btn-flat btn-edit">
                          <i class="fas fa-edit"></i>
                      </button>
                      <button type="button" data-id="' . $permission->id . '" class="btn btn-danger btn-flat btn-delete">
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


    /** ASSIGN METHODS */

    public function assign()
    {
        $getPermissions = Permission::get();

        $countPermissions = $getPermissions->count();
        $pembagian = floor($countPermissions / 2);

        $startSatu = 0;
        $startDua = $pembagian;

        $permissionsSatu = $getPermissions->take($pembagian);
        $permissionsDua = $getPermissions->slice($pembagian);

        // $permissionsSatu = Permission::offset($startSatu)->limit($pembagian)->get();
        // $permissionsDua = Permission::offset($startDua)->limit($pembagian)->get();



        $dataPage = [
            'pageTitle' => "Assign Roles & Permissions",
            'page' => "assignPermission",
            'permissionsSatu' => $permissionsSatu,
            'permissionsDua' => $permissionsDua
        ];

        return view('pages.rolesPermissions.assignPermission', $dataPage);
    }

    public function viewPermissions($id = null)
    {

        $role = Role::find($id);

        if ($role) {

            $permissions = $role->permissions->pluck('name');

            if (count($permissions) <= 0) {
                return ResponseFormat::success([
                    'dataPermissions' => '',
                    'dataRole' => $role,
                    'message' => "Please give permission(s) to Role",
                ], "Permissions Not Found");
            } else {
                return ResponseFormat::success([
                    'dataRole' => $role,
                    'dataPermissions' => $permissions
                ], "Permissions Found");
            }
        } else {
            return ResponseFormat::error([
                'error' => "Role Not Found"
            ], "Role Not Found", 404);
        }
    }

    public function storeAssign(Request $request)
    {
        $dataValidate = [
            'idRole' => ['required', 'numeric'],
            'permissions' => ['required', 'array']
        ];

        $validator = Validator::make($request->all(), $dataValidate);

        if ($validator->fails()) {
            return ResponseFormat::error([
                'errorValidator' => $validator->messages()
            ], 'Error Validator', 402);
        }

        $role = Role::find($request->idRole);

        if ($role) {
            $role->syncPermissions($request->permissions);

            return ResponseFormat::success([
                'messages' => "Role has been assigned to permissions"
            ], "Role has been assigned to permissions");
        } else {
            return ResponseFormat::error(
                ['error' => "Role Not Found"],
                "Error Not Found",
                404
            );
        }


        // dd($request);
    }

    public function datatableAssign(Request $request)
    {
        $columns = array(
            0 => 'id',
            1 => 'roles.name',
            2 => 'permissions.name',
            3 => 'created_at'
        );

        $totalData = Role::count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');


        // query
        $search = $request->input('search.value');
        $filter = false;

        $getRoles = Role::with('permissions');

        //filter - filter
        if (!empty($search)) {
            $getRoles->where(function ($query) use ($search) {
                $query->where('roles.name', 'ILIKE', "%{$search}%");
            });

            $filter = true;
        }

        //getData
        $roles = $getRoles->offset($start)
            ->limit($limit)
            ->orderBy($order, $dir)
            ->get();

        if ($filter == true) {
            $totalFiltered = $getRoles->count();
        }


        $data = array();

        if (!empty($roles)) {
            $no = $start;
            foreach ($roles as $role) {
                $no++;
                $dataPermissions = [];
                foreach ($role->permissions as $permit) {
                    $dataPermissions[] = ucwords($permit->name);
                }

                $nestedData['no'] = $no;
                $nestedData['role'] = ucwords($role->name);
                $nestedData['permission'] = '<button data-id="' . $role->id . '" class="btn btn-primary btn-flat btn-view">
                <i class="nav-icon fas fa-user-shield"></i>&nbsp; View & Assign Permissions
            </button>';
                $nestedData['created_at'] = $role->created_at->diffForHumans();

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

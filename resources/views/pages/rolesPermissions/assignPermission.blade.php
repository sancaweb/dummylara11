@extends('layouts.app')

@section('content')
    <section class="content mt-4">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card card-outline card-success">
                        <div class="card-header">
                            <h3 class="card-title">Assigned Roles to Permissions</h3>
                            <div class="card-tools">
                                <button id="btn-assignPermissionReload" type="button"
                                    class="btn btn-sm btn-flat btn-success">
                                    <i class="fas fa-sync"></i> &nbsp; Reload
                                </button>
                            </div>

                        </div>
                        <div class="card-body">
                            <table id="table-assignPermission" class="table table-bordered table-hover">
                                <thead class="bg-info">
                                    <tr>
                                        <th>NO</th>
                                        <th>Roles</th>
                                        <th>Permissions</th>
                                        <th>Created At</th>
                                        <!-- <th>Action</th> -->
                                    </tr>
                                </thead>

                                <tbody>

                                </tbody>
                                <tfoot>
                                </tfoot>
                            </table>
                        </div>
                    </div> <!-- ./end card -->
                </div>
            </div>
        </div>
    </section>
    @include('rolesPermissions.modalViewPermissions')
@endsection

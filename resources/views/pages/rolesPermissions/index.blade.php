@extends('layouts.app-layout')

@section('content')
    <section class="content mt-4">
        <div class="container-fluid">
            <div class="row">

                <div class="col-md-6 col-sm-6 col-lg-6">
                    <div class="card card-outline card-success">
                        <div class="card-header">
                            <h3 class="card-title">Data Roles</h3>
                            <div class="card-tools">
                                <button id="openFormRole" type="button" class="btn btn-sm btn-flat btn-primary">
                                    <i class="fas fa-plus"></i> Tambah Roles
                                </button>
                                <button id="btn-roleReload" type="button" class="btn btn-sm btn-flat btn-success">
                                    <i class="fas fa-sync"></i> &nbsp; Reload
                                </button>
                            </div>

                        </div>
                        <div class="card-body">
                            <table id="table-role" class="table table-bordered table-hover">
                                <thead class="bg-info">
                                    <tr>
                                        <th>NO</th>
                                        <th>Role Name</th>
                                        <th>Created At</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>

                                <tbody>

                                </tbody>
                                <tfoot>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div> <!-- ./end col -->

                <div class="col-md-6 col-sm-6 col-lg-6">

                    <div class="card card-outline card-success">
                        <div class="card-header">
                            <h3 class="card-title">Data Permissions</h3>
                            <div class="card-tools">
                                <button id="openFormPermission" type="button" class="btn btn-sm btn-flat btn-primary ">
                                    <i class="fas fa-plus"></i> Tambah Permissions
                                </button>
                                <button id="btn-permissionReload" type="button" class="btn btn-sm btn-flat btn-success">
                                    <i class="fas fa-sync"></i> &nbsp; Reload
                                </button>
                            </div>

                        </div>
                        <div class="card-body">
                            <table id="table-permissions" class="table table-bordered table-hover">
                                <thead class="bg-info">
                                    <tr>
                                        <th>NO</th>
                                        <th>Permissions</th>
                                        <th>Created At</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>

                                <tbody>

                                </tbody>
                                <tfoot>
                                </tfoot>
                            </table>
                        </div>
                    </div> <!-- ./end card -->
                </div> <!-- ./end col -->

            </div>
            <!--- ./end row -->
        </div>
    </section>
    @include('pages.rolesPermissions.modalFormInputRole')
    @include('pages.rolesPermissions.modalFormInputPermission')
@endsection

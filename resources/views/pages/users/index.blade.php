@extends('layouts.app-layout')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card card-outline card-success">
                    <div class="card-header">
                        <h3 class="card-title">Data User</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-sm btn-flat btn-primary openForm">
                                <i class="fas fa-plus"></i> Tambah User
                            </button>
                            <button id="btn-userReload" type="button" class="btn btn-sm btn-flat btn-success">
                                <i class="fas fa-sync"></i> &nbsp; Reload
                            </button>
                            @can('user delete')
                                <a href="{{ route('user.trash') }}" type="button" class="btn btn-sm btn-flat btn-danger">
                                    <i class="fas fa-trash"></i> &nbsp; Users Trash
                                </a>
                            @endcan

                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="table-user" class="table table-bordered table-hover">
                            <thead class="bg-info">
                                <tr>
                                    <th>NO</th>
                                    <th>Foto</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Username</th>
                                    <th>Role</th>
                                    <th>Created</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>

                            </tbody>
                            <tfoot>
                            </tfoot>
                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div> <!-- ./end .col-12 -->
        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
@endsection

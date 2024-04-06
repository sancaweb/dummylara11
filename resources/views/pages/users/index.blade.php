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
                    <div class="card-body table-responsive">
                        <table id="table-user" class="table table-bordered table-hover table-striped">
                            <thead >
                                <tr>
                                    <th class="bg-info text-center">NO</th>
                                    <th class="bg-info text-center">FOTO</th>
                                    <th class="bg-info text-center">NAMA</th>
                                    <th class="bg-info text-center">EMAIL</th>
                                    <th class="bg-info text-center">USERNAME</th>
                                    <th class="bg-info text-center">ROLE</th>
                                    <th class="bg-info text-center">CREATED</th>
                                    <th class="bg-info text-center">ACTION</th>
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

    @include('pages.users.modalFormInput')
@endsection

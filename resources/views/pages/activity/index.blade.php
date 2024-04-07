@extends('layouts.app-layout')

@section('content')
    <div class="container-fluid">

        @include('pages.activity.formFilter')

        <div class="row">
            <div class="col-12">
                <div class="card card-outline card-info">
                    <div class="card-header">
                        <h3 class="card-title">{{ $pageTitle }}</h3>
                        <div class="card-tools">
                            <button id="btn-activityReload" type="button" class="btn btn-sm btn-success">
                                <i class="fas fa-sync"></i> &nbsp; Reload
                            </button>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="table-activity" class="table table-bordered table-hover">
                            <thead class="bg-info">
                                <tr>
                                    <th>NO</th>
                                    <th>User</th>
                                    <th>Log Name</th>
                                    <th>Description</th>
                                    <th>Date</th>
                                    <th>Detail</th>
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

            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div>


    <!-- Modal -->
    <div class="modal fade" id="detailAct" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="detailActLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailActLabel">Detail Activity</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="txt_user">User Log</label>
                        <input readonly disabled type="text" class="form-control" id="txt_user" placeholder="Log Name">
                    </div>

                    <div class="form-group">
                        <label for="txt_logName">Log Name</label>
                        <input readonly disabled type="text" class="form-control" id="txt_logName"
                            placeholder="Log Name">
                    </div>

                    <div class="form-group">
                        <label for="txt_desc">Keterangan</label>
                        <input readonly disabled type="text" class="form-control" id="txt_desc"
                            placeholder="Keterangan">
                    </div>

                    <div class="form-group">
                        <label for="txt_data">Data</label>
                        <textarea readonly disabled id="txt_data" cols="30" rows="10" class="form-control"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="txt_created">Created at</label>
                        <input readonly disabled type="text" class="form-control" id="txt_created"
                            placeholder="Keterangan">
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- ./end Modal -->
@endsection

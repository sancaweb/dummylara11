<div class="modal fade" id="modalFormInputPermission" tabindex="-1" role="dialog" aria-labelledby="modalFormInputPermissionLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <form action="{{ route('permission.store') }}" id="formPermission" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalFormInputPermissionLabel"><i class="fas fa-user-shield"></i>&nbsp; Add Permission</h5>
                    <button type="button" class="close closeFormPermission" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">


                    <div class="form-group">
                        <label>Permission Name</label>
                        <input type="text" class="form-control" name="permissionName" required>
                    </div>
                    <!-- /.form-group -->


                </div> <!-- ./modal-body -->

                <div class="modal-footer">

                    <button type="button" class="btn btn-secondary btn-flat btn-danger closeFormPermission"><i class="far fa-window-close"></i>&nbsp;Close</button>
                    <button type="submit" class="btn btn-primary btn-flat"><i class="fas fa-save"></i>&nbsp;Save changes</button>

                </div>
            </div> <!-- ./modal-content -->
        </form>
    </div> <!-- ./modal-dialog -->
</div> <!-- ./modal -->

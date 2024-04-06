<div class="modal fade" id="modalFormInputRole" tabindex="-1" role="dialog" aria-labelledby="modalFormInputRoleLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <form method="POST" action="{{ route('role.store') }}" id="formRole" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalFormInputRoleLabel"><i class="fas fa-user-shield"></i>&nbsp; Add Role</h5>
                    <button type="button" class="close closeFormRole" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">


                    <div class="form-group">
                        <label>Role Name</label>
                        <input type="text" class="form-control" name="roleName" required>
                    </div>
                    <!-- /.form-group -->


                </div> <!-- ./modal-body -->

                <div class="modal-footer">

                    <button type="button" class="btn btn-secondary btn-flat btn-danger closeFormRole"><i class="far fa-window-close"></i>&nbsp;Close</button>
                    <button type="submit" class="btn btn-primary btn-flat"><i class="fas fa-save"></i>&nbsp;Save changes</button>

                </div>
            </div> <!-- ./modal-content -->
        </form>
    </div> <!-- ./modal-dialog -->
</div> <!-- ./modal -->

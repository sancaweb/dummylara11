<div class="modal fade" id="modalViewPermissions" tabindex="-1" role="dialog" aria-labelledby="modalViewPermissionsLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">

        <form action="{{ route('assignPermission.store') }}" method="POST" id="formAssign">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalViewPermissionsLabel"><i class="fas fa-user-shield"></i>&nbsp;
                        Permissions of Role</h5>
                    <button type="button" class="close closeFormViewPermission" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">

                    <div class="row">
                        <div class="col-md-4 col-sm-4 col-lg-4" style="border-right:3px solid #00bc8c;">
                            <fieldset>
                                <legend style="border-bottom:1px solid #00bc8c; border-top:3px solid #00bc8c;">Role Name
                                    :</legend>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="roleName" disabled>
                                    <input type="hidden" class="form-control" name="idRole" readonly>
                                </div> <!-- /.form-group -->
                            </fieldset>

                        </div>
                        <div class="col-md-8 col-sm-8 col-lg-8">
                            <fieldset>
                                <legend style="border-bottom:1px solid #00bc8c; border-top:3px solid #00bc8c;">
                                    Permissions :</legend>
                                <div class="form-group">
                                    <button type="button" class="btn btn-primary btn-flat" id="checkAll"><i
                                            class="fas fa-check-square"></i>&nbsp;Check All</button>
                                    <button type="button" class="btn btn-primary btn-flat" id="unCheckAll"><i
                                            class="fas fa-minus-square"></i>&nbsp;UnCheck All</button>

                                </div>
                                <div class="form-group">

                                    <div class="row">
                                        <div class="col-md-6" style="border-right:1px solid #00bc8c;">

                                            @foreach ($permissionsSatu as $indexSatu => $permitSatu)
                                                <div class="custom-control custom-switch">
                                                    <input value="{{ $permitSatu->name }}" name="permissions[]"
                                                        type="checkbox" class="custom-control-input checkSatu"
                                                        id="switchSatu{{ $indexSatu }}">
                                                    <label class="custom-control-label"
                                                        for="switchSatu{{ $indexSatu }}">{{ $permitSatu->name }}</label>
                                                </div>
                                            @endforeach
                                        </div>

                                        <div class="col-md-6">
                                            @foreach ($permissionsDua as $indexDua => $permitDua)
                                                <div class="custom-control custom-switch">
                                                    <input value="{{ $permitDua->name }}" name="permissions[]"
                                                        type="checkbox" class="custom-control-input checkSatu"
                                                        id="switchSatu{{ $indexDua }}">
                                                    <label class="custom-control-label"
                                                        for="switchSatu{{ $indexDua }}">{{ $permitDua->name }}</label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>


                                </div>
                                <!-- /.form-group -->
                            </fieldset>
                        </div>
                    </div>


                </div> <!-- ./modal-body -->

                <div class="modal-footer">

                    <button type="button" class="btn btn-secondary btn-flat btn-danger closeFormViewPermission"><i
                            class="far fa-window-close"></i>&nbsp;Close</button>
                    <button type="submit" class="btn btn-primary btn-flat"><i class="fas fa-save"></i>&nbsp; Save
                        changes</button>

                </div>
            </div> <!-- ./modal-content -->
        </form>
    </div> <!-- ./modal-dialog -->
</div> <!-- ./modal -->

<div class="modal fade" id="modalInputAssign" tabindex="-1" role="dialog" aria-labelledby="modalInputAssignLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalInputAssignLabel"><i class="fas fa-user-shield"></i>&nbsp; Give Permissions to Role</h5>
                <button type="button" class="close closeInputAssign" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">

                <div class="row">
                    <div class="col-md-4 col-sm-4 col-lg-4" style="border-right:3px solid #00bc8c;">
                        <fieldset>
                            <legend style="border-bottom:1px solid #00bc8c; border-top:3px solid #00bc8c;">Role Name :</legend>
                            <div class="form-group">
                                <input type="text" class="form-control" name="roleName" disabled>
                            </div> <!-- /.form-group -->
                        </fieldset>

                    </div>
                    <div class="col-md-8 col-sm-8 col-lg-8">
                        <fieldset>
                            <legend style="border-bottom:1px solid #00bc8c; border-top:3px solid #00bc8c;">Permissions :</legend>
                            <div class="form-group">

                                <div class="row">
                                    <div class="col-md-6" style="border-right:1px solid #00bc8c;">

                                        @foreach ($permissionsSatu as $indexSatu=>$permitSatu)
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input" id="customSwitch{{ $indexSatu }}">
                                            <label class="custom-control-label" for="customSwitch{{ $indexSatu }}">{{ $permitSatu->name }}</label>
                                        </div>
                                        @endforeach
                                    </div>
                                    <div class="col-md-6">
                                        @foreach ($permissionsDua as $indexDua=>$permitDua)
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input" id="customSwitch{{ $indexDua }}">
                                            <label class="custom-control-label" for="customSwitch{{ $indexDua }}">{{ $permitDua->name }}</label>
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

                <button type="button" class="btn btn-secondary btn-flat btn-danger closeInputAssign">Close</button>

            </div>
        </div> <!-- ./modal-content -->
    </div> <!-- ./modal-dialog -->
</div> <!-- ./modal -->

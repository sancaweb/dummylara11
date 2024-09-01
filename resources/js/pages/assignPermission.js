$(function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    var columnsAssignPermission = [
        { data: "no" },
        { data: "role" },
        { data: "permission" },
        { data: "created_at" },
    ];

    var tableAssignPermission = $("#table-assignPermission").DataTable({
        searching: true,
        order: [[1, "ASC"]],
        processing: true,
        serverSide: true,
        ajax: {
            url: base_url + "/assignpermission/datatable",
            dataType: "json",
            type: "POST",
            error: function (jqXHR, textStatus, errorThrown) {
                if (jqXHR.responseJSON.data) {
                    var error = jqXHR.responseJSON.data.error;
                    Swal.fire({
                        icon: "error",
                        title: " <br>Application error!",
                        html:
                            '<div class="alert alert-danger text-left" role="alert">' +
                            "<p>Error Message: <strong>" +
                            error +
                            "</strong></p>" +
                            "</div>",
                        allowOutsideClick: false,
                        showConfirmButton: true,
                    }).then(function () {
                        refreshTable();
                    });
                } else {
                    var message = jqXHR.responseJSON.message;
                    var errorLine = jqXHR.responseJSON.line;
                    var file = jqXHR.responseJSON.file;
                    Swal.fire({
                        icon: "error",
                        title: " <br>Application error!",
                        html:
                            '<div class="alert alert-danger text-left" role="alert">' +
                            "<p>Error Message: <strong>" +
                            message +
                            "</strong></p>" +
                            "<p>File: " +
                            file +
                            "</p>" +
                            "<p>Line: " +
                            errorLine +
                            "</p>" +
                            "</div>",
                        allowOutsideClick: false,
                        showConfirmButton: true,
                    }).then(function () {
                        refreshTable();
                    });
                }
            },
        },

        columns: columnsAssignPermission,
        columnDefs: [
            {
                orderable: false,
                targets: [0, -1],
            },
            {
                targets: [0],
                createdCell: function (td, cellData, rowData, row, col) {
                    $(td).addClass("text-right");
                },
            },
        ],
    });
    $("#table-assignPermission_filter input").off();
    $("#table-assignPermission_filter input").on("keyup", function (e) {
        if (e.code == "Enter") {
            tableAssignPermission.search(this.value).draw();
        }
    });

    function refreshTableAssignPermission() {
        tableAssignPermission.search("").ajax.reload();
    }

    var btnAssignPermission = document.getElementById(
        "btn-assignPermissionReload"
    );
    if (btnAssignPermission) {
        btnAssignPermission.addEventListener("click", function () {
            refreshTableAssignPermission();
        });
    }

    /** ./end datatable */

    var modalViewPermissionsOptions = {
        backdrop: false,
        keyboard: false,
    };
    var modalViewPermissions = new bootstrap.Modal(
        document.getElementById("modalViewPermissions"),
        modalViewPermissionsOptions
    );

    function formAttribute(dataPermissions) {
        var checkSatu = $(".checkSatu");

        $.each(checkSatu, (indexSatu, permitValSatu) => {

            if (dataPermissions.includes(permitValSatu.value)) {
                $("#switchSatu" + indexSatu).prop("checked", true);
            } else {
                $("#switchSatu" + indexSatu).prop("checked", false);
            }
        });

        Swal.close();
        modalViewPermissions.show();
    }

    $("#table-assignPermission").on("click", ".btn-view", function () {
        Swal.fire({
            imageHeight: 300,
            showConfirmButton: false,
            title: '<i class="fas fa-spinner fa-pulse fa-10x" ></i>',
            text: "Loading ...",
            allowOutsideClick: false,
            timerProgressBar: true,
        });

        var idRole = $(this).data("id");
        var urlViewPermissions =
            base_url + "/assignpermission/" + idRole + "/viewpermission";
        $.ajax({
            url: urlViewPermissions,
            type: "get",
            success: function (x) {
                var dataPermissions = x.data.dataPermissions;
                var dataRole = x.data.dataRole;
                $('[name="roleName"]').val(dataRole.name);
                $('[name="idRole"]').val(dataRole.id);

                if (dataPermissions == "") {
                    Swal.fire({
                        icon: "error",
                        title: x.meta.message,
                        html:
                            '<div class="alert alert-danger text-left" role="alert">' +
                            "<p>" +
                            x.data.message +
                            "</p>" +
                            "</div>",
                        allowOutsideClick: false,
                    }).then(() => {
                        formAttribute(dataPermissions);
                    });
                } else {
                    formAttribute(dataPermissions);
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                var meta = jqXHR.responseJSON.meta;
                var data = jqXHR.responseJSON.data;

                Swal.fire({
                    icon: "error",
                    title: meta.message,
                    html:
                        '<div class="alert alert-danger text-left" role="alert">' +
                        "<p>" +
                        data.error +
                        "</p>" +
                        "</div>",
                    allowOutsideClick: false,
                });
            },
        });
    });

    $(".closeFormViewPermission").on("click", () => {
        modalViewPermissions.hide();
    });

    // // submit assign
    $("#formAssign").on("submit", function (e) {
        e.preventDefault();
        Swal.fire({
            imageHeight: 300,
            showConfirmButton: false,
            title: '<i class="fas fa-spinner fa-pulse fa-10x" ></i>',
            text: "Loading ...",
            allowOutsideClick: false,
            timerProgressBar: true,
        });
        var formData = new FormData($("#formAssign")[0]);
        var url = $("#formAssign").attr("action");
        $.ajax({
            url: url,
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            dataType: "JSON",
            success: function (data) {
                Swal.fire({
                    icon: "success",
                    title: data.meta.message,
                    showConfirmButton: false,
                    timer: 2000,
                    allowOutsideClick: false,
                }).then(function () {
                    refreshTableAssignPermission();

                    modalViewPermissions.hide();
                });
            },
            error: function (jqXHR, textStatus, errorThrown) {
                if (jqXHR.responseJSON.data.errorValidator) {
                    var errors = jqXHR.responseJSON.data.errorValidator;
                    var message = jqXHR.responseJSON.message;
                    var li = "";
                    $.each(errors, function (key, value) {
                        li += "<li>" + value + "</li>";
                    });

                    Swal.fire({
                        icon: "error",
                        title: message,
                        html:
                            '<div class="alert alert-danger text-left" role="alert">' +
                            "<ul>" +
                            li +
                            "</ul>" +
                            "</div>",
                        footer: "Pastikan data yang anda masukkan sudah benar!",
                        allowOutsideClick: false,
                        showConfirmButton: true,
                    });
                } else {
                    var message = jqXHR.responseJSON.meta.message;
                    var data = jqXHR.responseJSON.data;

                    Swal.fire({
                        icon: "error",
                        title: message + " <br>Application error!",
                        html:
                            '<div class="alert alert-danger text-left" role="alert">' +
                            "<p>Error Message: <strong>" +
                            message +
                            "</strong></p>" +
                            "<p>Error: " +
                            data.error +
                            "</p>" +
                            "</div>",
                        allowOutsideClick: false,
                        showConfirmButton: true,
                    });
                }
            },
        });
    });
    //./end submit assign

    $("#checkAll").on("click", function () {
        $(".checkSatu").prop("checked", true);
        $(".checkDua").prop("checked", true);
    });

    $("#unCheckAll").on("click", function () {
        $(".checkSatu").prop("checked", false);
        $(".checkDua").prop("checked", false);
    });
}); // ./end document

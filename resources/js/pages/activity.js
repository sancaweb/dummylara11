import moment from "moment";
import { DateRangePicker } from "vanillajs-datepicker";

$(function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });
// console.log(moment().format("DD-MM-YYYY"));
    const elem = document.getElementById("dateRangeFilter");
    const datePickerOpt = {
        clearButton:true,
        buttonClass: 'btn',
        format:"dd/mm/yyyy",
        maxDate:moment().format("DD-MM-YYYY")
    };
    new DateRangePicker(elem,datePickerOpt);

    // $(".rangeDate").val();

    $("#userAct").select2({
        theme: "bootstrap-5",
        placeholder: "Select User",
        allowClear: true,
    });

    $("#logNameAct").select2({
        theme: "bootstrap-5",
        placeholder: "Select Log Name",
        allowClear: true,
    });

    var columnsTable = [
        { data: "no" },
        { data: "user" },
        { data: "logName" },
        { data: "description" },
        { data: "created_at" },
        { data: "action" },
    ];

    var tableActivity = $("#table-activity").DataTable({
        searching: false,
        order: [[0, "DESC"]],
        processing: true,
        serverSide: true,
        ajax: {
            url: base_url + "/activity/datatable",
            dataType: "json",
            type: "POST",
            data: function (dataFilter) {
                var userAct = $("#userAct").val();
                var logNameAct = $("#logNameAct").val();
                var rangeStart = $("#rangeStart").val();
                var rangeEnd = $("#rangeEnd").val();
                var descAct = $("#descAct").val();

                dataFilter.userAct = userAct;
                dataFilter.logNameAct = logNameAct;
                dataFilter.rangeStart = rangeStart;
                dataFilter.rangeEnd = rangeEnd;
                dataFilter.descAct = descAct;
            },
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

        columns: columnsTable,
        columnDefs: [
            {
                orderable: false,
                targets: [0, -1],
            },
        ],
    });
    $("#table-activity_filter input").off();
    $("#table-activity_filter input").on("keyup", function (e) {
        if (e.code == "Enter") {
            tableActivity.search(this.value).draw();
        }
    });

    function refreshTable() {
        tableActivity.search("").draw();
    }

    var btnReloadActivity = document.getElementById("btn-activityReload");
    if (btnReloadActivity) {
        btnReloadActivity.addEventListener("click", function () {
            refreshTable();
        });
    }

    /** ./end datatable */

    $("#openCard").on("click", function () {
        openCard();
    });

    $("#closeCard").on("click", function () {
        closeCard();
    });

    function closeCard() {
        var elementLink = document.getElementById("cardFormUser");
        elementLink.classList.add("collapsed-card");

        $(".collapse").collapse("hide");
    }

    function openCard() {
        var elementLink = document.getElementById("cardFormUser");
        elementLink.classList.remove("collapsed-card");

        $(".collapse").collapse("show");
    }


    $("#btn-resetFilter").on("click", function () {
        $("#userAct").val("").trigger('change');
        $("#logNameAct").val("").trigger('change');
        $("#descAct").val("");
        $("#rangeStart").val("");
        $("#rangeEnd").val("");
        closeCard();
    });

    $("#btn-filter").on("click", function () {
        tableActivity.draw();
    });

    var modalDetailOptions = {
        backdrop: false,
        keyboard:false
    };
    var modalDetail = new bootstrap.Modal(
        document.getElementById("detailAct"),
        modalDetailOptions
    );

    $("#table-activity").on("click", ".btn-detail", function () {
        Swal.fire({
            imageHeight: 300,
            showConfirmButton: false,
            title: '<i class="fas fa-spinner fa-pulse fa-10x" ></i>',
            text: "Loading ...",
            allowOutsideClick: false,
            timerProgressBar: true,
        });

        var idAct = $(this).data("id");
        var urlDetail = base_url + "/activity/" + idAct + "/show";

        $.ajax({
            url: urlDetail,
            type: "get",
            success: function (data) {
                var dataAct = data.data;
                $("#txt_user").val(dataAct.user);
                $("#txt_logName").val(dataAct.log_name);
                $("#txt_desc").val(dataAct.description);
                $("#txt_data").val(JSON.stringify(dataAct.properties));
                $("#txt_created").val(dataAct.created_at);

                Swal.close();
                modalDetail.show();
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
}); // ./end document

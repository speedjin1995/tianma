<?php
require_once 'php/db_connect.php';

session_start();

if(!isset($_SESSION['userID'])){
  echo '<script type="text/javascript">';
  echo 'window.location.href = "login.html";</script>';
}
else{
  $user = $_SESSION['userID'];
  $name = $_SESSION['name'];
}
?>

<style>
    .bootstrap-datetimepicker-widget table th:hover {
        color: black;
    }

    .mt-32{
        margin-top:32px;
    } 
</style>

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
			<div class="col-sm-6">
				<h1 class="m-0 text-dark">Log 日志</h1>
			</div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
	<div class="container-fluid">
        <div class="row">
			<div class="col-12">
				<div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-3">
                                    <label>From Date 开始日期</label>
                                    <div class="input-group date" id="fromDatePicker" data-target-input="nearest">
                                        <input type="text" class="form-control datetimepicker-input" data-target="#fromDatePicker" id="fromDate" name="fromDate" required/>
                                        <div class="input-group-append" data-target="#fromDatePicker" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div></div>
                                    </div>
                                </div>

                                <div class="form-group col-3">
                                    <label>To Date 结束日期</label>
                                    <div class="input-group date" id="toDatePicker" data-target-input="nearest">
                                        <input type="text" class="form-control datetimepicker-input" data-target="#toDatePicker" id="toDate" name="toDate" required/>
                                        <div class="input-group-append" data-target="#toDatePicker" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="userName">User Name 用户名字</label>
                                        <input type="text" class="form-control" name="userName" id="userName" placeholder="Enter Box/Tray No">
                                    </div>
                                </div>

                                <div class="form-group col-md-3 mt-32">
                                    <button class="btn btn-success" id="filterSearch"><i class="fas fa-search"></i>Filter 筛选</button> 
                                </div>  
                            </div>
                        </div>

					<div class="card-body">
						<table id="logsTable" class="table table-bordered table-striped">
							<thead>
								<tr>
									<th>No. 排号</th>
                                    <th>User Name 用户名字</th>
                                    <th>Date & Time 时间</th>
									<th>Actions 行动</th>
								</tr>
							</thead>
						</table>
					</div><!-- /.card-body -->
				</div><!-- /.card -->
			</div><!-- /.col -->
		</div><!-- /.row -->
	</div><!-- /.container-fluid -->
</section><!-- /.content -->

<script>
$(function () {

      //Date picker
  $('#fromDatePicker').datetimepicker({
      icons: { time: 'far fa-clock' },
      format: 'DD/MM/YYYY HH:mm:ss A',
      defaultDate: new Date
  });

  $('#toDatePicker').datetimepicker({
      icons: { time: 'far fa-clock' },
      format: 'DD/MM/YYYY HH:mm:ss A',
      defaultDate: new Date
  });


    $("#logsTable").DataTable({
        "responsive": true,
        "autoWidth": false,
        'processing': true,
        'serverSide': true,
        'searching': false,
        'serverMethod': 'post',
        'order': [[ 3, 'asc' ]],
        'columnDefs': [ { orderable: false, targets: [0] }],
        'ajax': {
            'url':'php/loadLog.php'
        },
        'columns': [
            { data: 'counter' },
            { data: 'userName' },
            { data: 'created_dateTime' },
            { data: 'action' }
            // { 
            //     data: 'id',
            //     render: function ( data, type, row ) {
            //         return '<div class="row"><div class="col-3"><button type="button" id="edit'+data+'" onclick="edit('+data+')" class="btn btn-success btn-sm"><i class="fas fa-pen"></i></button></div><div class="col-3"><button type="button" id="deactivate'+data+'" onclick="deactivate('+data+')" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button></div></div>';
            //     }
            // }
        ],
        "rowCallback": function( row, data, index ) {

            $('td', row).css('background-color', '#E6E6FA');
        },

    });


    $('#filterSearch').on('click', function(){
        $('#spinnerLoading').show();

        var fromDateValue = '';
        var toDateValue = '';
        var filterUserName = '';

        if($('#fromDate').val()){
        var convert1 = $('#fromDate').val().replace(", ", " ");
        convert1 = convert1.replace(":", "/");
        convert1 = convert1.replace(":", "/");
        convert1 = convert1.replace(" ", "/");
        convert1 = convert1.replace(" pm", "");
        convert1 = convert1.replace(" am", "");
        convert1 = convert1.replace(" PM", "");
        convert1 = convert1.replace(" AM", "");
        var convert2 = convert1.split("/");
        var date  = new Date(convert2[2], convert2[1] - 1, convert2[0], convert2[3], convert2[4], convert2[5]);
        fromDateValue = date.getFullYear() + "-" + (date.getMonth() + 1) + "-" + date.getDate() + " " + date.getHours() + ":" + date.getMinutes() + ":" + date.getSeconds();
        }
        
        if($('#toDate').val()){
        var convert3 = $('#toDate').val().replace(", ", " ");
        convert3 = convert3.replace(":", "/");
        convert3 = convert3.replace(":", "/");
        convert3 = convert3.replace(" ", "/");
        convert3 = convert3.replace(" pm", "");
        convert3 = convert3.replace(" am", "");
        convert3 = convert3.replace(" PM", "");
        convert3 = convert3.replace(" AM", "");
        var convert4 = convert3.split("/");
        var date2  = new Date(convert4[2], convert4[1] - 1, convert4[0], convert4[3], convert4[4], convert4[5]);
        toDateValue = date2.getFullYear() + "-" + (date2.getMonth() + 1) + "-" + date2.getDate() + " " + date2.getHours() + ":" + date2.getMinutes() + ":" + date2.getSeconds();
        }

        filterUserName = $("#userName").val();

        //Destroy the old Datatable
        $("#logsTable").DataTable().clear().destroy();

        //Create new Datatable
        table = $("#logsTable").DataTable({
            "responsive": true,
            "autoWidth": false,
            'processing': true,
            'serverSide': true,
            'serverMethod': 'post',
            'searching': false,
            'order': [[ 3, 'asc' ]],
            'columnDefs': [ { orderable: false, targets: [0] }],
            'ajax': {
                'type': 'POST',
                'url':'php/filterLog.php',
                'data': {
                    fromDate: fromDateValue,
                    toDate: toDateValue,
                    filterUserName : filterUserName,
                } 
            },
            'columns': [
                { data: 'counter' },
                { data: 'userName' },
                { data: 'created_dateTime' },
                { data: 'action' },
                ],
                "rowCallback": function( row, data, index ) {
                    $('td', row).css('background-color', '#E6E6FA');
                }

        });

        $('#spinnerLoading').hide();
    });
    // $.validator.setDefaults({
    //     submitHandler: function () {
    //         $('#spinnerLoading').show();
    //         $.post('php/reasons.php', $('#reasonForm').serialize(), function(data){
    //             var obj = JSON.parse(data); 
                
    //             if(obj.status === 'success'){
    //                 $('#reasonModal').modal('hide');
    //                 toastr["success"](obj.message, "Success:");
                    
    //                 $.get('reasons.php', function(data) {
    //                     $('#mainContents').html(data);
    //                     $('#spinnerLoading').hide();
    //                 });
    //             }
    //             else if(obj.status === 'failed'){
    //                 toastr["error"](obj.message, "Failed:");
    //                 $('#spinnerLoading').hide();
    //             }
    //             else{
    //                 toastr["error"]("Something wrong when edit", "Failed:");
    //                 $('#spinnerLoading').hide();
    //             }
    //         });
    //     }
    // });

    // $('#addReasons').on('click', function(){
    //     $('#reasonModal').find('#id').val("");
    //     $('#reasonModal').find('#itemType').val('');
    //     $('#reasonModal').find('#reasons').val("");
    //     $('#reasonModal').modal('show');
        
    //     $('#reasonForm').validate({
    //         errorElement: 'span',
    //         errorPlacement: function (error, element) {
    //             error.addClass('invalid-feedback');
    //             element.closest('.form-group').append(error);
    //         },
    //         highlight: function (element, errorClass, validClass) {
    //             $(element).addClass('is-invalid');
    //         },
    //         unhighlight: function (element, errorClass, validClass) {
    //             $(element).removeClass('is-invalid');
    //         }
    //     });
    // });
});

// function edit(id){
//     $('#spinnerLoading').show();
//     $.post('php/getReasons.php', {userID: id}, function(data){
//         var obj = JSON.parse(data);
        
//         if(obj.status === 'success'){
//             $('#reasonModal').find('#id').val(obj.message.id);
//             $('#reasonModal').find('#itemType').val(obj.message.itemType);
//             $('#reasonModal').find('#reasons').val(obj.message.reasons);
//             $('#reasonModal').modal('show');
            
//             $('#reasonForm').validate({
//                 errorElement: 'span',
//                 errorPlacement: function (error, element) {
//                     error.addClass('invalid-feedback');
//                     element.closest('.form-group').append(error);
//                 },
//                 highlight: function (element, errorClass, validClass) {
//                     $(element).addClass('is-invalid');
//                 },
//                 unhighlight: function (element, errorClass, validClass) {
//                     $(element).removeClass('is-invalid');
//                 }
//             });
//         }
//         else if(obj.status === 'failed'){
//             toastr["error"](obj.message, "Failed:");
//         }
//         else{
//             toastr["error"]("Something wrong when activate", "Failed:");
//         }
//         $('#spinnerLoading').hide();
//     });
// }

// function deactivate(id){
//     $('#spinnerLoading').show();
//     $.post('php/deleteReasons.php', {userID: id}, function(data){
//         var obj = JSON.parse(data);
        
//         if(obj.status === 'success'){
//             toastr["success"](obj.message, "Success:");
//             $.get('Reasons.php', function(data) {
//                 $('#mainContents').html(data);
//                 $('#spinnerLoading').hide();
//             });
//         }
//         else if(obj.status === 'failed'){
//             toastr["error"](obj.message, "Failed:");
//             $('#spinnerLoading').hide();
//         }
//         else{
//             toastr["error"]("Something wrong when activate", "Failed:");
//             $('#spinnerLoading').hide();
//         }
//     });
// }
</script>
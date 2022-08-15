<?php
require_once 'php/db_connect.php';

session_start();

if(!isset($_SESSION['userID'])){
  echo '<script type="text/javascript">';
  echo 'window.location.href = "login.html";</script>';
}
else{
  $user = $_SESSION['userID'];
}
?>

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
					<!-- <div class="card-header">
                        <div class="row">
                            <div class="col-9"></div>
                            <div class="col-3">
                                <button type="button" class="btn btn-block bg-gradient-warning btn-sm" id="addReasons">Add Lo 新增理由</button>
                            </div>
                        </div>
                    </div> -->
					<div class="card-body">
						<table id="reasonsTable" class="table table-bordered table-striped">
							<thead>
								<tr>
									<th>No. 排号</th>
                                    <th>User Id 用户身份</th>
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
    $("#reasonsTable").DataTable({
        "responsive": true,
        "autoWidth": false,
        'processing': true,
        'serverSide': true,
        'serverMethod': 'post',
        'order': [[ 2, 'asc' ]],
        'columnDefs': [ { orderable: false, targets: [0] }],
        'ajax': {
            'url':'php/loadLog.php'
        },
        'columns': [
            { data: 'counter' },
            { data: 'userId' },
            { data: 'dateTime' },
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
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
				<h1 class="m-0 text-dark">Packages</h1>
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
					<div class="card-header">
                        <div class="row">
                            <div class="col-9"></div>
                            <div class="col-3">
                                <button type="button" class="btn btn-block bg-gradient-warning btn-sm" id="addPackages">Add Packages</button>
                            </div>
                        </div>
                    </div>
					<div class="card-body">
						<table id="packageTable" class="table table-bordered table-striped">
							<thead>
								<tr>
									<th>No.</th>
                                    <th>Code</th>
									<th>Packages.</th>
									<th>Actions</th>
								</tr>
							</thead>
						</table>
					</div><!-- /.card-body -->
				</div><!-- /.card -->
			</div><!-- /.col -->
		</div><!-- /.row -->
	</div><!-- /.container-fluid -->
</section><!-- /.content -->

<div class="modal fade" id="packagesModal">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <form role="form" id="packageForm">
            <div class="modal-header">
              <h4 class="modal-title">Add Lots</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="card-body">
                <div class="form-group">
                  <input type="hidden" class="form-control" id="id" name="id">
                </div>
                <div class="form-group">
                  <label for="code">Packages Code *</label>
                  <input type="text" class="form-control" name="code" id="code" placeholder="Enter Product Code" maxlength="10" required>
                </div>
                <div class="form-group">
                  <label for="packages">Packages *</label>
                  <input type="text" class="form-control" name="packages" id="packages" placeholder="Enter Packages Number" required>
                </div>
              </div>
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary" name="submit" id="submitLot">Submit</button>
            </div>
        </form>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<script>
$(function () {
    $("#packageTable").DataTable({
        "responsive": true,
        "autoWidth": false,
        'processing': true,
        'serverSide': true,
        'serverMethod': 'post',
        'order': [[ 1, 'asc' ]],
        'columnDefs': [ { orderable: false, targets: [0] }],
        'ajax': {
            'url':'php/loadPackages.php'
        },
        'columns': [
            { data: 'counter' },
            { data: 'packages_code' },
            { data: 'packages' },
            { 
                data: 'id',
                render: function ( data, type, row ) {
                    return '<div class="row"><div class="col-3"><button type="button" id="edit'+data+'" onclick="edit('+data+')" class="btn btn-success btn-sm"><i class="fas fa-pen"></i></button></div><div class="col-3"><button type="button" id="deactivate'+data+'" onclick="deactivate('+data+')" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button></div></div>';
                }
            }
        ],
        "rowCallback": function( row, data, index ) {

            $('td', row).css('background-color', '#E6E6FA');
        },        
    });
    
    $.validator.setDefaults({
        submitHandler: function () {
            $('#spinnerLoading').show();
            $.post('php/packages.php', $('#packageForm').serialize(), function(data){
                var obj = JSON.parse(data); 
                
                if(obj.status === 'success'){
                    $('#packagesModal').modal('hide');
                    toastr["success"](obj.message, "Success:");
                    
                    $.get('packages.php', function(data) {
                        $('#mainContents').html(data);
                        $('#spinnerLoading').hide();
                    });
                }
                else if(obj.status === 'failed'){
                    toastr["error"](obj.message, "Failed:");
                    $('#spinnerLoading').hide();
                }
                else{
                    toastr["error"]("Something wrong when edit", "Failed:");
                    $('#spinnerLoading').hide();
                }
            });
        }
    });

    $('#addPackages').on('click', function(){
        $('#packagesModal').find('#id').val("");
        $('#packagesModal').find('#code').val("");
        $('#packagesModal').find('#packages').val("");
        $('#packagesModal').modal('show');
        
        $('#packageForm').validate({
            errorElement: 'span',
            errorPlacement: function (error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function (element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });
    });
});

function edit(id){
    $('#spinnerLoading').show();
    $.post('php/getPackages.php', {userID: id}, function(data){
        var obj = JSON.parse(data);
        
        if(obj.status === 'success'){
            $('#packagesModal').find('#id').val(obj.message.id);
            $('#packagesModal').find('#code').val(obj.message.packages_code);
            $('#packagesModal').find('#packages').val(obj.message.packages);
            $('#packagesModal').modal('show');
            
            $('#packageForm').validate({
                errorElement: 'span',
                errorPlacement: function (error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                },
                highlight: function (element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function (element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                }
            });
        }
        else if(obj.status === 'failed'){
            toastr["error"](obj.message, "Failed:");
        }
        else{
            toastr["error"]("Something wrong when activate", "Failed:");
        }
        $('#spinnerLoading').hide();
    });
}

function deactivate(id){
    $('#spinnerLoading').show();
    $.post('php/deletePackages.php', {userID: id}, function(data){
        var obj = JSON.parse(data);
        
        if(obj.status === 'success'){
            toastr["success"](obj.message, "Success:");
            $.get('packages.php', function(data) {
                $('#mainContents').html(data);
                $('#spinnerLoading').hide();
            });
        }
        else if(obj.status === 'failed'){
            toastr["error"](obj.message, "Failed:");
            $('#spinnerLoading').hide();
        }
        else{
            toastr["error"]("Something wrong when activate", "Failed:");
            $('#spinnerLoading').hide();
        }
    });
}
</script>
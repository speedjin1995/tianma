<?php
require_once 'php/db_connect.php';

session_start();

if(!isset($_SESSION['userID'])){
    echo '<script type="text/javascript">';
	echo 'window.location.href = "../login.html";</script>';
}
else{
    /*$stmt = $db->prepare("SELECT users.id, users.name, users.email, users.joined_date, users.expired_date, users.status, roles.role_name from users, roles WHERE users.role_code = roles.role_code");
    $stmt->execute();
    $result = $stmt->get_result();
    
    $stmt2 = $db->prepare("SELECT * FROM roles");
    $stmt2->execute();
    $result2 = $stmt2->get_result();*/
}
?>

<style>
    .bootstrap-datetimepicker-widget table th:hover {
        color: black;
    }
</style>

<section class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
			<div class="col-sm-6">
				<h1 class="m-0 text-dark">Batch Report 批次报告</h1>
			</div>
		</div>
	</div>
</section>

<section class="content" style="min-height:700px;">
	<div class="card">
		<form role="form" id="profileForm" novalidate="novalidate">
			<div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="newLotNo">Batch No 批号</label>
                            <input type="text" class="form-control" name="newLotNo" id="newLotNo" placeholder="Enter Lot No">
                        </div>
                    </div>
                </div>
			</div>
			
			<div class="card-footer">
				<button class="btn btn-success" id="exportProfile"><i class="fas fa-file-export"></i>Export 导出</button>
			</div>
		</form>
	</div>
</section>

<script>
$(function () {
  //Date picker
  /*$('#fromDatePicker').datetimepicker({
      icons: { time: 'far fa-clock' },
      format: 'DD/MM/YYYY',
      defaultDate: new Date
  });

  $('#toDatePicker').datetimepicker({
      icons: { time: 'far fa-clock' },
      format: 'DD/MM/YYYY',
      defaultDate: new Date
  });*/

    /*$.validator.setDefaults({
        submitHandler: function () {
            $('#spinnerLoading').show();
            $.post('php/exportBatch.php', $('#profileForm').serialize(), function(data){
                var obj = JSON.parse(data); 
                
                if(obj.status === 'success'){
                    toastr["success"](obj.message, "Success:");
                    
                    $.get('myprofile.php', function(data) {
                        $('#mainContents').html(data);
                        $('#spinnerLoading').hide();
                    });
        		}
        		else if(obj.status === 'failed'){
        		    toastr["error"](obj.message, "Failed:");
                    $('#spinnerLoading').hide();
                }
        		else{
        			toastr["error"]("Failed to update profile", "Failed:");
                    $('#spinnerLoading').hide();
        		}
            });
        }
    });*/
    
    $('#profileForm').validate({
        rules: {
            text: {
                required: true
            }
        },
        messages: {
            text: {
                required: "Please fill in this field"
            }
        },
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
</script>
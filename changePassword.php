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

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Change Password</h1>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="card">
        <form role="form" id="passwordForm">
            <div class="card-body">
                <div class="form-group">
                    <label for="oldPassword">Old Password *</label>
                    <input type="password" class="form-control" name="oldPassword" placeholder="Old Password" required="">
                </div>
                
                <div class="form-group">
                    <label for="newPassword">New Password</label>
                    <input type="password" class="form-control" name="newPassword" id="newPassword" placeholder="New Password" required="">
                </div>
                
                <div class="form-group">
                    <label for="confirmPassword">Confirm Password *</label>
                    <input type="password" class="form-control" name="confirmPassword" id="confirmPassword" placeholder="Re-type Password" required="">
                </div>
            </div>
            
            <div class="card-footer">
                <button type="submit" class="btn btn-success" name="submit"><i class="fas fa-save"></i> Save</button>
            </div>
        </form>
    </div>
</section>

<script>
$(function () {
    $.validator.setDefaults({
        submitHandler: function () {
            $('#spinnerLoading').show();
            $.post('php/changePassword.php', $('#passwordForm').serialize(), function(data){
                var obj = JSON.parse(data); 
                
                if(obj.status === 'success'){
                    toastr["success"](obj.message, "Success:");
                    
                    $.get('changePassword.php', function(data) {
                        $('#mainContents').html(data);
                        $('#spinnerLoading').hide();
                    });
                }
                else if(obj.status === 'failed'){
                    toastr["error"](obj.message, "Failed:");
                    $('#spinnerLoading').hide();
                }
                else{
                    toastr["error"]("Failed to update password", "Failed:");
                    $('#spinnerLoading').hide();
                }
            });
        }
    });
    
    $('#passwordForm').validate({
        rules: {
            newPassword: {
                minlength: 6
            },
            confirmPassword: {
                equalTo: "#newPassword"
            }
        },
        messages: {
            newPassword: {
                minlength: "Your password must be at least 6 characters long"
            },
            confirmPassword: " Enter Confirm Password Same as New Password"
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
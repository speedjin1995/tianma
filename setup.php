<?php
require_once 'php/db_connect.php';

session_start();

if(!isset($_SESSION['userID'])){
    echo '<script type="text/javascript">';
    echo 'window.location.href = "login.html";</script>';
}
else{
    $id = $_SESSION['userID'];
    $stmt = $db->prepare("SELECT * from users where id = ?");
	$stmt->bind_param('s', $id);
	$stmt->execute();
	$result = $stmt->get_result();
    $port = '';
    $baudrate = '';
    $databits = '';
    $parity = '';
    $stopbits = '';
	
	if(($row = $result->fetch_assoc()) !== null){
        $port = $row['port'];
        $baudrate = $row['baudrate'];
        $databits = $row['databits'];
        $parity = $row['parity'];
        $stopbits = $row['stopbits'];
    }
}
?>

<section class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
			<div class="col-sm-6">
				<h1 class="m-0 text-dark">Setup</h1>
			</div>
		</div>
	</div>
</section>

<section class="content" style="min-height:700px;">
	<div class="card">
		<form role="form" id="profileForm" novalidate="novalidate">
			<div class="card-body">
                <div class="row">
                    <div class="col-4">
                        <div class="form-group">
                            <label>Serial Port</label>
                            <select class="form-control" style="width: 100%;" id="serialPort" name="serialPort" required></select>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label>Baud Rate</label>
                            <select class="form-control" style="width: 100%;" id="serialPortBaudRate" name="serialPortBaudRate" required>
                                <option value="110" <?=$baudrate == '110' ? 'selected="selected"' : '';?>>110</option>
                                <option value="300" <?=$baudrate == '300' ? ' selected="selected"' : '';?>>300</option>
                                <option value="600" <?=$baudrate == '600' ? ' selected="selected"' : '';?>>600</option>
                                <option value="1200" <?=$baudrate == '1200' ? ' selected="selected"' : '';?>>1200</option>
                                <option value="2400" <?=$baudrate == '2400' ? ' selected="selected"' : '';?>>2400</option>
                                <option value="4800" <?=$baudrate == '4800' ? ' selected="selected"' : '';?>>4800</option>
                                <option value="9600" <?=$baudrate == '9600' ? ' selected="selected"' : '';?>>9600</option>
                                <option value="14400" <?=$baudrate == '14400' ? ' selected="selected"' : '';?>>14400</option>
                                <option value="19200" <?=$baudrate == '19200' ? ' selected="selected"' : '';?>>19200</option>
                                <option value="38400" <?=$baudrate == '38400' ? ' selected="selected"' : '';?>>38400</option>
                                <option value="57600" <?=$baudrate == '57600' ? ' selected="selected"' : '';?>>57600</option>
                                <option value="115200" <?=$baudrate == '115200' ? ' selected="selected"' : '';?>>115200</option>
                                <option value="128000" <?=$baudrate == '128000' ? ' selected="selected"' : '';?>>128000</option>
                                <option value="256000" <?=$baudrate == '256000' ? ' selected="selected"' : '';?>>256000</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label>Data Bits</label>
                            <select class="form-control" style="width: 100%;" id="serialPortDataBits" name="serialPortDataBits" required>
                                <option value="8" <?=$databits == '8' ? 'selected="selected"' : '';?>>8</option>
                                <option value="7" <?=$databits == '7' ? 'selected="selected"' : '';?>>7</option>
                                <option value="6" <?=$databits == '6' ? 'selected="selected"' : '';?>>6</option>
                                <option value="5" <?=$databits == '5' ? 'selected="selected"' : '';?>>5</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-4">
                        <div class="form-group">
                            <label>Parity</label>
                            <select class="form-control" style="width: 100%;" id="serialPortParity" name="serialPortParity" required>
                                <option value="N" <?=$parity == 'N' ? 'selected="selected"' : '';?>>None</option>
                                <option value="O" <?=$parity == 'O' ? 'selected="selected"' : '';?>>Odd</option>
                                <option value="E" <?=$parity == 'E' ? 'selected="selected"' : '';?>>Even</option>
                                <option value="M" <?=$parity == 'M' ? 'selected="selected"' : '';?>>Mark</option>
                                <option value="S" <?=$parity == 'S' ? 'selected="selected"' : '';?>>Space</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label>Stop bits</label>
                            <select class="form-control" style="width: 100%;" id="serialPortStopBits" name="serialPortStopBits" required>
                                <option value="1" <?=$stopbits == '1' ? 'selected="selected"' : '';?>>1</option>
                                <option value="1.5" <?=$stopbits == '1.5' ? 'selected="selected"' : '';?>>1.5</option>
                                <option value="2" <?=$stopbits == '2' ? 'selected="selected"' : '';?>>2</option>
                            </select>
                        </div>
                    </div>
                </div>
			</div>
			
			<div class="card-footer">
				<button class="btn btn-success" id="saveProfile"><i class="fas fa-save"></i> Save</button>
			</div>
		</form>
	</div>
</section>

<script>
$(function () {
    $.post('http://127.0.0.1:5002/getcomport', function(data){
        var decoded = JSON.parse(data);
        var options = '';

        for (var i = 0; i < decoded.length; i++) {
            options += '<option value="' + decoded[i] + '">' + decoded[i] + '</option>';
        }

        $('#serialPort').html(options);
        $('#serialPort').val(<?=$port ?>);
    });

    $.validator.setDefaults({
        submitHandler: function () {
            $('#spinnerLoading').show();
            $.post('php/updatePort.php', $('#profileForm').serialize(), function(data){
                var obj = JSON.parse(data); 
                
                if(obj.status === 'success'){
                    toastr["success"](obj.message, "Success:");
                    
                    $.get('setup.php', function(data) {
                        $('#mainContents').html(data);
                        $('#spinnerLoading').hide();
                    });
        		}
        		else if(obj.status === 'failed'){
        		    toastr["error"](obj.message, "Failed:");
                    $('#spinnerLoading').hide();
                }
        		else{
        			toastr["error"]("Failed to update ports", "Failed:");
                    $('#spinnerLoading').hide();
        		}
            });
        }
    });
    
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
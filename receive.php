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
				<h1 class="m-0 text-dark">Receive 验收</h1>
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
                                <button type="button" class="btn btn-block bg-gradient-warning btn-sm" id="addReceive">Add Receive 新增验收</button>
                            </div>
                        </div>
                    </div>
					<div class="card-body">
						<table id="receiveTable" class="table table-bordered table-striped">
							<thead>
								<tr>
									<th>No. <br>排号</th>
                                    <th>Box/Tray No <br>桶/托盘代号</th>
									<th>Box/Tray Weight <br>桶/托盘重量(G)</th>
                                    <th>Grading Gross weight <br>分级毛重(G)</th>
                                    <th>Grading Net weight <br>分级净重(G)</th>
                                    <th>Action <br>行动</th>
                                    
								</tr>
							</thead>
						</table>
					</div><!-- /.card-body -->
				</div><!-- /.card -->
			</div><!-- /.col -->
		</div><!-- /.row -->
	</div><!-- /.container-fluid -->
</section><!-- /.content -->

<div class="modal fade" id="receiveModal">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <form role="form" id="receiveForm">
            <div class="modal-header">
              <h4 class="modal-title">Add Receive 新增验收</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="card-body">
                <input type="hidden" class="form-control" id="id" name="id">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                        <label for="itemType">Item Types 货品种类</label>
                            <select class="form-control" style="width: 100%;" id="itemType" name="itemType" required>
                                <option selected="selected">-</option>
                                <option value="T1">T1</option>
                                <option value="T3">T3</option>
                                <option value="T4">T4</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="grossWeight">Grading Gross weight 分级毛重(G)</label>
                            <input type="number" class="form-control" name="grossWeight" id="grossWeight" placeholder="Enter Grading Gross weight" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="lotNo">Lot No 批号</label>
                            <input type="text" class="form-control" name="lotNo" id="lotNo" placeholder="Enter Lot No" >
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="bTrayWeight">Box/Tray Weight 桶/托盘重量(G)</label>
                            <input type="number" class="form-control" name="bTrayWeight" id="bTrayWeight" placeholder="Enter Box/Tray Weight" required>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="bTrayNo">Box/Tray No 桶/托盘代号</label>
                            <input type="text" class="form-control" name="bTrayNo" id="bTrayNo" placeholder="Enter Box/Tray No" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="netWeight">Grading Net weight 分级净重(G)</label>
                            <input type="number" class="form-control" name="netWeight" id="netWeight" placeholder="Enter Grading Net weight" required>
                        </div>
                    </div>
                </div>
              </div>
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-danger" data-dismiss="modal">Close 关闭</button>
              <button type="submit" class="btn btn-primary" name="submit" id="submitLot">Submit 提交</button>
            </div>
        </form>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<script>
$(function () {
    $("#receiveTable").DataTable({
        "responsive": true,
        "autoWidth": false,
        'processing': true,
        'serverSide': true,
        'serverMethod': 'post',
        'order': [[ 1, 'asc' ]],
        'columnDefs': [ { orderable: false, targets: [0] }],
        'ajax': {
            'url':'php/loadReceives.php'
        },
        'columns': [
            { data: 'counter' },
            { data: 'tray_no' },
            { data: 'tray_weight' },
            { data: 'gross_weight' },
            { data: 'net_weight' },
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
            $.post('php/receive.php', $('#receiveForm').serialize(), function(data){
                var obj = JSON.parse(data); 
                
                if(obj.status === 'success'){
                    $('#receiveModal').modal('hide');
                    toastr["success"](obj.message, "Success:");
                    
                    $.get('receive.php', function(data) {
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

    $('#addReceive').on('click', function(){
        $('#receiveModal').find('#id').val("");
        $('#receiveModal').find('#itemType').val('-');
        $('#receiveModal').find('#grossWeight').val("");
        $('#receiveModal').find('#lotNo').val("");
        $('#receiveModal').find('#bTrayWeight').val("");
        $('#receiveModal').find('#bTrayNo').val("");
        $('#receiveModal').find('#netWeight').val("");
        $('#receiveModal').modal('show');
        
        $('#receiveForm').validate({
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
    $.post('php/getReceives.php', {userID: id}, function(data){
        var obj = JSON.parse(data);
        
        if(obj.status === 'success'){
            $('#receiveModal').find('#id').val(obj.message.id);
            $('#receiveModal').find('#itemType').val(obj.message.itemType);
            $('#receiveModal').find('#grossWeight').val(obj.message.grossWeight);
            $('#receiveModal').find('#lotNo').val(obj.message.lotNo);
            $('#receiveModal').find('#bTrayWeight').val(obj.message.bTrayWeight);
            $('#receiveModal').find('#bTrayNo').val(obj.message.bTrayNo);
            $('#receiveModal').find('#netWeight').val(obj.message.netWeight);
            $('#receiveModal').modal('show');
            
            $('#receiveForm').validate({
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
    $.post('php/deleteReceives.php', {userID: id}, function(data){
        var obj = JSON.parse(data);
        
        if(obj.status === 'success'){
            toastr["success"](obj.message, "Success:");
            $.get('receive.php', function(data) {
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
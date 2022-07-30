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

<style>
    @media screen and (min-width: 676px) {
        .modal-dialog {
          max-width: 1600px; /* New width for default modal */
        }
    }

    table{
        width: 100%;
        margin-bottom: 20px;
		border-collapse: collapse;
    }
    table, th, td{
        border: 1px solid #cdcdcd;
    }
    table th, table td{
        padding: 10px;
        text-align: left;
    }
</style>
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
                            <div class="col-6"></div>
                            <div class="col-3">
                                <button type="button" class="btn btn-block bg-gradient-success btn-sm" id="excelSearch"><i class="fas fa-file-excel"></i>Export Excel</button>
                            </div>
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
                                    <th>Lot No <br>批号</th>
                                    <th>Box/Tray No <br>桶/托盘代号</th>
									<th>Box/Tray Weight <br>桶/托盘重量(G)</th>
                                    <th>Receive Gross weight <br>验收毛重(G)</th>
                                    <th>Receive Net weight <br>验收净重(G)</th>
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
                        <div class="col-md-3">
                            <div class="form-group">
                            <label for="itemType">Item Types 货品种类 *</label>
                                <select class="form-control" style="width: 100%;" id="itemType" name="itemType">
                                    <option selected="selected">-</option>
                                    <option value="T1">T1</option>
                                    <option value="T3">T3</option>
                                    <option value="T4">T4</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="lotNo">Lot No 批号 *</label>
                                <input type="text" class="form-control" name="lotNo" id="lotNo" placeholder="Enter Lot No">
                            </div>
                        </div>
                    </div>
                </div>
                <hr>

                <div class="row">
                    <h4>Add Receiving 添加验收</h4>
                    <button style="margin-left:auto;margin-right: 25px;" type="button" class="btn btn-primary add-row">Add New</button>
                </div> 

                <div class="card-body">
                    <input type="hidden" class="form-control" id="id" name="id">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="bTrayNo">Box/Tray No 桶/托盘代号 *</label>
                                <input type="text" class="form-control" name="bTrayNo" id="bTrayNo" placeholder="Enter Box/Tray No" readonly>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="grossWeight">Gross Weight 验收毛重(G) *</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" name="grossWeight" id="grossWeight" placeholder="Enter Receive Gross weight">                                    
                                    <button type="button" class="btn btn-primary" id="grossWeightSyncBtn"><i class="fas fa-sync"></i></button>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="bTrayWeight">Box/Tray Weight 桶/托盘重量(G) *</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" name="bTrayWeight" id="bTrayWeight" placeholder="Enter Box/Tray Weight">
                                    <button type="button" class="btn btn-primary" id="trayWeightSyncBtn"><i class="fas fa-sync"></i></button>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="netWeight">Net weight 验收净重(G)</label>
                                <input type="number" class="form-control" name="netWeight" id="netWeight" placeholder="Enter receive Net weight" readonly>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="moistureValue">Moisture Value 水分值</label>
                                <input type="text" class="form-control" name="moistureValue" id="moistureValue" placeholder="Enter Moisture Value" min="0" max="100">
                            </div>
                        </div>
                    </div>
                </div>

                <table id="TableId">
                    <thead>
                        <tr>
                            <th>Item Types <br>货品种类</th>
                            <th>Lot No <br>批号</th>
                            <th>Box/Tray No <br>桶/托盘代号</th>
                            <th>Box/Tray Weight <br>桶/托盘重量(G)</th>
                            <th>Gross weight <br>分级毛重(G)</th>
                            <th>Net weight <br>分级净重(G)</th>
                            <th>Moisture Value <br>水分值</th>
                            <th>Action <br>行动</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
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

<div class="modal fade" id="editModal">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <form role="form" id="editForm">
            <div class="modal-header">
              <h4 class="modal-title">Edit Receive 修改验收</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <div class="card-body">
                <input type="hidden" class="form-control" id="id" name="id">
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="itemType">Item Types 货品种类 *</label>
                                <input type="text" class="form-control" name="itemType" id="itemType" placeholder="Enter Lot No" readonly>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="form-group">
                                <label for="lotNo">Lot No 批号 *</label>
                                <input type="text" class="form-control" name="lotNo" id="lotNo" placeholder="Enter Lot No" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="bTrayNo">Box/Tray No 桶/托盘代号 *</label>
                                <input type="text" class="form-control" name="bTrayNo" id="bTrayNo" placeholder="Enter Box/Tray No" readonly>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="form-group">
                                <label for="bTrayWeight">Box/Tray Weight 桶/托盘重量(G) *</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" name="bTrayWeight" id="bTrayWeight" placeholder="Enter Box/Tray Weight" required>
                                    <button type="button" class="btn btn-primary" id="trayWeightSyncBtn"><i class="fas fa-sync"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="grossWeight">Gross Weight 验收毛重(G) *</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" name="grossWeight" id="grossWeight" placeholder="Enter Receive Gross weight" required>                                    
                                    <button type="button" class="btn btn-primary" id="grossWeightSyncBtn"><i class="fas fa-sync"></i></button>
                                </div>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="form-group">
                                <label for="netWeight">Net weight 验收净重(G)</label>
                                <input type="number" class="form-control" name="netWeight" id="netWeight" placeholder="Enter receive Net weight" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="moistureValue">Moisture Value 水分值</label>
                                <input type="text" class="form-control" name="moistureValue" id="moistureValue" placeholder="Enter Moisture Value" min="0" max="100" required>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-danger" data-dismiss="modal">Close 关闭</button>
              <button type="submit" class="btn btn-primary" name="submit" id="submitEdit">Submit 提交</button>
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
            { data: 'lot_no' },
            { data: 'tray_no' },
            { data: 'tray_weight' },
            { data: 'gross_weight' },
            { data: 'net_weight' },
            { 
                data: 'id',
                render: function ( data, type, row ) {
                    return '<div class="row"><div class="col-3"><button type="button" id="edit'+data+'" onclick="edit('+data+')" class="btn btn-success btn-sm"><i class="fas fa-pen"></i></button></div><div class="col-3"><button type="button" id="print'+data+'" onclick="print('+data+')" class="btn btn-info btn-sm"><i class="fas fa-print"></i></button></div><div class="col-3"><button type="button" id="deactivate'+data+'" onclick="deactivate('+data+')" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button></div></div>';
                }
            }
        ],
        "rowCallback": function( row, data, index ) {

            $('td', row).css('background-color', '#E6E6FA');
        },        
    });
    
    $.validator.setDefaults({
        submitHandler: function () {
            if($('#receiveModal').hasClass('show')){
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
            else if($('#editModal').hasClass('show')){
                $('#spinnerLoading').show();
                $.post('php/editReceive.php', $('#editForm').serialize(), function(data){
                    var obj = JSON.parse(data); 
                    
                    if(obj.status === 'success'){
                        $('#editModal').modal('hide');
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
        }
    });

    $(".add-row").click(function(){
        var size = $("#TableId").find("tr").length - 1;
        var itemType;
        var lotNo;
        var bTrayNo;
        var grossWeight;
        var bTrayWeight;
        var netWeight;
        var moistureValue;

        if($("#itemType").val() == "T1" || $("#itemType").val() == "T3"){
            if($("#grossWeight").val() != "" && $("#netWeight").val() != "" && $("#moistureValue").val() != ""){
                itemType = $("#itemType").val();
                lotNo = $("#lotNo").val();
                bTrayNo = $("#bTrayNo").val();
                grossWeight = $("#grossWeight").val();
                bTrayWeight = $("#bTrayWeight").val();
                netWeight = $("#netWeight").val();
                moistureValue = $("#moistureValue").val();

                var markup = "<tr><td><input type='hidden' name='itemType["+size+"]' value='"+itemType+"' />" +
                itemType + "</td><td><input type='hidden' name='lotNo["+size+"]' value='"+lotNo+"' />" + 
                lotNo + "</td><td><input type='hidden' name='bTrayNo["+size+"]' value='"+bTrayNo+"' />" + 
                bTrayNo + "</td><td><input type='hidden' name='grossWeight["+size+"]' value='"+grossWeight+"' />" + 
                grossWeight + "</td><td><input type='hidden' name='bTrayWeight["+size+"]' value='"+bTrayWeight+"' />" + 
                bTrayWeight + "</td><td><input type='hidden' name='netWeight["+size+"]' value='"+netWeight+"' />" + 
                netWeight + "</td><td><input type='hidden' name='moistureValue["+size+"]' value='"+moistureValue+"' />" + 
                moistureValue + "</td><td><button type='button' class='btn btn-danger' name=delete"+ size +">delete</button></td></tr>";
                
                $("#TableId tbody").append(markup);

                // Reset to empty again
                $("#itemType").val(itemType);
                $("#lotNo").val(lotNo);
                $("#bTrayNo").val(parseInt($('#lotNo').val() + "00") + (size+2).toString());
                $("#grossWeight").val("");
                $("#bTrayWeight").val("");
                $("#netWeight").val("");
                $("#moistureValue").val("");
            }else{
                alert("Please Fill in all the required field!");
            }

        }
        else{
            if($("#grossWeight").val() != "" && $("#netWeight").val() != "" && $("#moistureValue").val() != "" && $("#bTrayWeight").val() != "" && $("#bTrayNo").val() != ""){
                itemType = $("#itemType").val();
                lotNo = $("#lotNo").val();
                bTrayNo = $("#bTrayNo").val();
                grossWeight = $("#grossWeight").val();
                bTrayWeight = $("#bTrayWeight").val();
                netWeight = $("#netWeight").val();
                moistureValue = $("#moistureValue").val();

                var markup = "<tr><td><input type='hidden' name='itemType["+size+"]' value='"+itemType+"' />" +
                itemType + "</td><td><input type='hidden' name='lotNo["+size+"]' value='"+lotNo+"' />" + 
                lotNo + "</td><td><input type='hidden' name='bTrayNo["+size+"]' value='"+bTrayNo+"' />" + 
                bTrayNo + "</td><td><input type='hidden' name='grossWeight["+size+"]' value='"+grossWeight+"' />" + 
                grossWeight + "</td><td><input type='hidden' name='bTrayWeight["+size+"]' value='"+bTrayWeight+"' />" + 
                bTrayWeight + "</td><td><input type='hidden' name='netWeight["+size+"]' value='"+netWeight+"' />" + 
                netWeight + "</td><td><input type='hidden' name='moistureValue["+size+"]' value='"+moistureValue+"' />" + 
                moistureValue + "</td><td><button type='button' class='btn btn-danger' name=delete"+ size +">delete</button></td></tr>";
                
                $("#TableId tbody").append(markup);

                // Reset to empty again
                $("#itemType").val(itemType);
                $("#lotNo").val(lotNo);
                $("#bTrayNo").val(parseInt($('#lotNo').val() + "00") + (size+2).toString());
                $("#grossWeight").val("");
                $("#bTrayWeight").val("");
                $("#netWeight").val("");
                $("#moistureValue").val("");
            }
            else{
                alert("Please Fill in all the required field!");
            }
        }
    });

    // Find and remove selected table rows
    $("#TableId tbody").on('click', 'button[name^="delete"]', function () {
        $(this).parents("tr").remove();
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

    $('#grossWeight').on('change', function(){
        var grossWeight = $(this).val();
        var bTrayNo = 0;

        if($('#bTrayWeight').val()){
            bTrayNo = $('#bTrayWeight').val();
            var netweight = grossWeight - bTrayNo;
            $('#netWeight').val(netweight.toFixed(2));

        }
        else{
            $('#netWeight').val(grossWeight.toFixed(2));
        }
    });

    $('#bTrayWeight').on('change', function(){
        var grossWeight = 0;
        var bTrayNo = $(this).val();

        if($('#grossWeight').val()){
            grossWeight = $('#grossWeight').val();
            var netweight = grossWeight - bTrayNo;
            $('#netWeight').val(netweight.toFixed(2));

        }
        else{
            $('#netWeight').val((0).toFixed(2));
        }
    });

    $('#itemType').on('change', function(){
        var itemType = $(this).val();

        if(itemType == 'T3' || itemType == 'T1'){
            $("#bTrayWeight").removeAttr("required");
            $("#bTrayNo").removeAttr("required");
        }
        else{
            $("#bTrayWeight").attr("required","required");
            $("#bTrayNo").attr("required","required");
        }
    });

    $('#lotNo').on('change', function(){
        if($("#bTrayNo").val() == null || $("#bTrayNo").val() == ""){
            var size = $("#TableId").find("tr").length;
            $("#bTrayNo").val(parseInt($('#lotNo').val() + "00") + (size).toString());
        }
    });
});

function edit(id){
    $('#spinnerLoading').show();
    $.post('php/getReceives.php', {userID: id}, function(data){
        var obj = JSON.parse(data);
        
        if(obj.status === 'success'){
            $('#editModal').find('#id').val(obj.message.id);
            $('#editModal').find('#itemType').val(obj.message.itemType);
            $('#editModal').find('#grossWeight').val(obj.message.grossWeight);
            $('#editModal').find('#lotNo').val(obj.message.lotNo);
            $('#editModal').find('#bTrayWeight').val(obj.message.bTrayWeight);
            $('#editModal').find('#bTrayNo').val(obj.message.bTrayNo);
            $('#editModal').find('#netWeight').val(obj.message.netWeight);
            $('#editModal').find('#moistureValue').val(obj.message.moistureAfterReceiving);
            $('#editModal').modal('show');
            
            $('#editForm').validate({
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

function print(id){
    $.post('php/printReceive.php', {userID: id}, function(data){
        var obj = JSON.parse(data);

        if(obj.status === 'success'){
            var printWindow = window.open('', '', 'height=400,width=800');
            printWindow.document.write(obj.message);
            printWindow.document.close();
            setTimeout(function(){
                printWindow.print();
                printWindow.close();
            }, 500);
        }
        else if(obj.status === 'failed'){
            toastr["error"](obj.message, "Failed:");
        }
        else{
            toastr["error"]("Something wrong when activate", "Failed:");
        }
    });
}

function deactivate(id){
    alert(id);
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
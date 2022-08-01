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
				<h1 class="m-0 text-dark">Moisturise/Drying 风干/加湿</h1>
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
                            <!--div class="col-3">
                                <button type="button" class="btn btn-block bg-gradient-success btn-sm" id="excelSearch"><i class="fas fa-file-excel"></i>Export Excel</button>
                            </div-->
                            <div class="col-3">
                                <button type="button" class="btn btn-block bg-gradient-info btn-sm" id="scanMoistures">Scan 扫描</button>
                            </div>
                            <div class="col-3">
                                <button type="button" class="btn btn-block bg-gradient-warning btn-sm" id="addMoistures">Add Moisturise/Drying 新增风干/加湿</button>
                            </div>
                        </div>
                    </div>
					<div class="card-body">
						<table id="moistureTable" class="table table-bordered table-striped">
							<thead>
								<tr>
									<th>No. <br>排号</th>
                                    <th>Lot No <br>批号</th>
									<th>Grade <br>等级</th>
                                    <th>Box/Tray No <br>桶/托盘代号</th>
                                    <th>Box/Tray Weight <br>桶/托盘重量(G)</th>
                                    <th>Grading Gross Weight <br>分级毛重(G)</th>
                                    <th>Qty <br>片数(PCS)</th>
                                    <th>Grading Net Weight <br>分级净重(G)</th>
                                    <th>Moisture after moisturing <br>分级后湿度(%)</th> 
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
<input type="text" id="barcodeScan">

<div class="modal fade" id="moistureModal">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <form role="form" id="moistureForm">
            <div class="modal-header">
              <h4 class="modal-title">Add Moisture 新增品规</h4>
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
                                <input type="text" class="form-control" name="moisturiseItemType" id="moisturiseItemType" placeholder="Enter item type" readonly>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="bTrayWeight">Box/Tray Weight 桶/托盘重量(G)</label>
                                <input type="number" class="form-control" name="moisturiseTrayWeight" id="moisturiseTrayWeight" placeholder="Enter Box/Tray Weight" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="lotNo">Lot No 批号</label>
                                <input type="text" class="form-control" name="moisturiselotNo" id="moisturiselotNo" placeholder="Enter Lot No" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="grossWeight">Moisturise/Drying Gross weight 加湿/风干后毛重(G) *</label>
                                <input type="number" class="form-control" name="moisturiseGrossWeight" id="moisturiseGrossWeight" placeholder="Enter Grading Gross weight" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="bTrayNo">Box/Tray No 桶/托盘代号</label>
                                <input type="text" class="form-control" name="moisturiseTrayNo" id="moisturiseTrayNo" placeholder="Enter Box/Tray No" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="netWeight">Moisturise/Drying Net weight 加湿/风干后净重(G) *</label>
                                <input type="number" class="form-control" name="moisturiseNetWeight" id="moisturiseNetWeight" placeholder="Enter Grading Net weight" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="qty">Qty 片数 (pcs) *</label>
                                <input type="number" class="form-control" name="moisturiseQty" id="moisturiseQty" placeholder="Enter Moisturise Qty" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="netWeight">Stock Out Moisture 出库湿度(%) *</label>
                                <input type="number" class="form-control" name="stockOutMoisture" id="stockOutMoisture" placeholder="Enter Stock Out Moisture" required>
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
    $("#moistureTable").DataTable({
        "responsive": true,
        "autoWidth": false,
        'processing': true,
        'serverSide': true,
        'serverMethod': 'post',
        'order': [[ 1, 'asc' ]],
        'columnDefs': [ { orderable: false, targets: [0] }],
        'ajax': {
            'url':'php/loadMoistures.php'
        },
        'columns': [
            { data: 'counter' },
            { data: 'lot_no' },
            { data: 'grade' },
            { data: 'bTrayNo' },
            { data: 'bTrayWeight' },
            { data: 'moisture_gross_weight' },
            { data: 'pieces' },
            { data: 'moisture_net_weight' },
            { data: 'moisture_after_moisturing' },
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
            $('#spinnerLoading').show();
            $.post('php/moisture.php', $('#moistureForm').serialize(), function(data){
                var obj = JSON.parse(data); 
                
                if(obj.status === 'success'){
                    $('#moistureModal').modal('hide');
                    toastr["success"](obj.message, "Success:");
                    
                    $.get('wMoisturise.php', function(data) {
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

    $('#scanMoistures').on('click', function(){
        $('#barcodeScan').trigger('focus');
    });

    $('#barcodeScan').on('change', function(){
        $('#spinnerLoading').show();
        var url = this.val();
        this.val('');

        $.get(url, function(data){
            var obj = JSON.parse(data);
            
            if(obj.status === 'success'){
                $('#moistureModal').find('#id').val(obj.message.id);
                $('#moistureModal').find('#moisturiseItemType').val(obj.message.itemTypes);
                $('#moistureModal').find('#moisturiseTrayWeight').val(obj.message.trayWeight);
                $('#moistureModal').find('#moisturiselotNo').val(obj.message.lotNo);
                $('#moistureModal').find('#moisturiseGrossWeight').val(obj.message.moistureGrossWeight);
                $('#moistureModal').find('#moisturiseTrayNo').val(obj.message.bTrayNo);
                $('#moistureModal').find('#moisturiseNetWeight').val(obj.message.moistureNetWeight);
                $('#moistureModal').find('#moisturiseQty').val(obj.message.pieces);
                $('#moistureModal').find('#stockOutMoisture').val(obj.message.moistureAfterMoisturing);
                $('#moistureModal').modal('show');
            }
            else if(obj.status === 'failed'){
                toastr["error"](obj.message, "Failed:");
            }
            else{
                toastr["error"]("Something wrong when activate", "Failed:");
            }
            $('#spinnerLoading').hide();
        });
    });

    $('#addMoistures').on('click', function(){
        $('#moistureModal').find('#id').val("");
        $('#moistureModal').find('#moisturiseItemType').val("");
        $('#moistureModal').find('#moisturiseGrossWeight').val("");
        $('#moistureModal').find('#moisturiselotNo').val("");
        $('#moistureModal').find('#moisturiseTrayWeight').val("");
        $('#moistureModal').find('#moisturiseTrayNo').val("");
        $('#moistureModal').find('#moisturiseNetWeight').val("");
        $('#moistureModal').find('#moisturiseQty').val("");
        $('#moistureModal').find('#stockOutMoisture').val("");
        $('#moistureModal').modal('show');
        
        $('#moistureForm').validate({
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

    $('#moisturiselotNo').on('change', function(){
        if($(this).val() && $('#moisturiseTrayNo').val()){
            $('#spinnerLoading').show();
            var lotNo = $(this).val();
            var bTrayNo = $('#moisturiseTrayNo').val();

            $.post('php/getGradingInfo.php', {lotNum: lotNo, trayNo: bTrayNo}, function(data){
                var obj = JSON.parse(data);
                
                if(obj.status === 'success'){
                    $('#moistureModal').find('#id').val(obj.message.id);
                    $('#moistureModal').find('#moisturiseItemType').val(obj.message.itemType);
                    $('#moistureModal').find('#moisturiseTrayWeight').val(obj.message.tray_weight);
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
    });
    
    $('#moisturiseTrayNo').on('change', function(){
        if($(this).val() && $('#moisturiselotNo').val()){
            $('#spinnerLoading').show();
            var lotNo = $('#moisturiselotNo').val();
            var bTrayNo = $(this).val();

            $.post('php/getGradingInfo.php', {lotNum: lotNo, trayNo: bTrayNo}, function(data){
                var obj = JSON.parse(data);
                
                if(obj.status === 'success'){
                    $('#moistureModal').find('#id').val(obj.message.id);
                    $('#moistureModal').find('#moisturiseItemType').val(obj.message.itemType);
                    $('#moistureModal').find('#moisturiseTrayWeight').val(obj.message.tray_weight);
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
    });

    $('#moisturiseGrossWeight').on('change', function(){
        var grossWeight = $(this).val();
        var bTrayNo = 0;

        if($('#moisturiseTrayWeight').val()){
            bTrayNo = $('#moisturiseTrayWeight').val();
            var netweight = grossWeight - bTrayNo;
            $('#moisturiseNetWeight').val(netweight.toFixed(2));

        }
        else{
            $('#moisturiseNetWeight').val(grossWeight.toFixed(2));
        }
    });

    $('#moisturiseTrayWeight').on('change', function(){
        var grossWeight = 0;
        var bTrayNo = $(this).val();

        if($('#moisturiseGrossWeight').val()){
            grossWeight = $('#moisturiseGrossWeight').val();
            var netweight = grossWeight - bTrayNo;
            $('#moisturiseNetWeight').val(netweight.toFixed(2));

        }
        else{
            $('#moisturiseNetWeight').val((0).toFixed(2));
        }
    });
});

function edit(id){
    $('#spinnerLoading').show();
    $.post('php/getMoisture.php', {userID: id}, function(data){
        var obj = JSON.parse(data);
        
        if(obj.status === 'success'){
            $('#moistureModal').find('#id').val(obj.message.id);
            $('#moistureModal').find('#moisturiseItemType').val(obj.message.itemType);
            $('#moistureModal').find('#moisturiseGrossWeight').val(obj.message.moisture_gross_weight);
            $('#moistureModal').find('#moisturiselotNo').val(obj.message.lotNo);
            $('#moistureModal').find('#moisturiseTrayWeight').val(obj.message.bTrayWeight);
            $('#moistureModal').find('#moisturiseTrayNo').val(obj.message.bTrayNo);
            $('#moistureModal').find('#moisturiseNetWeight').val(obj.message.moisture_net_weight);
            $('#moistureModal').find('#moisturiseQty').val(obj.message.pieces);
            $('#moistureModal').find('#stockOutMoisture').val(obj.message.moisture_after_moisturing);
            $('#moistureModal').modal('show');
            
            $('#moistureForm').validate({
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
    $.post('php/printMosturing.php', {userID: id}, function(data){
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
    $('#spinnerLoading').show();
    $.post('php/deleteReceives.php', {userID: id}, function(data){
        var obj = JSON.parse(data);
        
        if(obj.status === 'success'){
            toastr["success"](obj.message, "Success:");
            $.get('wMoisturise.php', function(data) {
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
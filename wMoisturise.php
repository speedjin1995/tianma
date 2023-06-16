<?php
require_once 'php/db_connect.php';

session_start();

if(!isset($_SESSION['userID'])){
  echo '<script type="text/javascript">';
  echo 'window.location.href = "login.html";</script>';
}
else{
  $user = $_SESSION['userID'];
  $stmt = $db->prepare("SELECT * from users where id = ?");
  $stmt->bind_param('s', $user);
  $stmt->execute();
  $result = $stmt->get_result();
  $role = 'NORMAL';

  
  if(($row = $result->fetch_assoc()) !== null){
   $role = $row['role_code'];
  }
}
?>

<style>
    @media screen and (min-width: 676px) {
        #gradesModal .modal-dialog {
          max-width: 1600px; /* New width for default modal */
        }
    }

    .mt-32{
        margin-top:32px;
    } 

    .bootstrap-datetimepicker-widget table th:hover {
        color: black;
    }
    .bootstrap-datetimepicker-widget table td.disabled, .bootstrap-datetimepicker-widget table td.disabled:hover {
        background-color: #d0d0d0;
    }
</style>
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

                            <div class="form-group col-md-2">
                                <div class="form-group">
                                <label for="itemTypeFilter">Item Types 货品种类</label>
                                    <select class="form-control" style="width: 100%;" id="itemTypeFilter" name="itemTypeFilter">
                                        <option selected="selected">-</option>
                                        <option value="T1">T1</option>
                                        <option value="T3">T3</option>
                                        <option value="T4">T4</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group col-md-2">
                                <div class="form-group">
                                    <label for="lotNo">Lot No 批号</label>
                                    <input type="text" class="form-control" name="lotNo" id="lotNo" placeholder="Enter Lot No">
                                </div>
                            </div>

                            <div class="form-group col-md-2">
                                <button class="btn btn-success" id="filterSearch"><i class="fas fa-search"></i> Filter 筛选</button> 
                            </div>                                            
                        </div>                         
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
                                    <th>Updated Datetime <br>更新时间</th>
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
                                <div class="input-group">
                                    <input type="number" class="form-control" name="moisturiseTrayWeight" id="moisturiseTrayWeight" placeholder="Enter Box/Tray Weight" readonly>
                                    <button type="button" class="btn btn-primary" id="trayWeightSyncBtn"><i class="fas fa-sync"></i></button>
                                </div>
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
                                <div class="input-group">
                                    <input type="number" class="form-control" name="moisturiseGrossWeight" id="moisturiseGrossWeight" placeholder="Enter Grading Gross weight" required>
                                    <button type="button" class="btn btn-primary" id="grossWeightSyncBtn"><i class="fas fa-sync"></i></button>                      
                                </div>
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
                                <label for="qty">Qty 片数 (pcs)</label>
                                <input type="number" class="form-control" name="moisturiseQty" id="moisturiseQty" placeholder="Enter Moisturise Qty">
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

    //Date picker
    var oneWeek = new Date();
    oneWeek.setHours(0,0,0,0);
    var oneWeek2 = new Date();
    oneWeek2.setHours(23,59,59,999);
    <?php 
            if($role  == "NORMAL"){
               echo "oneWeek.setDate(oneWeek.getDate() - 7);";
               

               echo "
               $('#fromDatePicker').datetimepicker({
                    icons: { time: 'far fa-clock' },
                    format: 'DD/MM/YYYY HH:mm:ss A',
                    defaultDate: oneWeek
                });";
        

                echo "
                $('#toDatePicker').datetimepicker({
                    icons: { time: 'far fa-clock' },
                    format: 'DD/MM/YYYY HH:mm:ss A',
                    defaultDate : oneWeek2
                });";
            }else{

                echo "$('#fromDatePicker').datetimepicker({
                    icons: { time: 'far fa-clock' },
                    format: 'DD/MM/YYYY HH:mm:ss A',
                    defaultDate: oneWeek
                });";
            
                echo "$('#toDatePicker').datetimepicker({
                    icons: { time: 'far fa-clock' },
                    format: 'DD/MM/YYYY HH:mm:ss A',
                    defaultDate: oneWeek2
                });";

            }
    ?>

    $("#moistureTable").DataTable({
        "responsive": true,
        "autoWidth": false,
        'processing': true,
        'serverSide': true,
        'searching': false,
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
            { data: 'updated_datetime' },
            { 
                data: 'id',
                width: '140px',
                render: function ( data, type, row ) {
                    return '<div class="row px-0"><div class="col-3 mr-1"><button type="button" id="edit'+data+'" onclick="edit('+data+')" class="btn btn-success btn-sm"><i class="fas fa-pen"></i></button></div><div class="col-3 mr-1"><button type="button" id="print'+data+'" onclick="print('+data+')" class="btn btn-info btn-sm"><i class="fas fa-print"></i></button></div><div class="col-3"><button type="button" id="deactivate'+data+'" onclick="deactivate('+data+')" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button></div></div>';
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

                    var printWindow = window.open('', '', 'height=400,width=800');
                    printWindow.document.write(obj.label);
                    printWindow.document.close();
                    setTimeout(function(){
                        printWindow.print();
                        printWindow.close();
                    }, 1000);
                    
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
        var url = $(this).val();
        $(this).val('');

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
                    $('#moistureModal').find('#moisturiseQty').val(obj.message.pieces);
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
                    $('#moistureModal').find('#moisturiseQty').val(obj.message.pieces);
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

    $('#filterSearch').on('click', function(){
        $('#spinnerLoading').show();

        var fromDateValue = '';
        var toDateValue = '';

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

        var itemTypeFilter = $('#itemTypeFilter').val() ? $('#itemTypeFilter').val() : '';
        var lotNo = $('#lotNo').val() ? $('#lotNo').val() : '';

        //Destroy the old Datatable
        $("#moistureTable").DataTable().clear().destroy();

        //Create new Datatable
        table = $("#moistureTable").DataTable({
            "responsive": true,
            "autoWidth": false,
            'processing': true,
            'serverSide': true,
            'serverMethod': 'post',
            'searching': false,
            'order': [[ 1, 'asc' ]],
            'columnDefs': [ { orderable: false, targets: [0] }],
            'ajax': {
                'type': 'POST',
                'url':'php/filterWMoisture.php',
                'data': {
                    fromDate: fromDateValue,
                    toDate: toDateValue,
                    itemTypeFilter: itemTypeFilter,
                    lotNo: lotNo
                } 
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
                { data: 'updated_datetime' },
                { 
                    data: 'id',
                    width: '140px',
                    render: function ( data, type, row ) {
                        return '<div class="row px-0"><div class="col-3 mr-1"><button type="button" id="edit'+data+'" onclick="edit('+data+')" class="btn btn-success btn-sm"><i class="fas fa-pen"></i></button></div><div class="col-3 mr-1"><button type="button" id="print'+data+'" onclick="print('+data+')" class="btn btn-info btn-sm"><i class="fas fa-print"></i></button></div><div class="col-3"><button type="button" id="deactivate'+data+'" onclick="deactivate('+data+')" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button></div></div>';
                    }
                }
                ],
                "rowCallback": function( row, data, index ) {
                    $('td', row).css('background-color', '#E6E6FA');
                }

        });

        $('#spinnerLoading').hide();
    });

    $('#grossWeightSyncBtn').on('click', function(){
        $.post('http://127.0.0.1:5002/handshaking', function(data){
            if(data != "Error"){
                console.log("Data Received:" + data);
                var temp = data.replace('S', '').replace('D', '').replace('+', '').replace('-', '').replace('g', '').replace('G', '').trim();
                var str = temp.split(".");
                var arr=[];
                
                for(var i=0; i<str[0].length; i++){
                    if(str[0].charAt(i).match(re3)){
                        arr.push(str[0][i]);
                    }
                }
                
                var text = arr.join("") + "." + str[1];
                $('#moistureModal').find('#moisturiseGrossWeight').val(parseFloat(text).toFixed(2));
                $('#moisturiseGrossWeight').trigger('change');
            }
            else{
                toastr["error"]("Failed to get the reading!", "Failed:");
            }
        });
    });

    $('#trayWeightSyncBtn').on('click', function(){
        $.post('http://127.0.0.1:5002/handshaking', function(data){
            if(data != "Error"){
                console.log("Data Received:" + data);
                var temp = data.replace('S', '').replace('D', '').replace('+', '').replace('-', '').replace('g', '').replace('G', '').trim();
                var str = temp.split(".");
                var arr=[];
                
                for(var i=0; i<str[0].length; i++){
                    if(str[0].charAt(i).match(re3)){
                        arr.push(str[0][i]);
                    }
                }
                
                var text = arr.join("") + "." + str[1];
                $('#moistureModal').find('#moisturiseTrayWeight').val(parseFloat(text).toFixed(2));
                $('#moisturiseTrayWeight').trigger('change');
            }
            else{
                toastr["error"]("Failed to get the reading!", "Failed:");
            }
        });
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
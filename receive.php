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
        .modal-dialog {
          max-width: 1600px; /* New width for default modal */
        }
    }

    #TableId{
        width: 100%;
        margin-bottom: 20px;
		border-collapse: collapse;
    }
    #TableId th, #TableId td{
        border: 1px solid #cdcdcd;
    }
    #TableId th, #TableId td{
        padding: 10px;
        text-align: left;
    }
    .bootstrap-datetimepicker-widget.dropdown-menu.wider  {
        width: auto;
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
                            <!--div class="col-3">
                                <button type="button" class="btn btn-block bg-gradient-success btn-sm" id="excelSearch"><i class="fas fa-file-excel"></i>Export Excel</button>
                            </div-->
                            <div class="col-3">
                                <button type="button" class="btn btn-block bg-gradient-info btn-sm" id="scanReceives">Scan 扫描</button>
                            </div>
                            <div class="col-3">
                                <button type="button" class="btn btn-block bg-gradient-warning btn-sm" id="addReceive">Add Receive 新增验收</button>
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

                            <div class="form-group col-md-3">
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
                            <div class="form-group col-md-3 mt-32">
                                <button class="btn btn-success" id="filterSearch"><i class="fas fa-search"></i>Filter 筛选</button> 
                            </div>                                            
                        </div>            
						<table id="receiveTable" class="table table-bordered table-striped">
							<thead>
								<tr>
									<th>No. <br>排号</th>
                                    <th>Item Type <br>货品种类</th>
                                    <th>Lot No <br>批号</th>
                                    <th>Box/Tray No <br>桶/托盘代号</th>
									<th>Box/Tray Weight <br>桶/托盘重量(G)</th>
                                    <th>Receive Gross weight <br>验收毛重(G)</th>
                                    <th>Receive Net weight <br>验收净重(G)</th>
                                    <th>Moisture Value <br>水分值</th>
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
                                <label for="bTrayWeight">Box/Tray Weight 桶/托盘重量(G) *</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" name="bTrayWeight" id="bTrayWeight" placeholder="Enter Box/Tray Weight">
                                    <button type="button" class="btn btn-primary" id="trayWeightSyncBtn"><i class="fas fa-sync"></i></button>
                                </div>
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
                            <th style="width:130px">Item Types <br>货品种类</th>
                            <th style="width:130px">Lot No <br>批号</th>
                            <th style="width:130px">Box/Tray No <br>桶/托盘代号</th>
                            <th style="width:130px">Gross weight <br>分级毛重(G)</th>
                            <th style="width:130px">Box/Tray Weight <br>桶/托盘重量(G)</th>
                            <th style="width:130px">Net weight <br>分级净重(G)</th>
                            <th style="width:130px">Moisture Value <br>水分值</th>
                            <th style="width:130px">Action <br>行动</th>
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
                                    <button type="button" class="btn btn-primary" id="editTrayWeightBtn"><i class="fas fa-sync"></i></button>
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
                                    <button type="button" class="btn btn-primary" id="editGrossWeightBtn"><i class="fas fa-sync"></i></button>
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
                                <input type="number" class="form-control" name="moistureValue" id="moistureValue" placeholder="Enter Moisture Value" min="0" max="100" required>
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
var re3= /[0-9]/;

$(function () {

    //Date picker
    var oneWeek = new Date();
    <?php 
            if($role  == "NORMAL"){
               echo "oneWeek.setDate(oneWeek.getDate() - 7);";
               

               echo "
               $('#fromDatePicker').datetimepicker({
                    icons: { time: 'far fa-clock' },
                    format: 'DD/MM/YYYY HH:mm:ss A',
                    minDate: oneWeek,
                    maxDate: new Date,
                    defaultDate: oneWeek
                });";
        

                echo "
                $('#toDatePicker').datetimepicker({
                    icons: { time: 'far fa-clock' },
                    format: 'DD/MM/YYYY HH:mm:ss A',
                    minDate: oneWeek,
                    maxDate: new Date,
                    defaultDate : new Date
                });";
            }else{

                echo "$('#fromDatePicker').datetimepicker({
                    icons: { time: 'far fa-clock' },
                    format: 'DD/MM/YYYY HH:mm:ss A',
                    defaultDate: new Date
                });";
            
                echo "$('#toDatePicker').datetimepicker({
                    icons: { time: 'far fa-clock' },
                    format: 'DD/MM/YYYY HH:mm:ss A',
                    defaultDate: new Date
                });";

            }
    ?>


    $("#receiveTable").DataTable({
        "responsive": true,
        "autoWidth": false,
        'processing': true,
        'serverSide': true,
        'serverMethod': 'post',
        'order': [[ 2, 'asc' ]],
        'columnDefs': [ { orderable: false, targets: [0] }],
        'ajax': {
            'url':'php/loadReceives.php'
        },
        'columns': [
            { data: 'counter' },
            { data: 'item_types' },
            { data: 'lot_no' },
            { data: 'tray_no' },
            { data: 'tray_weight' },
            { data: 'gross_weight' },
            { data: 'net_weight' },
            { data: 'moisture_after_receiving' },
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

                        var printWindow = window.open('', '', 'height=400,width=800');
                        printWindow.document.write(obj.label);
                        printWindow.document.close();
                        setTimeout(function(){
                            printWindow.print();
                            printWindow.close();
                        }, 1000);
                        
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

        // if($("#itemType").val() == "T1" || $("#itemType").val() == "T3"){
        //     if($("#grossWeight").val() != "" && $("#netWeight").val() != "" && $("#moistureValue").val() != ""){
        //         itemType = $("#itemType").val();
        //         lotNo = $("#lotNo").val();
        //         bTrayNo = $("#bTrayNo").val();
        //         grossWeight = $("#grossWeight").val();
        //         bTrayWeight = $("#bTrayWeight").val();
        //         netWeight = $("#netWeight").val();
        //         moistureValue = $("#moistureValue").val();

        //         var markup = "<tr><td><input type='hidden' name='itemType["+size+"]' value='"+itemType+"' />" +
        //         itemType + "</td><td><input type='hidden' name='lotNo["+size+"]' value='"+lotNo+"' />" + 
        //         lotNo + "</td><td><input type='hidden' name='bTrayNo["+size+"]' value='"+bTrayNo+"' />" + 
        //         bTrayNo + "</td><td><input type='hidden' name='grossWeight["+size+"]' value='"+grossWeight+"' />" + 
        //         grossWeight + "</td><td><input type='hidden' name='bTrayWeight["+size+"]' value='"+bTrayWeight+"' />" + 
        //         bTrayWeight + "</td><td><input type='hidden' name='netWeight["+size+"]' value='"+netWeight+"' />" + 
        //         netWeight + "</td><td><input type='hidden' name='moistureValue["+size+"]' value='"+moistureValue+"' />" + 
        //         moistureValue + "</td><td><button type='button' class='btn btn-danger' name=delete"+ size +">delete</button></td></tr>";
                
        //         $("#TableId tbody").append(markup);

        //         // Reset to empty again
        //         $("#itemType").val(itemType);
        //         $("#lotNo").val(lotNo);
        //         $("#bTrayNo").val($('#lotNo').val() + padLeadingZeros((size+2).toString(), 3));
        //         $("#grossWeight").val("");
        //         $("#bTrayWeight").val("");
        //         $("#netWeight").val("");
        //         $("#moistureValue").val("");
        //     }else{
        //         alert("Please Fill in all the required field!");
        //     }

        // }
        // else{
        //     if($("#grossWeight").val() != "" && $("#netWeight").val() != "" && $("#moistureValue").val() != "" && $("#bTrayWeight").val() != "" && $("#bTrayNo").val() != ""){
                itemType = $("#itemType").val();
                lotNo = $("#lotNo").val();
                bTrayNo = $("#bTrayNo").val();
                grossWeight = $("#grossWeight").val();
                bTrayWeight = $("#bTrayWeight").val();
                netWeight = $("#netWeight").val();
                moistureValue = $("#moistureValue").val();

                var markup = "<tr><td><select class='col-12 form-control' id='itemType' name='itemType["+size+"]' >"
                + "<option selected='selected'>-</option><option value='T1'>T1</option><option value='T3'>T3</option><option value='T4'>T4</option></select>" +
                '' + "</td><td><input class='col-12 form-control' type='text' name='lotNo["+size+"]' value='"+lotNo+"' />" + 
                '' + "</td><td><input class='col-12 form-control' type='text' name='bTrayNo["+size+"]' value='"+bTrayNo+"' />" + 
                '' + "</td><td><input class='col-12 form-control' type='number' id='"+size+"' name='grossWeight["+size+"]' value='"+grossWeight+"' />" + 
                '' + "</td><td><input class='col-12 form-control' type='number' id='"+size+"' name='bTrayWeight["+size+"]' value='"+bTrayWeight+"' />" + 
                '' + "</td><td><input class='col-12 form-control' type='number' id='"+size+"' name='netWeight["+size+"]' value='"+netWeight+"' />" + 
                '' + "</td><td><span class='form-group'><input class='col-12 form-control' type='number' name='moistureValue["+size+"]' value='"+moistureValue+"' min='0' max='100' /></span>" + 
                '' + "</td><td style='justify-content:center;display:flex;'><button type='button' class='btn btn-danger' name=delete"+ size +">delete</button></td></tr>";

                $("#TableId tbody").append(markup);
                $('[name="itemType['+size+']"]').val(itemType);

                // Reset to empty again
                $("#itemType").val(itemType);
                $("#lotNo").val(lotNo);
                $("#bTrayNo").val($('#lotNo').val() + padLeadingZeros((size+2).toString(), 3));
                $("#grossWeight").val("");
                $("#bTrayWeight").val("");
                $("#netWeight").val("");
                $("#moistureValue").val("");

                $('[name^="bTrayWeight["]').on('change', function(){
                    debugger;
                    var id = $(this).attr('id');
                    var trayW = $('[name="bTrayWeight['+id+']"]').val();
                    var grossW = $('[name="grossWeight['+id+']"]').val();
                    var netW;
                    if(typeof trayW !== 'undefined' && trayW !== null){
                        netW = grossW - trayW;
                        $('[name="netWeight['+id+']"]').val(netW.toFixed(2));
                    }
                });

                $('[name^="grossWeight["]').on('change', function(){
                    debugger;
                    var id = $(this).attr('id');
                    var trayW = $('[name="bTrayWeight['+id+']"]').val();
                    var grossW = $('[name="grossWeight['+id+']"]').val();
                    var netW;
                    if(typeof grossW !== 'undefined' && grossW !== null){
                        netW = grossW - trayW;
                        $('[name="netWeight['+id+']"]').val(netW.toFixed(2));
                    }
                });                
            // }
            // else{
            //     alert("Please Fill in all the required field!");
            // }
        // }
    });

    $('#scanReceives').on('click', function(){
        $('#barcodeScan').trigger('focus');
    });

    $('#barcodeScan').on('change', function(){
        $('#spinnerLoading').show();
        var url = $(this).val();
        $(this).val('');

        $.get(url, function(data){
            var obj = JSON.parse(data);
            
            if(obj.status === 'success'){
                $('#editModal').find('#id').val(obj.message.id);
                $('#editModal').find('#itemType').val(obj.message.itemTypes);
                $('#editModal').find('#lotNo').val(obj.message.lotNo);
                $('#editModal').find('#bTrayNo').val(obj.message.bTrayNo);
                $('#editModal').find('#bTrayWeight').val(obj.message.trayWeight);
                $('#editModal').find('#grossWeight').val(obj.message.grossWeight);
                $('#editModal').find('#netWeight').val(obj.message.netWeight);
                $('#editModal').find('#moistureValue').val(obj.message.afterReceiving);
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

    $('#receiveModal').find('#grossWeight').on('change', function(){
        var grossWeight = $(this).val();
        var bTrayNo = 0;

        if($('#receiveModal').find('#bTrayWeight').val()){
            bTrayNo = $('#receiveModal').find('#bTrayWeight').val();
            var netweight = grossWeight - bTrayNo;
            $('#receiveModal').find('#netWeight').val(netweight.toFixed(2));

        }
        else{
            $('#receiveModal').find('#netWeight').val(grossWeight.toFixed(2));
        }
    });

    $('#receiveModal').find('#bTrayWeight').on('change', function(){
        var grossWeight = 0;
        var bTrayNo = $(this).val();

        if($('#receiveModal').find('#grossWeight').val()){
            grossWeight = $('#receiveModal').find('#grossWeight').val();
            var netweight = grossWeight - bTrayNo;
            $('#receiveModal').find('#netWeight').val(netweight.toFixed(2));
        }
        else{
            $('#receiveModal').find('#netWeight').val((0).toFixed(2));
        }
    });

    $('#editModal').find('#grossWeight').on('change', function(){
        var grossWeight = $(this).val();
        var bTrayNo = 0;

        if($('#editModal').find('#bTrayWeight').val()){
            bTrayNo = $('#editModal').find('#bTrayWeight').val();
            var netweight = grossWeight - bTrayNo;
            $('#editModal').find('#netWeight').val(netweight.toFixed(2));
        }
        else{
            $('#editModal').find('#netWeight').val(grossWeight.toFixed(2));
        }
    });

    $('#editModal').find('#bTrayWeight').on('change', function(){
        var grossWeight = 0;
        var bTrayNo = $(this).val();

        if($('#editModal').find('#grossWeight').val()){
            grossWeight = $('#editModal').find('#grossWeight').val();
            var netweight = grossWeight - bTrayNo;
            $('#editModal').find('#netWeight').val(netweight.toFixed(2));
        }
        else{
            $('#editModal').find('#netWeight').val((0).toFixed(2));
        }
    });

    $('#itemType').on('change', function(){
        var itemType = $(this).val();

        if(itemType == 'T3' || itemType == 'T1'){
            $("#bTrayWeight").removeAttr("required");
            $("#bTrayNo").removeAttr("required");
        }
        else{
            //$("#bTrayWeight").attr("required","required");
            //$("#bTrayNo").attr("required","required");
        }
    });

    $('#lotNo').on('change', function(){
        var size = $("#TableId").find("tr").length;
        $("#bTrayNo").val($('#lotNo').val() + padLeadingZeros((size).toString(), 3));
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


        //Destroy the old Datatable
        $("#receiveTable").DataTable().clear().destroy();

        //Create new Datatable
        table = $("#receiveTable").DataTable({
        "responsive": true,
        "autoWidth": false,
        'processing': true,
        'serverSide': true,
        'serverMethod': 'post',
        'searching': false,
        'order': [[ 2, 'asc' ]],
        'columnDefs': [ { orderable: false, targets: [0] }],
        'ajax': {
            'type': 'POST',
            'url':'php/filterReceive.php',
            'data': {
                fromDate: fromDateValue,
                toDate: toDateValue,
                itemTypeFilter: itemTypeFilter,
            } 
        },
        'columns': [
        { data: 'counter' },
        { data: 'item_types' },
        { data: 'lot_no' },
        { data: 'tray_no' },
        { data: 'tray_weight' },
        { data: 'gross_weight' },
        { data: 'net_weight' },
        { data: 'moisture_after_receiving' },
        { 
            data: 'id',
            render: function ( data, type, row ) {
                return '<div class="row"><div class="col-3"><button type="button" id="edit'+data+'" onclick="edit('+data+')" class="btn btn-success btn-sm"><i class="fas fa-pen"></i></button></div><div class="col-3"><button type="button" id="print'+data+'" onclick="print('+data+')" class="btn btn-info btn-sm"><i class="fas fa-print"></i></button></div><div class="col-3"><button type="button" id="deactivate'+data+'" onclick="deactivate('+data+')" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button></div></div>';
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
                $('#receiveModal').find('#grossWeight').val(parseFloat(text).toFixed(2));
                $('#receiveModal').find('#grossWeight').trigger('change');
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
                $('#receiveModal').find('#bTrayWeight').val(parseFloat(text).toFixed(2));
                $('#receiveModal').find('#bTrayWeight').trigger('change');
            }
            else{
                toastr["error"]("Failed to get the reading!", "Failed:");
            }
        });
    });

    $('#editTrayWeightBtn').on('click', function(){
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
                $('#editModal').find('#bTrayWeight').val(parseFloat(text).toFixed(2));
                $('#editModal').find('#bTrayWeight').trigger('change');
            }
            else{
                toastr["error"]("Failed to get the reading!", "Failed:");
            }
        });
    });

    $('#editGrossWeightBtn').on('click', function(){
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
                $('#editModal').find('#grossWeight').val(parseFloat(text).toFixed(2));
                $('#editModal').find('#grossWeight').trigger('change');
            }
            else{
                toastr["error"]("Failed to get the reading!", "Failed:");
            }
        });
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
            $('#spinnerLoading').hide();
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
            }, 1000);
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

function padLeadingZeros(num, size) {
    var s = num+"";
    while (s.length < size) s = "0" + s;
    return s;
}
</script>
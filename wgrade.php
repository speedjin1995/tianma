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

$reasons = $db->query("SELECT * FROM reasons WHERE deleted = '0'");
$editGrades = $db->query("SELECT * FROM grades WHERE deleted = '0' AND class = 'T1'");
$editGrades2 = $db->query("SELECT * FROM grades WHERE deleted = '0' AND class = 'T3'");
$editGrades3 = $db->query("SELECT * FROM grades WHERE deleted = '0' AND class = 'T4'");
?>

<style>
    @media screen and (min-width: 676px) {
        #gradesModal .modal-dialog {
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

<select class="form-control" style="width: 100%;" id="editGradesHidden" style="display: none;">
  <option value="" selected disabled hidden>Please Select</option>
  <?php while($roweditGrades=mysqli_fetch_assoc($editGrades)){ ?>
    <option value="<?=$roweditGrades['id'] ?>"><?=$roweditGrades['grade'] ?></option>
  <?php } ?>
</select>

<select class="form-control" style="width: 100%;" id="editGrades2Hidden" style="display: none;">
  <option value="" selected disabled hidden>Please Select</option>
  <?php while($roweditGrades2=mysqli_fetch_assoc($editGrades2)){ ?>
    <option value="<?=$roweditGrades2['id'] ?>"><?=$roweditGrades2['grade'] ?></option>
  <?php } ?>
</select>

<select class="form-control" style="width: 100%;" id="editGrades3Hidden" style="display: none;">
  <option value="" selected disabled hidden>Please Select</option>
  <?php while($roweditGrades3=mysqli_fetch_assoc($editGrades3)){ ?>
    <option value="<?=$roweditGrades3['id'] ?>"><?=$roweditGrades3['grade'] ?></option>
  <?php } ?>
</select>

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
			<div class="col-sm-6">
				<h1 class="m-0 text-dark">Grade 分级</h1>
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
                                <button type="button" class="btn btn-block bg-gradient-info btn-sm" id="scanGrades">Scan 扫描</button>
                            </div>
                            <div class="col-3">
                                <button type="button" class="btn btn-block bg-gradient-warning btn-sm" id="addGrades">Add Grade 新增分级</button>
                            </div>
                        </div>
                    </div>
					<div class="card-body">
						<table id="gradeTable" class="table table-bordered table-striped">
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
                                    <th>Moisture after grading <br>分级后湿度(%)</th>
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

<div class="modal fade" id="gradesModal">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <form role="form" id="gradeForm">
            <div class="modal-header">
              <h4 class="modal-title">Add Grades 新增品规</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <div class="card-body">
                    <input type="hidden" class="form-control" id="id" name="id">
                    <input type="hidden" class="form-control" id="parentId" name="parentId">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="lotNo">Lot No 批号</label>
                                <input type="text" class="form-control" name="lotNo" id="lotNo" placeholder="Enter Lot No">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="bTrayNo">Box/Tray No 桶/托盘代号</label>
                                <input type="text" class="form-control" name="bTrayNo" id="bTrayNo" placeholder="Enter Box/Tray No">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                            <label for="itemType">Item Types 货品种类</label>
                                <input type="text" class="form-control" name="itemType" id="itemType" placeholder="Enter item type" readonly>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="grossWeight">Gross weight 分级毛重(G)</label>
                                <input type="number" class="form-control" name="grossWeight" id="grossWeight" placeholder="Enter Grading Gross weight" readonly>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="bTrayWeight">Box/Tray Weight 桶/托盘重量(G)</label>
                                <input type="number" class="form-control" name="bTrayWeight" id="bTrayWeight" placeholder="Enter Box/Tray Weight" readonly>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="netWeight">Net weight 分级净重(G)</label>
                                <input type="number" class="form-control" name="netWeight" id="netWeight" placeholder="Enter Grading Net weight" readonly>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>

                <div class="row">
                    <h4>Add Grading 添加分级</h4>
                    <button style="margin-left:auto;margin-right: 25px;" type="button" class="btn btn-primary add-row">Add New</button>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                            <label for="itemType">Status 状态</label>
                                <select class="form-control" style="width: 100%;" id="newStatus" name="newStatus">
                                    <option selected="selected" value="PASSED">Passed 合格</option>
                                    <option value="REJECT">Reject 不合格</option>
                                    <option value="LAB">Lab 化验</option>
                                </select>
                            </div>
                        </div>

                        <div id="hideReason" class="col-md-9" hidden>
                            <div class="form-group">
                            <label for="itemType">Reason 状态</label>
                                <select class="form-control" style="width: 100%;" id="newReason" name="newReason">
                                    <option selected="selected">-</option>
                                    <?php while($rowS=mysqli_fetch_assoc($reasons)){ ?>
                                        <option value="<?=$rowS['id'] ?>"><?=$rowS['reasons'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="newLotNo">Lot No 批号</label>
                                <input type="text" class="form-control" name="newLotNo" id="newLotNo" placeholder="Enter Lot No">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="newGrade">Grade 等级</label>
                                <select class="form-control" style="width: 100%;" id="newGrade" name="newGrade"></select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="newTrayNo">Box/Tray No 桶/托盘代号</label>
                                <input type="text" class="form-control" name="newTrayNo" id="newTrayNo" placeholder="Enter Box/Tray No">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="newTrayWeight">Box/Tray Weight 桶/托盘重量(G) <span style="color:red;">*</span></label>
                                <div class="input-group">
                                    <input type="number" class="form-control" name="newTrayWeight" id="newTrayWeight" placeholder="Enter Box/Tray Weight">
                                    <button type="button" class="btn btn-primary" id="trayWeightSyncBtn"><i class="fas fa-sync"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="newGrossWeight">Gross weight 分级毛重(G) <span style="color:red;">*</span></label>
                                <div class="input-group">
                                    <input type="number" class="form-control" name="newGrossWeight" id="newGrossWeight" placeholder="Enter Grading Gross weight">
                                    <button type="button" class="btn btn-primary" id="grossWeightSyncBtn"><i class="fas fa-sync"></i></button>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="qty">Qty 片数 (pcs) <span style="color:red;">*</span></label>
                                <input type="number" class="form-control" name="qty" id="qty" placeholder="Enter qty">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="newNetWeight">Net weight 分级净重(G)</label>
                                <input type="number" class="form-control" name="newNetWeight" id="newNetWeight" placeholder="Enter Grading Net weight">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="moistureAfGrade">Moisture after grading 分级后湿度(%)<span style="color:red;">*</span></label>
                                <input type="number" class="form-control" name="moistureAfGrade" id="moistureAfGrade" placeholder="Enter Moisture after grading" max="100">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="remark">Remark 备注</label>
                                <textarea class="form-control" name="remark" id="remark" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <table id="TableId">
                    <thead>
                        <tr>
                            <th>Lot No <br>批号</th>
                            <th>Grade <br>等级</th>
                            <th>Box/Tray No <br>桶/托盘代号</th>
                            <th>Box/Tray Weight <br>桶/托盘重量(G)</th>
                            <th>Gross weight <br>分级毛重(G)</th>
                            <th>Qty <br>片数(pcs)</th>
                            <th>Net weight <br>分级净重(G)</th>
                            <th>Moisture after grading <br>分级后湿度(%)</th>
                            <th>Status <br>状态</th>
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

<div class="modal fade" id="editGradesModal">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <form role="form" id="editGradeForm">
            <div class="modal-header">
              <h4 class="modal-title">Edit Grades 修改品规</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <div class="card-body">
                    <input type="hidden" class="form-control" id="editId" name="editId">
                    <input type="hidden" class="form-control" id="editParentId" name="editParentId">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="editLotNo">Lot No 批号</label>
                                <input type="text" class="form-control" name="editLotNo" id="editLotNo" placeholder="Enter Lot No">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="editBTrayNo">Box/Tray No 桶/托盘代号</label>
                                <input type="text" class="form-control" name="editBTrayNo" id="editBTrayNo" placeholder="Enter Box/Tray No">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                            <label for="editItemType">Item Types 货品种类</label>
                                <input type="text" class="form-control" name="editItemType" id="editItemType" placeholder="Enter item type">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="editGrossWeight">Gross weight 分级毛重(G)</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" name="editGrossWeight" id="editGrossWeight" placeholder="Enter Grading Gross weight">
                                    <button type="button" class="btn btn-primary" id="trayWeightSyncBtn"><i class="fas fa-sync"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="editBTrayWeight">Box/Tray Weight 桶/托盘重量(G)</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" name="editBTrayWeight" id="editBTrayWeight" placeholder="Enter Box/Tray Weight">
                                    <button type="button" class="btn btn-primary" id="trayWeightSyncBtn"><i class="fas fa-sync"></i></button>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="editNetWeight">Net weight 分级净重(G)</label>
                                <input type="number" class="form-control" name="editNetWeight" id="editNetWeight" placeholder="Enter Grading Net weight" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="editQty">Qty 片数 (pcs) <span style="color:red;">*</span></label>
                                <input type="number" class="form-control" name="editQty" id="editQty" placeholder="Enter qty">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="editGrade">Grade 等级</label>
                                <select class="form-control" style="width: 100%;" id="editGrade" name="editGrade"></select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                                <div class="form-group">
                                    <label for="editMoistureAfGrade">Moisture after grading 分级后湿度(%)<span style="color:red;">*</span></label>
                                    <input type="number" class="form-control" name="editMoistureAfGrade" id="editMoistureAfGrade" placeholder="Enter Moisture after grading" max="100">
                                </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="editRemark">Remark 备注</label>
                                <textarea class="form-control" name="editRemark" id="editRemark" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-danger editCloseBtn" data-dismiss="modal">Close 关闭</button>
              <button type="submit" class="btn btn-primary editSubmitBtn" name="submit" id="submitLot">Submit 提交</button>
            </div>
        </form>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<script>
$(function () {
    $("#gradeTable").DataTable({
        "responsive": true,
        "autoWidth": false,
        'processing': true,
        'serverSide': true,
        'serverMethod': 'post',
        'order': [[ 1, 'asc' ]],
        'columnDefs': [ { orderable: false, targets: [0] }],
        'ajax': {
            'url':'php/loadWgrade.php'
        },
        'columns': [
            { data: 'counter' },
            { data: 'lot_no' },
            { data: 'grade' },
            { data: 'tray_no' },
            { data: 'tray_weight' },
            { data: 'grading_gross_weight' },
            { data: 'pieces' },
            { data: 'grading_net_weight' },
            { data: 'moisture_after_grading' },
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
            if($('#gradesModal').hasClass('show')){

                $.post('php/wgrade.php', $('#gradeForm').serialize(), function(data){
                        var obj = JSON.parse(data); 
                        
                        if(obj.status === 'success'){
                            $('#gradesModal').modal('hide');
                            toastr["success"](obj.message, "Success:");
                            
                            $.get('wgrade.php', function(data) {
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
                
            }else if($('#editGradesModal').hasClass('show')){

                $.post('php/editGrading.php', $('#editGradeForm').serialize(), function(data){
                        var obj = JSON.parse(data); 
                        
                        if(obj.status === 'success'){
                            $('#gradesModal').modal('hide');
                            toastr["success"](obj.message, "Success:");
                            
                            $.get('wgrade.php', function(data) {
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

    $('#scanGrades').on('click', function(){
        $('#barcodeScan').trigger('focus');
    });

    $('#barcodeScan').on('change', function(){
        $('#spinnerLoading').show();
        var url = this.val();
        this.val('');

        $.get(url, function(data){
            var obj = JSON.parse(data);
            
            if(obj.status === 'success'){
                if(obj.message.parentNo == '0'){
                    $('#gradesModal').find('#parentId').val(obj.message.id);
                    $('#gradesModal').find('#lotNo').val(obj.message.lotNo);
                    $('#gradesModal').find('#bTrayNo').val(obj.message.bTrayNo);
                    $('#gradesModal').find('#lotNo').trigger('change');
                    $('#gradesModal').modal('show');

                    if(obj.message.itemTypes == 'T1'){
                        $('#gradesModal').find("#newGrade").html($('#editGradesHidden').html());
                    }
                    else if(obj.message.itemTypes == 'T3'){
                        $('#gradesModal').find("#newGrade").html($('#editGrades2Hidden').html());
                    }
                    else if(obj.message.itemTypes == 'T4'){
                        $('#gradesModal').find("#newGrade").html($('#editGrades3Hidden').html());
                    }
                }
                else{
                    $('#editGradesModal').find('#editId').val(obj.message.id);
                    $('#editGradesModal').find('#editParentId').val(obj.message.parentNo);
                    $('#editGradesModal').find('#editLotNo').val(obj.message.lotNo);
                    $('#editGradesModal').find('#editBTrayNo').val(obj.message.bTrayNo);
                    $('#editGradesModal').find('#editItemType').val(obj.message.itemTypes);
                    $('#editGradesModal').find('#editGrossWeight').val(obj.message.gradingGrossWeight);
                    $('#editGradesModal').find('#editBTrayWeight').val(obj.message.trayWeight);
                    $('#editGradesModal').find('#editNetWeight').val(obj.message.gradingNetWeight);
                    $('#editGradesModal').find('#editQty').val(obj.message.pieces);
                    $('#editGradesModal').find('#editGrade').val(obj.message.grade);
                    $('#editGradesModal').find('#editMoistureAfGrade').val(obj.message.moistureAfterGrading);
                    $('#editGradesModal').find('#editRemark').val(obj.message.remark);
                    $('#editGradesModal').modal('show');

                    if(obj.message.itemTypes == 'T1'){
                        $('#editGradesModal').find("#editGrade").html($('#editGradesHidden').html());
                    }
                    else if(obj.message.itemTypes == 'T3'){
                        $('#editGradesModal').find("#editGrade").html($('#editGrades2Hidden').html());
                    }
                    else if(obj.message.itemTypes == 'T4'){
                        $('#editGradesModal').find("#editGrade").html($('#editGrades3Hidden').html());
                    }
                }
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

    $('#addGrades').on('click', function(){
        $('#gradesModal').find('#id').val("");
        $('#gradesModal').modal('show');
        
        $('#gradeForm').validate({
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

    $('#lotNo').on('change', function(){
        if($(this).val() && $('#bTrayNo').val()){
            $('#spinnerLoading').show();
            var lotNo = $(this).val();
            var bTrayNo = $('#bTrayNo').val();

            $.post('php/getReceiveInfo.php', {lotNum: lotNo, trayNo: bTrayNo}, function(data){
                var obj = JSON.parse(data);
                
                if(obj.status === 'success'){
                    $('#gradesModal').find('#parentId').val(obj.message.id);
                    $('#gradesModal').find('#itemType').val(obj.message.itemType);
                    $('#gradesModal').find('#grossWeight').val(obj.message.grossWeight);
                    $('#gradesModal').find('#bTrayWeight').val(obj.message.bTrayWeight);
                    $('#gradesModal').find('#netWeight').val(obj.message.netWeight);
                    $('#gradesModal').find("#newLotNo").val(lotNo + '/1');

                    if(obj.message.itemType == 'T1'){
                        $('#gradesModal').find("#newGrade").html($('#editGradesHidden').html());
                    }
                    else if(obj.message.itemType == 'T3'){
                        $('#gradesModal').find("#newGrade").html($('#editGrades2Hidden').html());
                    }
                    else if(obj.message.itemType == 'T4'){
                        $('#gradesModal').find("#newGrade").html($('#editGrades3Hidden').html());
                    }
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
    
    $('#bTrayNo').on('change', function(){
        if($(this).val() && $('#lotNo').val()){
            $('#spinnerLoading').show();
            var lotNo = $('#lotNo').val();
            var bTrayNo = $(this).val();

            $.post('php/getReceiveInfo.php', {lotNum: lotNo, trayNo: bTrayNo}, function(data){
                var obj = JSON.parse(data);
                
                if(obj.status === 'success'){
                    $('#gradesModal').find('#parentId').val(obj.message.id);
                    $('#gradesModal').find('#itemType').val(obj.message.itemType);
                    $('#gradesModal').find('#grossWeight').val(obj.message.grossWeight);
                    $('#gradesModal').find('#bTrayWeight').val(obj.message.bTrayWeight);
                    $('#gradesModal').find('#netWeight').val(obj.message.netWeight);
                    $('#gradesModal').find("#newTrayNo").val(bTrayNo + '/1');
                    $('#gradesModal').find("#newLotNo").val(lotNo);
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

    $(".add-row").click(function(){
        var size = $("#TableId").find("tr").length - 1;

        var newLotNo;
        var newGrade;
        var newTrayNo;
        var newTrayWeight;
        var newGrossWeight;
        var qty;
        var newNetWeight;
        var moistureAfGrade;
        var newStatus;
        var newReason;
        var newRemark;

        if($("#newStatus").val() != "LAB"){
            if($("#newTrayWeight").val() != "" && $("#newGrossWeight").val() != ""  && $("#qty").val() != "" && $("#newNetWeight").val() != "" && $("#moistureAfGrade").val() != ""){
                newLotNo = $("#newLotNo").val();
                newGrade = $("#newGrade").val();
                newTrayNo = $("#newTrayNo").val();
                newTrayWeight = $("#newTrayWeight").val();
                newGrossWeight = $("#newGrossWeight").val();
                qty = $("#qty").val();
                newNetWeight = $("#newNetWeight").val();
                moistureAfGrade = $("#moistureAfGrade").val();
                newStatus = $("#newStatus").val();
                newReason = $("#newReason").val();
                
                if($("#remark").val()){
                    newRemark = $("#remark").val();
                }
                else{
                    newRemark = "";
                }

                var markup = "<tr><td><input type='hidden' name='newLotNo["+size+"]' value='"+newLotNo+"' />" +
                newLotNo + "</td><td><input type='hidden' name='newGrade["+size+"]' value='"+newGrade+"' />" + 
                newGrade + "</td><td><input type='hidden' name='newTrayNo["+size+"]' value='"+newTrayNo+"' />" + 
                newTrayNo + "</td><td><input type='hidden' name='newTrayWeight["+size+"]' value='"+newTrayWeight+"' />" + 
                newTrayWeight + "</td><td><input type='hidden' name='newGrossWeight["+size+"]' value='"+newGrossWeight+"' />" + 
                newGrossWeight + "</td><td><input type='hidden' name='qty["+size+"]' value='"+qty+"' />" + 
                qty + "</td><td><input type='hidden' name='newNetWeight["+size+"]' value='"+newNetWeight+"' />" + 
                newNetWeight + "</td><td><input type='hidden' name='moistureAfGrade["+size+"]' value='"+moistureAfGrade+"' />" + 
                moistureAfGrade + "</td><td><input type='hidden' name='newStatus["+size+"]' value='"+newStatus+"' />" + 
                newStatus + "</td><input type='hidden' name='newReason["+size+"]' value='"+newReason+"' hidden/>" + 
                newReason + "<td><input type='hidden' name='newRemark["+size+"]' value='"+newRemark+"' hidden/><button type='button' class='btn btn-danger' name=delete"+ size +">delete</button></td></tr>";
                
                $("#TableId tbody").append(markup);

                // Reset to empty again
                $("#newLotNo").val($('#lotNo').val());
                $("#newGrade").val("");
                $("#newTrayNo").val($('#bTrayNo').val() + "/" + (size+2).toString());
                $("#newTrayWeight").val("");
                $("#newGrossWeight").val("");
                $("#qty").val("");
                $("#newNetWeight").val("");
                $("#moistureAfGrade").val("");
                $('#newStatus').val('PASSED');
                $('#hideReason').attr('hidden', 'hidden');
                $('#newReason').val('');
                $('#remark').val('');
            }else{
                alert("Please Fill in all the required field!");
            }
        }else{
            if($("#newLotNo").val() != "" && $("#newGrossWeight").val() != "" && $("#qty").val() != ""){
                newLotNo = $("#newLotNo").val();
                newGrade = $("#newGrade").val();
                newTrayNo = $("#newTrayNo").val();
                newTrayWeight = $("#newTrayWeight").val();
                newGrossWeight = $("#newGrossWeight").val();
                qty = $("#qty").val();
                newNetWeight = $("#newNetWeight").val();
                moistureAfGrade = $("#moistureAfGrade").val();
                newStatus = $("#newStatus").val();
                newReason = $("#newReason").val();

                if($("#remark").val()){
                    newRemark = $("#remark").val();
                }
                else{
                    newRemark = "";
                }

                var markup = "<tr><td><input type='hidden' name='newLotNo["+size+"]' value='"+newLotNo+"' />" +
                newLotNo + "</td><td><input type='hidden' name='newGrade["+size+"]' value='"+newGrade+"' />" + 
                newGrade + "</td><td><input type='hidden' name='newTrayNo["+size+"]' value='"+newTrayNo+"' />" + 
                newTrayNo + "</td><td><input type='hidden' name='newTrayWeight["+size+"]' value='"+newTrayWeight+"' />" + 
                newTrayWeight + "</td><td><input type='hidden' name='newGrossWeight["+size+"]' value='"+newGrossWeight+"' />" + 
                newGrossWeight + "</td><td><input type='hidden' name='qty["+size+"]' value='"+qty+"' />" + 
                qty + "</td><td><input type='hidden' name='newNetWeight["+size+"]' value='"+newNetWeight+"' />" + 
                newNetWeight + "</td><td><input type='hidden' name='moistureAfGrade["+size+"]' value='"+moistureAfGrade+"' />" + 
                moistureAfGrade + "</td><td><input type='hidden' name='newStatus["+size+"]' value='"+newStatus+"' />" + 
                newStatus + "</td><input type='hidden' name='newReason["+size+"]' value='"+newReason+"' hidden/>" + 
                newReason + "<td><input type='hidden' name='newRemark["+size+"]' value='"+newRemark+"' hidden/><button type='button' class='btn btn-danger' name=delete"+ size +">delete</button></td></tr>";
                
                $("#TableId tbody").append(markup);

                // Reset to empty again
                $("#newLotNo").val($('#lotNo').val());
                $("#newGrade").val("");
                $("#newTrayNo").val($('#bTrayNo').val() + "/" + (size+2).toString());
                $("#newTrayWeight").val("");
                $("#newGrossWeight").val("");
                $("#qty").val("");
                $("#newNetWeight").val("");
                $("#moistureAfGrade").val("");
                $('#newStatus').val('PASSED');
                $('#hideReason').attr('hidden', 'hidden');
                $('#newReason').val('');
                $('#remark').val('');
            }else{
                alert("Please Fill in all the required field!");
            }

        }
    });

    $('#newGrossWeight').on('change', function(){
        var grossWeight = $(this).val();
        var bTrayNo = 0;

        if($('#newTrayWeight').val()){
            bTrayNo = $('#newTrayWeight').val();
            var netweight = grossWeight - bTrayNo;
            $('#newNetWeight').val(netweight.toFixed(2));

        }
        else{
            $('#newNetWeight').val(grossWeight.toFixed(2));
        }
    });

    $('#newTrayWeight').on('change', function(){
        var grossWeight = 0;
        var bTrayNo = $(this).val();

        if($('#newGrossWeight').val()){
            grossWeight = $('#newGrossWeight').val();
            var netweight = grossWeight - bTrayNo;
            $('#newNetWeight').val(netweight.toFixed(2));

        }
        else{
            $('#newNetWeight').val((0).toFixed(2));
        }
    });

    $('#newStatus').on('change', function(){
        if($('#newStatus').val() == 'PASSED')
        {
            $('#hideReason').attr('hidden', 'hidden');
            $('#newReason').val('');

        }else{
            $('#hideReason').removeAttr('hidden');
        }

    });
        
    // Find and remove selected table rows
    $("#TableId tbody").on('click', 'button[name^="delete"]', function () {
        $(this).parents("tr").remove();
    });
});

function edit(id){
    $('#spinnerLoading').show();
    $.post('php/getEditGrading.php', {userID: id}, function(data){
        var obj = JSON.parse(data);
        
        if(obj.status === 'success'){
            $('#editGradesModal').find('#editId').val(obj.message.id);
            $('#editGradesModal').find('#editParentId').val(obj.message.parent_no);
            $('#editGradesModal').find('#editItemType').val(obj.message.itemType);
            $('#editGradesModal').find('#editGrossWeight').val(obj.message.grossWeight);
            $('#editGradesModal').find('#editLotNo').val(obj.message.lotNo);
            $('#editGradesModal').find('#editBTrayWeight').val(obj.message.tray_weight);
            $('#editGradesModal').find('#editBTrayNo').val(obj.message.bTrayNo);
            $('#editGradesModal').find('#editNetWeight').val(obj.message.netWeight);
            $('#editGradesModal').find('#editQty').val(obj.message.pieces);
            $('#editGradesModal').find('#editGrade').val(obj.message.grade);
            $('#editGradesModal').find('#editMoistureAfGrade').val(obj.message.moisture_after_grading);
            $('#editGradesModal').find('#editRemark').val(obj.message.remark);
            $('#editGradesModal').modal('show');

            if(obj.message.itemType == 'T1'){
                $('#editGradesModal').find("#editGrade").html($('#editGradesHidden').html());
            }
            else if(obj.message.itemType == 'T3'){
                $('#editGradesModal').find("#editGrade").html($('#editGrades2Hidden').html());
            }
            else if(obj.message.itemType == 'T4'){
                $('#editGradesModal').find("#editGrade").html($('#editGrades3Hidden').html());
            }
            
            $('#editGradeForm').validate({
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
    $.post('php/printGrading.php', {userID: id}, function(data){
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
    $.post('php/deleteGrades.php', {userID: id}, function(data){
        var obj = JSON.parse(data);
        
        if(obj.status === 'success'){
            toastr["success"](obj.message, "Success:");
            $.get('wgrade.php', function(data) {
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
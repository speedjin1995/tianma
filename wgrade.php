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

$reasons = $db->query("SELECT * FROM reasons WHERE deleted = '0'");
$editGrades = $db->query("SELECT * FROM grades WHERE deleted = '0' AND class = 'T1'");
$editGrades2 = $db->query("SELECT * FROM grades WHERE deleted = '0' AND class = 'T3'");
$editGrades3 = $db->query("SELECT * FROM grades WHERE deleted = '0' AND class = 'T4'");
$editReasons = $db->query("SELECT * FROM reasons WHERE deleted = '0' AND class = 'T1'");
$editReasons2 = $db->query("SELECT * FROM reasons WHERE deleted = '0' AND class = 'T3'");
$editReasons3 = $db->query("SELECT * FROM reasons WHERE deleted = '0' AND class = 'T4'");
?>

<style>
    @media screen and (min-width: 676px) {
        #gradesModal .modal-dialog {
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

    .radioTray{
        display: flex;
        justify-content: center;
        margin-top:10px;
    }

    #newBarcodeScan {
      background-color: #f4f6f9;
      color: #f4f6f9;
      border: none;
    }

    #newBarcodeScan:focus {
      outline: none;
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

<select class="form-control" style="width: 100%;" id="editReasonHidden" style="display: none;">
  <option value="" selected disabled hidden>Please Select</option>
  <?php while($roweditReasons=mysqli_fetch_assoc($editReasons)){ ?>
    <option value="<?=$roweditReasons['id'] ?>"><?=$roweditReasons['reasons'] ?></option>
  <?php } ?>
</select>

<select class="form-control" style="width: 100%;" id="editReason2Hidden" style="display: none;">
  <option value="" selected disabled hidden>Please Select</option>
  <?php while($roweditReasons2=mysqli_fetch_assoc($editReasons2)){ ?>
    <option value="<?=$roweditReasons2['id'] ?>"><?=$roweditReasons2['reasons'] ?></option>
  <?php } ?>
</select>

<select class="form-control" style="width: 100%;" id="editReason3Hidden" style="display: none;">
  <option value="" selected disabled hidden>Please Select</option>
  <?php while($roweditReasons3=mysqli_fetch_assoc($editReasons3)){ ?>
    <option value="<?=$roweditReasons3['id'] ?>"><?=$roweditReasons3['reasons'] ?></option>
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
						<table id="gradeTable" class="table table-bordered table-striped">
							<thead>
								<tr>
									<th>No. <br>排号</th>
                                    <th>Item Types <br>货品种类</th>
                                    <th>Lot No <br>批号</th>
									<th>Grade <br>等级</th>
                                    <th>Box/Tray No <br>桶/托盘代号</th>
                                    <th>Box/Tray Weight <br>桶/托盘重量(G)</th>
                                    <th>Grading Gross Weight <br>分级毛重(G)</th>
                                    <th>Qty <br>片数(PCS)</th>
                                    <th>Grading Net Weight <br>分级净重(G)</th>
                                    <th>Moisture after grading <br>分级后湿度(%)</th>
                                    <th>Status <br>状态</th>
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
<input type="text" id="newBarcodeScan">

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
            <div class="modal-body" id="TableId">
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

                        <!-- <div class="col-md-2">
                            <div class="form-group">
                                <label for="bTrayNo">Box/Tray No 桶/托盘代号</label>
                                <input type="text" class="form-control" name="bTrayNo" id="bTrayNo" placeholder="Enter Box/Tray No">
                            </div>
                        </div> -->

                        <div class="col-md-2">
                            <div class="form-group">
                            <label for="itemType">Item Types 货品种类</label>
                                <input type="text" class="form-control" name="itemType" id="itemType" placeholder="Enter item type" readonly>
                            </div>
                        </div>

                        <!-- <div class="col-md-2">
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
                        </div> -->
                    </div>
                </div>
                <hr>

                <div class="row">
                    <h4>Add Grading 添加分级</h4>
                    <button style="margin-left:auto;margin-right: 25px;" type="button" class="btn btn-primary add-row">Add New</button>
                </div>

                

                <!--table id="TableId">
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
                </table-->
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
                                    <button type="button" class="btn btn-primary" id="editGrossWeightBtn"><i class="fas fa-sync"></i></button>
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
                                    <button type="button" class="btn btn-primary" id="editTrayWeightBtn"><i class="fas fa-sync"></i></button>
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

<div id="addContents">
    <div class="card-body details">
        <div class="row">
            <div class="col-md-2">
                <div class="form-group">
                <label for="itemType">Status 状态</label>
                    <select class="form-control" style="width: 100%;" id="newStatus" name="newStatus">
                        <option selected="selected" value="PASSED">Passed 合格</option>
                        <option value="REJECT">Reject 不合格</option>
                        <option value="LAB">Lab 化验</option>
                    </select>
                </div>
            </div>

            <div class="col-md-3" >
                <div class="form-group" id="hideReason" hidden>
                    <label for="itemType">Reason 状态</label>
                    <select class="form-control" style="width: 100%;" id="newReason" name="newReason"></select>
                </div>
            </div>

            <div class="col-md-3 radioTray">
                <div class="form-check form-check-inline mr-5">
                    <input class="form-check-input" type="radio" name="sameTray" id="sameTrayYes" value="Yes">
                    <label class="form-check-label" for="sameTrayYes">Same Tray <br>同样桶/托盘</label>
                </div>

                <div class="form-check form-check-inline ml-10">
                    <input class="form-check-input" type="radio" name="sameTray" id="sameTrayNo" value="No" checked>
                    <label class="form-check-label" for="sameTrayNo">Non-Same Tray <br>不同样桶/托盘</label>
                </div>
            </div>

            <div class="col-md-3" >
                <div class="form-group" id="hideOldTrayNo" hidden>
                    <label for="bTrayNo">Old Tray No 旧桶/托盘代号</label>
                    <div class="input-group">
                        <input type="text" class="form-control" name="bTrayNo" id="bTrayNo" placeholder="Enter Box/Tray No">
                        <button type="button" class="btn btn-primary" id="oldTrayNoSyncBtn"><i class="fas fa-sync"></i></button>
                    </div>
                </div>
            </div>
            
            <div class="col-md-1">
                <button class="btn btn-danger btn-sm" id="remove"><i class="fa fa-times"></i></button>
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
            <div class="col-md-12">
                <div class="form-group">
                    <label for="remark">Remark 备注</label>
                    <textarea class="form-control" name="remark" id="remark" rows="3"></textarea>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
var contentIndex = 0;
var contentItems = "T4";

$(function () {
    $('#editGradesHidden').hide();
    $('#editGrades2Hidden').hide();
    $('#editGrades3Hidden').hide();
    $('#editReasonHidden').hide();
    $('#editReason2Hidden').hide();
    $('#editReason3Hidden').hide();

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

    $("#gradeTable").DataTable({
        "responsive": true,
        "autoWidth": false,
        'processing': true,
        'serverSide': true,
        'serverMethod': 'post',
        'order': [[ 2, 'asc' ]],
        'columnDefs': [ { orderable: false, targets: [0] }],
        'ajax': {
            'url':'php/loadWgrade.php'
        },
        'columns': [
            { data: 'counter' },
            { data: 'item_types' },
            { data: 'lot_no' },
            { data: 'grade' },
            { data: 'tray_no' },
            { data: 'tray_weight' },
            { data: 'grading_gross_weight' },
            { data: 'pieces' },
            { data: 'grading_net_weight' },
            { data: 'moisture_after_grading' },
            { data: 'status' },
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
            if($('#gradesModal').hasClass('show')){
                $.post('php/wgrade.php', $('#gradeForm').serialize(), function(data){
                        var obj = JSON.parse(data); 
                        
                        if(obj.status === 'success'){
                            $('#gradesModal').modal('hide');
                            toastr["success"](obj.message, "Success:");

                            var printWindow = window.open('', '', 'height=400,width=800');
                            printWindow.document.write(obj.label);
                            printWindow.document.close();
                            setTimeout(function(){
                                printWindow.print();
                                printWindow.close();
                            }, 1000);
                            
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

    $('#newBarcodeScan').on('change', function(){
        $('#spinnerLoading').show();
        var url = $(this).val();
        $(this).val('');

        $.get(url, function(data){
            var obj = JSON.parse(data);
            
            if(obj.status === 'success'){
                $('#gradesModal').find('input[name="bTrayNo['+contentIndex+']"]').val(obj.message.bTrayNo);
                $('#gradesModal').find('input[name="bTrayNo['+contentIndex+']"]').trigger("change");
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

    $('#barcodeScan').on('change', function(){
        $('#spinnerLoading').show();
        var url = $(this).val();
        $(this).val('');

        $.get(url, function(data){
            var obj = JSON.parse(data);
            
            if(obj.status === 'success'){
                if(obj.message.parentNo == '0'){
                    //var size = $("#TableId").find("tr").length;
                    $('#gradesModal').find('#parentId').val(obj.message.id);
                    $('#gradesModal').find('#lotNo').val(obj.message.lotNo);
                    $('#gradesModal').find('#lotNo').val(obj.message.lotNo);
                    contentItems = obj.message.itemTypes;
                    $('#gradesModal').find('#lotNo').trigger('change');
                    $('#gradesModal').modal('show');

                    if(obj.message.itemTypes == 'T1'){
                        $('#addContents').find("#newGrade").html($('#editGradesHidden').html());
                        $('#addContents').find("#newReason").html($('#editReasonHidden').html());
                    }
                    else if(obj.message.itemTypes == 'T3'){
                        $('#addContents').find("#newGrade").html($('#editGrades2Hidden').html());
                        $('#addContents').find("#newReason").html($('#editReason2Hidden').html());
                    }
                    else if(obj.message.itemTypes == 'T4'){
                        $('#addContents').find("#newGrade").html($('#editGrades3Hidden').html());
                        $('#addContents').find("#newReason").html($('#editReason3Hidden').html());
                    }

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
        if($(this).val()){
            $('#spinnerLoading').show();
            var lotNo = $(this).val();

            $.post('php/getReceiveInfo.php', {lotNum: lotNo}, function(data){
                var obj = JSON.parse(data);
                
                if(obj.status === 'success'){
                    $('#gradesModal').find('#parentId').val(obj.message.id);
                    $('#gradesModal').find('#itemType').val(obj.message.itemType);
                    contentItems = obj.message.itemTypes;
                    /*$('#gradesModal').find('#grossWeight').val(obj.message.grossWeight);
                    $('#gradesModal').find('#bTrayWeight').val(obj.message.bTrayWeight);
                    $('#gradesModal').find('#netWeight').val(obj.message.netWeight);
                    $('#gradesModal').find("#newTrayNo").val(bTrayNo + '/1');
                    $('#gradesModal').find("#newLotNo").val(lotNo);*/

                    if(obj.message.itemType == 'T1'){
                        $('#addContents').find("#newGrade").html($('#editGradesHidden').html());
                        $('#addContents').find("#newReason").html($('#editReasonHidden').html());
                    }
                    else if(obj.message.itemType == 'T3'){
                        $('#addContents').find("#newGrade").html($('#editGrades2Hidden').html());
                        $('#addContents').find("#newReason").html($('#editReason2Hidden').html());
                    }
                    else if(obj.message.itemType == 'T4'){
                        $('#addContents').find("#newGrade").html($('#editGrades3Hidden').html());
                        $('#addContents').find("#newReason").html($('#editReason3Hidden').html());
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

    $(".add-row").click(function(){
        var $addContents = $("#addContents").html();
        var size = $("#TableId").find(".details").length;

        $addContents.find('.details').attr("id", "detail" + size);
        $addContents.find('.details').attr("data-index", size);
        $addContents.find('#hideReason').attr("id", "hideReason" + size);
        $addContents.find('#hideOldTrayNo').attr("id", "hideOldTrayNo" + size);
        $addContents.find('#remove').attr("id", "remove" + size);
        $addContents.find('#grossWeightSyncBtn').attr("id", "grossWeightSyncBtn" + size);
        $addContents.find('#trayWeightSyncBtn').attr("id", "trayWeightSyncBtn" + size);
        $addContents.find('#oldTrayNoSyncBtn').attr("id", "oldTrayNoSyncBtn" + size);

        $addContents.find('#newStatus').attr('name', 'newStatus['+size+']').attr("id", "newStatus" + size);
        $addContents.find('#newReason').attr('name', 'newReason['+size+']'.attr("id", "newReason" + size));
        $addContents.find('#sameTrayYes').attr('name', 'sameTray['+size+']').attr("id", "sameTrayYes" + size);
        $addContents.find('#sameTrayNo').attr('name', 'sameTray['+size+']').attr("id", "sameTrayNo" + size);
        $addContents.find('#bTrayNo').attr('name', 'bTrayNo['+size+']'.attr("id", "bTrayNo" + size));
        $addContents.find('#newLotNo').attr('name', 'newLotNo['+size+']').attr("id", "newLotNo" + size).val($('#lotNo').val());
        $addContents.find('#newGrade').attr('name', 'newGrade['+size+']').attr("id", "newGrade" + size);
        $addContents.find('#newTrayNo').attr('name', 'newTrayNo['+size+']'.attr("id", "newTrayNo" + size)).val($('#lotNo').val() + "/G" + (size).toString());
        $addContents.find('#newTrayWeight').attr('name', 'newTrayWeight['+size+']').attr("id", "newTrayWeight" + size);
        $addContents.find('#newGrossWeight').attr('name', 'newGrossWeight['+size+']').attr("id", "newGrossWeight" + size);
        $addContents.find('#qty').attr('name', 'qty['+size+']'.attr("id", "qty" + size));
        $addContents.find('#newNetWeight').attr('name', 'newNetWeight['+size+']').attr("id", "newNetWeight" + size);
        $addContents.find('#moistureAfGrade').attr('name', 'moistureAfGrade['+size+']'.attr("id", "moistureAfGrade" + size));
        $addContents.find('#remark').attr('name', 'remark['+size+']').attr("id", "remark" + size);
        $("#TableId").append($addContents);
    });

    $("#TableId").on('click', 'input[name^="sameTray"]', function () {
        var index = $(this).parents('.details').attr('data-index');

        if($('input[name="sameTray['+index+']"]:checked').val() == 'Yes'){
            $(this).parents('.details').find('[id^="hideOldTrayNo"]').removeAttr('hidden');
        }
        else{
            $(this).parents('.details').find('[id^="hideOldTrayNo"]').attr('hidden', 'hidden');
            $(this).parents('.details').find('[id^="bTrayNo"]').val('');
        }
    });
    
    $("#TableId").on('click', 'button[id^="oldTrayNoSyncBtn"]', function () {
        var contentIndex = $(this).parents('.details').attr('data-index');
        $(this).parents('.details').find('input[name^="bTrayNo"]').val('');
        $('#newBarcodeScan').val('');
        $('#newBarcodeScan').trigger('focus');
    });

    $("#TableId").on('change', 'input[name^="bTrayNo"]', function(){
        if($(this).val() && $('#lotNo').val()){
            $('#spinnerLoading').show();
            var lotNo = $('#lotNo').val();
            var bTrayNo = $(this).val();

            $.post('php/getOldReceiveInfo.php', {lotNum: lotNo, trayNo: bTrayNo}, function(data){
                var obj = JSON.parse(data);
                if(obj.status === 'success'){
                    var size = $("#TableId").find(".details").length;
                    $(this).parents('.details').find('input[name^="newGrossWeight"]').val(obj.message.grossWeight);
                    $(this).parents('.details').find('input[name^="newTrayWeight"]').val(obj.message.grossWeight);
                    $(this).parents('.details').find('input[name^="newNetWeight"]').val(obj.message.grossWeight);
                    $(this).parents('.details').find('input[name^="newTrayNo"]').val(lotNo + '/G' + (size).toString());
                    $(this).parents('.details').find('input[name^="newLotNo"]').val(lotNo);

                    if(obj.message.itemType == 'T1'){
                        $(this).parents('.details').find('input[name^="newGrade"]').html($('#editGradesHidden').html());
                    }
                    else if(obj.message.itemType == 'T3'){
                        $(this).parents('.details').find('input[name^="newGrade"]').html($('#editGrades2Hidden').html());
                    }
                    else if(obj.message.itemType == 'T4'){
                        $(this).parents('.details').find('input[name^="newGrade"]').html($('#editGrades3Hidden').html());
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

    $("#TableId").on('change', 'input[name^="newGrossWeight"]', function(){
        var grossWeight = $(this).val();
        var bTrayNo = 0;

        if($(this).parents('.details').find('input[name^="newTrayWeight"]').val()){
            bTrayNo = $(this).parents('.details').find('input[name^="newTrayWeight"]').val();
            var netweight = grossWeight - bTrayNo;
            $(this).parents('.details').find('input[name^="newNetWeight"]').val(netweight.toFixed(2));
        }
        else{
            $(this).parents('.details').find('input[name^="newNetWeight"]').val(grossWeight.toFixed(2));
        }
    });

    $("#TableId").on('change', 'input[name^="newTrayWeight"]', function(){
        var grossWeight = 0;
        var bTrayNo = $(this).val();

        if($(this).parents('.details').find('input[name^="newGrossWeight"]').val()){
            grossWeight = $(this).parents('.details').find('input[name^="newGrossWeight"]').val();
            var netweight = grossWeight - bTrayNo;
            $(this).parents('.details').find('input[name^="newNetWeight"]').val(netweight.toFixed(2));
        }
        else{
            $(this).parents('.details').find('input[name^="newNetWeight"]').val((0).toFixed(2));
        }
    });

    $('#editGrossWeight').on('change', function(){
        var grossWeight = $(this).val();
        var bTrayNo = 0;

        if($('#editBTrayWeight').val()){
            bTrayNo = $('#editBTrayWeight').val();
            var netweight = grossWeight - bTrayNo;
            $('#editNetWeight').val(netweight.toFixed(2));

        }
        else{
            $('#editNetWeight').val(grossWeight.toFixed(2));
        }
    });

    $('#editBTrayWeight').on('change', function(){
        var grossWeight = 0;
        var bTrayNo = $(this).val();

        if($('#editGrossWeight').val()){
            grossWeight = $('#editGrossWeight').val();
            var netweight = grossWeight - bTrayNo;
            $('#editNetWeight').val(netweight.toFixed(2));

        }
        else{
            $('#editNetWeight').val((0).toFixed(2));
        }
    });

    $("#TableId").on('change', '[name^="newStatus"]', function(){
        if($(this).val() == 'PASSED'){
            $(this).parents('.details').find('[id^="hideReason"]').attr('hidden', 'hidden');
            $(this).parents('.details').find('[id^="newReason"]').val('');
        }
        else{
            $(this).parents('.details').find('[id^="hideReason"]').removeAttr('hidden');
        }
    });
        
    // Find and remove selected table rows
    $("#TableId").on('click', 'button[id^="remove"]', function () {
        $(this).parents('.details').remove();
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
        $("#gradeTable").DataTable().clear().destroy();

        //Create new Datatable
        table = $("#gradeTable").DataTable({
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
                'url':'php/filterWGrade.php',
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
            { data: 'grade' },
            { data: 'tray_no' },
            { data: 'tray_weight' },
            { data: 'grading_gross_weight' },
            { data: 'pieces' },
            { data: 'grading_net_weight' },
            { data: 'moisture_after_grading' },
            { data: 'status' },
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

    $("#TableId").on('change', 'button[id^="grossWeightSyncBtn"]', function(){
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
                $(this).parents('.details').find('input[name^="newGrossWeight"]').val(parseFloat(text).toFixed(2));
                $(this).parents('.details').find('input[name^="newGrossWeight"]').trigger('change');
            }
            else{
                toastr["error"]("Failed to get the reading!", "Failed:");
            }
        });
    });

    $("#TableId").on('change', 'button[id^="trayWeightSyncBtn"]', function(){
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
                $(this).parents('.details').find('input[name^="newTrayWeight"]').val(parseFloat(text).toFixed(2));
                $(this).parents('.details').find('input[name^="newTrayWeight"]').trigger('change');
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
                $('#editGradesModal').find('#editBTrayWeight').val(parseFloat(text).toFixed(2));
                $('#editBTrayWeight').trigger('change');
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
                $('#editGradesModal').find('#editGrossWeight').val(parseFloat(text).toFixed(2));
                $('#editGrossWeight').trigger('change');
            }
            else{
                toastr["error"]("Failed to get the reading!", "Failed:");
            }
        });
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
    $.post('php/deleteReceives.php', {userID: id}, function(data){
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
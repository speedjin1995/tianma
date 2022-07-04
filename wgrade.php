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
                            <div class="col-9"></div>
                            <div class="col-3">
                                <button type="button" class="btn btn-block bg-gradient-warning btn-sm" id="addPackages">Add Grade 新增分级</button>
                            </div>
                        </div>
                    </div>
					<div class="card-body">
						<table id="packageTable" class="table table-bordered table-striped">
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
								</tr>
							</thead>
						</table>
					</div><!-- /.card-body -->
				</div><!-- /.card -->
			</div><!-- /.col -->
		</div><!-- /.row -->
	</div><!-- /.container-fluid -->
</section><!-- /.content -->

<div class="modal fade" id="packagesModal">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <form role="form" id="packageForm">
            <div class="modal-header">
              <h4 class="modal-title">Add Grades 新增品规</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">

              <div class="card-body">
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                        <label for="itemType">Item Types 货品种类</label>
                            <select class="form-control" style="width: 100%;" id="itemType" name="itemType" disabled>
                                <option selected="selected">-</option>
                                <option value="t1">T1</option>
                                <option value="t3">T3</option>
                                <option value="t4">T4</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="lotNo">Lot No 批号</label>
                            <input type="text" class="form-control" name="lotNo" id="lotNo" placeholder="Enter Lot No" disabled>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="bTrayNo">Box/Tray No 桶/托盘代号</label>
                            <input type="text" class="form-control" name="bTrayNo" id="bTrayNo" placeholder="Enter Box/Tray No" disabled>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="grossWeight">Gross weight 分级毛重(G)</label>
                            <input type="number" class="form-control" name="grossWeight" id="grossWeight" placeholder="Enter Grading Gross weight" disabled>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="bTrayWeight">Box/Tray Weight 桶/托盘重量(G)</label>
                            <input type="number" class="form-control" name="bTrayWeight" id="bTrayWeight" placeholder="Enter Box/Tray Weight" disabled>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="netWeight">Net weight 分级净重(G)</label>
                            <input type="number" class="form-control" name="netWeight" id="netWeight" placeholder="Enter Grading Net weight" disabled>
                        </div>
                    </div>
                </div>
              </div>

            <hr>
            <div class="row">
                <h4>Add New Grade</h4>
                <button style="margin-left:auto;margin-right: 25px;" type="button" class="btn btn-primary add-row">Add New</button>
            </div>
              <div class="card-body">
                <div class="row">

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="newLotNo">Lot No 批号</label>
                            <input type="text" class="form-control" name="newLotNo" id="newLotNo" placeholder="Enter Lot No" >
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="newGrade">Grade 等级</label>
                            <input type="text" class="form-control" name="newGrade" id="newGrade" placeholder="Enter Box/Tray No">
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
                            <label for="newTrayWeight">Box/Tray Weight 桶/托盘重量(G)</label>
                            <input type="number" class="form-control" name="newTrayWeight" id="newTrayWeight" placeholder="Enter Box/Tray No">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="newGrossWeight">Gross weight 分级毛重(G)</label>
                            <input type="number" class="form-control" name="newGrossWeight" id="newGrossWeight" placeholder="Enter Grading Gross weight" >
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="qty">Qty 片数 (pcs)</label>
                            <input type="number" class="form-control" name="qty" id="qty" placeholder="Enter Box/Tray Weight" >
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="newNetWeight">Net weight 分级净重(G)</label>
                            <input type="number" class="form-control" name="newNetWeight" id="newNetWeight" placeholder="Enter Grading Net weight" >
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="moistureAfGrade">Moisture after grading 分级后湿度(%)</label>
                            <input type="number" class="form-control" name="moistureAfGrade" id="moistureAfGrade" placeholder="Enter Grading Net weight" >
                        </div>
                    </div>
                </div>
              </div>

              <!-- <div class="card card-body"> -->
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

<script>
$(function () {
    $("#packageTable").DataTable({
        "responsive": true,
        "autoWidth": false,
        'processing': true,
        'serverSide': true,
        'serverMethod': 'post',
        'order': [[ 1, 'asc' ]],
        'columnDefs': [ { orderable: false, targets: [0] }],
        'ajax': {
            'url':'php/loadGrades.php'
        },
        'columns': [
            { data: 'counter' },
            { data: 'grade' },
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
            $.post('php/grades.php', $('#packageForm').serialize(), function(data){
                var obj = JSON.parse(data); 
                
                if(obj.status === 'success'){
                    $('#packagesModal').modal('hide');
                    toastr["success"](obj.message, "Success:");
                    
                    $.get('grades.php', function(data) {
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

    $('#addPackages').on('click', function(){
        $('#packagesModal').find('#id').val("");
        $('#packagesModal').find('#code').val("");
        $('#packagesModal').find('#market').val("");
        $('#packagesModal').find('#packages').val("");
        $('#packagesModal').modal('show');
        
        $('#packageForm').validate({
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

    $(".add-row").click(function(){
        var size = $("#TableId").find("tr").length;

        var newLotNo = $("#newLotNo").val();
        var newGrade = $("#newGrade").val();
        var newTrayNo = $("#newTrayNo").val();
        var newTrayWeight = $("#newTrayWeight").val();
        var newGrossWeight = $("#newGrossWeight").val();
        var qty = $("#qty").val();
        var newNetWeight = $("#newNetWeight").val();
        var moistureAfGrade = $("#moistureAfGrade").val();

        var markup = "<tr><td>" + 
        newLotNo + "</td><td>" + 
        newGrade + "</td><td>" + 
        newTrayNo + "</td><td>" + 
        newTrayWeight + "</td>" +
        "<td>" + 
        newGrossWeight + "</td><td>" + 
        qty + "</td><td>" + 
        newNetWeight + "</td><td>" + 
        moistureAfGrade + "</td><td><button type='button' class='btn btn-danger' name=delete"+ size +">delete</button></td></tr>";
        $("#TableId tbody").append(markup);
    });
        
    // Find and remove selected table rows
    $("#TableId tbody").on('click', 'button[name^="delete"]', function () {
        $(this).parents("tr").remove();
    });

});

function edit(id){
    $('#spinnerLoading').show();
    $.post('php/getGrades.php', {userID: id}, function(data){
        var obj = JSON.parse(data);
        
        if(obj.status === 'success'){
            $('#packagesModal').find('#id').val(obj.message.id);
            $('#packagesModal').find('#code').val(obj.message.class);
            $('#packagesModal').find('#market').val(obj.message.market);
            $('#packagesModal').find('#packages').val(obj.message.grade);
            $('#packagesModal').modal('show');
            
            $('#packageForm').validate({
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
    $.post('php/deleteGrades.php', {userID: id}, function(data){
        var obj = JSON.parse(data);
        
        if(obj.status === 'success'){
            toastr["success"](obj.message, "Success:");
            $.get('packages.php', function(data) {
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
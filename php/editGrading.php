<?php
require_once "db_connect.php";

session_start();

if(!isset($_SESSION['userID'])){
    echo '<script type="text/javascript">';
    echo 'window.location.href = "../login.html";</script>';
}

if(isset($_POST['editLotNo'], $_POST['editBTrayNo'], $_POST['editItemType'], $_POST['editGrossWeight'], $_POST['editBTrayWeight'], $_POST['editNetWeight']
, $_POST['editQty'], $_POST['editGrade'], $_POST['editMoistureAfGrade'])){
    $editLotNo = filter_input(INPUT_POST, 'editLotNo', FILTER_SANITIZE_STRING);
    $editBTrayNo = filter_input(INPUT_POST, 'editBTrayNo', FILTER_SANITIZE_STRING);
    $editItemType = filter_input(INPUT_POST, 'editItemType', FILTER_SANITIZE_STRING);
    $editGrossWeight = filter_input(INPUT_POST, 'editGrossWeight', FILTER_SANITIZE_STRING);
    $editBTrayWeight = filter_input(INPUT_POST, 'editBTrayWeight', FILTER_SANITIZE_STRING);
    $editNetWeight = filter_input(INPUT_POST, 'editNetWeight', FILTER_SANITIZE_STRING);
    $editQty = filter_input(INPUT_POST, 'editQty', FILTER_SANITIZE_STRING);
    $editGrade = filter_input(INPUT_POST, 'editGrade', FILTER_SANITIZE_STRING);
    $editMoistureAfGrade = filter_input(INPUT_POST, 'editMoistureAfGrade', FILTER_SANITIZE_STRING);

    if($_POST['editRemark'] != null && $_POST['editRemark'] != ""){
        $editRemark = $_POST['editRemark'];
    }

    if($_POST['editId'] != null && $_POST['editId'] != ''){
        if ($update_stmt = $db->prepare("UPDATE weighing SET item_types=?, lot_no=?, tray_weight=?, tray_no=?, grading_net_weight=?, grade=?, pieces=?, grading_gross_weight=?, moisture_after_grading=?, remark=? WHERE id=?")) {
            $update_stmt->bind_param('sssssssssss',  $editItemType, $editLotNo, $editBTrayWeight, $editBTrayNo, $editNetWeight, $editGrade, $editQty, $editGrossWeight, $editMoistureAfGrade, $editRemark , $_POST['editId']);
            
            // Execute the prepared query.
            if (! $update_stmt->execute()) {
                echo json_encode(
                    array(
                        "status"=> "failed", 
                        "message"=> $update_stmt->error
                    )
                );
            }
            else{
                $update_stmt->close();
                $db->close();
                
                echo json_encode(
                    array(
                        "status"=> "success", 
                        "message"=> "Updated Successfully!!" 
                    )
                );
            }
        }
    }
}
else{
    echo json_encode(
        array(
            "status"=> "failed", 
            "message"=> "Please fill in all the fields"
        )
    );
}
?>
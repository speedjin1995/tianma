<?php
require_once "db_connect.php";

session_start();

if(!isset($_SESSION['userID'])){
    echo '<script type="text/javascript">';
    echo 'window.location.href = "../login.html";</script>';
}

if(isset($_POST['lotNo'], $_POST['bTrayNo'], $_POST['itemType'], $_POST['grossWeight'], $_POST['bTrayWeight'], $_POST['netWeight'],
$_POST['newLotNo'], $_POST['newGrade'], $_POST['newTrayNo'], $_POST['newTrayWeight'], $_POST['newGrossWeight'], $_POST['qty'], 
$_POST['newNetWeight'], $_POST['moistureAfGrade'], $_POST['parentId'], $_POST['newStatus'])){
    $parentId = filter_input(INPUT_POST, 'parentId', FILTER_SANITIZE_STRING);
    $itemType = filter_input(INPUT_POST, 'itemType', FILTER_SANITIZE_STRING);
    $grossWeight = filter_input(INPUT_POST, 'grossWeight', FILTER_SANITIZE_STRING);
    $lotNo = filter_input(INPUT_POST, 'lotNo', FILTER_SANITIZE_STRING);
    $bTrayWeight = filter_input(INPUT_POST, 'bTrayWeight', FILTER_SANITIZE_STRING);
    $bTrayNo = filter_input(INPUT_POST, 'bTrayNo', FILTER_SANITIZE_STRING);
    $netWeight = filter_input(INPUT_POST, 'netWeight', FILTER_SANITIZE_STRING);
    $newLotNo=$_POST['newLotNo'];
    $newGrade=$_POST['newGrade'];
    $newTrayNo=$_POST['newTrayNo'];
    $newTrayWeight=$_POST['newTrayWeight'];
    $newGrossWeight=$_POST['newGrossWeight'];
    $newStatus=$_POST['newStatus'];
    $newReason=$_POST['newReason'];
    $qty=$_POST['qty'];
    $newNetWeight=$_POST['newNetWeight'];
    $moistureAfGrade=$_POST['moistureAfGrade'];
    $remark = "";

    if($_POST['remark'] != null && $_POST['remark'] != ""){
        $remark = $_POST['remark'];
    }

    $success = true;

    for($i=0; $i<sizeof($newLotNo); $i++){
        if ($insert_stmt = $db->prepare("INSERT INTO weighing (item_types, gross_weight, lot_no, tray_weight, tray_no, net_weight, grade, parent_no, pieces, grading_gross_weight, grading_net_weight, moisture_after_grading, status, reasons, remark) VALUES (?, ?, ?, ?, ? ,?, ?, ?, ?, ?, ?, ?, ?, ?, ?)")) {
            $insert_stmt->bind_param('sssssssssssssss', $itemType, $grossWeight, $newLotNo[$i], $newTrayWeight[$i], $newTrayNo[$i], $netWeight, $newGrade[$i], $parentId, $qty[$i], $newGrossWeight[$i], $newNetWeight[$i], $moistureAfGrade[$i], $newStatus[$i], $newReason[$i], $remark[$i]);
            
            // Execute the prepared query.
            if (! $insert_stmt->execute()) {
                $success = false;
            }
        }
    }

    if($success){
        echo json_encode(
            array(
                "status"=> "success", 
                "message"=> "Added Successfully!!" 
            )
        );
    }
    else{
        echo json_encode(
            array(
                "status"=> "failed", 
                "message"=> "Failed to insert into database!!" 
            )
        );
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
<?php
require_once "db_connect.php";

session_start();

if(!isset($_SESSION['userID'])){
    echo '<script type="text/javascript">';
    echo 'window.location.href = "../login.html";</script>';
}

if(isset($_POST['id'], $_POST['itemType'], $_POST['grossWeight'], $_POST['lotNo'], $_POST['bTrayWeight'], $_POST['bTrayNo'], $_POST['netWeight'])){
    $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_STRING);
    $itemType = filter_input(INPUT_POST, 'itemType', FILTER_SANITIZE_STRING);
    $lotNo = filter_input(INPUT_POST, 'lotNo', FILTER_SANITIZE_STRING);
    $bTrayNo = filter_input(INPUT_POST, 'bTrayNo', FILTER_SANITIZE_STRING);
    $bTrayWeight = filter_input(INPUT_POST, 'bTrayWeight', FILTER_SANITIZE_STRING);
    $grossWeight = filter_input(INPUT_POST, 'grossWeight', FILTER_SANITIZE_STRING);
    $netWeight = filter_input(INPUT_POST, 'netWeight', FILTER_SANITIZE_STRING);
    $moistureValue = filter_input(INPUT_POST, 'moistureValue', FILTER_SANITIZE_STRING);

    if ($update_stmt = $db->prepare("UPDATE weighing SET item_types=?, gross_weight=?, lot_no=?, tray_weight=?, tray_no=?, net_weight=?, moisture_after_receiving=? WHERE id=?")) {
        $update_stmt->bind_param('ssssssss', $itemType, $grossWeight, $lotNo, $bTrayWeight, $bTrayNo, $netWeight, $moistureValue, $id);
        
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
else{
    echo json_encode(
        array(
            "status"=> "failed", 
            "message"=> "Please fill in all the fields"
        )
    );
}
?>
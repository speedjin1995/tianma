<?php
require_once "db_connect.php";

session_start();

if(!isset($_SESSION['userID'])){
    echo '<script type="text/javascript">';
    echo 'window.location.href = "../login.html";</script>';
}

if(isset($_POST['itemType'], $_POST['grossWeight'], $_POST['lotNo'], $_POST['bTrayWeight'], $_POST['bTrayNo'], $_POST['netWeight'])){
    $itemType = $_POST['itemType'];
    $grossWeight = $_POST['grossWeight'];
    $lotNo = $_POST['lotNo'];
    $bTrayWeight = $_POST['bTrayWeight'];
    $bTrayNo = $_POST['bTrayNo'];
    $netWeight = $_POST['netWeight'];
    $moistureValue = $_POST['moistureValue'];
    $success = true;

    if($_POST['id'] != null && $_POST['id'] != ''){
        if ($update_stmt = $db->prepare("UPDATE weighing SET item_types=?, gross_weight=?, lot_no=?, tray_weight=?, tray_no=?, net_weight=?, moisture_after_receiving=? WHERE id=?")) {
            $update_stmt->bind_param('ssssssss', $itemType, $grossWeight, $lotNo, $bTrayWeight, $bTrayNo, $netWeight, $moistureValue, $_POST['id']);
            
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
        for($i=0; $i<sizeof($lotNo); $i++){
            if ($insert_stmt = $db->prepare("INSERT INTO weighing (item_types, gross_weight, lot_no, tray_weight, tray_no, net_weight, moisture_after_receiving) VALUES (?, ?, ?, ?, ? ,?, ?)")) {
                $insert_stmt->bind_param('sssssss', $itemType[$i], $grossWeight[$i], $lotNo[$i], $bTrayWeight[$i], $bTrayNo[$i], $netWeight[$i], $moistureValue[$i]);
                
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
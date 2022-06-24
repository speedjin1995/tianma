<?php
require_once "db_connect.php";

session_start();

if(!isset($_SESSION['userID'])){
    echo '<script type="text/javascript">';
    echo 'window.location.href = "../login.html";</script>';
}

if(isset($_POST['vehicleNumber'], $_POST['vehicleWeight'])){
    $vehicleNumber = filter_input(INPUT_POST, 'vehicleNumber', FILTER_SANITIZE_STRING);
    $vehicleWeight = filter_input(INPUT_POST, 'vehicleWeight', FILTER_SANITIZE_STRING);

    if($_POST['id'] != null && $_POST['id'] != ''){
        if ($update_stmt = $db->prepare("UPDATE vehicles SET veh_number=?, vehicleWeight=? WHERE id=?")) {
            $update_stmt->bind_param('sss', $vehicleNumber, $vehicleWeight, $_POST['id']);
            
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
        if ($insert_stmt = $db->prepare("INSERT INTO vehicles (veh_number, vehicleWeight) VALUES (?,?)")) {
            $insert_stmt->bind_param('ss', $vehicleNumber, $vehicleWeight);
            
            // Execute the prepared query.
            if (! $insert_stmt->execute()) {
                echo json_encode(
                    array(
                        "status"=> "failed", 
                        "message"=> $insert_stmt->error
                    )
                );
            }
            else{
                $insert_stmt->close();
                $db->close();
                
                echo json_encode(
                    array(
                        "status"=> "success", 
                        "message"=> "Added Successfully!!" 
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
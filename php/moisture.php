<?php
require_once "db_connect.php";

session_start();

if(!isset($_SESSION['userID'])){
    echo '<script type="text/javascript">';
    echo 'window.location.href = "../login.html";</script>';
}

if(isset($_POST['grossWeight'], $_POST['netWeight'], $_POST['stockOutMoisture'])){
    $grossWeight = filter_input(INPUT_POST, 'grossWeight', FILTER_SANITIZE_STRING);
    $netWeight = filter_input(INPUT_POST, 'netWeight', FILTER_SANITIZE_STRING);
    $stockOutMoisture = filter_input(INPUT_POST, 'stockOutMoisture', FILTER_SANITIZE_STRING);

    if($_POST['id'] != null && $_POST['id'] != ''){
        if ($update_stmt = $db->prepare("UPDATE weighing SET moisture_gross_weight=?, moisture_net_weight=?, moisture_after_moisturing=? WHERE id=?")) {
            $update_stmt->bind_param('ssss', $grossWeight, $netWeight, $stockOutMoisture, $_POST['id']);
            
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
                "message"=> "No ID for records" 
            );
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
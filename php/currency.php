<?php
require_once "db_connect.php";

session_start();

if(!isset($_SESSION['userID'])){
    echo '<script type="text/javascript">';
    echo 'window.location.href = "../login.html";</script>';
}

if(isset($_POST['units'], $_POST['desc'], $_POST['rate'])){
    $lotsNumber = filter_input(INPUT_POST, 'units', FILTER_SANITIZE_STRING);
    $desc = filter_input(INPUT_POST, 'desc', FILTER_SANITIZE_STRING);
    $rate = filter_input(INPUT_POST, 'rate', FILTER_SANITIZE_STRING);

    if($_POST['id'] != null && $_POST['id'] != ''){
        if ($update_stmt = $db->prepare("UPDATE currency SET currency=?, description=?, rate=? WHERE id=?")) {
            $update_stmt->bind_param('ssss', $lotsNumber, $desc, $rate, $_POST['id']);
            
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
        if ($insert_stmt = $db->prepare("INSERT INTO currency (currency, description, rate) VALUES (?, ?, ?)")) {
            $insert_stmt->bind_param('sss', $lotsNumber, $desc, $rate);
            
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
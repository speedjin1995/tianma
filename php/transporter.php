<?php
require_once "db_connect.php";

session_start();

if(!isset($_SESSION['userID'])){
    echo '<script type="text/javascript">';
    echo 'window.location.href = "../login.html";</script>';
}

if(isset($_POST['code'], $_POST['transporter'], $_POST['price'])){
    $code = filter_input(INPUT_POST, 'code', FILTER_SANITIZE_STRING);
    $transporter = filter_input(INPUT_POST, 'transporter', FILTER_SANITIZE_STRING);
    $price = filter_input(INPUT_POST, 'price', FILTER_SANITIZE_STRING);

    if($_POST['id'] != null && $_POST['id'] != ''){
        if ($update_stmt = $db->prepare("UPDATE transporters SET transporter_code=?, transporter_name=?, transporter_price=? WHERE id=?")) {
            $update_stmt->bind_param('ssss', $code, $transporter, $price, $_POST['id']);
            
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
        if ($insert_stmt = $db->prepare("INSERT INTO transporters (transporter_code, transporter_name, transporter_price) VALUES (?, ?, ?)")) {
            $insert_stmt->bind_param('sss', $code, $transporter, $price);
            
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
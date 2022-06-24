<?php
require_once "db_connect.php";

session_start();

if(!isset($_SESSION['userID'])){
    echo '<script type="text/javascript">';
    echo 'window.location.href = "../login.html";</script>';
}
else{
    $userId = $_SESSION['userID'];
}

if(isset($_POST['code'], $_POST['name'], $_POST['address'], $_POST['phone'], $_POST['email'])){
    $code = filter_input(INPUT_POST, 'code', FILTER_SANITIZE_STRING);
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
	$address = filter_input(INPUT_POST, 'address', FILTER_SANITIZE_STRING);
    $phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING);
    $role = 'SUPPLIERS';

    if(filter_var($email, FILTER_VALIDATE_EMAIL)){
        if($_POST['id'] != null && $_POST['id'] != ''){
            if ($update_stmt = $db->prepare("UPDATE customers SET customer_code=?, customer_name=?, customer_address=?, customer_phone=?, customer_email=? WHERE id=?")) {
                $update_stmt->bind_param('ssssss', $code, $name, $address, $phone, $email, $_POST['id']);
                
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
            if ($insert_stmt = $db->prepare("INSERT INTO customers (customer_code, customer_name, customer_address, customer_phone, customer_email, customer_status) VALUES (?, ?, ?, ?, ?)")) {
                $insert_stmt->bind_param('ssssss', $code, $name, $address, $phone, $email, $role);
                
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
                "message"=> "Please enter a valid email address"
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
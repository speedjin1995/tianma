<?php
require_once "db_connect.php";

session_start();

if(!isset($_SESSION['userID'])){
    echo '<script type="text/javascript">';
    echo 'window.location.href = "../login.html";</script>';
}

if(isset($_POST['status'], $_POST['prefix'])){
    $lotsNumber = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_STRING);
    $prefix = filter_input(INPUT_POST, 'prefix', FILTER_SANITIZE_STRING);

    if($_POST['id'] != null && $_POST['id'] != ''){
        if ($update_stmt = $db->prepare("UPDATE `status` SET `status`=?, `prefix`=? WHERE id=?")) {
            $update_stmt->bind_param('sss', $lotsNumber, $prefix, $_POST['id']);
            
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
        $valuePre = "1";

        if ($insert_stmt2 = $db->prepare("INSERT INTO `miscellaneous` (`name`, `value`) VALUES (?, ?)")) {
            $insert_stmt2->bind_param('ss', $lotsNumber, $valuePre);

            if (! $insert_stmt2->execute()) {
                echo json_encode(
                    array(
                        "status"=> "failed", 
                        "message"=> $insert_stmt2->error
                    )
                );
            }
            else{
                $last_id = mysqli_insert_id($db);

                if ($insert_stmt = $db->prepare("INSERT INTO `status` (`status`, `prefix`, `misc_id`) VALUES (?, ?, ?)")) {
                    $insert_stmt->bind_param('sss', $lotsNumber, $prefix, $last_id);
                    
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
                else{
                    echo json_encode(
                        array(
                            "status"=> "failed", 
                            "message"=> "Something goes wrong when create status"
                        )
                    );
                }
            }
        }
        else{
            echo json_encode(
                array(
                    "status"=> "failed", 
                    "message"=> "Something goes wrong when create prefixes"
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
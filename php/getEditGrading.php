<?php
require_once "db_connect.php";

session_start();

if(isset($_POST['userID'])){
    $id = filter_input(INPUT_POST, 'userID', FILTER_SANITIZE_STRING);

    if ($update_stmt = $db->prepare("SELECT * FROM weighing WHERE id=?")) {
        $update_stmt->bind_param('s', $id);

        // Execute the prepared query.
        if (! $update_stmt->execute()) {
            echo json_encode(
                array(
                    "status" => "failed",
                    "message" => "Something went wrong"
                )); 
        }
        else{
            $result = $update_stmt->get_result();
            $message = array();
            
            while ($row = $result->fetch_assoc()) {
                $message['id'] = $row['id'];
                $message['parent_no'] = $row['parent_no'];
                $message['itemType'] = $row['item_types'];
                $message['grossWeight'] = $row['grading_gross_weight'];
                $message['lotNo'] = $row['lot_no'];
                $message['tray_weight'] = $row['tray_weight'];
                $message['bTrayNo'] = $row['tray_no'];
                $message['netWeight'] = $row['grading_net_weight'];
                $message['pieces'] = $row['pieces'];
                $message['grade'] = $row['grade'];
                $message['moisture_after_grading'] = $row['moisture_after_grading'];
                $message['remark'] = $row['remark'];
            }
            
            echo json_encode(
                array(
                    "status" => "success",
                    "message" => $message
                ));   
        }
    }
}
else{
    echo json_encode(
        array(
            "status" => "failed",
            "message" => "Missing Attribute"
        )
    ); 
}
?>
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
                $message['itemType'] = $row['item_types'];
                $message['moisture_gross_weight'] = $row['moisture_gross_weight'];
                $message['lotNo'] = $row['lot_no'];
                $message['bTrayWeight'] = $row['tray_weight'];
                $message['bTrayNo'] = $row['tray_no'];
                $message['pieces'] = $row['pieces'];
                $message['moisture_net_weight'] = $row['moisture_net_weight'];
                $message['moisture_after_moisturing'] = $row['moisture_after_moisturing'];
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
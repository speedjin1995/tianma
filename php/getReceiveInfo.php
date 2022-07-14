<?php
require_once "db_connect.php";

session_start();

if(isset($_POST['lotNum'], $_POST['trayNo'])){
	$lotNum = filter_input(INPUT_POST, 'lotNum', FILTER_SANITIZE_STRING);
    $trayNo = filter_input(INPUT_POST, 'trayNo', FILTER_SANITIZE_STRING);

    if ($update_stmt = $db->prepare("SELECT * FROM weighing WHERE lot_no=? AND tray_no=?")) {
        $update_stmt->bind_param('ss', $lotNum, $trayNo);
        
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
                $message['grossWeight'] = $row['gross_weight'];
                $message['lotNo'] = $row['lot_no'];
                $message['bTrayWeight'] = $row['tray_weight'];
                $message['bTrayNo'] = $row['tray_no'];
                $message['netWeight'] = $row['net_weight'];
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
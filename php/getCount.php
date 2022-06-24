<?php
require_once "db_connect.php";

session_start();

if(isset($_POST['userID'])){
	$id = filter_input(INPUT_POST, 'userID', FILTER_SANITIZE_STRING);
    $del = "1";

    if ($update_stmt = $db->prepare("SELECT * FROM count WHERE id=?")) {
        $update_stmt->bind_param('s', $del);
        
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
                $message['serialNo'] = $row['serialNo'];
                $message['vehicleNo'] = $row['vehicleNo'];
                $message['lotNo'] = $row['lotNo'];
                $message['batchNo'] = $row['batchNo'];
                $message['invoiceNo'] = $row['invoiceNo'];
                $message['purchaseNo'] = $row['purchaseNo'];
                $message['deliveryNo'] = $row['deliveryNo'];
                $message['customer'] = $row['customer'];
                $message['productName'] = $row['productName'];
                $message['package'] = $row['package'];
                $message['unitWeight'] = $row['unitWeight'];
                $message['unitWeight1'] = $row['unitWeight1'];
                $message['tare'] = $row['tare'];
                // $message['totalWeight'] = $row['totalWeight'];
                $message['actualWeight'] = $row['actualWeight'];
                $message['unit'] = $row['unit'];
                $message['moq'] = $row['moq'];
                $message['dateTime'] = $row['dateTime'];
                $message['unitPrice'] = $row['unitPrice'];
                $message['totalPrice'] = $row['totalPrice'];
                $message['remark'] = $row['remark'];
                $message['status'] = $row['status'];
                $message['totalPCS'] = $row['totalPCS'];
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
            )); 
}
?>
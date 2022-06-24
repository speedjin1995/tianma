<?php
require_once "db_connect.php";

session_start();

if(isset($_POST['userID'])){
	$id = filter_input(INPUT_POST, 'userID', FILTER_SANITIZE_STRING);

    if ($update_stmt = $db->prepare("SELECT * FROM weight WHERE id=?")) {
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
                if($row['dateTime'] == null || $row['dateTime'] == ''){
                    $dateTime = '-';
                }
                else{
                    $convertDate = new DateTime($row['dateTime']);
                    $dateTime = date_format($convertDate,"d/m/Y H:i:s A");
                }

                $message['id'] = $row['id'];
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
                $message['tare'] = $row['tare'];
                $message['totalWeight'] = $row['totalWeight'];
                $message['actualWeight'] = $row['actualWeight'];
                $message['supplyWeight'] = $row['supplyWeight'];
                $message['varianceWeight'] = $row['varianceWeight'];
                $message['currentWeight'] = $row['currentWeight'];
                $message['unit'] = $row['unit'];
                $message['moq'] = $row['moq'];
                $message['dateTime'] = $dateTime;
                $message['unitPrice'] = $row['unitPrice'];
                $message['totalPrice'] = $row['totalPrice'];
                $message['remark'] = $row['remark'];
                $message['status'] = $row['status'];
                $message['manual'] = $row['manual'];
                $message['manualOutgoing'] = $row['manualOutgoing'];
                $message['manualVehicle'] = $row['manualVehicle'];
                $message['reduceWeight'] = $row['reduceWeight'];
                $message['outGDateTime'] = $row['outGDateTime'];
                $message['inCDateTime'] = $row['inCDateTime'];
                $message['pStatus'] = $row['pStatus'];
                $message['variancePerc'] = $row['variancePerc'];
                $message['transporter'] = $row['transporter'];

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
<?php
require_once 'db_connect.php';
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

session_start();

if(isset($_POST['status'], $_POST['unitWeight'], $_POST['moq'], $_POST['tareWeight'], $_POST['currentWeight'], $_POST['dateTime']
,$_POST['product'],$_POST['package'],$_POST['unitPrice'],$_POST['actualWeight'],$_POST['totalPrice'],$_POST['totalWeight']
,$_POST['supplyWeight'], $_POST['varianceWeight'], $_POST['reduceWeight'] ,$_POST['outGDateTime'], $_POST['inCDateTime'])){

	$userId = $_SESSION['userID'];
	$status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_STRING);
	$manualVehicle = filter_input(INPUT_POST, 'manualVehicle', FILTER_SANITIZE_STRING);
	$lotNo = '-';
	$vehicleNo = '-';
	$invoiceNo = '-';
	$deliveryNo = '-';
	$batchNo = '-';
	$purchaseNo = '-';
	$customerNo = '-';
	$remark = '';
	$transporter = '-';
	$manual = filter_input(INPUT_POST, 'manual', FILTER_SANITIZE_STRING);
	$manualOutgoing = filter_input(INPUT_POST, 'manualOutgoing', FILTER_SANITIZE_STRING);

	if($_POST['lotNo'] != null && $_POST['lotNo'] != ''){
		$lotNo = filter_input(INPUT_POST, 'lotNo', FILTER_SANITIZE_STRING);
	}

	if($_POST['transporter'] != null && $_POST['transporter'] != ''){
		$transporter = filter_input(INPUT_POST, 'transporter', FILTER_SANITIZE_STRING);
	}

	if($_POST['invoiceNo'] != null && $_POST['invoiceNo'] != ''){
		$invoiceNo = filter_input(INPUT_POST, 'invoiceNo', FILTER_SANITIZE_STRING);
	}

	if($_POST['deliveryNo'] != null && $_POST['deliveryNo'] != ''){
		$deliveryNo = filter_input(INPUT_POST, 'deliveryNo', FILTER_SANITIZE_STRING);
	}

	if($_POST['batchNo'] != null && $_POST['batchNo'] != ''){
		$batchNo = filter_input(INPUT_POST, 'batchNo', FILTER_SANITIZE_STRING);
	}

	if($_POST['purchaseNo'] != null && $_POST['purchaseNo'] != ''){
		$purchaseNo = filter_input(INPUT_POST, 'purchaseNo', FILTER_SANITIZE_STRING);
	}

	if($_POST['remark'] != null && $_POST['remark'] != ''){
		$remark = filter_input(INPUT_POST, 'remark', FILTER_SANITIZE_STRING);
	}
	
	if($manual != null && $manual != ''){
	    $manual = "1";
	}else{
		$manual = "0";
	}

	if($manualOutgoing != null && $manualOutgoing != ''){
	    $manualOutgoing = "1";
	}else{
		$manualOutgoing = "0";
	}

	if($manualVehicle != null && $manualVehicle != ''){
		$manualVehicle == "1";
		if($_POST['vehicleNoTxt'] != null && $_POST['vehicleNoTxt'] != ''){
			$vehicleNo = filter_input(INPUT_POST, 'vehicleNoTxt', FILTER_SANITIZE_STRING);
		}
	}else{
		$manualVehicle = "0";
	    if($_POST['vehicleNo'] != null && $_POST['vehicleNo'] != ''){
			$vehicleNo = filter_input(INPUT_POST, 'vehicleNo', FILTER_SANITIZE_STRING);
		}
	}

	if($status != '1' && $status != '2'){
		$customerNo = filter_input(INPUT_POST, 'customerNoTxt', FILTER_SANITIZE_STRING);
	}
	else{
		$customerNo = filter_input(INPUT_POST, 'customerNo', FILTER_SANITIZE_STRING);
	}
	
	$unitWeight = filter_input(INPUT_POST, 'unitWeight', FILTER_SANITIZE_STRING);
	$currentWeight = filter_input(INPUT_POST, 'currentWeight', FILTER_SANITIZE_STRING);
	$supplyWeight = filter_input(INPUT_POST, 'supplyWeight', FILTER_SANITIZE_STRING);
	$varianceWeight = filter_input(INPUT_POST, 'varianceWeight', FILTER_SANITIZE_STRING);
	$variancePerc = filter_input(INPUT_POST, 'variancePerc', FILTER_SANITIZE_STRING);
	$product = filter_input(INPUT_POST, 'product', FILTER_SANITIZE_STRING);
	$moq = filter_input(INPUT_POST, 'moq', FILTER_SANITIZE_STRING);
	$tareWeight = filter_input(INPUT_POST, 'tareWeight', FILTER_SANITIZE_STRING);
	$package = filter_input(INPUT_POST, 'package', FILTER_SANITIZE_STRING);
	$unitPrice = filter_input(INPUT_POST, 'unitPrice', FILTER_SANITIZE_STRING);
	$actualWeight = filter_input(INPUT_POST, 'actualWeight', FILTER_SANITIZE_STRING);
	$totalPrice = filter_input(INPUT_POST, 'totalPrice', FILTER_SANITIZE_STRING);
	$totalWeight = filter_input(INPUT_POST, 'totalWeight', FILTER_SANITIZE_STRING);
	$date = new DateTime($_POST['dateTime']);
	$dateTime = date_format($date,"Y-m-d H:i:s");
	$reduceWeight = filter_input(INPUT_POST, 'reduceWeight', FILTER_SANITIZE_STRING);
	
	if($_POST['inCDateTime'] != null && $_POST['inCDateTime'] != ''){
	    $inDate = new DateTime($_POST['inCDateTime']);
		$inCDateTime = date_format($inDate,"Y-m-d H:i:s");
		$pStatus = "Pending";
	}

	if($_POST['outGDateTime'] != null && $_POST['outGDateTime'] != ''){
		$outDate = new DateTime($_POST['outGDateTime']);
		$outGDateTime = date_format($outDate,"Y-m-d H:i:s");
		$pStatus = "Complete";
	}

	if($_POST['id'] != null && $_POST['id'] != ''){
		if ($update_stmt = $db->prepare("UPDATE weight SET vehicleNo=?, lotNo=?, batchNo=?, invoiceNo=?, deliveryNo=?, purchaseNo=?, customer=?, productName=?, package=?
		, unitWeight=?, currentWeight=?, tare=?, totalWeight=?, actualWeight=?, moq=?, unitPrice=?, totalPrice=?, remark=?, supplyWeight=?, varianceWeight=?, status=?, 
		dateTime=?, manual=?, manualVehicle=?, manualOutgoing=?, reduceWeight=?, outGDateTime=?, inCDateTime=?, pStatus=?, variancePerc=?, transporter=?, updated_by=? WHERE id=?")){
			$update_stmt->bind_param('sssssssssssssssssssssssssssssssss', $vehicleNo, $lotNo, $batchNo, $invoiceNo, $deliveryNo, $purchaseNo, $customerNo, $product,
			$package, $unitWeight, $currentWeight, $tareWeight, $totalWeight, $actualWeight, $moq, $unitPrice, $totalPrice, $remark, $supplyWeight, $varianceWeight, 
			$status, $dateTime, $manual, $manualVehicle, $manualOutgoing, $reduceWeight, $outGDateTime, $inCDateTime, $pStatus, $variancePerc, $transporter, $userId, $_POST['id']);
		
			// Execute the prepared query.
			if (! $update_stmt->execute()){
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
		else{
			echo json_encode(
				array(
					"status"=> "failed", 
					"message"=> $insert_stmt->error
				)
			);
		}
	}
	else{
		$x=$_POST['status'];

		if($update_stmt2 = $db->prepare("SELECT * FROM status WHERE id=?")){
			$update_stmt2->bind_param('s', $status);

			if (! $update_stmt2->execute()) {
          echo json_encode(
              array(
                  "status" => "failed",
                  "message" => "Something went wrong when pulling status"
              )); 
      }
      else{
				$result2 = $update_stmt2->get_result();
				$id=$_POST['status'];
				$firstChar = "";

				if ($row2 = $result2->fetch_assoc()) {
					$id = $row2['misc_id'];
					$firstChar = $row2['prefix'];
				}

				if ($update_stmt = $db->prepare("SELECT * FROM miscellaneous WHERE id=?")) {
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
							$charSize = strlen($row['value']);
							$misValue = $row['value'];
		
							for($i=0; $i<(5-(int)$charSize); $i++){
								$firstChar.='0';  // S0000
							}
					
							$firstChar.=$misValue;  //S00009
		
							if ($insert_stmt = $db->prepare("INSERT INTO weight (serialNo, vehicleNo, lotNo, batchNo, invoiceNo, deliveryNo, purchaseNo, 
							customer, productName, package, unitWeight, tare, totalWeight, actualWeight, currentWeight, moq, unitPrice, totalPrice, remark, 
							status, dateTime, manual, manualVehicle, supplyWeight, varianceWeight, manualOutgoing, reduceWeight, outGDateTime, inCDateTime, 
							pStatus, variancePerc, transporter, created_by) 
							VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)")){
								$insert_stmt->bind_param('sssssssssssssssssssssssssssssssss', $firstChar, $vehicleNo, $lotNo, $batchNo, $invoiceNo, $deliveryNo, 
								$purchaseNo, $customerNo, $product, $package, $unitWeight, $tareWeight, $totalWeight, $actualWeight, $currentWeight, $moq, 
								$unitPrice, $totalPrice, $remark, $status, $dateTime, $manual, $manualVehicle, $supplyWeight, $varianceWeight, 
								$manualOutgoing, $reduceWeight, $outGDateTime, $inCDateTime, $pStatus, $variancePerc, $transporter, $userId);
								
								// Execute the prepared query.
								if (! $insert_stmt->execute()){
									echo json_encode(
										array(
											"status"=> "failed", 
											"message"=> $insert_stmt->error
										)
									);
								} 
								else{
									$misValue++;
									///insert miscellaneous
									if ($update_stmt = $db->prepare("UPDATE miscellaneous SET value=? WHERE id=?")){
										$update_stmt->bind_param('ss', $misValue, $id);
										
										// Execute the prepared query.
										if (! $update_stmt->execute()){
							
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
													"message"=> "Added Successfully!!" 
												)
											);
							
										}
									} else{
							
										echo json_encode(
											array(
												"status"=> "failed", 
												"message"=> $update_stmt->error
											)
										);
									}
								}
							}
						}
					}
				}
			}
		}
		else{
			echo json_encode(
				array(
					"status"=> "failed", 
					"message"=> "Error when pulling status"
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
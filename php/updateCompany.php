<?php
require_once 'db_connect.php';

if(isset($_POST['name'], $_POST['address'])){
	$name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
	$address = filter_input(INPUT_POST, 'address', FILTER_SANITIZE_STRING);
	$phone = null;
	$email = null;
	$id = '1';

	if($_POST['phone'] != null && $_POST['phone'] != ""){
		$phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_STRING);
	}
	
	if($_POST['email'] != null && $_POST['email'] != ""){
		$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING);
	}

	if ($stmt2 = $db->prepare("UPDATE companies SET name=?, address=?, phone=?, email=? WHERE id=?")) {
		$stmt2->bind_param('sssss', $name, $address, $phone, $email, $id);
		
		if($stmt2->execute()){
			$stmt2->close();
			$db->close();
			
			echo json_encode(
				array(
					"status"=> "success", 
					"message"=> "Your company profile is updated successfully!" 
				)
			);
		} else{
			echo json_encode(
				array(
					"status"=> "failed", 
					"message"=> $stmt->error
				)
			);
		}
	} 
	else{
		echo json_encode(
			array(
				"status"=> "failed", 
				"message"=> "Something went wrong!"
			)
		);
	}
} 
else{
	echo json_encode(
        array(
            "status"=> "failed", 
            "message"=> "Please fill in all fields"
        )
    ); 
}
?>

<?php
require_once 'db_connect.php';

session_start();

if(!isset($_SESSION['userID'])){
	echo '<script type="text/javascript">location.href = "../login.html";</script>'; 
} else{
	$id = $_SESSION['userID'];
}

if(isset($_POST['serialPort'], $_POST['serialPortBaudRate'], $_POST['serialPortDataBits'], $_POST['serialPortParity'], $_POST['serialPortStopBits'])){
	$serialPort = filter_input(INPUT_POST, 'serialPort', FILTER_SANITIZE_STRING);
	$serialPortBaudRate = filter_input(INPUT_POST, 'serialPortBaudRate', FILTER_SANITIZE_STRING);
	$serialPortDataBits = filter_input(INPUT_POST, 'serialPortDataBits', FILTER_SANITIZE_STRING);
	$serialPortParity = filter_input(INPUT_POST, 'serialPortParity', FILTER_SANITIZE_STRING);
	$serialPortStopBits = filter_input(INPUT_POST, 'serialPortStopBits', FILTER_SANITIZE_STRING);
	
	if ($stmt2 = $db->prepare("UPDATE users SET port=?, baudrate=?, databits=?, parity=?, stopbits=? WHERE id=?")) {
		$stmt2->bind_param('ssssss', $serialPort, $serialPortBaudRate, $serialPortDataBits, $serialPortParity, $serialPortStopBits, $id);
		
		if($stmt2->execute()){
			$stmt2->close();
			$db->close();
			
			echo json_encode(
				array(
					"status"=> "success", 
					"message"=> "Your port setup is updated successfully!" 
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

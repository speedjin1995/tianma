<?php
require_once 'db_connect.php';

session_start();

if(!isset($_SESSION['userID'])){
	echo '<script type="text/javascript">location.href = "../login.html";</script>'; 
}

if(isset($_POST['userID'])){
	$id = filter_input(INPUT_POST, 'userID', FILTER_SANITIZE_STRING);
	$userId = $_SESSION['userID'];
	$name = $_SESSION['name'];
	$del = "1";
	$trayNo = '';

	$empQuery = "select * FROM weighing WHERE id=".$id.";";
	$empRecords = mysqli_query($db, $empQuery);
	while($row = mysqli_fetch_assoc($empRecords)) {
		$trayNo = $row['tray_no'];
	}

	if ($stmt2 = $db->prepare("UPDATE grades SET deleted=? WHERE id=?")) {
		$stmt2->bind_param('ss', $del , $id);
		
		if($stmt2->execute()){

			$action = "User : ".$name." Deleted Tray No : ".$trayNo." in Grades table!";

			if ($log_delete_stmt = $db->prepare("INSERT INTO log (userId, action) VALUES (?, ?)")) {
				$log_delete_stmt->bind_param('ss', $userId, $action);
			

				if (! $log_delete_stmt->execute()) {

				}
				else{

					$log_delete_stmt->close();
					
				}
			}

			$stmt2->close();
			$db->close();
			
			echo json_encode(
    	        array(
    	            "status"=> "success", 
    	            "message"=> "Deleted"
    	        )
    	    );
		} else{
		    echo json_encode(
    	        array(
    	            "status"=> "failed", 
    	            "message"=> $stmt2->error
    	        )
    	    );
		}
	} 
	else{
	    echo json_encode(
	        array(
	            "status"=> "failed", 
	            "message"=> "Somthings wrong"
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

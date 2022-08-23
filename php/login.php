<?php
require_once 'db_connect.php';

session_start();

$username=$_POST['userEmail'];
$password=$_POST['userPassword'];
$now = date("Y-m-d H:i:s");

$stmt = $db->prepare("SELECT * from users where username= ?");
$stmt->bind_param('s', $username);
$stmt->execute();
$result = $stmt->get_result();

if(($row = $result->fetch_assoc()) !== null){
	$password = hash('sha512', $password . $row['salt']);
	
	if($password == $row['password']){
		$_SESSION['userID']=$row['id'];
		$_SESSION['name']=$row['name'];
		$stmt->close();
		$db->close();
		
		echo '<script type="text/javascript">';
		echo 'window.location.href = "../index.php";</script>';
	} 
	else{
		echo '<script type="text/javascript">alert("Login unsuccessful, password or username is not matched");';
		echo 'window.location.href = "../login.html";</script>';
	}
	
} 
else{
	 echo '<script type="text/javascript">alert("Login unsuccessful, password or username is not matched");';
	 echo 'window.location.href = "../login.html";</script>';
}
?>

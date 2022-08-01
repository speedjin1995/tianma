<?php
require_once 'php/db_connect.php';

session_start();

if(!isset($_SESSION['userID'])){
    echo '<script type="text/javascript">';
	echo 'window.location.href = "../login.html";</script>';
}
else{
    /*$stmt = $db->prepare("SELECT users.id, users.name, users.email, users.joined_date, users.expired_date, users.status, roles.role_name from users, roles WHERE users.role_code = roles.role_code");
    $stmt->execute();
    $result = $stmt->get_result();*/
    
    $stmt2 = $db->prepare("SELECT * FROM roles");
    $stmt2->execute();
    $result2 = $stmt2->get_result();
}
?>
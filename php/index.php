<?php
session_start();

if(!isset($_SESSION['userID'])){
    echo '<script type="text/javascript">';
    echo 'window.location.href = "../login.html";</script>';
}
else{
    echo '<script type="text/javascript">';
    echo 'window.location.href = "../index.php";</script>';
}
?>
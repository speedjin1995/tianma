<?php
require_once "db_connect.php";
include 'phpqrcode/qrlib.php';

session_start();

if(!isset($_SESSION['userID'])){
    echo '<script type="text/javascript">';
    echo 'window.location.href = "../login.html";</script>';
}

if(isset($_POST['lotNo'], $_POST['bTrayNo'], $_POST['itemType'],
$_POST['newLotNo'], $_POST['newGrade'], $_POST['newTrayNo'], $_POST['newTrayWeight'], $_POST['newGrossWeight'], $_POST['qty'], 
$_POST['newNetWeight'], $_POST['moistureAfGrade'], $_POST['parentId'], $_POST['newStatus'])){
    $parentId = filter_input(INPUT_POST, 'parentId', FILTER_SANITIZE_STRING);
    $itemType = filter_input(INPUT_POST, 'itemType', FILTER_SANITIZE_STRING);
    $grossWeight = filter_input(INPUT_POST, 'grossWeight', FILTER_SANITIZE_STRING);
    $lotNo = filter_input(INPUT_POST, 'lotNo', FILTER_SANITIZE_STRING);
    $bTrayWeight = filter_input(INPUT_POST, 'bTrayWeight', FILTER_SANITIZE_STRING);
    $bTrayNo = filter_input(INPUT_POST, 'bTrayNo', FILTER_SANITIZE_STRING);
    $netWeight = filter_input(INPUT_POST, 'netWeight', FILTER_SANITIZE_STRING);
    $newLotNo=$_POST['newLotNo'];
    $newGrade=$_POST['newGrade'];
    $newTrayNo=$_POST['newTrayNo'];
    $newTrayWeight=$_POST['newTrayWeight'];
    $newGrossWeight=$_POST['newGrossWeight'];
    $newStatus=$_POST['newStatus'];
    $newReason=$_POST['newReason'];
    $newRemark=$_POST['remark'];
    $qty=$_POST['qty'];
    $newNetWeight=$_POST['newNetWeight'];
    $moistureAfGrade=$_POST['moistureAfGrade'];
    //$remark = "";
    $success = true;
    $userID = $_SESSION['userID'];
    $name = $_SESSION['name'];
    $gradingDateTime = date("Y-m-d H:i:s");

    if($_POST['id'] != null && $_POST['id'] != ''){
        if ($update_stmt = $db->prepare("UPDATE weighing SET item_types=?, lot_no=?, tray_weight=?, tray_no=?, grading_net_weight=?, grade, pieces, grading_gross_weight, grading_net_weight, moisture_after_grading=? WHERE id=?")) {
            $update_stmt->bind_param('ssssssss', $itemType, $grossWeight, $lotNo, $bTrayWeight, $bTrayNo, $netWeight, $moistureValue, $_POST['id']);
            
            // Execute the prepared query.
            if (! $update_stmt->execute()) {
                echo json_encode(
                    array(
                        "status"=> "failed", 
                        "message"=> $update_stmt->error
                    )
                );
            }
            else{

                $action = "User : ".$name."Update Tray No : ".$bTrayNo." in grades table!";

                if ($log_insert_stmt = $db->prepare("INSERT INTO log (userId , userName, action) VALUES (?, ?, ?)")) {
                    $log_insert_stmt->bind_param('sss', $userID, $name, $action);
                

                    if (! $log_insert_stmt->execute()) {
                        echo json_encode(
                            array(
                                "status"=> "failed", 
                                "message"=> $log_insert_stmt->error 
                            )
                        );
                    }
                    else{
                        $log_insert_stmt->close();
                    }
                }

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
    }
    else{
        $count = 0;
        $message = '<html>
        <head>
            <style>
                @media print {
                    @page {
                        margin-left: 0.5in;
                        margin-right: 0.5in;
                        margin-top: 0.1in;
                        margin-bottom: 0.1in;
                    }
                    
                } 
                        
                table {
                    width: 100%;
                    border-collapse: collapse;
                    
                } 
                
                .table th, .table td {
                    padding: 0.70rem;
                    vertical-align: top;
                    border-top: 1px solid #dee2e6;
                    
                } 
                
                .table-bordered {
                    border: 1px solid #000000;
                    
                } 
                
                .table-bordered th, .table-bordered td {
                    border: 1px solid #000000;
                    font-family: sans-serif;
                    font-size: 12px;
                    
                } 
                
                .row {
                    display: flex;
                    flex-wrap: wrap;
                    margin-top: 20px;
                    margin-right: -15px;
                    margin-left: -15px;
                    
                } 
                
                .col-md-4{
                    position: relative;
                    width: 33.333333%;
                }
                
                .center {
                    display: block;
                    margin-left: auto;
                    margin-right: auto;
                }
            </style>
        </head>
        <body>';

        for($i=0; $i<sizeof($newLotNo); $i++){
            if($newLotNo[$i] != null){
                $reason = null;

                if($newReason[$i] != null && $newReason[$i] != ""){
                    $reason = $newReason[$i];
                }

                if ($insert_stmt = $db->prepare("INSERT INTO weighing (item_types, gross_weight, lot_no, tray_weight, tray_no, net_weight, grade, parent_no, pieces, grading_gross_weight, grading_net_weight, moisture_after_grading, status, reasons, remark, grading_datetime) VALUES (?, ?, ?, ?, ? ,?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)")) {
                    $insert_stmt->bind_param('ssssssssssssssss', $itemType, $newGrossWeight[$i], $newLotNo[$i], $newTrayWeight[$i], $newTrayNo[$i], $newNetWeight[$i], $newGrade[$i], $parentId, $qty[$i], $newGrossWeight[$i], $newNetWeight[$i], $moistureAfGrade[$i], $newStatus[$i], $reason, $newRemark[$i], $gradingDateTime);
                    
                    // Execute the prepared query.
                    if (! $insert_stmt->execute()) {
                        $success = false;
                    }
                    else{

                        $action = "User : ".$name." Add new Lot No : ".$newLotNo[$i]." And Tray No : ".$newTrayNo[$i]." in grades table!";

                        if ($log_insert_stmt = $db->prepare("INSERT INTO log (userId, userName, action) VALUES (?, ?, ?)")) {
                            $log_insert_stmt->bind_param('sss', $userID, $name, $action);
                            
                            if (!$log_insert_stmt->execute()) {
                                echo json_encode(
                                    array(
                                        "status"=> "failed", 
                                        "message"=> $log_insert_stmt->error 
                                    )
                                );
                            }
                            else{
                                $log_insert_stmt->close();
                            }
                        }

                        if($count > 0){
                            $message .= '<p style="page-break-after:always;"></p>';
                        }

                        $text = "php/qrprotrait.php?id=".$insert_stmt->insert_id;;
                        $path = 'receivesLabel/';
                        $file = $path.uniqid().".png";
                        
                        // $ecc stores error correction capability('L')
                        $ecc = 'L';
                        $pixel_Size = 10;
                        $frame_Size = 10;
                        
                        // Generates QR Code and Stores it in directory given
                        QRcode::png($text, $file, $ecc, $pixel_Size, $frame_Size);

                        if ($update_stmt = $db->prepare("SELECT * FROM grades WHERE id=?")) {
                            $update_stmt->bind_param('s', $newGrade[$i]);
                            
                            // Execute the prepared query.
                            if ($update_stmt->execute()) {
                                $result = $update_stmt->get_result();

                                if($row = $result->fetch_assoc()){
                                    $message = '<table style="width: 100%;height: 100px;">
                                    <tr>
                                        <td>
                                            <h2 style="text-align: center;">Grading Labels 分级标签</h2>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: center;">
                                            <img src="https://speedjin.com/tianma/php/'.$file.'" heigth="auto" width="50%" class="center"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <table class="table-bordered" style="width:100%">
                                                <tr>
                                                    <td>
                                                        <p style="font-size: 12px;">Lot No <br>批号</p>
                                                    </td>
                                                    <td>
                                                        <p style="font-size: 12px;">'.$newLotNo[$i].'</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <p style="font-size: 12px;">Box/tray no<br>桶/托盘代号</p>
                                                    </td>
                                                    <td>
                                                        <p style="font-size: 12px;">'.str_replace(array($newLotNo[$i]), "", $newTrayNo[$i]).'</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <p style="font-size: 12px;">Grade <br>等级</p>
                                                    </td>
                                                    <td>
                                                        <p style="font-size: 12px;">'.$newGrade[$i].'</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <p style="font-size: 12px;">Grading Gross weight<br>分级毛重,g</p>
                                                    </td>
                                                    <td>
                                                        <p style="font-size: 12px;">'.$newGrossWeight[$i].'</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <p style="font-size: 12px;">Box/tray weight,g<br>桶/托盘重量</p>
                                                    </td>
                                                    <td>
                                                        <p style="font-size: 12px;">'.$newTrayWeight[$i].'</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <p style="font-size: 12px;">Grading Net weight<br>分级净重,g</p>
                                                    </td>
                                                    <td>
                                                        <p style="font-size: 12px;">'.$newNetWeight[$i].'</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <p style="font-size: 12px;">Qty <br>片数(pcs)</p>
                                                    </td>
                                                    <td>
                                                        <p style="font-size: 12px;">'.$qty[$i].'</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <p style="font-size: 12px;">Moisture after grading<br>分级后湿度(%)</p>
                                                    </td>
                                                    <td>
                                                        <p style="font-size: 12px;">'.$moistureAfGrade[$i].'</p>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>';

                    $count++;
                                }
                            }
                        }
                    }
                }
            }
        }

        if($success){

            $message .= '</body></html>';

            echo json_encode(
                array(
                    "status"=> "success", 
                    "message"=> "Added Successfully!!",
                    "label"=> $message
                )
            );
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
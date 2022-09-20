<?php
require_once "db_connect.php";
include 'phpqrcode/qrlib.php';

session_start();

if(!isset($_SESSION['userID'])){
    echo '<script type="text/javascript">';
    echo 'window.location.href = "../login.html";</script>';
}

if(isset($_POST['itemType'], $_POST['grossWeight'], $_POST['lotNo'], $_POST['bTrayWeight'], $_POST['bTrayNo'], $_POST['netWeight'])){
    $itemType = $_POST['itemType'];
    $grossWeight = $_POST['grossWeight'];
    $lotNo = $_POST['lotNo'];
    $bTrayWeight = $_POST['bTrayWeight'];
    $bTrayNo = $_POST['bTrayNo'];
    $netWeight = $_POST['netWeight'];
    $moistureValue = $_POST['moistureValue'];
    $success = true;
    $userId = $_SESSION['userID'];
    $name = $_SESSION['name'];

    if($_POST['id'] != null && $_POST['id'] != ''){
        if ($update_stmt = $db->prepare("UPDATE weighing SET item_types=?, gross_weight=?, lot_no=?, tray_weight=?, tray_no=?, net_weight=?, moisture_after_receiving=? WHERE id=?")) {
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
                $action = "User : ".$name." Update Tray No : ".$bTrayWeight." in receives table!";

                if ($log_insert_stmt = $db->prepare("INSERT INTO log (userId, userName, action) VALUES (?, ?, ?)")) {
                    $log_insert_stmt->bind_param('sss', $userId, $name, $action);
                
    
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
        $message = "";
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
                </head><body>';

        for($i=0; $i<sizeof($lotNo); $i++){
            if ($insert_stmt = $db->prepare("INSERT INTO weighing (item_types, gross_weight, lot_no, tray_weight, tray_no, net_weight, moisture_after_receiving) VALUES (?, ?, ?, ?, ? ,?, ?)")) {
                $insert_stmt->bind_param('sssssss', $itemType[$i], $grossWeight[$i], $lotNo[$i], $bTrayWeight[$i], $bTrayNo[$i], $netWeight[$i], $moistureValue[$i]);
                
                // Execute the prepared query.
                if (! $insert_stmt->execute()) {
                    $success = false;
                }
                else{

                    $action = "User : ".$name." Add new Lot No : ".$lotNo[$i]." And Tray No : ".$bTrayNo[$i]." in receives table!";

                    if ($log_insert_stmt = $db->prepare("INSERT INTO log (userId, userName, action) VALUES (?, ?, ?)")) {
                        $log_insert_stmt->bind_param('sss', $userId, $name, $action);
                    
        
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

                    if($count > 0){
                        $message .= '<p style="page-break-after:always;"></p>';
                    }

                    $text = "php/qrprotrait.php?id=".$insert_stmt->insert_id;
                    $path = 'receivesLabel/';
                    $file = $path.uniqid().".png";
                    
                    // $ecc stores error correction capability('L')
                    $ecc = 'L';
                    $pixel_Size = 10;
                    $frame_Size = 10;
                    
                    // Generates QR Code and Stores it in directory given
                    QRcode::png($text, $file, $ecc, $pixel_Size, $frame_Size);

                    $message .= '<table style="width: 27px;height: 100px;">
                        <tr>
                            <td>
                                <h2 style="text-align: center;">Receive Labels 验收标签</h2>
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: center; width:30%;">
                                <img src="https://speedjin.com/tianma/php/'.$file.'" heigth="auto" width="50%" class="center"/>
                            </td>
                            <td style="width:70%;">
                                <table class="table-bordered" style="width:100%">
                                    <tr>
                                        <td>
                                            <p style="font-size: 14px;">Lot No <br>批号</p>
                                        </td>
                                        <td>
                                            <p style="font-size: 14px;">'.$lotNo[$i].'</p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p style="font-size: 14px;">Box/tray no<br>桶/托盘代号</p>
                                        </td>
                                        <td>
                                            <p style="font-size: 14px;">'.$bTrayNo[$i].'</p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p style="font-size: 14px;">Receive Gross weight<br>验收毛重,g</p>
                                        </td>
                                        <td>
                                            <p style="font-size: 14px;">'.$grossWeight[$i].'</p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p style="font-size: 14px;">Box/tray weight,g<br>桶/托盘重量</p>
                                        </td>
                                        <td>
                                            <p style="font-size: 14px;">'.$bTrayWeight[$i].'</p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p style="font-size: 14px;">Receive Net weight<br>验收净重,g</p>
                                        </td>
                                        <td>
                                            <p style="font-size: 14px;">'.$netWeight[$i].'</p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p style="font-size: 14px;">Receiving Moisture <br>验收湿度(%)</p>
                                        </td>
                                        <td>
                                            <p style="font-size: 14px;">'.$moistureValue[$i].'</p>
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
                    "message"=> "Failed to insert into database!!" 
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
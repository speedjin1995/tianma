<?php

require_once 'db_connect.php';
include 'phpqrcode/qrlib.php';

function filterData(&$str){ 
    $str = preg_replace("/\t/", "\\t", $str); 
    $str = preg_replace("/\r?\n/", "\\n", $str); 
    if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"'; 
}

if(isset($_POST['userID'])){
    $id = filter_input(INPUT_POST, 'userID', FILTER_SANITIZE_STRING);

    if ($select_stmt = $db->prepare("SELECT * FROM weighing WHERE id=?")) {
        $select_stmt->bind_param('s', $id);

        // Execute the prepared query.
        if (! $select_stmt->execute()) {
            echo json_encode(
                array(
                    "status" => "failed",
                    "message" => "Something went wrong"
                )); 
        }
        else{
            $result = $select_stmt->get_result();
                
            if ($row = $result->fetch_assoc()) {
                $text = "php/qrprotrait.php?id=".$id;
                $path = 'receivesLabel/';
                $file = $path.uniqid().".png";
                  
                // $ecc stores error correction capability('L')
                $ecc = 'L';
                $pixel_Size = 10;
                $frame_Size = 10;
                  
                // Generates QR Code and Stores it in directory given
                QRcode::png($text, $file, $ecc, $pixel_Size, $frame_Size);
                
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
                <body>
                    <table style="height: 100px;width: 27px;">
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
                                            <p style="font-size: 14px;">'.$row['lot_no'].'</p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p style="font-size: 14px;">Box/tray no<br>桶/托盘代号</p>
                                        </td>
                                        <td>
                                            <p style="font-size: 14px;">'.$row['tray_no'].'</p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p style="font-size: 14px;">Receive Gross weight<br>验收毛重,g</p>
                                        </td>
                                        <td>
                                            <p style="font-size: 14px;">'.$row['gross_weight'].'</p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p style="font-size: 14px;">Box/tray weight,g<br>桶/托盘重量</p>
                                        </td>
                                        <td>
                                            <p style="font-size: 14px;">'.$row['tray_weight'].'</p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p style="font-size: 14px;">Receive Net weight<br>验收净重,g</p>
                                        </td>
                                        <td>
                                            <p style="font-size: 14px;">'.$row['net_weight'].'</p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p style="font-size: 14px;">Receiving Moisture <br>验收湿度(%)</p>
                                        </td>
                                        <td>
                                            <p style="font-size: 14px;">'.$row['moisture_after_receiving'].'</p>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </body>
            </html>';

                echo json_encode(
                    array(
                        "status" => "success",
                        "message" => $message
                    )
                );
            }
            else{
                echo json_encode(
                    array(
                        "status" => "failed",
                        "message" => 'Unable to read data'
                    )
                );
            }
            
            
        }
    }
    else{
        echo json_encode(
            array(
                "status" => "failed",
                "message" => "Something Goes Wrong"
            ));
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
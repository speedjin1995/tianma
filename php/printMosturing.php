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

    if ($select_stmt = $db->prepare("select weighing.lot_no, grades.grade, weighing.tray_no, weighing.tray_weight, 
    weighing.moisture_gross_weight, weighing.pieces, weighing.moisture_net_weight, weighing.moisture_after_moisturing 
    from weighing, grades WHERE parent_no <> '0' AND weighing.grade=grades.id AND weighing.id=?")) {
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
                $path = 'moisturingLabel/';
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
                    <table style="width: 100%;height: 100px;">
                        <tr>
                            <td>
                                <h2 style="text-align: center;">Moisturing & Drying Labels 风干/加湿标签</h2>
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
                                            <p style="font-size: 12px;">Lot No 批号</p>
                                        </td>
                                        <td>
                                            <p style="font-size: 12px;">'.$row['lot_no'].'</p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p style="font-size: 12px;">Box/tray no<br>桶/托盘代号</p>
                                        </td>
                                        <td>
                                            <p style="font-size: 12px;">'.str_replace(array($row['lot_no']), "", $row['tray_no']).'</p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p style="font-size: 12px;">Grade 等级</p>
                                        </td>
                                        <td>
                                            <p style="font-size: 12px;">'.$row['grade'].'</p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p style="font-size: 12px;">Moisturise/drying<br>Gross weight<br>加湿毛重,g</p>
                                        </td>
                                        <td>
                                            <p style="font-size: 12px;">'.$row['moisture_gross_weight'].'</p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p style="font-size: 12px;">Box/tray weight,g<br>桶/托盘重量</p>
                                        </td>
                                        <td>
                                            <p style="font-size: 12px;">'.$row['tray_weight'].'</p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p style="font-size: 12px;">Moisturise/drying<br>Net weight<br>加湿净重,g</p>
                                        </td>
                                        <td>
                                            <p style="font-size: 12px;">'.$row['moisture_net_weight'].'</p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p style="font-size: 12px;">Qty 片数(pcs)</p>
                                        </td>
                                        <td>
                                            <p style="font-size: 12px;">'.$row['pieces'].'</p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p style="font-size: 12px;">Stock out Moisture<br>出库湿度(%)</p>
                                        </td>
                                        <td>
                                            <p style="font-size: 12px;">'.$row['moisture_after_moisturing'].'</p>
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
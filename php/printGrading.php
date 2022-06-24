<?php

require_once 'db_connect.php';
include 'phpqrcode/qrlib.php';

$compids = '1';
$compname = 'SYNCTRONIX TECHNOLOGY (M) SDN BHD';
$compaddress = 'No.34, Jalan Bagan 1, Taman Bagan, 13400 Butterworth. Penang. Malaysia.';
$compphone = '6043325822';
$compiemail = 'admin@synctronix.com.my';
 
// Filter the excel data 
function filterData(&$str){ 
    $str = preg_replace("/\t/", "\\t", $str); 
    $str = preg_replace("/\r?\n/", "\\n", $str); 
    if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"'; 
}

if(isset($_POST['userID'], $_POST["file"])){
    $stmt = $db->prepare("SELECT * FROM companies WHERE id=?");
    $stmt->bind_param('s', $compids);
    $stmt->execute();
    $result1 = $stmt->get_result();
    $id = filter_input(INPUT_POST, 'userID', FILTER_SANITIZE_STRING);
            
    if ($row = $result1->fetch_assoc()) {
        $compname = $row['name'];
        $compaddress = $row['address'];
        $compphone = $row['phone'];
        $compiemail = $row['email'];
    }

    if($_POST["file"] == 'weight'){
        //i remove this because both(billboard and weight) also call this print page.
        //AND weight.pStatus = 'Pending'

        if ($select_stmt = $db->prepare("select weight.id, weight.serialNo, weight.vehicleNo, weight.lotNo, weight.batchNo, weight.invoiceNo, weight.deliveryNo, users.name,
        weight.purchaseNo, weight.customer, products.product_name, packages.packages, weight.unitWeight, weight.tare, weight.totalWeight, weight.actualWeight, 
        weight.supplyWeight, weight.varianceWeight, weight.currentWeight, units.units, weight.moq, weight.dateTime, weight.unitPrice, weight.totalPrice, weight.remark, 
        weight.status as Status, status.status, weight.manual, weight.manualVehicle, weight.manualOutgoing, weight.reduceWeight, weight.outGDateTime, weight.inCDateTime, 
        weight.pStatus, weight.variancePerc, weight.transporter from weight, packages, products, units, status, users 
        WHERE weight.package = packages.id AND users.id = weight.created_by AND weight.productName = products.id AND status.id=weight.status AND 
        units.id=weight.unitWeight AND weight.deleted = '0' AND weight.id=?")) {
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
                    $ow = 0;
                    $vw = 0;
                    $cw = 0;
                    $tw = 0;
                    $ttw = 0;
                    $customer = '';
                    $customerP = '';
                    $customerA = '';
                    $customerE = '';
                    
                    if($row['unitWeight'] == '1'){
                        $ow = $row['supplyWeight'];
                        $vw = $row['varianceWeight'];
                        $cw = $row['currentWeight'];
                        $tw = $row['tare'];
                        $ttw = $row['totalWeight'];
                    }
                    else{
                        $ow = number_format(((float)$row['supplyWeight'] * 1000), 2);
                        $vw = number_format(((float)$row['varianceWeight'] * 1000), 2);
                        $cw = number_format(((float)$row['currentWeight'] * 1000), 2);
                        $tw = number_format(((float)$row['tare'] * 1000), 2);
                        $ttw = number_format(((float)$row['totalWeight'] * 1000), 2);
                    }
                    
                    if($row['Status'] != '1' && $row['Status'] != '2'){
                        $customer = $row['customer'];
                    }
                    else{
                        $cid = $row['customer'];
                    
                        if ($update_stmt = $db->prepare("SELECT * FROM customers WHERE id=?")) {
                            $update_stmt->bind_param('s', $cid);
                            
                            // Execute the prepared query.
                            if ($update_stmt->execute()) {
                                $result2 = $update_stmt->get_result();
                                
                                if ($row2 = $result2->fetch_assoc()) {
                                    $customer = $row2['customer_name'];
                                    $customerP = $row2['customer_phone'];
                                    $customerA = $row2['customer_address'];
                                    $customerE = $row2['customer_email'];
                                }
                            }
                        }
                    }
                    
                    $text = "https://speedjin.com/synctronix/qr.php?id=".$id."&compid=".$compids;
  
                    // $path variable store the location where to 
                    // store image and $file creates directory name
                    // of the QR code file by using 'uniqid'
                    // uniqid creates unique id based on microtime
                    $path = 'images/';
                    $file = $path.uniqid().".png";
                      
                    // $ecc stores error correction capability('L')
                    $ecc = 'L';
                    $pixel_Size = 10;
                    $frame_Size = 10;
                      
                    // Generates QR Code and Stores it in directory given
                    QRcode::png($text, $file, $ecc, $pixel_Size, $frame_size);
                    
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
        </style>
    </head>
    <body>
        <table style="width:100%">
            <tr>
                <td style="width: 60%;">
                    <img src="https://speedjin.com/synctronix/assets/logoWhite.jpg" heigth="auto" width="30%" /><br>
                    <p>
                        <span style="font-weight: bold;font-size: 16px;">'.$compname.'</span><br><br>
                        <span style="font-size: 12px;">'.$compaddress.'</span><br>
                        <span style="font-size: 12px;">'.$compphone.' / EMAIL: '.$compiemail.'</span>
                    </p>
                </td>
                <td>
                    <p>
                        <span style="font-weight: bold;font-size: 12px;">Transaction Date. : '.$row['dateTime'].'</span><br><br>
                        <span style="font-size: 12px;">Transaction Status: '.$row['status'].'</span><br>';
                        
                    if($row['manual'] == '1'){
                        $message .= '<span style="font-size: 12px;">Weight Status &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: Manual Weighing</span><br>';
                    }
                    else{
                        $message .= '<span style="font-size: 12px;">Weight Status &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: Auto Weighing</span><br>';
                    }
                    
                    $message .= '<span style="font-size: 12px;">Invoice No. &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: '.$row['invoiceNo'].'</span><br>
                        <span style="font-size: 12px;">Delivery No. &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: '.$row['deliveryNo'].'</span><br>
                        <span style="font-size: 12px;">Purchase No. &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: '.$row['purchaseNo'].'</span><br>
                        <span style="font-size: 12px;">Batch No. &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: '.$row['batchNo'].'</span>
                    </p>
                </td>
            </tr>
        </table>
        <hr>
        <table style="width:100%">
        <tr>
            <td style="width: 40%;">
                <p>
                    <span style="font-weight: bold;font-size: 16px;">'.$customer.'</span><br>
                </p>
            </td>
            <td style="width: 20%;">
                <p>&nbsp;</p>
            </td>
            <td style="width: 40%;">
                <p>
                    <span style="font-weight: bold;font-size: 14px;margin: 0 auto;display: table;">CASH BILL</span><br>
                    <span style="font-weight: bold;font-size: 12px;">Serial No. &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: '.$row['serialNo'].'</span>
                </p>
            </td>
        </tr>
        <tr>
            <td>
                <p>
                    <span style="font-size: 12px;">'.$customerA.'</span><br>
                    <span style="font-size: 12px;">TEL: '.$customerP.'</span><br>
                    <span style="font-size: 12px;">EMAIL: '.$customerE.'</span>
                </p>
                <table style="width:100%; border:1px solid black;">
                    <tr>
                        <th colspan="2" style="border:1px solid black; font-size: 14px;">Order Weight</th>
                        <th colspan="2" style="border:1px solid black; font-size: 14px;">Variance Weight</th>
                        <th style="border:1px solid black; font-size: 14px;">Variance %</th>
                    </tr>
                    <tr>
                        <td style="border:1px solid black;">'.$ow.'</td>
                        <td style="border:1px solid black;">kg</td>
                        <td style="border:1px solid black;">'.$vw.'</td>
                        <td style="border:1px solid black;">kg</td>
                        <td style="border:1px solid black;">'.$row['variancePerc'].' %</td>
                    </tr>
                </table>
            </td>
            <td style="width: 20%;">
                <center><img src="https://speedjin.com/synctronix/php/'.$file.'" height="auto" width="50%" /></center>
            </td>
            <td>
                <p>
                    <span style="font-size: 12px;">Weight Date & Time : '.$row['inCDateTime'].'</span><br>
                    <span style="font-size: 12px;">User Weight &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: '.$row['name'].'</span><br>
                    <span style="font-size: 12px;">Current Weight &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: '.$cw.' kg</span><br>
                    <span style="font-size: 12px;">Tare Weight &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: '.$tw.' kg</span><br><br>
                    <span style="font-size: 14px;font-weight: bold;">Total Weight &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: '.$ttw.' kg</span><br>
                </p>
            </td>
        </tr>
        </table><br>
        <table style="width:100%; border:1px solid black;">
            <tr>
                <th style="border:1px solid black;font-size: 14px;">Vehicle No.</th>
                <th style="border:1px solid black;font-size: 14px;">Product Name</th>
                <th style="border:1px solid black;font-size: 14px;">Package</th>
                <th style="border:1px solid black;font-size: 14px;">Unit Price</th>
                <th colspan="2" style="border:1px solid black;font-size: 14px;">Total Weight</th>
                <th style="border:1px solid black;font-size: 14px;">Total Price</th>
            </tr>
            <tr>
                <td style="border:1px solid black;font-size: 14px;">'.$row['vehicleNo'].'</td>
                <td style="border:1px solid black;font-size: 14px;">'.$row['product_name'].'</td>
                <td style="border:1px solid black;font-size: 14px;">'.$row['packages'].'</td>
                <td style="border:1px solid black;font-size: 14px;">RM '.$row['unitPrice'].'</td>
                <td style="border:1px solid black;font-size: 14px;">'.$ttw.'</td>
                <td style="border:1px solid black;font-size: 14px;">kg</td>
                <td style="border:1px solid black;font-weight: bold;font-size: 14px;">RM '.$row['totalPrice'].'</td>
            </tr>
        </table>
        <p>
            <span style="font-size: 12px;font-weight: bold;">Remark: </span>
            <span style="font-size: 12px;">'.$row['remark'].'</span>
        </p>
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
        $empQuery = "select count.id, count.serialNo, vehicles.veh_number, lots.lots_no, count.batchNo, count.invoiceNo, count.deliveryNo, 
        count.purchaseNo, customers.customer_name, products.product_name, packages.packages, count.unitWeight, count.tare, count.totalWeight, 
        count.actualWeight, count.currentWeight, units.units, count.moq, count.dateTime, count.unitPrice, count.totalPrice,count.totalPCS, 
        count.remark, status.status from count, vehicles, packages, lots, customers, products, units, status WHERE 
        count.vehicleNo = vehicles.id AND count.package = packages.id AND count.lotNo = lots.id AND count.customer = customers.id AND 
        count.productName = products.id AND status.id=count.status AND units.id=count.unit AND count.deleted = '0' AND count.id=?";

        if ($select_stmt = $db->prepare($empQuery)) {
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
                    $message = '<html>
                    <head>
                        <title>Html to PDF</title>
                    </head>
                    <body>
                        <h3>'.$compname.'</h3>
                        <p>No.34, Jalan Bagan 1, <br>Taman Bagan, 13400 Butterworth.<br> Penang. Malaysia.</p>
                        <p>TEL: 6043325822 | EMAIL: admin@synctronix.com.my</p><hr>
                        <table style="width:100%">
                        <tr>
                            <td>
                                <h4>CUSTOMER NAME: '.$row['customer_name'].'</h4>
                            </td>
                            <td>
                                <h4>SERIAL NO: '.$row['serialNo'].'</h4>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p>No.34, Jalan Bagan 1, <br>Taman Bagan, <br>13400 Butterworth. Penang. Malaysia.</p>
                            </td>
                            <td>
                                <h4>Status: '.$row['status'].'</h4>
                                <p>Date: 23/03/2022<br>Delivery No: '.$row['deliveryNo'].'</p>
                            </td>
                        </tr>
                        </table>
                        <table style="width:100%; border:1px solid black;">
                        <tr>
                            <th style="border:1px solid black;">Vehicle No.</th>
                            <th style="border:1px solid black;">Product Name</th>
                            <th style="border:1px solid black;">Date & Time</th>
                            <th style="border:1px solid black;">Weight</th>
                        </tr>
                        <tr>
                            <td style="border:1px solid black;">'.$row['veh_number'].'</td>
                            <td style="border:1px solid black;">'.$row['product_name'].'</td>
                            <td style="border:1px solid black;">'.$row['dateTime'].'</td>
                            <td style="border:1px solid black;">'.$row['unitWeight'].' '.$row['units'].'</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td style="border:1px solid black;">Tare Weight</td>
                            <td style="border:1px solid black;">'.$row['tare'].' '.$row['units'].'</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td style="border:1px solid black;">Net Weight</td>
                            <td style="border:1px solid black;">'.$row['actualWeight'].' '.$row['units'].'</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td style="border:1px solid black;">M.O.Q</td>
                            <td style="border:1px solid black;">'.$row['moq'].'</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td style="border:1px solid black;">Total Weight</td>
                            <td style="border:1px solid black;">'.$row['totalWeight'].' '.$row['units'].'</td>
                        </tr>
                        </table>
                        <p>Remark: '.$row['remark'].'</p>
                    </body>
                </html>';
                }
                
                echo json_encode(
                    array(
                        "status" => "success",
                        "message" => $message
                    ));
            }
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
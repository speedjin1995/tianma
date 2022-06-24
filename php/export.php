<?php

require_once 'db_connect.php';
// // Load the database configuration file 
 
// Filter the excel data 
function filterData(&$str){ 
    $str = preg_replace("/\t/", "\\t", $str); 
    $str = preg_replace("/\r?\n/", "\\n", $str); 
    if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"'; 
} 
 
// Excel file name for download 
if($_GET["file"] == 'weight'){
    $fileName = "Weight-data_" . date('Y-m-d') . ".xls";
}else{
    $fileName = "Count-data_" . date('Y-m-d') . ".xls";
} 
 
// Column names 
if($_GET["file"] == 'weight'){
    $fields = array('SERIAL NO', 'PRODUCT NO', 'UNIT WEIGHT', 'TARE WEIGHT', 'TOTAL WEIGHT', 'ACTUAL WEIGHT', 'MOQ', 'UNIT PRICE(RM)', 'TOTAL PRICE(RM)', 
                'ORDER WEIGHT', 'CURRENT WEIGHT','VARIANCE WEIGHT', 'REDUCE WEIGHT', 'INCOMING DATETIME', 'OUTGOING DATETIME', 'VARIANCE %',
                'VEHICLE NO', 'LOT NO', 'BATCH NO', 'INVOICE NO', 'DELIVERY NO', 'PURCHASE NO', 'CUSTOMER', 'PACKAGE', 'DATE', 'REMARK', 'STATUS', 'DELETED'); 
}else{
    $fields = array('SERIAL NO', 'PRODUCT NO', 'UNIT', 'UNIT WEIGHT', 'TARE', 'CURRENT WEIGHT', 'ACTUAL WEIGHT', 'TOTAL PCS','MOQ', 'UNIT PRICE(RM)', 'TOTAL PRICE(RM)',
    'VEHICLE NO', 'LOT NO', 'BATCH NO', 'INVOICE NO', 'DELIVERY NO', 'PURCHASE NO', 'CUSTOMER', 'PACKAGE', 'DATE', 'REMARK', 'STATUS', 'DELETED');    
}

// Display column names as first row 
$excelData = implode("\t", array_values($fields)) . "\n"; 

## Search 
$searchQuery = " ";


if($_GET['fromDate'] != null && $_GET['fromDate'] != ''){
    if($_GET["file"] == 'weight'){
        $searchQuery = " and weight.inCDateTime >= '".$_GET['fromDate']."'";
    }else{
        $searchQuery = " and count.dateTime >= '".$_GET['fromDate']."'";
    }
}

if($_GET['toDate'] != null && $_GET['toDate'] != ''){
    if($_GET["file"] == 'weight'){
        $searchQuery = " and weight.inCDateTime <= '".$_GET['toDate']."'";
    }else{
        $searchQuery = " and count.dateTime <= '".$_GET['toDate']."'";
    }
}

if($_GET['status'] != null && $_GET['status'] != '' && $_GET['status'] != '-'){
    if($_GET["file"] == 'weight'){
        $searchQuery = " and weight.status = '".$_GET['status']."'";
    }else{
        $searchQuery = " and count.status = '".$_GET['status']."'";
    }	
}

if($_GET['customer'] != null && $_GET['customer'] != '' && $_GET['customer'] != '-'){
    if($_GET["file"] == 'weight'){
        $searchQuery = " and weight.customer = '".$_GET['customer']."'";
    }else{
        $searchQuery = " and count.customer = '".$_GET['customer']."'";
    }
}

if($_GET['vehicle'] != null && $_GET['vehicle'] != '' && $_GET['vehicle'] != '-'){
    if($_GET["file"] == 'weight'){
        $searchQuery = " and weight.vehicleNo = '".$_GET['vehicle']."'";
    }else{
        $searchQuery = " and count.vehicleNo = '".$_GET['vehicle']."'";
    }
}

if($_GET['invoice'] != null && $_GET['invoice'] != ''){
    if($_GET["file"] == 'weight'){
        $searchQuery = " and weight.invoiceNo like '%".$_GET['invoice']."%'";
    }else{
        $searchQuery = " and count.invoiceNo like '%".$_GET['invoice']."%'";
    }
}

if($_GET['batch'] != null && $_GET['batch'] != ''){
    if($_GET["file"] == 'weight'){
        $searchQuery = " and weight.batchNo like '%".$_GET['batch']."%'";
    }else{
        $searchQuery = " and count.batchNo like '%".$_GET['batch']."%'";
    }
}

if($_GET['product'] != null && $_GET['product'] != '' && $_GET['product'] != '-'){
    if($_GET["file"] == 'weight'){
        $searchQuery = " and weight.productName = '".$_GET['product']."'";
    }else{
        $searchQuery = " and count.productName = '".$_GET['product']."'";
    }
}

// Fetch records from database
if($_GET["file"] == 'weight'){
    $query = $db->query("select weight.id, weight.serialNo, weight.vehicleNo, weight.lotNo, weight.batchNo, weight.invoiceNo, weight.deliveryNo, users.name,
    weight.purchaseNo, weight.customer, products.product_name, packages.packages, weight.unitWeight, weight.tare, weight.totalWeight, weight.actualWeight, 
    weight.supplyWeight, weight.varianceWeight, weight.currentWeight, units.units, weight.moq, weight.dateTime, weight.unitPrice, weight.totalPrice, weight.remark, 
    weight.status as Status, status.status, weight.manual, weight.manualVehicle, weight.manualOutgoing, weight.reduceWeight, weight.outGDateTime, weight.inCDateTime, 
    weight.pStatus, weight.variancePerc, weight.transporter from weight, packages, products, units, status, users 
    WHERE weight.package = packages.id AND users.id = weight.created_by AND weight.pStatus = 'Complete' AND weight.productName = products.id AND status.id=weight.status AND 
    units.id=weight.unitWeight AND weight.deleted = '0'".$searchQuery."");
}else{
    $query = $db->query("select count.id, count.serialNo, vehicles.veh_number, lots.lots_no, count.batchNo, count.invoiceNo, count.deliveryNo, 
    count.purchaseNo, customers.customer_name, products.product_name, packages.packages, count.unitWeight, count.tare, count.totalWeight, 
    count.actualWeight, count.currentWeight, units.units, count.moq, count.dateTime, count.unitPrice, count.totalPrice,count.totalPCS, 
    count.remark, count.deleted, status.status from count, vehicles, packages, lots, customers, products, units, status WHERE 
    count.vehicleNo = vehicles.id AND count.package = packages.id AND count.lotNo = lots.id AND count.customer = customers.id AND 
    count.productName = products.id AND status.id=count.status AND units.id=count.unit ".$searchQuery."");
}

if($query->num_rows > 0){ 
    // Output each row of the data 
    while($row = $query->fetch_assoc()){ 
        $deleted = ($row['deleted'] == 1)?'Active':'Inactive';
        
        if($_GET["file"] == 'weight'){
            $customer = '';

            if($row['Status'] != '1' && $row['Status'] != '2'){
                $customer = $row['customer'];
            }
            else{
                $cid = $row['customer'];
            
                if ($update_stmt = $db->prepare("SELECT * FROM customers WHERE id=?")) {
                    $update_stmt->bind_param('s', $cid);
                
                    // Execute the prepared query.
                    if ($update_stmt->execute()) {
                        $result = $update_stmt->get_result();
                        
                        if ($row2 = $result->fetch_assoc()) {
                            $customer = $row2['customer_name'];
                        }
                    }
                }
            }

            $lineData = array($row['serialNo'], $row['product_name'], $row['units'], $row['tare'], $row['totalWeight'], $row['actualWeight'],
            $row['moq'], $row['unitPrice'], $row['totalPrice'], $row['supplyWeight'], $row['currentWeight'], $row['varianceWeight'], $row['reduceWeight'],
            $row['inCDateTime'], $row['outGDateTime'], $row['variancePerc'], $row['vehicleNo'], $row['lotNo'], $row['batchNo'], $row['invoiceNo']
            , $row['deliveryNo'], $row['purchaseNo'], $customer, $row['packages'], $row['dateTime'], $row['remark'], $row['status'], $deleted);
        }else{
            $lineData = array($row['serialNo'], $row['product_name'], $row['units'], $row['unitWeight'], $row['tare'], $row['currentWeight'], $row['actualWeight'],
            $row['totalPCS'], $row['moq'], $row['unitPrice'], $row['totalPrice'], $row['veh_number'], $row['lots_no'], $row['batchNo'], $row['invoiceNo']
            , $row['deliveryNo'], $row['purchaseNo'], $row['customer_name'], $row['packages'], $row['dateTime'], $row['remark'], $row['status'], $deleted);
        }

        array_walk($lineData, 'filterData'); 
        $excelData .= implode("\t", array_values($lineData)) . "\n"; 
    } 
}else{ 
    $excelData .= 'No records found...'. "\n"; 
} 
 
// Headers for download 
header("Content-Type: application/vnd.ms-excel"); 
header("Content-Disposition: attachment; filename=\"$fileName\""); 
 
// Render excel data 
echo $excelData; 
 
exit;
?>

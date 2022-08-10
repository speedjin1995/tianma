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
$fileName = "";
 
// Column names 
if($_GET["file"] == 'weight'){
    $fields = array('SERIAL NO', 'PRODUCT NO', 'UNIT WEIGHT', 'TARE WEIGHT', 'TOTAL WEIGHT', 'ACTUAL WEIGHT', 'MOQ', 'UNIT PRICE(RM)', 'TOTAL PRICE(RM)', 
                'ORDER WEIGHT', 'CURRENT WEIGHT','VARIANCE WEIGHT', 'REDUCE WEIGHT', 'INCOMING DATETIME', 'OUTGOING DATETIME', 'VARIANCE %',
                'VEHICLE NO', 'LOT NO', 'BATCH NO', 'INVOICE NO', 'DELIVERY NO', 'PURCHASE NO', 'CUSTOMER', 'PACKAGE', 'DATE', 'REMARK', 'STATUS', 'DELETED'); 
}else{
    
}

// Display column names as first row 


if($_GET['batch'] != null && $_GET['batch'] != ''){
    $fileName = "Batch_Report_".$_GET['batch'].".xls";
    $fields = array('Batch no: '.$_GET['batch']);
    $excelData = implode("\t", array_values($fields)) . "\n"; 

    $searchQuery = " and count.batchNo = '".$_GET['batch']."'";
    $query = $db->query("select weighing.tray_no, weighing.tray_weight, weighing.moisture_gross_weight, grades.grade
    weighing.pieces, weighing.moisture_net_weight, weighing.moisture_after_moisturing from weighing, grades 
    WHERE parent_no <> '0'".$searchQuery);
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
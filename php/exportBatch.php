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
$fileName = "Batch_report" . date('Y-m-d') . ".xls";
$output = '';
$lotNo = $_GET['batch'];
 
// Column names 
/*if($_GET["file"] == 'weight'){
    $fields = array('SERIAL NO', 'PRODUCT NO', 'UNIT WEIGHT', 'TARE WEIGHT', 'TOTAL WEIGHT', 'ACTUAL WEIGHT', 'MOQ', 'UNIT PRICE(RM)', 'TOTAL PRICE(RM)', 
                'ORDER WEIGHT', 'CURRENT WEIGHT','VARIANCE WEIGHT', 'REDUCE WEIGHT', 'INCOMING DATETIME', 'OUTGOING DATETIME', 'VARIANCE %',
                'VEHICLE NO', 'LOT NO', 'BATCH NO', 'INVOICE NO', 'DELIVERY NO', 'PURCHASE NO', 'CUSTOMER', 'PACKAGE', 'DATE', 'REMARK', 'STATUS', 'DELETED'); 
}else{
    
}*/
## Search 
$searchQuery = " ";


if($_GET['batch'] != null && $_GET['batch'] != '' && $_GET['batch'] != '-'){

    $searchQuery = "weighing.lot_no = '".$_GET['batch']."'";

}

// Fetch records from database
$query = $db->query("select * from weighing WHERE ".$searchQuery."");
if($query->num_rows > 0){ 
    $output .= '
    <table class="table" border="1">
        <tr>
            <th colspan="2" style="background-color: #E2EFDA;">T4 Full Stock Summary</th>
            <th style="background-color: #E2EFDA;"></th>
            <th style="background-color: #E2EFDA;"></th>
            <th style="background-color: #E2EFDA;"></th>
            <th style="background-color: #E2EFDA;"></th>
            <th style="background-color: #FFFF00;"></th>
            <th style="background-color: #FFFF00;"></th>
            <th style="background-color: #FCE4D6;"></th>
            <th style="background-color: #FCE4D6;"></th>
            <th style="background-color: #FCE4D6;"></th>
            <th style="background-color: #FCE4D6;"></th>
            <th style="background-color: #FCE4D6;"></th>
            <th style="background-color: #FCE4D6;"></th>
            <th style="background-color: #FCE4D6;"></th>
            <th style="background-color: #FCE4D6;"></th>
            <th style="background-color: #FCE4D6;"></th>
            <th style="background-color: #FCE4D6;"></th>
            <th style="background-color: #FCE4D6;"></th>
            <th style="background-color: #FCE4D6;"></th>
            <th style="background-color: #FCE4D6;"></th>
            <th style="background-color: #D9E1F2;"></th>
            <th style="background-color: #D9E1F2;"></th>
            <th style="background-color: #D9E1F2;"></th>
            <th style="background-color: #D9E1F2;"></th>
            <th style="background-color: #D9E1F2;"></th>
            <th style="background-color: #D9E1F2;"></th>
            <th style="background-color: #D9E1F2;"></th>
            <th style="background-color: #D9E1F2;"></th>
            <th style="background-color: #D9E1F2;"></th>
            <th style="background-color: #D9E1F2;"></th>
            <th style="background-color: #D9E1F2;"></th>
            <th style="background-color: #D9E1F2;"></th>
            <th style="background-color: #FFF2CC;"></th>
            <th style="background-color: #FFF2CC;"></th>
        </tr>
        <tr>
            <th colspan="2" style="background-color: #E2EFDA;">Purchase Receiving 采购验收</th>
            <th style="background-color: #E2EFDA;"></th>
            <th style="background-color: #E2EFDA;"></th>
            <th style="background-color: #E2EFDA;"></th>
            <th style="background-color: #E2EFDA;"></th>
            <th style="background-color: #E2EFDA;"></th>
            <th colspan="7" style="background-color: #FCE4D6;">Grading/Drying 异物排查、风干</th>
            <th colspan="6" style="background-color: #FCE4D6;">Add Moisture 加湿</th>
            <th style="background-color: #FCE4D6;"></th>
            <th colspan="12" style="background-color: #D9E1F2;">Production Packing 生产包装</th>
            <th style="background-color: #FFF2CC;"></th>
            <th style="background-color: #FFF2CC;"></th>
        </tr>
        <tr>
            <th colspan="2" style="background-color: #E2EFDA;"></th>
            <th style="background-color: #E2EFDA;"></th>
            <th style="background-color: #E2EFDA;"></th>
            <th style="background-color: #E2EFDA;"></th>
            <th style="background-color: #E2EFDA;"></th>
            <th style="background-color: #E2EFDA;"></th>
            <th colspan="5" style="background-color: #FCE4D6;">IN 进</th>
            <th colspan="2" style="background-color: #FCE4D6;">Different 差异</th>
            <th colspan="4" style="background-color: #FCE4D6;">Out 出</th>
            <th colspan="2" style="background-color: #FCE4D6;">Loss 损失</th>
            <th style="background-color: #FCE4D6;"></th>
            <th colspan="3" style="background-color: #D9E1F2;">IN 进</th>
            <th colspan="2" style="background-color: #D9E1F2;"></th>
            <th colspan="4" style="background-color: #D9E1F2;">Out 出</th>
            <th colspan="2" style="background-color: #D9E1F2;">Different 差异</th>
            <th style="background-color: #D9E1F2;"></th>
            <th colspan="2" style="background-color: #FFF2CC;">Total Loss 总差额</th>
        </tr>  
        <tr>
            <th style="background-color: #E2EFDA;">Date<br/>日期</th>
            <th style="width:130px;background-color: #E2EFDA;">Batch<br/>批号</th>
            <th style="background-color: #E2EFDA;">MSIA (M) / INDO (I)</th>
            <th style="background-color: #E2EFDA;">Grade<br>等级</th>
            <th style="background-color: #E2EFDA;">Weight<br>重量<br>(g)</th>
            <th style="background-color: #FFFF00;">Total Box<br>盒数</th>
            <th style="background-color: #FFFF00;">Lab Sample<br>样本 <br>(g)</th>
            <th style="background-color: #FCE4D6;">Date<br>日期</th>
            <th style="background-color: #FCE4D6;">Weight<br>重量<br>(g)</th>
            <th style="background-color: #FCE4D6;">Moist<br>水份<br>(g)</th>
            <th style="background-color: #FCE4D6;">Grade<br>等级</th>
            <th style="background-color: #FCE4D6;">Weight<br>重量<br>(g)</th>
            <th style="background-color: #FCE4D6;">Weight<br>重量<br>(g)</th>
            <th style="background-color: #FCE4D6;">Percentage<br>比例<br>(%)</th>
            <th style="background-color: #FCE4D6;">Date<br>日期</th>
            <th style="background-color: #FCE4D6;">Grade<br>等级</th>
            <th style="background-color: #FCE4D6;">Weight<br>重量<br>(g)</th>
            <th style="background-color: #FCE4D6;">Moist<br>水份<br>(g)</th>
            <th style="background-color: #FCE4D6;">Weight<br>重量<br>(g)</th>
            <th style="background-color: #FCE4D6;">Percentage<br>比例<br>(%)</th>
            <th style="background-color: #FFFF00;">Remark<br>备注</th>
            <th style="background-color: #D9E1F2;">Date<br>日期</th>
            <th style="background-color: #D9E1F2;">Weight<br>重量<br>(g)</th>
            <th style="background-color: #D9E1F2;">Moist<br>水份<br>(g)</th>
            <th style="background-color: #D9E1F2;">Diff<br>收货差异<br>(g)</th>
            <th style="background-color: #D9E1F2;">Diff<br>收货差异<br>(%)</th>
            <th style="background-color: #D9E1F2;">Date<br>日期</th>
            <th style="background-color: #D9E1F2;">Grade<br>等级</th>
            <th style="background-color: #D9E1F2;">Weight<br>重量<br>(g)</th>
            <th style="background-color: #D9E1F2;">Qty<br>片<br>(pcs)</th>
            <th style="background-color: #D9E1F2;">Weight<br>重量<br>(g)</th>
            <th style="background-color: #D9E1F2;">Percentage<br>比例<br>(%)</th>
            <th style="background-color: #D9E1F2;">Remark<br>备注</th>
            <th style="background-color: #FFF2CC;">Weight<br>重量<br>(g)</th>
            <th style="background-color: #FFF2CC;">Percentage<br>比例<br>(%)</th>
        </tr>

    ';

    $passRate = 0.00;
    $lossWeightPerc = 0.00;
    $diffWeightPerc = 0.00;
    $countLotNo = 0;
    $totalNetWeight = 0;
    $totalMoistureNetWeight = 0;
    $totalLossWeight = 0;
    $totalLossWeightPerc = 0;

    // Output each row of the data linkgoog
    while($row = $query->fetch_assoc()){ 

        if($row['net_weight'] > 0 && $row['grading_net_weight'] > 0){
            $passRate = $row['net_weight'] / $row['grading_net_weight'];
        }

        $lossWeight = $row['moisture_net_weight'] - $row['grading_net_weight'];

        if($row['moisture_net_weight'] > 0 && $row['grading_net_weight'] > 0){
            $lossWeightPerc = ($row['moisture_net_weight'] - $row['grading_net_weight']) / 100;
        }

        //for T4
        $diffWeight = $row['grading_net_weight'] - $row['net_weight'];

        if($row['grading_net_weight'] > 0 && $row['net_weight'] > 0){
            $diffWeightPerc = ($row['grading_net_weight'] - $row['net_weight']) / 100;
        }

        if($row['lot_no'] != null)
        {
            $countLotNo++;
        }

        $totalNetWeight += $row['net_weight'];
        $totalMoistureNetWeight += $row['moisture_net_weight'];
        $totalLossWeight += $lossWeight;
        $totalLossWeightPerc += $lossWeightPerc;

        $output .= '
            <tr>
                <td style="text-align: center;background-color: #E2EFDA;">'.substr($row['created_datetime'], 0, 10).'</td>
                <td style="text-align: center;background-color: #E2EFDA;">="'.$countLotNo.'"</td>
                <td style="text-align: center;background-color: #E2EFDA;"></td>
                <td style="text-align: center;background-color: #E2EFDA;"></td>
                <td style="text-align: center;background-color: #E2EFDA;"></td>
                <td style="text-align: center;background-color: #E2EFDA;"></td>
                <td style="text-align: center;background-color: #E2EFDA;"></td>
                <td style="text-align: center;background-color: #FCE4D6;">'.$row['grading_datetime'].'</td>
                <td style="text-align: center;background-color: #FCE4D6;">'.$row['net_weight'].'</td>
                <td style="text-align: center;background-color: #FCE4D6;"></td>
                <td style="text-align: center;background-color: #FCE4D6;">'.$row['grade'].'</td>
                <td style="text-align: center;background-color: #FCE4D6;">'.$row['grading_net_weight'].'</td>
                <td style="text-align: center;background-color: #FCE4D6;">'.$diffWeight.'</td>
                <td style="text-align: center;background-color: #FCE4D6;">'.$diffWeightPerc.'</td>
                <td style="text-align: center;background-color: #FCE4D6;">'.$row['moisturing_datetime'].'</td>
                <td style="text-align: center;background-color: #FCE4D6;">'.$row['grade'].'</td>
                <td style="text-align: center;background-color: #FCE4D6;">'.$row['moisture_net_weight'].'</td>
                <td style="text-align: center;background-color: #FCE4D6;">'.$row['moisture_after_moisturing'].'</td>
                <td style="text-align: center;background-color: #FCE4D6;">'.$lossWeight.'</td>
                <td style="text-align: center;background-color: #FCE4D6;">'.$lossWeightPerc.'</td>
                <td style="text-align: center;background-color: #FCE4D6;">'.$row['remark'].'</td>
                <td style="text-align: center;background-color: #D9E1F2;"></td>
                <td style="text-align: center;background-color: #D9E1F2;"></td>
                <td style="text-align: center;background-color: #D9E1F2;"></td>
                <td style="text-align: center;background-color: #D9E1F2;"></td>
                <td style="text-align: center;background-color: #D9E1F2;"></td>
                <td style="text-align: center;background-color: #D9E1F2;"></td>
                <td style="text-align: center;background-color: #D9E1F2;"></td>
                <td style="text-align: center;background-color: #D9E1F2;"></td>
                <td style="text-align: center;background-color: #D9E1F2;"></td>
                <td style="text-align: center;background-color: #D9E1F2;"></td>
                <td style="text-align: center;background-color: #D9E1F2;"></td>
                <td style="text-align: center;background-color: #D9E1F2;"></td>
                <td style="text-align: center;background-color: #FFF2CC;"></td>
                <td style="text-align: center;background-color: #FFF2CC;"></td>
            </tr>';

    }

    $output .= '
    <tr>
    <td style="text-align: center;background-color: #E2EFDA;">Total</td>
    <td style="text-align: center;background-color: #E2EFDA;">="'.$countLotNo.'"</td>
    <td style="text-align: center;background-color: #E2EFDA;"></td>
    <td style="text-align: center;background-color: #E2EFDA;"></td>
    <td style="text-align: center;background-color: #E2EFDA;"></td>
    <td style="text-align: center;background-color: #E2EFDA;"></td>
    <td style="text-align: center;background-color: #E2EFDA;"></td>
    <td style="text-align: center;background-color: #FCE4D6;"></td>
    <td style="text-align: center;background-color: #FCE4D6;">'.$totalNetWeight.'</td>
    <td style="text-align: center;background-color: #FCE4D6;"></td>
    <td style="text-align: center;background-color: #FCE4D6;"></td>
    <td style="text-align: center;background-color: #FCE4D6;"></td>
    <td style="text-align: center;background-color: #FCE4D6;">'.$diffWeight.'</td>
    <td style="text-align: center;background-color: #FCE4D6;">'.$diffWeightPerc.'</td>
    <td style="text-align: center;background-color: #FCE4D6;"></td>
    <td style="text-align: center;background-color: #FCE4D6;"></td>
    <td style="text-align: center;background-color: #FCE4D6;">'.$totalMoistureNetWeight.'</td>
    <td style="text-align: center;background-color: #FCE4D6;"></td>
    <td style="text-align: center;background-color: #FCE4D6;">'.$totalLossWeight.'</td>
    <td style="text-align: center;background-color: #FCE4D6;">'.$lossWeightPerc.'</td>
    <td style="text-align: center;background-color: #FCE4D6;"></td>
    <td style="text-align: center;background-color: #D9E1F2;"></td>
    <td style="text-align: center;background-color: #D9E1F2;"></td>
    <td style="text-align: center;background-color: #D9E1F2;"></td>
    <td style="text-align: center;background-color: #D9E1F2;"></td>
    <td style="text-align: center;background-color: #D9E1F2;"></td>
    <td style="text-align: center;background-color: #D9E1F2;"></td>
    <td style="text-align: center;background-color: #D9E1F2;"></td>
    <td style="text-align: center;background-color: #D9E1F2;"></td>
    <td style="text-align: center;background-color: #D9E1F2;"></td>
    <td style="text-align: center;background-color: #D9E1F2;"></td>
    <td style="text-align: center;background-color: #D9E1F2;"></td>
    <td style="text-align: center;background-color: #D9E1F2;"></td>
    <td style="text-align: center;background-color: #FFF2CC;"></td>
    <td style="text-align: center;background-color: #FFF2CC;"></td>
</tr>
<tr>
</tr>
<tr>
</tr>
<tr>
    <th colspan="2" >CONCLUSION</th>
    <th>GRAM</th>
</tr>
<tr>
    <th rowspan="2" >QC</th>
    <th>TOTAL BATCH</th>
    <th>'.$countLotNo.'</th>
</tr>
<tr>
    <th>TOTAL GRADING WEIGHT</th>
    <th>'.$totalNetWeight.'</th>
</tr>
<tr>
    <th></th>
    <th>TOTAL RECEIVING WEIGHT</th>
    <th>'.$totalNetWeight.'</th>
</tr>
<tr>
    <th></th>
    <th>TOTAL MOISTURING WEIGHT</th>
    <th>'.$totalMoistureNetWeight.'</th>
</tr>
<tr>
</tr>  
    </table>';
}
else{ 
    $output .= 'No records found...'. "\n"; 
}



/*if($_GET['batch'] != null && $_GET['batch'] != ''){
    $fileName = "Batch_Report_".$_GET['batch'].".xls";
    $fields = array('Batch no: '.$_GET['batch']);
    $excelData = implode("\t", array_values($fields)) . "\n"; 

    $searchQuery = " and count.batchNo = '".$_GET['batch']."'";
    $query = $db->query("select weighing.tray_no, weighing.tray_weight, weighing.grading_gross_weight, 
    weighing.moisture_gross_weight, weighing.grading_net_weight, weighing.moisture_after_grading, 
    weighing.moisture_after_receiving, grades.grade, weighing.pieces, weighing.moisture_net_weight, 
    weighing.moisture_after_moisturing from weighing, grades WHERE parent_no <> '0'".$searchQuery);
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
}*/
 
// Headers for download 
header("Content-Type: application/vnd.ms-excel"); 
header("Content-Disposition: attachment; filename=\"$fileName\""); 
 
// Render excel data 
echo $output;
exit;
?>

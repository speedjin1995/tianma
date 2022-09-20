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
$fileName = "Summary_report" . date('Y-m-d') . ".xls";
$output = '';
$itemType = $_GET['itemType'];
// // Column names 
// $fields = array('Date 日期','Batch 批号', 'Grading Weight 分级重量 (g)', 'Lab Sample 样本 (g)', 'QC Broken 分级破裂 (g)', 'Soaking / Cooking Test 泡发/炖煮测试(g)',
//                 'Grade 等级', 'Weight 重量 (g)', 'Pass Rate 合格率（%)', 'Qty 片 (pcs)', 'Remark 备注','Date 日期', 'Weight 重量 (g)', 'Moist 水份 (G)', 'Date 日期', 
//                 'GRADE 等级', 'Weight 重量 (g)', 'Moist 水份', 'Weight 重量(g) *compare to stock in', 'Percentage 比例 (%)', 'Remark 备注', 'Date 日期', 
//                 'Form No 表格号码', 'Box No 盒号', 'GRADE 等级', 'Weight 重量 (g)', 'Qty 片 (pcs)', 'Weight 重量 (g)', 'Qty 片 (pcs)', 'Percentage 比例 (≤1%)',
//                 'Remark 备注'); 

// Display column names as first row 
// $excelData = implode("\t", array_values($fields)) . "\n"; 

## Search 
$searchQuery = " ";


if($_GET['fromDate'] != null && $_GET['fromDate'] != ''){

    $searchQuery = " and weighing.created_datetime >= '".$_GET['fromDate']."'";

}

if($_GET['toDate'] != null && $_GET['toDate'] != ''){

    $searchQuery = " and weighing.created_datetime <= '".$_GET['toDate']."'";
}

if($_GET['itemType'] != null && $_GET['itemType'] != '' && $_GET['itemType'] != '-'){

    $searchQuery = " and weighing.item_types = '".$_GET['itemType']."'";

}


// Fetch records from database
$query = $db->query("select * from weighing WHERE parent_no = '0'".$searchQuery."");
if($itemType == 'T4'){

    if($query->num_rows > 0){ 
        $output .= '
        <table class="table" border="1">
            <tr>
                <th colspan="2" style="background-color: #E2EFDA;">T4 Full Stock Summary</th>
                <th style="background-color: #E2EFDA;"></th>
                <th style="background-color: #E2EFDA;"></th>
                <th style="background-color: #E2EFDA;"></th>
                <th style="background-color: #E2EFDA;"></th>
                <th style="background-color: #E2EFDA;"></th>
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
                <th style="background-color: #E2EFDA;">Total Box<br>盒数</th>
                <th style="background-color: #E2EFDA;">Lab Sample<br>样本 <br>(g)</th>
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
                <th style="background-color: #FCE4D6;">Remark<br>备注</th>
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
                    <td style="text-align: center;background-color: #E2EFDA;">'.$row['created_datetime'].'</td>
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
    }else{ 
        $output .= 'No records found...'. "\n"; 
    }

}else{

    if($query->num_rows > 0){ 
        $output .= '
        <table class="table" border="1">
            <tr>
                <th colspan="2"></th>
                <th colspan="9">'.$itemType.' Full Stock Summary</th>
                <th colspan="3" style="background-color: #FCE4D6;"></th>
                <th colspan="4" style="background-color: #FCE4D6;"></th>
                <th colspan="2" style="background-color: #FCE4D6;"></th>
                <th style="background-color: #FFFF00;"></th>
                <th colspan="3" style="background-color: #D9E1F2;"></th>
                <th colspan="3" style="background-color: #D9E1F2;"></th>
                <th colspan="4" style="background-color: #D9E1F2;"></th>
            </tr>
            <tr>
                <th colspan="2"></th>
                <th colspan="9">QC Grading 分级</th>
                <th colspan="10" style="background-color: #FCE4D6;">Drying/Humidify 风干或加湿</th>
                <th colspan="10" style="background-color: #D9E1F2;">Production Packing 生产包装</th>
            </tr>
            <tr>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th colspan="3" style="background-color: #FCE4D6;">IN 进</th>
                <th colspan="4" style="background-color: #FCE4D6;">Out 出</th>
                <th colspan="2" style="background-color: #FCE4D6;">Loss 损失</th>
                <th style="background-color: #FFFF00;"></th>
                <th colspan="3" style="background-color: #D9E1F2;">IN 进</th>
                <th colspan="3" style="background-color: #D9E1F2;">Out 出</th>
                <th colspan="4" style="background-color: #D9E1F2;">Loss 损失</th>
            </tr>        
            <tr>  
                <th>Date<br/>日期</th>
                <th style="width:130px">Batch<br/>批号</th>
                <th>Grading Weight<br>分级重量 <br>(g)</th>
                <th>Lab Sample<br>样本 <br>(g)</th>
                <th>QC Broken<br>分级破裂 <br>(g)</th>
                <th>Soaking / Cooking Test<br>泡发/炖煮测试<br>(g)</th>
                <th>Grade<br>等级</th>
                <th>Weight<br>重量<br>(g)</th>
                <th>Pass Rate<br>合格率<br>(%)</th>
                <th>Qty<br>片<br>(pcs)<br>(g)</th>
                <th>Remark<br>备注</th>
                <th style="background-color: #FCE4D6;">Date<br>日期</th>
                <th style="background-color: #FCE4D6;">Weight<br>重量 (g)</th>
                <th style="background-color: #FCE4D6;">Moist<br>水份<br>(g)</th>
                <th style="background-color: #FCE4D6;">Date<br>日期</th>
                <th style="background-color: #FCE4D6;">GRADE<br>等级</th>
                <th style="background-color: #FCE4D6;">Weight<br>重量 (g)</th>
                <th style="background-color: #FCE4D6;">Moist<br>水份</th>
                <th style="background-color: #FCE4D6;">Weight<br>重量<br>(g) *compare to stock in</th>
                <th style="background-color: #FCE4D6;">Percentage<br>比例<br>(%)</th>
                <th style="background-color: #FCE4D6;">Remark<br>备注</th>
                <th style="background-color: #D9E1F2;">Date<br>日期</th>
                <th style="background-color: #D9E1F2;">Form No<br>表格号码</th>
                <th style="background-color: #D9E1F2;">Box No<br>盒号</th>
                <th style="background-color: #D9E1F2;">GRADE<br>等级</th>
                <th style="background-color: #D9E1F2;">Weight<br>重量<br>(g)</th>
                <th style="background-color: #D9E1F2;">Qty<br>片<br>(pcs)</th>
                <th style="background-color: #D9E1F2;">Weight<br>重量<br>(g)</th>
                <th style="background-color: #D9E1F2;">Qty<br>片<br>(pcs)</th>
                <th style="background-color: #D9E1F2;">Percentage<br>比例<br>(≤1%)</th>
                <th style="background-color: #D9E1F2;">Remark<br>备注</th>
            </tr>

        ';

        $passRate = 0.00;
        $lossWeightPerc = 0.00;
        $countLotNo = 0;
        $totalPassRate = 0;
        $totalMoistureNetWeight = 0;
        $totalLossWeight = 0;
        $totalNetWeight = 0;
        $totalGradingNetWeight = 0;

        // Output each row of the data linkgoog
        while($row = $query->fetch_assoc()){ 

            if($row['net_weight'] > 0 && $row['grading_net_weight'] > 0){
                $passRate = $row['net_weight'] / $row['grading_net_weight'];
            }

            $lossWeight = $row['moisture_net_weight'] - $row['grading_net_weight'];

            if($row['moisture_net_weight'] > 0 && $row['grading_net_weight'] > 0){
                $lossWeightPerc = ($row['moisture_net_weight'] - $row['grading_net_weight']) / 100;
            }

            if($row['lot_no'] != null)
            {
                $countLotNo++;
            }
            
            $totalPassRate += $passRate;
            $totalMoistureNetWeight += $row['moisture_net_weight'];
            $totalLossWeight += $lossWeight;
            $totalGradingNetWeight += $row['grading_net_weight'];
            $totalNetWeight += $row['net_weight'];

            $output .= '
                <tr>
                    <td style="text-align: center;">'.$row['created_datetime'].'</td>
                    <td style="text-align: center;">="'.$row['lot_no'].'"</td>
                    <td style="text-align: center;">'.$row['net_weight'].'</td>
                    <td style="text-align: center;"></td>
                    <td style="text-align: center;"></td>
                    <td style="text-align: center;">'."-".'</td>
                    <td style="text-align: center;">'.$row['grade'].'</td>
                    <td style="text-align: center;">'.$row['grading_net_weight'].'</td>
                    <td style="text-align: center;">'.$passRate.'</td>
                    <td style="text-align: center;">'.$row['pieces'].'</td>
                    <td style="text-align: center;"></td>
                    <td style="text-align: center;background-color: #FCE4D6;">'.$row['grading_datetime'].'</td>
                    <td style="text-align: center;background-color: #FCE4D6;">'.$row['grading_net_weight'].'</td>
                    <td style="text-align: center;background-color: #FCE4D6;">'.$row['moisture_after_grading'].'</td>
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
                </tr>       
            ';
            // array_walk($lineData, 'filterData'); 
            // $excelData .= implode("\t", array_values($lineData)) . "\n"; 
        }

        $totalPassRatePerc = 0;
        $totalPassRatePerc = ($totalGradingNetWeight / $totalNetWeight) * 100;
        $output .= '
        <tr>
            <th style="text-align: center;">Total</th>
            <th style="text-align: center;">="'.$countLotNo.'"</th>
            <th style="text-align: center;"></th>
            <th style="text-align: center;"></th>
            <th style="text-align: center;"></th>
            <th style="text-align: center;">'."-".'</th>
            <th style="text-align: center;"></th>
            <th style="text-align: center;"></th>
            <th style="text-align: center;">'.$totalPassRate .'</th>
            <th style="text-align: center;"></th>
            <th style="text-align: center;"></th>
            <th style="text-align: center;background-color: #FCE4D6;"></th>
            <th style="text-align: center;background-color: #FCE4D6;"></th>
            <th style="text-align: center;background-color: #FCE4D6;"></th>
            <th style="text-align: center;background-color: #FCE4D6;"></th>
            <th style="text-align: center;background-color: #FCE4D6;"></th>
            <th style="text-align: center;background-color: #FCE4D6;">'.$totalMoistureNetWeight.'</th>
            <th style="text-align: center;background-color: #FCE4D6;"></th>
            <th style="text-align: center;background-color: #FCE4D6;">'.$lossWeight.'</th>
            <th style="text-align: center;background-color: #FCE4D6;">'.$lossWeightPerc.'</th>
            <th style="text-align: center;background-color: #FCE4D6;"></th>
            <th style="text-align: center;background-color: #D9E1F2;"></th>
            <th style="text-align: center;background-color: #D9E1F2;"></th>
            <th style="text-align: center;background-color: #D9E1F2;"></th>
            <th style="text-align: center;background-color: #D9E1F2;"></th>
            <th style="text-align: center;background-color: #D9E1F2;"></th>
            <th style="text-align: center;background-color: #D9E1F2;"></th>
            <th style="text-align: center;background-color: #D9E1F2;"></th>
            <th style="text-align: center;background-color: #D9E1F2;"></th>
            <th style="text-align: center;background-color: #D9E1F2;"></th>
            <th style="text-align: center;background-color: #D9E1F2;"></th>
        </tr> 
        <tr>
        </tr>
        <tr>
        </tr>
        <tr>
            <th colspan="2" >CONCLUSION</th>
            <th>GRAM</th>
            <th>%</th>
        </tr>
        <tr>
            <th rowspan="3" >QC</th>
            <th>TOTAL BATCH</th>
            <th>'.$countLotNo.'</th>
            <th>-</th>
        </tr>
        <tr>
            <th>TOTAL  WEIGHT</th>
            <th>'.$totalNetWeight.'</th>
            <th></th>
        </tr>
        <tr>
            <th>PASS RATE</th>
            <th>'.$totalGradingNetWeight.'</th>
            <th>'.$totalPassRatePerc.'</th>
        </tr>                
        <tr>
            <th rowspan="4">DRYING / HUMIFIYING</th>
            <th>TOTAL BATCH</th>
            <th></th>
            <th>-</th>
        </tr>
        <tr>
            <th>TOTAL WEIGHT</th>
            <th></th>
            <th></th>
        </tr>
        <tr>
            <th>BROKEN</th>
            <th></th>
            <th></th>
        </tr>
        <tr>
            <th>YS</th>
            <th></th>
            <th></th>
        </tr>
        <tr>
            <th rowspan="4">P2 </th>
            <th>TOTAL BATCH</th>
            <th></th>
            <th>-</th>
        </tr>
        <tr>
            <th>BROKEN</th>
            <th></th>
            <th></th>
        </tr>
        <tr>
            <th>YS</th>
            <th></th>
            <th></th>
        </tr>
        <tr>
            <th>LOSS</th>
            <th></th>
            <th></th>
        </tr>
        <tr>
        </tr>       
        </table>';
    }else{ 
        $output .= 'No records found...'. "\n"; 
    }

}
 
// Headers for download 
header("Content-Type: application/vnd.ms-excel; charset=utf-8"); 
header("Content-Disposition: attachment; filename=\"$fileName\""); 

// Render excel data 
// $str = utf8_decode($excelData);
echo $output; 
 
exit;
?>

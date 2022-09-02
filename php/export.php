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

if($query->num_rows > 0){ 
    $output .= '
    <table class="table" border="1">
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
            <th>Date<br>日期</th>
            <th>Weight<br>重量 (g)</th>
            <th>Moist<br>水份<br>(g)</th>
            <th>Date<br>日期</th>
            <th>GRADE<br>等级</th>
            <th>Weight<br>重量 (g)</th>
            <th>Moist<br>水份</th>
            <th>Weight<br>重量<br>(g) *compare to stock in</th>
            <th>Percentage<br>比例<br>(%)</th>
            <th>Remark<br>备注</th>
            <th>Date<br>日期</th>
            <th>Form No<br>表格号码</th>
            <th>Box No<br>盒号</th>
            <th>GRADE<br>等级</th>
            <th>Weight<br>重量<br>(g)</th>
            <th>Qty<br>片<br>(pcs)</th>
            <th>Weight<br>重量<br>(g)</th>
            <th>Qty<br>片<br>(pcs)</th>
            <th>Percentage<br>比例<br>(≤1%)</th>
            <th>Remark<br>备注</th>
        </tr>

    ';

    // Output each row of the data linkgoog
    while($row = $query->fetch_assoc()){ 

        $passRate = 0.00;
        $lossWeightPerc = 0.00;
        if($row['net_weight'] > 0 && $row['grading_net_weight'] > 0){
            $passRate = $row['net_weight'] / $row['grading_net_weight'];
        }

        $lossWeight = $row['moisture_net_weight'] - $row['grading_net_weight'];

        if($row['moisture_net_weight'] > 0 && $row['grading_net_weight'] > 0){
            $lossWeightPerc = ($row['moisture_net_weight'] - $row['grading_net_weight']) / 100;
        }

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
                <td style="text-align: center;">'.$row['created_datetime'].'</td>
                <td style="text-align: center;">'.$row['grading_net_weight'].'</td>
                <td style="text-align: center;">'.$row['moisture_after_grading'].'</td>
                <td style="text-align: center;">'.$row['created_datetime'].'</td>
                <td style="text-align: center;">'.$row['grade'].'</td>
                <td style="text-align: center;">'.$row['moisture_net_weight'].'</td>
                <td style="text-align: center;">'.$row['moisture_after_moisturing'].'</td>
                <td style="text-align: center;">'.$lossWeight.'</td>
                <td style="text-align: center;">'.$lossWeightPerc.'</td>
                <td style="text-align: center;">'.$row['remark'].'</td>
                <td style="text-align: center;"></td>
                <td style="text-align: center;"></td>
                <td style="text-align: center;"></td>
                <td style="text-align: center;"></td>
                <td style="text-align: center;"></td>
                <td style="text-align: center;"></td>
                <td style="text-align: center;"></td>
                <td style="text-align: center;"></td>
                <td style="text-align: center;"></td>
                <td style="text-align: center;"></td>
            </tr>
    
        ';
        // array_walk($lineData, 'filterData'); 
        // $excelData .= implode("\t", array_values($lineData)) . "\n"; 
    }
    $output .= '</table>';
}else{ 
    $output .= 'No records found...'. "\n"; 
} 
 
// Headers for download 
header("Content-Type: application/vnd.ms-excel; charset=utf-8"); 
header("Content-Disposition: attachment; filename=\"$fileName\""); 

// Render excel data 
// $str = utf8_decode($excelData);
echo $output; 
 
exit;
?>

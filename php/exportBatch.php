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
$itemtype = "T4";

if($_GET['batch'] != null && $_GET['batch'] != '' && $_GET['batch'] != '-'){
    $searchQuery = "weighing.lot_no = '".$_GET['batch']."' AND parent_no <> '0'";

    // Fetch item types
    $itemtype = $db->query("select DISTINCT item_types FROM `weighing` WHERE lot_no = '" .$_GET['batch']."'");
}

// Fetch records from database
$query = $db->query("select * from weighing WHERE ".$searchQuery);

if($itemtype == 'T4'){
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
                <th style="background-color: #FCE4D6s;"></th>
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
}
else if($itemtype == 'T3'){
    if($query->num_rows > 0){
        $searchQuery2 = "weighing.lot_no = '".$_GET['batch']."' AND parent_no = '0'";
        $createdDate = date('Y-m-d');

        // Fetch records from database
        $query2 = $db->query("select * from weighing WHERE ".$searchQuery2);

        if($row2 = $query2->fetch_assoc()){ 
            $createdDate = substr($row2['created_datetime'], 0, 10);
        }

        $marketGrades = array();
        $grades = array();
        $gradesCheck = array();
        $marketGradesCheck = array();
        $totalPieces = 0;
        $totalWeight = 0.00;
        $temp = '';

        while($row = $query->fetch_assoc()){
            if($row['status'] == "PASSED"){
                if(!in_array($row['grade'], $gradesCheck)){
                    $grades[] = array( 
                        'gradeId' => $row['grade'],
                        'gradeName' => $row['grade'],
                        'pieces' => 0,
                        'weight' => 0,
                        'status' => $row['status']
                    );
    
                    array_push($gradesCheck, $row['grade']);
                }

                $key = array_search($row['grade'], $gradesCheck);
                $grades[$key]['pieces'] += (int)$row['pieces'];
                $grades[$key]['weight'] += (float)$row['grading_net_weight'];
                $totalPieces += (int)$row['pieces'];
                $totalWeight += (float)$row['grading_net_weight'];
            }
            else{
                if(!in_array($row['reasons'], $gradesCheck)){
                    $grades[] = array( 
                        'gradeId' => $row['reasons'],
                        'gradeName' => $row['reasons'],
                        'pieces' => 0,
                        'weight' => 0,
                        'status' => $row['status']
                    );
    
                    array_push($gradesCheck, $row['reasons']);
                }

                $key = array_search($row['reasons'], $gradesCheck);
                $grades[$key]['pieces'] += (int)$row['pieces'];
                $grades[$key]['weight'] += (float)$row['grading_net_weight'];
                $totalPieces += (int)$row['pieces'];
                $totalWeight += (float)$row['grading_net_weight'];
            }
        }

        for($i=0; $i<count($grades); $i++){
            if($grades[$i]['status'] == 'PASSED'){
                $query3 = $db->query("select * from grades WHERE id = '" .$grades[$i]['gradeId']."'");

                if($row3 = $query3->fetch_assoc()){ 
                    if(!in_array('PASSED', $marketGradesCheck)){
                        $marketGrades[] = array( 
                            'market' => 'PASSED',
                            'grades' => array(),
                            'totaWeight' => 0.00,
                            'weightPerc' => 0.00,
                        );
        
                        array_push($marketGradesCheck, 'PASSED');
                    }
    
                    $key2 = array_search($row3['market'], $marketGradesCheck);
                    $grades[$i]['gradeName'] = $row3['grade'];
                    $marketGrades[$key2]['totaWeight'] += (float)$grades[$i]['weight'];
                    $marketGrades[$key2]['weightPerc'] = ($marketGrades[$key2]['totaWeight'] / $totalWeight) * 100;
                    array_push($marketGrades[$key2]['grades'], $grades[$i]);
                }
            }
            else{
                $query3 = $db->query("select * from reasons WHERE id = '" .$grades[$i]['gradeId']."'");

                if($row3 = $query3->fetch_assoc()){ 
                    if(!in_array('REJECT', $marketGradesCheck)){
                        $marketGrades[] = array( 
                            'market' => 'REJECT',
                            'grades' => array(),
                            'totaWeight' => 0.00,
                            'weightPerc' => 0.00,
                        );
        
                        array_push($marketGradesCheck, 'REJECT');
                    }
    
                    $key2 = array_search($row3['market'], $marketGradesCheck);
                    $grades[$i]['gradeName'] = $row3['reasons'];
                    $marketGrades[$key2]['totaWeight'] += (float)$grades[$i]['weight'];
                    $marketGrades[$key2]['weightPerc'] = ($marketGrades[$key2]['totaWeight'] / $totalWeight) * 100;
                    array_push($marketGrades[$key2]['grades'], $grades[$i]);
                }
            }
            
        }

        $output .= '<table width="1178">
            <tbody>
                <tr>
                    <td colspan="8" width="630">Raw Clean EBN Grading Report 净燕分级报告</td>
                </tr>
                <tr>
                    <td colspan="2" width="154">Received Date</td>
                    <td colspan="2" rowspan="2" width="168">'.$createdDate.'</td>
                    <td colspan="2" width="154">Date Out</td>
                    <td colspan="2" rowspan="2" width="154">'.date('Y-m-d').'</td>
                </tr>
                <tr>
                    <td colspan="2" width="154">收货日期</td>
                    <td colspan="2" width="154">出货日期</td>
                </tr>
                <tr>
                    <td colspan="2" width="154">Lot Number</td>
                    <td colspan="2" rowspan="2" width="168">'.$_GET['batch'].'</td>
                    <td colspan="2" width="154">Item</td>
                    <td colspan="2" rowspan="2" width="154"></td>
                </tr>
                <tr>
                    <td colspan="2" width="154">原料批次号</td>
                    <td colspan="2" width="154">品项</td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="8" width="630">Grading Summary： O 1st QC / O After Individual Pack / O Other: </td>
                </tr>
                <tr>
                    <td>Status</td>
                    <td colspan="2">Item description</td>
                    <td>Qty (pcs)</td>
                    <td colspan="2">Weight</td>
                    <td>Total Weight</td>
                    <td>Weight Percentage</td>
                </tr>
                <tr>
                    <td>等级</td>
                    <td colspan="2">种类</td>
                    <td>片数 (pcs)</td>
                    <td colspan="2">重量 (g)</td>
                    <td>总重量 (g)</td>
                    <td>巴仙 (%)</td>
                </tr>';

        for($j=0; $j<count($marketGrades); $j++){
            $temp .= '<tr><td rowspan="'.count($marketGrades[$j]['grades']).'">'.$marketGrades[$j]['market'].'</td>';

            for($l=0; $l<count($marketGrades[$j]['grades']); $l++){
                if($l == 0){
                    $temp .= '<td>'.$marketGrades[$j]['grades'][$l]['gradeName'].'</td>
                    <td>'.$marketGrades[$j]['grades'][$l]['pieces'].'</td>
                    <td>'.$marketGrades[$j]['grades'][$l]['weight'].'</td>
                    <td rowspan="'.count($marketGrades[$j]['grades']).'">'.$marketGrades[$j]['totaWeight'].'</td>
                    <td rowspan="'.count($marketGrades[$j]['grades']).'"">'.$marketGrades[$j]['weightPerc'].' %</td></tr>'; 
                }
                else{
                    $temp .= '<tr>
                    <td>'.$marketGrades[$j]['grades'][$l]['gradeName'].'</td>
                    <td>'.$marketGrades[$j]['grades'][$l]['pieces'].'</td>
                    <td>'.$marketGrades[$j]['grades'][$l]['weight'].'</td></tr>';
                }
            }
        }
        
        $output .= $temp;
        $output .= '<tr>
            <td colspan="3">Lab Test 化验样本</td>
            <td></td>
            <td colspan="2"></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="3">Cooking / Soaking Test 炖煮/泡发样本</td>
            <td></td>
            <td colspan="2"></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="3">Total 总数</td>
            <td>'.$totalPieces.'</td>
            <td colspan="2">'.$totalWeight.'</td>
            <td>'.$totalWeight.'</td>
            <td>100.0</td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2">Moisture Report</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2">Supplier Weight (A)</td>
            <td>Weight 重量 (g)</td>
            <td colspan="2">Supplier Loss (B-A)/A</td>
            <td>Weight 重量  (g)</td>
            <td>Percentage 比例 (%)</td>
            <td></td>
        </tr>

        <tr>
            <td colspan="2">Supplier Weight (A)</td>
            <td></td>
            <td colspan="2">Supplier Loss (B-A)/A</td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2">Reweight after Dry / 1st QC (B)</td>
            <td></td>
            <td colspan="2">Grading / IP Loss (C-B)/B</td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2">Weight after QC / IP (C.)</td>
            <td></td>
            <td colspan="2">Total Loss (C-A)/A</td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2" width="154">Weight By 称重人，</td>
            <td colspan="2" width="168">Reviewed By 复核人，</td>
            <td colspan="2" width="154">Received By 收货人，</td>
        </tr>
        <tr>
            <td width="77"></td>
            <td width="77"></td>
            <td width="84"></td>
        </tr>
        <tr>
            <td width="77"></td>
            <td width="84"></td>
            <td width="84"></td>
        </tr>
        <tr>
            <td width="77"></td>
            <td width="77"></td>
            <td width="84"></td>
        </tr>
        <tr>
            <td width="77">Date日期：</td>
            <td width="77"></td>
            <td width="84">Date日期：</td>
            <td width="84"></td>
            <td width="77">Date日期：</td>
            <td width="77"></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2" rowspan="2" width="154">Purchase Weight <br />采购量 (g):</td>
            <td colspan="2" rowspan="2"></td>
            <td colspan="2" rowspan="2" width="154">Actual Stock In Weight 实际入库量 (g):</td>
            <td colspan="2" rowspan="2"></td>
        </tr></tbody></table>';
    }
}
else{
    //T1 template
    if($query->num_rows > 0){
        $searchQuery2 = "weighing.lot_no = '".$_GET['batch']."' AND parent_no = '0'";
        $createdDate = date('Y-m-d');

        // Fetch records from database
        $query2 = $db->query("select * from weighing WHERE ".$searchQuery2);

        if($row2 = $query2->fetch_assoc()){ 
            $createdDate = substr($row2['created_datetime'], 0, 10);
        }

        $marketGrades = array();
        $grades = array();
        $gradesCheck = array();
        $marketGradesCheck = array();
        $totalPieces = 0;
        $totalWeight = 0.00;
        $temp = '';

        while($row = $query->fetch_assoc()){
            if($row['status'] == "PASSED"){
                if(!in_array($row['grade'], $gradesCheck)){
                    $grades[] = array( 
                        'gradeId' => $row['grade'],
                        'gradeName' => $row['grade'],
                        'pieces' => 0,
                        'weight' => 0,
                    );
    
                    array_push($gradesCheck, $row['grade']);
                }

                $key = array_search($row['grade'], $gradesCheck);
                $grades[$key]['pieces'] += (int)$row['pieces'];
                $grades[$key]['weight'] += (float)$row['grading_net_weight'];
                $totalPieces += (int)$row['pieces'];
                $totalWeight += (float)$row['grading_net_weight'];
            }
        }

        for($i=0; $i<count($grades); $i++){
            $query3 = $db->query("select * from grades WHERE id = '" .$grades[$i]['gradeId']."'");

            if($row3 = $query3->fetch_assoc()){ 
                if(!in_array($row3['market'], $marketGradesCheck)){
                    $marketGrades[] = array( 
                        'market' => $row3['market'],
                        'grades' => array(),
                        'totaWeight' => 0.00,
                        'weightPerc' => 0.00,
                    );
    
                    array_push($marketGradesCheck, $row3['market']);
                }

                $key2 = array_search($row3['market'], $marketGradesCheck);
                $grades[$i]['gradeName'] = $row3['grade'];
                $marketGrades[$key2]['totaWeight'] += (float)$grades[$i]['weight'];
                $marketGrades[$key2]['weightPerc'] = ($marketGrades[$key2]['totaWeight'] / $totalWeight) * 100;
                array_push($marketGrades[$key2]['grades'], $grades[$i]);
            }
        }

        $output .= '
        <table width="1178">
            <tbody>
                <tr>
                    <td colspan="8" width="630">Raw Material Sorting Report 原料分级报告</td>
                    <td width="66"></td>
                    <td width="66"></td>
                    <td width="66"></td>
                    <td width="66"></td>
                    <td width="77"></td>
                    <td width="74"></td>
                    <td width="67"></td>
                    <td width="66"></td>
                </tr>
                <tr>
                    <td colspan="2" width="154">Batch No</td>
                    <td colspan="2" rowspan="2" width="168">'.$_GET['batch'].'</td>
                    <td colspan="2" width="154">Reported Date</td>
                    <td colspan="2" rowspan="2" width="154">'.date('Y-m-d').'</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="2" width="154">批次号</td>
                    <td colspan="2" width="154">报告日期</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="2" width="154">Received Date</td>
                    <td colspan="2" rowspan="2" width="168">'.$createdDate.'</td>
                    <td colspan="2" width="154">Stock Out Moisture (%)</td>
                    <td colspan="2" rowspan="2" width="154"></td>
                    <td width="66"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="2" width="154">收货日期</td>
                    <td colspan="2" width="154">入库水份 (%)</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="2" width="154">Drying Period</td>
                    <td colspan="2" rowspan="2" width="168"></td>
                    <td colspan="2">GRN No:</td>
                    <td colspan="2" rowspan="2" width="154"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="2" width="154">风干时间</td>
                    <td colspan="2">入库单号</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td width="77"></td>
                    <td colspan="2" width="168"></td>
                    <td colspan="2" width="154"></td>
                    <td colspan="2" width="154"></td>
                    <td width="66"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td width="77">Market Grade</td>
                    <td width="77">Grade</td>
                    <td width="84">Quantity</td>
                    <td>Weight</td>
                    <td width="77">Total Weight</td>
                    <td width="77">Weight</td>
                    <td colspan="2" width="154">Remarks</td>
                </tr>
                <tr>
                    <td>市场规格</td>
                    <td>等级</td>
                    <td>片数 (pcs)</td>
                    <td>重量 (g)</td>
                    <td>总重量 (g)</td>
                    <td>巴仙 (%)</td>
                    <td colspan="2">备注</td>
                </tr>';

        for($j=0; $j<count($marketGrades); $j++){
            $temp .= '<tr><td rowspan="'.count($marketGrades[$j]['grades']).'" width="77">'.$marketGrades[$j]['market'].'</td>';

            for($l=0; $l<count($marketGrades[$j]['grades']); $l++){
                if($l == 0){
                    $temp .= '<td width="77">'.$marketGrades[$j]['grades'][$l]['gradeName'].'</td>
                    <td width="84">'.$marketGrades[$j]['grades'][$l]['pieces'].'</td>
                    <td width="84">'.$marketGrades[$j]['grades'][$l]['weight'].'</td>
                    <td rowspan="'.count($marketGrades[$j]['grades']).'" width="77">'.$marketGrades[$j]['totaWeight'].'</td>
                    <td rowspan="'.count($marketGrades[$j]['grades']).'" width="77">'.$marketGrades[$j]['weightPerc'].' %</td>
                    <td></td></tr>'; 
                }
                else{
                    $temp .= '<tr>
                    <td width="77">'.$marketGrades[$j]['grades'][$l]['gradeName'].'</td>
                    <td width="84">'.$marketGrades[$j]['grades'][$l]['pieces'].'</td>
                    <td width="84">'.$marketGrades[$j]['grades'][$l]['weight'].'</td>
                    <td></td></tr>';
                }
            }
        }

        $output .= $temp;
        $output .= '<tr>
                    <td colspan="2" width="154">Total :</td>
                    <td width="84">'.$totalPieces.'</td>
                    <td width="84">'.$totalWeight.'</td>
                    <td width="77">'.$totalWeight.'</td>
                    <td width="77">100%</td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td width="77"></td>
                    <td width="84"></td>
                    <td width="84"></td>
                    <td width="77"></td>
                    <td width="77"></td>
                    <td width="77"></td>
                    <td width="77"></td>
                    <td></td>
                    <td width="66"></td>
                    <td></td>
                    <td></td>
                    <td width="77"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="2">Moisture Report</td>
                    <td width="84"></td>
                    <td width="84"></td>
                    <td width="77"></td>
                    <td width="77"></td>
                    <td width="77"></td>
                    <td width="77"></td>
                    <td></td>
                    <td></td>
                    <td width="66"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="4">Item （种类）</td>
                    <td colspan="2" width="154">Weight 重量(g)</td>
                    <td colspan="2" width="154">Percentage 比例 (%)</td>
                </tr>
                <tr>
                    <td colspan="4" width="322">Supplier Weight 供应商重量, g (A)</td>
                    <td colspan="2" width="154"></td>
                    <td colspan="2" width="154"></td>
                </tr>
                <tr>
                    <td colspan="4" width="322">
                        <table>
                            <tbody>
                                <tr>
                                    <td colspan="4" width="322">O Reweight / O Dried Weight 吹干后重量, g (B)</td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                    <td colspan="2" width="154"></td>
                    <td colspan="2" width="154"></td>
                </tr>
                <tr>
                    <td colspan="4" width="322">After Sorting Weight 分级后重量， g (C)</td>
                    <td colspan="2" width="154"></td>
                    <td colspan="2" width="154"></td>
                </tr>
                <tr>
                    <td colspan="4" width="322">Moisture Loss 水份损失, g (B-A)</td>
                    <td colspan="2" width="154"></td>
                    <td colspan="2" width="154"></td>
                </tr>
                <tr>
                    <td colspan="4" width="322">Sorting Loss 分级损失, g (C-B)</td>
                    <td colspan="2" width="154"></td>
                    <td colspan="2" width="154"></td>
                </tr>
                <tr>
                    <td colspan="4" width="322">Total Loss (C-A)</td>
                    <td colspan="2" width="154"></td>
                    <td colspan="2" width="154"></td>
                </tr>
                <tr>
                    <td></td>
                    <td width="77"></td>
                    <td width="84"></td>
                    <td width="84"></td>
                    <td width="77"></td>
                    <td width="77"></td>
                    <td width="77"></td>
                    <td width="77"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="2" width="154">Weight By 称重人，</td>
                    <td colspan="2" width="168">Reviewed By 复核人，</td>
                    <td colspan="2" width="154">Received By 收货人，</td>
                    <td>Note:</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td width="77"></td>
                    <td width="77"></td>
                    <td width="84"></td>
                    <td width="84"></td>
                    <td width="77"></td>
                    <td width="77"></td>
                    <td colspan="2" width="154">Receiving moisture</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td width="77"></td>
                    <td width="84"></td>
                    <td width="84"></td>
                    <td width="77"></td>
                    <td width="77"></td>
                    <td colspan="2" width="154"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td width="77"></td>
                    <td width="77"></td>
                    <td width="84"></td>
                    <td width="84"></td>
                    <td width="77"></td>
                    <td width="77"></td>
                    <td width="77"></td>
                    <td width="77"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td width="77">Date日期：</td>
                    <td width="77"></td>
                    <td width="84">Date日期：</td>
                    <td width="84"></td>
                    <td width="77">Date日期：</td>
                    <td width="77"></td>
                    <td width="77"></td>
                    <td width="77"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td width="77"></td>
                    <td width="77"></td>
                    <td width="84"></td>
                    <td width="84"></td>
                    <td width="77"></td>
                    <td width="77"></td>
                    <td width="77"></td>
                    <td width="77"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="2" rowspan="2" width="154">Purchase Weight <br />采购量 (g):</td>
                    <td colspan="2" rowspan="2"></td>
                    <td colspan="2" rowspan="2" width="154">Actual Stock In Weight 实际入库量 (g):</td>
                    <td colspan="2" rowspan="2"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td width="77">REMARK:</td>
                    <td colspan="7" rowspan="2" width="553"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </tbody>
        </table>';
    }
}
 
// Headers for download 
header("Content-Type: application/vnd.ms-excel"); 
header("Content-Disposition: attachment; filename=\"$fileName\""); 
 
// Render excel data 
echo $output;
exit;
?>

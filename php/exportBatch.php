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
    $searchQuery = "weighing.lot_no = '".$_GET['batch']."'";

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
    $output .= 'T3';
}
else{
    //T1 template
    if($query->num_rows > 0){
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
                    <td colspan="2" rowspan="2" width="168">5-220104</td>
                    <td colspan="2" width="154">Reported Date</td>
                    <td colspan="2" rowspan="2" width="154">2/8/2022</td>
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
                    <td colspan="2" rowspan="2" width="168">1/4/2022</td>
                    <td colspan="2" width="154">Stock Out Moisture (%)</td>
                    <td colspan="2" rowspan="2" width="154">16-17%</td>
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
                    <td colspan="2" rowspan="2" width="168">27HR</td>
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
                    <td>Grading Summary: O 1st QC / O Other:</td>
                    <td width="77">
                        <table>
                            <tbody>
                                <tr>
                                    <td width="39"></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td width="25"></td>
                                </tr>
                                <tr>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                    <td width="84"></td>
                    <td width="84"></td>
                    <td width="77"></td>
                    <td width="77"></td>
                    <td width="77"></td>
                    <td width="77"></td>
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
                    <td></td>
                    <td></td>
                    <td></td>
                    <td colspan="3"></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>市场规格</td>
                    <td>等级</td>
                    <td>片数 (pcs)</td>
                    <td>重量 (g)</td>
                    <td>总重量 (g)</td>
                    <td>巴仙 (%)</td>
                    <td colspan="2">备注</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td width="66">pcs</td>
                    <td width="77">g</td>
                    <td width="74">RM</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td rowspan="4" width="77">A <br />180⁰</td>
                    <td width="77">AA-W</td>
                    <td width="84">7</td>
                    <td width="84">57</td>
                    <td rowspan="2" width="77">130</td>
                    <td rowspan="2" width="77">0.9%</td>
                    <td colspan="2" rowspan="21" width="154"></td>
                    <td></td>
                    <td width="66">AA-W</td>
                    <td>5.50</td>
                    <td width="66">7</td>
                    <td width="77">57</td>
                    <td>313.5</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td width="77">AA-Y</td>
                    <td width="84">10</td>
                    <td width="84">73</td>
                    <td></td>
                    <td width="66">AA-Y</td>
                    <td>4.50</td>
                    <td width="66">10</td>
                    <td width="77">73</td>
                    <td>328.5</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td width="77">AB-W</td>
                    <td width="84">39</td>
                    <td width="84">297</td>
                    <td rowspan="2" width="77">1,094</td>
                    <td rowspan="2" width="77">7.6%</td>
                    <td></td>
                    <td width="66">AB-W</td>
                    <td>4.00</td>
                    <td width="66">39</td>
                    <td width="77">297</td>
                    <td>1188</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td width="77">AB-Y</td>
                    <td width="84">104</td>
                    <td width="84">797</td>
                    <td></td>
                    <td width="66">AB-Y</td>
                    <td>3.40</td>
                    <td width="66">104</td>
                    <td width="77">797</td>
                    <td>2709.8</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td rowspan="4" width="77">B<br />165⁰,135⁰</td>
                    <td width="77">BA -W</td>
                    <td width="84">39</td>
                    <td width="84">286</td>
                    <td rowspan="2" width="77">715</td>
                    <td rowspan="2" width="77">4.9%</td>
                    <td></td>
                    <td width="66">BA -W</td>
                    <td>3.70</td>
                    <td width="66">39</td>
                    <td width="77">286</td>
                    <td>1058.2</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td width="77">BA -Y</td>
                    <td width="84">62</td>
                    <td width="84">429</td>
                    <td></td>
                    <td width="66">BA -Y</td>
                    <td>3.30</td>
                    <td width="66">62</td>
                    <td width="77">429</td>
                    <td>1415.7</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td width="77">BB -W</td>
                    <td width="84">92</td>
                    <td width="84">633</td>
                    <td rowspan="2" width="77">2,071</td>
                    <td rowspan="2" width="77">14.3%</td>
                    <td></td>
                    <td width="66">BB -W</td>
                    <td>3.70</td>
                    <td width="66">92</td>
                    <td width="77">633</td>
                    <td>2342.1</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td width="77">BB -Y</td>
                    <td width="84">196</td>
                    <td width="84">1,438</td>
                    <td></td>
                    <td width="66">BB -Y</td>
                    <td>3.00</td>
                    <td width="66">196</td>
                    <td width="77">1,438</td>
                    <td>4314</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td rowspan="5" width="77">C</td>
                    <td width="77">C -W</td>
                    <td width="84">120</td>
                    <td width="84">900</td>
                    <td rowspan="2" width="77">3,311</td>
                    <td rowspan="2" width="77">22.9%</td>
                    <td></td>
                    <td width="66">C -W</td>
                    <td>2.60</td>
                    <td width="66">120</td>
                    <td width="77">900</td>
                    <td>2340</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td width="77">C -Y</td>
                    <td width="84">321</td>
                    <td width="84">2,411</td>
                    <td></td>
                    <td width="66">C -Y</td>
                    <td>2.60</td>
                    <td width="66">321</td>
                    <td width="77">2,411</td>
                    <td>6268.6</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td width="77">C1-W</td>
                    <td width="84">0</td>
                    <td width="84">0</td>
                    <td rowspan="2" width="77">5,811</td>
                    <td rowspan="2" width="77">40.2%</td>
                    <td></td>
                    <td width="66">C1-W</td>
                    <td>3.00</td>
                    <td width="66"></td>
                    <td width="77"></td>
                    <td>0</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td width="77">C1</td>
                    <td width="84">832</td>
                    <td width="84">5,811</td>
                    <td></td>
                    <td width="66">C1</td>
                    <td>3.00</td>
                    <td width="66">832</td>
                    <td width="77">5,811</td>
                    <td>17433</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>H (small)</td>
                    <td width="84">74</td>
                    <td width="84">380</td>
                    <td width="77">380</td>
                    <td width="77">2.6%</td>
                    <td></td>
                    <td>H (small)</td>
                    <td>2.50</td>
                    <td>74</td>
                    <td width="77">380</td>
                    <td>950</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td rowspan="7">Other</td>
                    <td width="77">Double Layer</td>
                    <td width="84">0</td>
                    <td width="84">0</td>
                    <td rowspan="5" width="77">878</td>
                    <td rowspan="5" width="77">6.1%</td>
                    <td></td>
                    <td>Double Layer</td>
                    <td>2.5</td>
                    <td width="66"></td>
                    <td width="77"></td>
                    <td>0</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td width="77">Dropping</td>
                    <td width="84">53</td>
                    <td width="84">393</td>
                    <td></td>
                    <td width="66">Dropping</td>
                    <td>2.5</td>
                    <td width="66">53</td>
                    <td width="77">393</td>
                    <td>982.5</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td width="77">H</td>
                    <td width="84">5</td>
                    <td width="84">31</td>
                    <td></td>
                    <td width="66">H</td>
                    <td>2.50</td>
                    <td width="66">5</td>
                    <td width="77">31</td>
                    <td>77.5</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td width="77">L</td>
                    <td width="84">12</td>
                    <td width="84">96</td>
                    <td></td>
                    <td width="66">L</td>
                    <td>2.5</td>
                    <td width="66">12</td>
                    <td width="77">96</td>
                    <td>240</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td width="77">J</td>
                    <td width="84">50</td>
                    <td width="84">358</td>
                    <td></td>
                    <td width="66">J</td>
                    <td>2.30</td>
                    <td width="66">50</td>
                    <td width="77">358</td>
                    <td>823.4</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td width="77">M1</td>
                    <td width="84">0</td>
                    <td width="84">20</td>
                    <td rowspan="2" width="77">64</td>
                    <td rowspan="2" width="77">0.4%</td>
                    <td></td>
                    <td width="66">M1</td>
                    <td>1.8</td>
                    <td width="66"></td>
                    <td width="77">20</td>
                    <td>36</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td width="77">M2</td>
                    <td width="84">0</td>
                    <td width="84">44</td>
                    <td></td>
                    <td width="66">M2</td>
                    <td>1</td>
                    <td width="66"></td>
                    <td width="77">44</td>
                    <td>44</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="2" width="154">Total :</td>
                    <td width="84">2,016</td>
                    <td width="84">14,454</td>
                    <td width="77">14,454</td>
                    <td width="77">100%</td>
                    <td></td>
                    <td width="66"></td>
                    <td width="66"></td>
                    <td width="66"></td>
                    <td width="77">14,454.00</td>
                    <td width="74">42,864.80</td>
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
                    <td width="77"></td>
                    <td width="77"></td>
                    <td></td>
                    <td width="66"></td>
                    <td></td>
                    <td>E.RM/g</td>
                    <td width="77"></td>
                    <td> 2.97</td>
                    <td>wit rej</td>
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
                    <td>wto rej</td>
                    <td></td>
                    <td> 3.01</td>
                    <td>wto rej</td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="4">Item （种类）</td>
                    <td colspan="2" width="154">Weight 重量(g)</td>
                    <td colspan="2" width="154">Percentage 比例 (%)</td>
                    <td></td>
                    <td width="66"></td>
                    <td width="66"></td>
                    <td>moist</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="4" width="322">Supplier Weight 供应商重量, g (A)</td>
                    <td colspan="2" width="154">15,961</td>
                    <td colspan="2" width="154">-</td>
                    <td></td>
                    <td width="66"></td>
                    <td width="66"></td>
                    <td>SW</td>
                    <td> 15,961.0</td>
                    <td>-</td>
                    <td>15,961.0</td>
                    <td>-</td>
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
                    <td colspan="2" width="154">15,961</td>
                    <td colspan="2" width="154">-</td>
                    <td></td>
                    <td width="66"></td>
                    <td></td>
                    <td>RW</td>
                    <td> 15,961.0</td>
                    <td>-</td>
                    <td>15,961.0</td>
                    <td>-</td>
                </tr>
                <tr>
                    <td colspan="4" width="322">After Sorting Weight 分级后重量， g (C)</td>
                    <td colspan="2" width="154">14,454</td>
                    <td colspan="2" width="154">-</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>AS</td>
                    <td> 14,454.0</td>
                    <td></td>
                    <td>14,454.0</td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="4" width="322">Moisture Loss 水份损失, g (B-A)</td>
                    <td colspan="2" width="154">0</td>
                    <td colspan="2" width="154">0.0%</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>MOIST%</td>
                    <td> -</td>
                    <td>0.0</td>
                    <td> -</td>
                    <td>0.0</td>
                </tr>
                <tr>
                    <td colspan="4" width="322">Sorting Loss 分级损失, g (C-B)</td>
                    <td colspan="2" width="154">-1,507</td>
                    <td colspan="2" width="154">-9.4%</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>SL</td>
                    <td> (1,507.0)</td>
                    <td>-9.4</td>
                    <td> (1,507.0)</td>
                    <td>-9.4</td>
                </tr>
                <tr>
                    <td colspan="4" width="322">Total Loss (C-A)</td>
                    <td colspan="2" width="154">-1,507</td>
                    <td colspan="2" width="154">-9.4%</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>TL</td>
                    <td> (1,507.0)</td>
                    <td>-9.4</td>
                    <td> (1,507.0)</td>
                    <td>-9.4</td>
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
                    <td colspan="2" width="154">40-44%</td>
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
        </table>
    
        ';
    }
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

<?php
## Database configuration
require_once 'db_connect.php';

## Read value
$draw = $_POST['draw'];
$row = $_POST['start'];
$rowperpage = $_POST['length']; // Rows display per page
$columnIndex = $_POST['order'][0]['column']; // Column index
$columnName = $_POST['columns'][$columnIndex]['data']; // Column name
$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
$searchValue = mysqli_real_escape_string($db,$_POST['search']['value']); // Search value

## Search 
$searchQuery = " ";

if($_POST['fromDate'] != null && $_POST['fromDate'] != ''){
  $fromDate = new DateTime($_POST['fromDate']);
  $fromDateTime = date_format($fromDate,"Y-m-d H:i:s");
  $searchQuery = " and weighing.created_datetime >= '".$fromDateTime."'";
}

if($_POST['toDate'] != null && $_POST['toDate'] != ''){
  $toDate = new DateTime($_POST['toDate']);
  $toDateTime = date_format($toDate,"Y-m-d H:i:s");
	$searchQuery .= " and weighing.created_datetime <= '".$toDateTime."'";
}

if($_POST['itemTypeFilter'] != null && $_POST['itemTypeFilter'] != '' && $_POST['itemTypeFilter'] != '-'){
	$searchQuery .= " and weighing.item_Types = '".$_POST['itemTypeFilter']."'";
}

if($_POST['lotNo'] != null && $_POST['lotNo'] != '' && $_POST['lotNo'] != '-'){
	$searchQuery .= " and weighing.lot_no like '%".$_POST['lotNo']."%'";
}

## Total number of records without filtering
$sel = mysqli_query($db,"select count(*) as allcount from weighing WHERE parent_no <> '0'");
$records = mysqli_fetch_assoc($sel);
$totalRecords = $records['allcount'];

## Total number of record with filtering
$sel = mysqli_query($db,"select count(*) as allcount from weighing, grades WHERE parent_no <> '0' AND weighing.grade=grades.id". $searchQuery);
$records = mysqli_fetch_assoc($sel);
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$empQuery = "select weighing.lot_no, weighing.item_types, grades.grade, weighing.tray_no, weighing.tray_weight, weighing.grading_gross_weight, 
weighing.pieces, weighing.grading_net_weight, weighing.id, weighing.moisture_after_grading, weighing.status from weighing, grades WHERE 
parent_no <> '0' AND weighing.grade=grades.id".$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;

$empRecords = mysqli_query($db, $empQuery);
$data = array();
$counter = 1;

while($row = mysqli_fetch_assoc($empRecords)) {
  // if($row['outGDateTime'] == null || $row['outGDateTime'] == ''){
  //   $outGDateTime = '-';
  // }
  // else{
  //   $dateOut = new DateTime($row['outGDateTime']);
  //   $outGDateTime = date_format($dateOut,"d/m/Y H:i:s A");
  // }

  // if($row['inCDateTime'] == null || $row['inCDateTime'] == ''){
  //   $outGDateTime = '-';
  // }
  // else{
  //   $dateInt = new DateTime($row['inCDateTime']);
  //   $inCDateTime = date_format($dateInt,"d/m/Y H:i:s A");
  // }
    $data[] = array( 
      "counter"=>$counter,
      "lot_no"=>$row['lot_no'],
      "item_types"=>$row['item_types'],
      "grade"=>$row['grade'],
      "tray_no"=>$row['tray_no'],
      "tray_weight"=>$row['tray_weight'],
      "grading_gross_weight"=>$row['grading_gross_weight'],
      "pieces"=>$row['pieces'],
      "grading_net_weight"=>$row['grading_net_weight'],
      "id"=>$row['id'],
      "moisture_after_grading"=>$row['moisture_after_grading'],
      "status"=>$row['status'],
    );

  $counter++;

}

## Response
$response = array(
  "draw" => intval($draw),
  "iTotalRecords" => $totalRecords,
  "iTotalDisplayRecords" => $totalRecordwithFilter,
  "aaData" => $data
);

echo json_encode($response);

?>
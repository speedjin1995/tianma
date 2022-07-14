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
if($searchValue != ''){
  $searchQuery = " AND tray_no like '%".$searchValue."%'";
}

## Total number of records without filtering
$sel = mysqli_query($db,"select count(*) as allcount from weighing WHERE parent_no <> '0'");
$records = mysqli_fetch_assoc($sel);
$totalRecords = $records['allcount'];

## Total number of record with filtering
$sel = mysqli_query($db,"select count(*) as allcount from weighing WHERE parent_no <> '0' AND weighing.grade=grades.id");
$records = mysqli_fetch_assoc($sel);
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$empQuery = "select weighing.lot_no, grades.grade, weighing.tray_no, weighing.tray_weight, weighing.grading_gross_weight, 
weighing.pieces, weighing.grading_net_weight, weighing.id, weighing.moisture_after_grading from weighing, grades WHERE 
parent_no <> '0' AND weighing.grade=grades.id".$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
$empRecords = mysqli_query($db, $empQuery);
$data = array();
$counter = 1;
echo $empQuery;
while($row = mysqli_fetch_assoc($empRecords)) {
  $data[] = array( 
    "counter"=>$counter,
    "lot_no"=>$row['lot_no'],
    "grade"=>$row['grade'],
    "tray_no"=>$row['tray_no'],
    "tray_weight"=>$row['tray_weight'],
    "grading_gross_weight"=>$row['grading_gross_weight'],
    "pieces"=>$row['pieces'],
    "grading_net_weight"=>$row['grading_net_weight'],
    "id"=>$row['id'],
    "moisture_after_grading"=>$row['moisture_after_grading'],
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
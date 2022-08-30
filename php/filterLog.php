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
  $searchQuery = " where log.created_dateTime >= '".$fromDateTime."'";
}

if($_POST['toDate'] != null && $_POST['toDate'] != ''){
  $toDate = new DateTime($_POST['toDate']);
  $toDateTime = date_format($toDate,"Y-m-d H:i:s");
	$searchQuery .= " and log.created_dateTime <= '".$toDateTime."'";
}

if($_POST['filterUserName'] != null && $_POST['filterUserName'] != ''){
  $filterUserName = $_POST['filterUserName'];
	$searchQuery .= " and log.userName like '%".$filterUserName."%'";
}

## Total number of records without filtering
$sel = mysqli_query($db,"select count(*) as allcount from log");
$records = mysqli_fetch_assoc($sel);
$totalRecords = $records['allcount'];

## Total number of record with filtering
$sel = mysqli_query($db,"select count(*) as allcount from log".$searchQuery);
$records = mysqli_fetch_assoc($sel);
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$empQuery = "select * from log".$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;

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
      "id"=>$row['id'],
      "userId"=>$row['userId'],
      "userName"=>$row['userName'],
      "created_dateTime"=>$row['created_dateTime'],
      "action"=>$row['action']
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
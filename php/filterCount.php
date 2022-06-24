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
    $searchQuery = " and count.dateTime >= '".$_POST['fromDate']."'";
}

if($_POST['toDate'] != null && $_POST['toDate'] != ''){
  $searchQuery = " and count.dateTime <= '".$_POST['toDate']."'";
}

if($_POST['status'] != null && $_POST['status'] != '' && $_POST['status'] != '-'){
  $searchQuery = " and count.status = '".$_POST['status']."'";
}

if($_POST['customer'] != null && $_POST['customer'] != '' && $_POST['customer'] != '-'){
  $searchQuery = " and count.customer = '".$_POST['customer']."'";
}

if($_POST['vehicle'] != null && $_POST['vehicle'] != '' && $_POST['vehicle'] != '-'){
  $searchQuery = " and count.vehicleNo = '".$_POST['vehicle']."'";
}

if($_POST['invoice'] != null && $_POST['invoice'] != ''){
  $searchQuery = " and count.invoiceNo like '%".$_POST['invoice']."%'";
}

if($_POST['batch'] != null && $_POST['batch'] != ''){
  $searchQuery = " and count.batchNo like '%".$_POST['batch']."%'";
}

if($_POST['product'] != null && $_POST['product'] != '' && $_POST['product'] != '-'){
  $searchQuery = " and count.productName = '".$_POST['product']."'";
}

## Total number of records without filtering
$sel = mysqli_query($db,"select count(*) as allcount from count, vehicles, packages, lots, customers, products, units, status WHERE count.vehicleNo = vehicles.id AND count.package = packages.id AND count.lotNo = lots.id AND count.customer = customers.id AND count.productName = products.id AND status.id=count.status AND units.id=count.unit AND count.deleted = '0'");
$records = mysqli_fetch_assoc($sel);
$totalRecords = $records['allcount'];

## Total number of record with filtering
$sel = mysqli_query($db,"select count(*) as allcount from count, vehicles, packages, lots, customers, products, status, units WHERE count.vehicleNo = vehicles.id AND count.package = packages.id AND count.lotNo = lots.id AND count.customer = customers.id AND count.productName = products.id AND status.id=count.status AND units.id=count.unit AND count.deleted = '0'".$searchQuery);
$records = mysqli_fetch_assoc($sel);
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$empQuery = "select count.id, count.serialNo, vehicles.veh_number, lots.lots_no, count.batchNo, count.invoiceNo, count.deliveryNo, 
count.purchaseNo, customers.customer_name, products.product_name, packages.packages, count.unitWeight, count.tare, count.totalWeight, 
count.actualWeight, count.currentWeight, units.units, count.moq, count.dateTime, count.unitPrice, count.totalPrice,count.totalPCS, 
count.remark, status.status from count, vehicles, packages, lots, customers, products, units, status WHERE 
count.vehicleNo = vehicles.id AND count.package = packages.id AND count.lotNo = lots.id AND count.customer = customers.id AND 
count.productName = products.id AND status.id=count.status AND units.id=count.unit AND count.deleted = '0'".$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;

$empRecords = mysqli_query($db, $empQuery);
$data = array();

while($row = mysqli_fetch_assoc($empRecords)) {
  $data[] = array( 
    "id"=>$row['id'],
    "serialNo"=>$row['serialNo'],
    "veh_number"=>$row['veh_number'],
    "lots_no"=>$row['lots_no'],
    "batchNo"=>$row['batchNo'],
    "invoiceNo"=>$row['invoiceNo'],
    "deliveryNo"=>$row['deliveryNo'],
    "purchaseNo"=>$row['purchaseNo'],
    "customer_name"=>$row['customer_name'],
    "product_name"=>$row['product_name'],
    "packages"=>$row['packages'],
    "unitWeight"=>$row['unitWeight'],
    "tare"=>$row['tare'],
    "totalWeight"=>$row['totalWeight'],
    "actualWeight"=>$row['actualWeight'],
    "currentWeight"=>$row['currentWeight'],
    "unit"=>$row['units'],
    "moq"=>$row['moq'],
    "dateTime"=>$row['dateTime'],
    "unitPrice"=>$row['unitPrice'],
    "totalPrice"=>$row['totalPrice'],
    "totalPCS"=>$row['totalPCS'],
    "remark"=>$row['remark'],
    "status"=>$row['status']
  );
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
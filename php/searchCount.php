<?php
## Database configuration
require_once 'db_connect.php';
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

session_start();

## Read value
$draw = $_POST['draw'];
$row = $_POST['start'];
$rowperpage = $_POST['length']; // Rows display per page
$columnIndex = $_POST['order'][0]['column']; // Column index
$columnName = $_POST['columns'][$columnIndex]['data']; // Column name
$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
$searchValue = mysqli_real_escape_string($db,$_POST['search']['value']); // Search value

//
$fromDate = filter_input(INPUT_POST, 'fromDate', FILTER_SANITIZE_STRING);
$fromDate = filter_input(INPUT_POST, 'toDate', FILTER_SANITIZE_STRING);
$searchCustomer = filter_input(INPUT_POST, 'searchCustomer', FILTER_SANITIZE_STRING);
$searchStatus = filter_input(INPUT_POST, 'searchStatus', FILTER_SANITIZE_STRING);
$searchVehicleNo = filter_input(INPUT_POST, 'searchVehicleNo', FILTER_SANITIZE_STRING);
$searchInvoice = filter_input(INPUT_POST, 'searchInvoice', FILTER_SANITIZE_STRING);


## Search 
$searchQuery = " ";
if($searchValue != ''){
   $searchQuery = " and vehicles.veh_number like '%".$searchVehicleNo."%' ";
}

## Total number of records without filtering
$sel = mysqli_query($db,"select count(*) as allcount from count, vehicles, packages, lots, customers, products WHERE count.vehicleNo = vehicles.id AND count.package = packages.id AND count.lotNo = lots.id AND count.customer = customers.id AND count.productName = products.id");
$records = mysqli_fetch_assoc($sel);
$totalRecords = $records['allcount'];

## Total number of record with filtering
//echo "select count(*) as allcount from users, roles WHERE".$searchQuery;
$sel = mysqli_query($db,"select count(*) as allcount from count, vehicles, packages, lots, customers, products WHERE count.vehicleNo = vehicles.id AND count.package = packages.id AND count.lotNo = lots.id AND count.customer = customers.id AND count.productName = products.id".$searchQuery);
$records = mysqli_fetch_assoc($sel);
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$empQuery = "select count.serialNo, vehicles.veh_number, lots.lots_no, count.batchNo, count.invoiceNo, count.deliveryNo, count.purchaseNo, 
customers.customer_name, products.product_name, packages.packages, count.unitWeight, count.tare, count.totalWeight, count.actualWeight, count.currentWeight, 
count.unit, count.moq, count.dateTime, count.unitPrice, count.totalPrice,count.totalPCS, count.remark, count.status from count, 
vehicles, packages, lots, customers, products WHERE count.vehicleNo = vehicles.id AND count.package = packages.id AND 
count.lotNo = lots.id AND count.customer = customers.id AND count.productName = products.id".$searchQuery." 
order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;

$empRecords = mysqli_query($db, $empQuery);
$data = array();

while($row = mysqli_fetch_assoc($empRecords)) {
  $data[] = array( 
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
    "unit"=>$row['unit'],
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
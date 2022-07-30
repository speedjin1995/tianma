<?php
require_once 'db_connect.php';
include 'phpqrcode/qrlib.php';
 
// Filter the excel data 
function filterData(&$str){ 
    $str = preg_replace("/\t/", "\\t", $str); 
    $str = preg_replace("/\r?\n/", "\\n", $str); 
    if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"'; 
}

if($_GET["id"] != null && $_GET["id"] != ''){
    $id = $_GET["id"];

    if ($select_stmt = $db->prepare("SELECT * FROM weighing WHERE id=?")) {
        $select_stmt->bind_param('s', $id);

        // Execute the prepared query.
        if (! $select_stmt->execute()) {
            echo json_encode(
                array(
                    "status" => "failed",
                    "message" => "Something went wrong"
                )); 
        }
        else{
            $result = $select_stmt->get_result();
            $message = array();
                
            if ($row = $result->fetch_assoc()) {
                $message['id'] = $row['id'];
                $message['itemTypes'] = $row['item_types'];
                $message['trayWeight'] = $row['tray_weight'];
                $message['lotNo'] = $row['lot_no'];
                $message['moistureGrossWeight'] = $row['moisture_gross_weight'];
                $message['bTrayNo'] = $row['tray_no'];
                $message['moistureNetWeight'] = $row['moisture_net_weight'];
                $message['pieces'] = $row['pieces'];
                $message['moistureAfterMoisturing'] = $row['moisture_after_moisturing'];
                $message['parentNo'] = $row['parent_no'];
                $message['grossWeight'] = $row['gross_weight'];
                $message['netWeight'] = $row['net_weight'];
                $message['afterReceiving'] = $row['moisture_after_receiving'];
                $message['gradingGrossWeight'] = $row['grading_gross_weight'];
                $message['gradingNetWeight'] = $row['grading_net_weight'];
                $message['moistureAfterGrading'] = $row['moisture_after_grading'];
                $message['grade'] = $row['grade'];
                $message['remark'] = $row['remark'];
                
                echo json_encode(
                    array(
                        "status" => "success",
                        "message" => $message
                    )
                );   
            }
            else{
                echo json_encode(
                    array(
                        "status" => "failed",
                        "message" => 'Unable to read data'
                    )
                );
            }
            
            
        }
    }
    else{
        echo json_encode(
            array(
                "status" => "failed",
                "message" => "Something Goes Wrong"
            ));
    }
}
else{
    echo json_encode(
        array(
            "status"=> "failed", 
            "message"=> "Please fill in all the fields"
        )
    ); 
}

?>
<?php

session_start();
error_reporting(0);
include('includes/config.php');
    $vendor_id = $_POST['vendor_issued'];
    $item_ids = $_POST['item_name_issued'];
    $quantitys = $_POST['quantity_issued'];
    $challan = $_POST['challan_issued'];
    $date = $_POST['date_issued'];
    if($vendor_id != ""){
        foreach($item_ids as $item_id){
        foreach($quantitys as $quantity){

            $sql = "INSERT INTO item_issued_to_vendor(item_name_issued, quantity_issued, date_issued, challan_issued,
                    vendor_issued,total_item_left, created_at)
                    VALUES(:item_name,:item_quantity,:item_date,:challan_issued,:vendor_issued,:item_quantity,NOW())";
        $query = $dbh->prepare($sql);
        $query->bindParam(':item_name', $item_id, PDO::PARAM_STR);
        $query->bindParam(':item_quantity', $quantity, PDO::PARAM_STR);
        $query->bindParam(':item_date', $date, PDO::PARAM_STR);
        $query->bindParam(':challan_issued', $challan, PDO::PARAM_STR);
        $query->bindParam(':vendor_issued', $vendor_id, PDO::PARAM_STR);
        $query->execute();

        $lastInsertId = $dbh->lastInsertId();
        mysqli_query($connection, "INSERT INTO item_received_from_vendor(issued_id, total_item_left, updated_at) VALUES('$lastInsertId','$quantity', NOW())");
        }
    }
    echo json_encode('success');
    }
    
?>
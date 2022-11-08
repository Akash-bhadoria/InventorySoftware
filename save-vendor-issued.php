<?php

session_start();
error_reporting(0);
include('includes/config.php');
$vendor_id = $_POST['vendor_issued'];
$item_ids = $_POST['item_name_issued'];
$quantitys = $_POST['quantity_issued'];
$challan = $_POST['challan_issued'];
$date = $_POST['date_issued'];


if ($vendor_id != "") {
    foreach ($item_ids as $key => $item_id) {


        $sql = "INSERT INTO item_issued_to_vendor(item_name_issued, quantity_issued, date_issued, challan_issued,
                    vendor_issued,total_item_left, created_at)
                    VALUES(:item_name,:item_quantity,:item_date,:challan_issued,:vendor_issued,:item_quantity,NOW())";
        $query = $dbh->prepare($sql);
        $query->bindParam(':item_name', $item_id, PDO::PARAM_STR);
        $query->bindParam(':item_quantity', $quantitys[$key], PDO::PARAM_STR);
        $query->bindParam(':item_date', $date, PDO::PARAM_STR);
        $query->bindParam(':challan_issued', $challan, PDO::PARAM_STR);
        $query->bindParam(':vendor_issued', $vendor_id, PDO::PARAM_STR);
        $query->execute();

        $lastInsertId = $dbh->lastInsertId();
        $sql2 = "INSERT INTO item_received_from_vendor(issued_id, total_item_left, updated_at) VALUES(:last_id,:item_quantity, NOW())";
        $query2 = $dbh->prepare($sql2);
        $query2->bindParam(':last_id', $lastInsertId, PDO::PARAM_STR);
        $query2->bindParam(':item_quantity', $quantitys[$key], PDO::PARAM_STR);
        $query2->execute();
    }
    echo json_encode('success');
}
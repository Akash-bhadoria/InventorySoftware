<?php
session_start();
// error_reporting(0);
include('includes/config.php');
include('includes/layout.php');

if(strlen($_SESSION['emplogin'])==0)
    {
header('location:index.php');
}
else{
   
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <!-- Title -->
    <title>SANTLAL&SONS</title>
</head>

<body>
    <?php include('includes/header.php');?>

    <?php include('includes/sidebar.php');?>
    <main class="mn-inner">
        <div class="row">
            <div class="col s12 m12 l12">
                <div class="card">
                    <div class="card-content">
                        <span class="headc"> RECEIVED FROM VENDOR
                            <hr>
                        </span>
                        <table id="receivedVendor" class="display responsive-table ">
                            <thead>
                                <tr>
                                    <th>Sr no</th>
                                    <th>Item Name</th>
                                    <th>Quantity Issued</th>
                                    <th>Date Of Issued</th>
                                    <th>Challan No</th>
                                    <th>Vendor Name</th>
                                    <th>Item Left</th>
                                    <th>Defective Item</th>
                                    <th>Last Quantity Received</th>
                                    <th>Total Quantity Received</th>
                                    <th>Created At</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php $sql = "SELECT item_issued_to_vendor.*,item_received_from_vendor.*, GROUP_CONCAT(tbl_item_type.item_type SEPARATOR ', ')as item_name, tbl_vendor.vendor_name from item_issued_to_vendor LEFT JOIN tbl_item_type ON FIND_IN_SET(tbl_item_type.id, item_issued_to_vendor.item_name_issued) > 0 LEFT JOIN item_received_from_vendor ON item_received_from_vendor.issued_id = item_issued_to_vendor.id LEFT JOIN tbl_vendor on tbl_vendor.id=item_issued_to_vendor.vendor_issued GROUP BY item_issued_to_vendor.id ";
                                $query = $dbh -> prepare($sql);
                                $query->execute();
                                $results=$query->fetchAll(PDO::FETCH_OBJ);
                                $cnt=1;
                                if($query->rowCount() > 0){
                                foreach($results as $result){
                                ?>
                                <tr>
                                    <td> <?php echo htmlentities($cnt);?></td>
                                    <td><?php echo htmlentities($result->item_name);?></td>
                                    <td style="color: green;">
                                        <b><?php echo htmlentities($result->quantity_issued);?></b>
                                    </td>
                                    <td><?php echo htmlentities($result->date_issued);?></td>
                                    <td><?php echo htmlentities($result->challan_issued);?></td>
                                    <td><?php echo htmlentities($result->vendor_name);?></td>
                                    <td style="color: red;"><b><?php echo htmlentities($result->total_item_left);?></b>
                                    <td style="color: blue;"><b><?php echo htmlentities($result->defective_item);?></b>
                                    <td style="color: orange;">
                                        <b><?php echo htmlentities($result->last_quantity_received);?></b>
                                    </td>
                                    <td style="color: blue;"><b><?php echo htmlentities($result->total_received);?></b>
                                    <td><?php echo htmlentities($result->created_at);?></td>
                                    <td><a class="btn btn-success"
                                            href="item-received-update-vendor.php?id=<?php echo htmlentities($result->issued_id);?>">
                                            ADD RECEIVED</a>
                                    </td>
                                </tr>
                                <?php $cnt++;} }?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>
    </div>
    <div class="left-sidebar-hover"></div>

</body>

</html>
<?php } ?>
<script>
$('#receivedVendor').DataTable({
    "pageLength": 50,
    "bLengthChange": false,
});
</script>
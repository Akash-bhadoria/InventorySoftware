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
                                    <th>Challan No</th>
                                    <th>Vendor Name</th>
                                    <th>Total Quantity</th>
                                    <th>Total Item</th>
                                    <th>Date Of Issue</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php $sql = "SELECT
                                            	iitv.*,
                                            	tv.vendor_name,
                                            	sum(iitv.quantity_issued) AS total_quantity,
	                                            count(iitv.item_name_issued) AS total_item
                                            FROM
                                            	item_issued_to_vendor iitv
                                            LEFT JOIN tbl_vendor tv ON
                                            	tv.id = iitv.vendor_issued
                                            GROUP BY
                                            	iitv.challan_issued";
                                $query = $dbh -> prepare($sql);
                                $query->execute();
                                $results=$query->fetchAll(PDO::FETCH_OBJ);
                                $cnt=1;
                                if($query->rowCount() > 0){
                                foreach($results as $result){
                                ?>
                                <tr>
                                    <td><?php echo htmlentities($result->challan_issued); ?></td>
                                    <td><?php echo htmlentities($result->vendor_name); ?></td>
                                    <td><?php echo htmlentities($result->total_quantity); ?></td>
                                    <td><?php echo htmlentities($result->total_item); ?></td>
                                    <td><?php echo htmlentities($result->date_issued); ?></td>
                                    <td><a class="btn btn-success" style="background-color: blue"
                                            href="item-by-challan.php?challan_id=<?php echo htmlentities($result->challan_issued); ?>&vendor_name=<?php echo htmlentities($result->vendor_name); ?>&date=<?php echo htmlentities($result->date_issued); ?>">OPEN
                                            CHALLAN</a>
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
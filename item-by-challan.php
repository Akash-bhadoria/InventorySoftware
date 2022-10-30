<?php
session_start();
error_reporting(0);
include('includes/config.php');
include('includes/layout.php');

if(strlen($_SESSION['emplogin'])==0)
    {
header('location:index.php');
}
else{
    $challan_id=$_GET['challan_id'];
    $vendor_name=$_GET['vendor_name'];
    $date=$_GET['date'];
    

    if (isset($_GET['issue_id'])) {
         $id = $_GET['issue_id'];

         $del1 = mysqli_query($connection, "DELETE FROM `item_issued_to_vendor` WHERE id = '$id'");
         $del2 = mysqli_query($connection, "DELETE FROM `item_received_from_vendor` WHERE issued_id = '$id'");

         if ($del1 && $del2) {
             $msg = "Issued vendor Entry Deleted";
         } else {
             $error = "Something went wrong. Please try again";
         }
     }
   
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
                        <span class="headc"> ITEM BY CHALLAN NUMBER
                            <hr>
                            <h6>CHALLAN NUMBER : <?php  echo $challan_id?></h6>
                            <h6>VENDOR NAME : <?php  echo $vendor_name?></h6>
                            <h6>ISSUED DATE : <?php  echo $date?></h6>
                        </span>
                        <table id="receivedVendor" class="display responsive-table ">
                            <thead>
                                <tr>
                                    <th>Sr no</th>
                                    <th>Item Name</th>
                                    <th>Quantity Issued</th>
                                    <th>Challan No</th>
                                    <th>Item Left</th>
                                    <th>Defective Item</th>
                                    <th>Last Quantity Received</th>
                                    <th>Total Quantity Received</th>
                                    <th>Created At</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php $sql = "SELECT
                                            	iitv.*,
                                            	irfv.*,
                                            	tit.item_type AS item_name
                                            FROM
                                            	item_issued_to_vendor iitv
                                            	LEFT JOIN item_received_from_vendor irfv ON
                                            	irfv.issued_id = iitv.id 
                                            	LEFT JOIN tbl_item_type tit ON
                                            	tit.id = iitv.item_name_issued 
                                            where iitv.challan_issued = '$challan_id'";
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
                                    <td><?php echo htmlentities($result->challan_issued);?></td>
                                    <td style="color: red;"><b><?php echo htmlentities($result->total_item_left);?></b>
                                    <td style="color: blue;"><b><?php echo htmlentities($result->defective_item);?></b>
                                    <td style="color: orange;">
                                        <b><?php echo htmlentities($result->last_quantity_received);?></b>
                                    </td>
                                    <td style="color: blue;"><b><?php echo htmlentities($result->total_received);?></b>
                                    <td><?php echo htmlentities($result->created_at);?></td>
                                    <td><a class="btn btn-success" style="background-color: green"
                                            href="item-received-update-vendor.php?id=<?php echo htmlentities($result->issued_id);?>&challan_id=<?php echo $challan_id; ?>&vendor_name=<?php echo $vendor_name; ?>&date=<?php echo $date; ?>">
                                            ADD RECEIVED</a>
                                        <a class="btn btn-success" style="background-color: red"
                                            onclick="return confirm('Do you want to delete vendor entry. Once its done it cant be revert');"
                                            href="item-by-challan.php?issue_id=<?php echo $result->id; ?>&challan_id=<?php echo $challan_id; ?>&vendor_name=<?php echo $vendor_name; ?>&date=<?php echo $date; ?>">DELETE</a>
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
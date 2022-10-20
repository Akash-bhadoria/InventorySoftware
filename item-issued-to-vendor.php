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
    $cdate = date('Y-m-d');

    $sql = "SELECT MAX(challan_issued) as challan_no from item_issued_to_vendor";
    $query = $dbh -> prepare($sql);
    $query->execute();
    $results=$query->fetchAll(PDO::FETCH_OBJ);
    $cnt=1;
    if($query->rowCount() > 0){
        foreach($results as $result){ 
            $challan_count = $result->challan_no ?? "1500"; 
        }
    }

if(isset($_POST['add_issued'])){
$item_name= implode(",",$_POST['item_name_issued']);
$quantity=$_POST['quantity_issued'];
$date=$_POST['date_issued'];
$challan=$_POST['challan_issued'];
$vendor=$_POST['vendor_issued'];



$sql="INSERT INTO item_issued_to_vendor(item_name_issued, quantity_issued, date_issued, challan_issued,
vendor_issued,total_item_left, created_at)
VALUES(:item_name,:item_quantity,:item_date,:challan_issued,:vendor_issued,:item_quantity,NOW())";
$query = $dbh->prepare($sql);
$query->bindParam(':item_name',$item_name,PDO::PARAM_STR);
$query->bindParam(':item_quantity',$quantity,PDO::PARAM_STR);
$query->bindParam(':item_date',$date,PDO::PARAM_STR);
$query->bindParam(':challan_issued',$challan,PDO::PARAM_STR);
$query->bindParam(':vendor_issued',$vendor,PDO::PARAM_STR);
$query->execute();

$lastInsertId = $dbh->lastInsertId();
mysqli_query($connection, "INSERT INTO item_received_from_vendor(issued_id, total_item_left, updated_at) VALUES
('$lastInsertId','$quantity', NOW())");
if($lastInsertId){
$msg="Details Saved Successfully";
?>
<script>
window.setTimeout(function() {
    window.location = "item-issued-to-vendor.php";
}, 2000);
</script>
<?php
        }else{
            $error="Something went wrong. Please try again";
            ?>
<script>
window.setTimeout(function() {
    window.location = "item-issued-to-vendor.php";
}, 2000);
</script>
<?php
        }
    }

    if(isset($_GET['delId'])){
        $id=$_GET['delId'];

        $del1 = mysqli_query($connection, "DELETE FROM `item_issued_to_vendor` WHERE id = '$id'");
        $del2 = mysqli_query($connection, "DELETE FROM `item_received_from_vendor` WHERE issued_id = '$id'");

        if($del1 && $del2){
            $msg="Issued vendor Entry Deleted";
             ?>
<script>
window.location.href = 'item-issued-to-vendor.php';
</script>
<?php
        }else{
            $error="Something went wrong. Please try again";
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
            <div class="card-content">

                <span class="stats-counter">
                    <?php if($errorcasual){?><div class="errorWrap1" style="margin-left:12px">
                        <?php echo htmlentities($errorcasual); ?> </div><?php }
                       ?>
            </div>
            <div class="card-content">

                <span class="stats-counter">
                    <?php if($errorearned){?><div class="errorWrap1" style="margin-left:20px">
                        <?php echo htmlentities($errorearned); ?> </div><?php }
                         ?>
            </div>

        </div>
        <div class="row">
            <div class="col s12 m12 l8">
                <div class="card">
                    <div class="card-content" style="font-family: monospace;">
                        <form id="vendorForm" method="post" name="addemp">
                            <div>
                                <span class="headc">ITEM ISSUED TO VENDOR</span>
                                <span><a style="margin-left: 699px" class="btn btn-success" href="add-item.php">ADD
                                        ITEM</a></span>
                                <hr>
                                <section>
                                    <div class="wizard-content">
                                        <div class="row">
                                            <div class="col m12">
                                                <div class="row">
                                                    <?php if($error){?><script>
                                                    Swal.fire({
                                                        title: 'Error',
                                                        text: "<?php echo htmlentities($error)?>",
                                                        icon: "error",
                                                        timer: 4000,
                                                    });
                                                    </script><?php }
                                    else if($msg){?><script>
                                                    Swal.fire({
                                                        title: 'Success',
                                                        text: "<?php echo htmlentities($msg)?>",
                                                        icon: "success",
                                                        timer: 3000,
                                                    });
                                                    </script><?php }?>


                                                    <div class="input-field col  s12">
                                                        <select name="vendor_issued" id="vendor_issued"
                                                            autocomplete="off" class="form-control" required>
                                                            <option value="">Select Vendor Name...</option>
                                                            <?php $sql = "SELECT  id,vendor_name from tbl_vendor";
                                                                $query = $dbh -> prepare($sql);
                                                                $query->execute();
                                                                $results=$query->fetchAll(PDO::FETCH_OBJ);
                                                                $cnt=1;
                                                                if($query->rowCount() > 0)
                                                                {
                                                                foreach($results as $result)
                                                                {   ?>
                                                            <option value="<?php echo htmlentities($result->id);?>">
                                                                <?php echo htmlentities($result->vendor_name);?>
                                                            </option>
                                                            <?php }} ?>
                                                        </select>
                                                    </div>
                                                    <div class="input-field col  s12">
                                                        <select name="item_name_issued[]" autocomplete="off" required
                                                            multiple>
                                                            <option value="" disabled>Select Item Name...</option>
                                                            <?php $sql = "SELECT  id,item_type from tbl_item_type";
                                                                $query = $dbh -> prepare($sql);
                                                                $query->execute();
                                                                $results=$query->fetchAll(PDO::FETCH_OBJ);
                                                                $cnt=1;
                                                                if($query->rowCount() > 0)
                                                                {
                                                                foreach($results as $result)
                                                                {   ?>
                                                            <option value="<?php echo htmlentities($result->id);?>">
                                                                <?php echo htmlentities($result->item_type);?></option>
                                                            <?php }} ?>
                                                        </select>
                                                    </div>

                                                    <div class="input-field col m12 s12">
                                                        <label class="active" for="vendor">Challan No</label>
                                                        <input type="number" min="1500" id="challan_issued"
                                                            name="challan_issued"
                                                            value="<?php echo htmlentities($challan_count + 1);?>"
                                                            required />
                                                        <small class="challan">This is automated generated challan
                                                            number</small>
                                                    </div>

                                                    <div class="input-field col m12 s12">
                                                        <label class="active" for="date">Date</label>
                                                        <input type="date" id="date_issued" name="date_issued"
                                                            value="<?php echo htmlentities($cdate);?>" required />
                                                    </div>

                                                    <div class="input-field col m12 s12">
                                                        <label for="quantity_issued">Quantity</label>
                                                        <input type="number" id="quantity_issued" name="quantity_issued"
                                                            required />
                                                    </div>

                                                </div>

                                                <button type="submit" name="add_issued" id="add_issued"
                                                    class="waves-effect waves-light btn indigo m-b-xs">SAVE</button>

                                            </div>
                                        </div>
                                </section>


                                </section>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col s12 m12 l12">
                <div class="card">
                    <div class="card-content">
                        <span class="headc">MANAGE
                            <hr>
                        </span>
                        <table id="issuedVendor" class="display responsive-table ">
                            <thead>
                                <tr>
                                    <th>Sr no</th>
                                    <th>Item Name</th>
                                    <th>Quantity Issued</th>
                                    <th>Date Of Issued</th>
                                    <th>Challan No</th>
                                    <th>Vendor Name</th>
                                    <th>Item Left</th>
                                    <th>Created At</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php $sql = "SELECT item_issued_to_vendor.*, GROUP_CONCAT(tbl_item_type.item_type SEPARATOR ', ')as item_name ,tbl_vendor.vendor_name FROM item_issued_to_vendor LEFT JOIN tbl_item_type ON FIND_IN_SET(tbl_item_type.id, item_issued_to_vendor.item_name_issued) > 0 LEFT JOIN tbl_vendor on tbl_vendor.id=item_issued_to_vendor.vendor_issued GROUP BY item_issued_to_vendor.id";
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
                                    </td>
                                    <td><?php echo $result->created_at?></td>
                                    <td><a class="btn btn-success"
                                            href="item-received-update-vendor.php?id=<?php echo htmlentities($result->id);?>">ADD
                                            RECEIVED</a>
                                        <a class="btn btn-success" style="background-color: red"
                                            onclick="return confirm('Do you want to delete vendor entry. Once its done it cant be revert');"
                                            href="item-issued-to-vendor.php?delId=<?php echo htmlentities($result->id);?>">DELETE</a>
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
$('#issuedVendor').DataTable({
    "pageLength": 50,
    "bLengthChange": false,
});
</script>
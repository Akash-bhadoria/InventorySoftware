<?php
session_start();
// error_reporting(0);
include('includes/config.php');
include('includes/layout.php');
if (strlen($_SESSION['emplogin']) == 0) {
    header('location:index.php');
} else {

    
    if (isset($_POST['update'])) {

        $challan_id=$_GET['challan_id'];
        $vendor_name=$_GET['vendor_name'];
        $date=$_GET['date'];
    
        $Rid = intval($_GET['id']);
        $quantity_received = $_POST['quantity_received'];
        $defective_received = $_POST['defective_received'];
        $quantity_issued = $_POST['quantity_issued'];

        $sql = "SELECT defective_item, total_received FROM item_received_from_vendor WHERE issued_id = :rid ";
        $query = $dbh->prepare($sql);
        $query->bindParam(':rid', $Rid, PDO::PARAM_STR);
        $query->execute();
        $results = $query->fetchAll(PDO::FETCH_OBJ);
        if ($query->rowCount() > 0) {
            foreach ($results as $result) {
                $return = $result->defective_item;
                $received = $result->total_received;
            }
        }

        $updated_item = (int)$quantity_issued - ((int)$quantity_received + (int)$defective_received);
        $updated_defective = $return + (int)$defective_received;
        $updated_received = $received + (int)$quantity_received;



        $query1 = mysqli_query($connection, " UPDATE item_received_from_vendor SET total_item_left = '$updated_item', defective_item = '$updated_defective', last_quantity_received = '$quantity_received', total_received = '$updated_received' WHERE issued_id = '$Rid' ");
        $query2 = mysqli_query($connection, " UPDATE item_issued_to_vendor SET total_item_left = '$updated_item' WHERE id = '$Rid' ");

        if ($query1 && $query2) {
            ?>
<script>
window.location =
    "item-by-challan.php?challan_id=<?php echo $challan_id; ?>&vendor_name=<?php echo $vendor_name; ?>&date=<?php echo $date; ?>";
</script>
<?php
        } else {
        ?>
<script>
Swal.fire({
    title: 'Error',
    text: "Something Went Wrong...!!",
    icon: "error",
    timer: 2000,
});
window.setTimeout(function() {
    window.location = "item-received-from-vendor.php";
}, 2000);
</script>
<?php
        }
    }

    ?>

<!DOCTYPE html>
<html lang="en">

<head>

    <!-- Title -->
    <title>SANTLAL&SONS | ITEM RECEIVED</title>

</head>

<body>
    <?php include('includes/header.php'); ?>

    <?php include('includes/sidebar.php'); ?>
    <main class="mn-inner">
        <div class="row">
            <div class="col s12">
            </div>
            <div class="col s12 m12 l6">
                <div class="card">
                    <div class="card-content">
                        <span class="headc">ITEM RECEIVED FROM VENDOR</span>
                        <hr>
                        <div class="row">
                            <form class="col s12" name="chngpwd" method="post">
                                <?php if ($error) { ?><div class="errorWrap"><strong>ERROR</strong> :
                                    <?php echo htmlentities($error); ?> </div><?php } else if ($msg) { ?><div
                                    class="succWrap"><strong>SUCCESS</strong> :
                                    <?php echo htmlentities($msg); ?>
                                </div><?php } ?>
                                <?php
                                    $Rid = intval($_GET['id']);
                                    $sql = "SELECT item_issued_to_vendor.*, GROUP_CONCAT(tbl_item_type.item_type SEPARATOR ', ')as item_name, tbl_vendor.vendor_name FROM item_issued_to_vendor LEFT JOIN tbl_item_type ON FIND_IN_SET(tbl_item_type.id, item_issued_to_vendor.item_name_issued) > 0 LEFT JOIN tbl_vendor on tbl_vendor.id = item_issued_to_vendor.vendor_issued WHERE item_issued_to_vendor.id=:Rid GROUP BY item_issued_to_vendor.id ";
                                    $query = $dbh->prepare($sql);
                                    $query->bindParam(':Rid', $Rid, PDO::PARAM_STR);
                                    $query->execute();
                                    $results = $query->fetchAll(PDO::FETCH_OBJ);
                                    $cnt = 1;
                                    if ($query->rowCount() > 0) {
                                        foreach ($results as $result) {
                                    ?>

                                <div class="row">
                                    <input type="hidden" name="formId" value="<?php echo htmlentities($result->id) ?>">

                                    <div class="input-field col m6 s12">
                                        <input id="item_name" type="text" class="validate" autocomplete="off"
                                            name="item_name" value="<?php echo htmlentities($result->item_name); ?>"
                                            readonly>
                                        <label class="active" for="item_name">Item Name</label>
                                    </div>
                                    <div class="input-field col m6 s12">
                                        <input id="quantity_issued" type="number" class="validate" autocomplete="off"
                                            name="quantity_issued"
                                            value="<?php echo htmlentities($result->quantity_issued); ?>" readonly>
                                        <label class="active" for="quantity_issued">Quantity Issued</label>
                                    </div>
                                    <div class="input-field col m6 s12">
                                        <input id="date_issued" type="date" class="validate" autocomplete="off"
                                            name="date_issued" value="<?php echo htmlentities($result->date_issued); ?>"
                                            readonly>
                                        <label class="active" for="date_issued">Date Issued</label>
                                    </div>
                                    <div class="input-field col m6 s12">
                                        <input id="challan_issued" type="text" class="validate" autocomplete="off"
                                            name="challan_issued"
                                            value="<?php echo htmlentities($result->challan_issued); ?>" readonly>
                                        <label class="active" for="challan_issued">Challan Issued</label>
                                    </div>
                                    <div class="input-field col m6 s12">
                                        <input id="vendor_issued" type="text" class="validate" autocomplete="off"
                                            name="vendor_issued"
                                            value="<?php echo htmlentities($result->vendor_name); ?>" readonly>
                                        <label class="active" for="vendor_issued">Vendor Issued</label>
                                    </div>
                                    <div class="col s12">
                                        <div class="card">
                                            <div class="card-content row">

                                                <div class="input-field col  s12">
                                                    <input id="quantity_issued" type="text" class="validate"
                                                        autocomplete="off" name="quantity_issued"
                                                        value="<?php echo htmlentities($result->total_item_left); ?>"
                                                        readonly>
                                                    <label class="active" for="quantity_issued">Total Item Left To
                                                        Vendor</label>
                                                </div>
                                                <div class="input-field col  s12">
                                                    <input id="quantity_received" type="number" class="validate"
                                                        autocomplete="off" min="0" name="quantity_received">
                                                    <label class="active" for="quantity_received">Item Received From
                                                        Vendor</label>
                                                </div>
                                                <div class="input-field col  s12">
                                                    <input id="defective_received" type="number" class="validate"
                                                        autocomplete="off" min="0" name="defective_received">
                                                    <label class="active" for="defective_received">Defective Item
                                                        Returned</label>
                                                </div>
                                                <?php }
                                            } ?>
                                                <div class="input-field col s12">
                                                    <button type="submit" name="update"
                                                        class="waves-effect waves-light btn indigo m-b-xs">Update</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            </form>
                        </div>
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
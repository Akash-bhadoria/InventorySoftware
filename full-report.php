<?php
session_start();
error_reporting(0);
include('includes/config.php');
include('includes/layout.php');
if (strlen($_SESSION['emplogin']) == 0) {
    header('location:index.php');
} else {

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <!-- Title -->
    <title>SANTLAL&SONS | REPORT</title>

</head>

<body>
    <?php include('includes/header.php'); ?>

    <?php include('includes/sidebar.php'); ?>
    <main class="mn-inner">
        <div class="row">
            <div class="col s12 m12 l6" style="width: 100%">
                <div class="card">
                    <div class="card-content">
                        <form id="filterForm" method="post" name="addemp">
                            <div class="row">
                                <div class="row">
                                    <span class="headc">REPORT</span>
                                    <hr>
                                    <div class="input-field col m3 s12">
                                        <input id="from_date" type="date" class="validate" autocomplete="off"
                                            name="from_date">
                                        <label class="active" for="from_date">FROM DATE</label>
                                    </div>
                                    <div class="input-field col m3 s12">
                                        <input id="to_date" type="date" class="validate" autocomplete="off"
                                            name="to_date">
                                        <label class="active" for="to_date">TO DATE</label>
                                    </div>
                                    <div class="input-field col m3  s12">
                                        <select name="item_name" id="item_name" class="select2" autocomplete="off">
                                            <option></option>
                                            <?php $sql = "SELECT  id,item_type from tbl_item_type";
                                                $query = $dbh->prepare($sql);
                                                $query->execute();
                                                $items = $query->fetchAll(PDO::FETCH_OBJ);
                                                $cnt = 1;
                                                if ($query->rowCount() > 0) {
                                                    foreach ($items as $item) {   ?>
                                            <option value="<?php echo $item->id; ?>">
                                                <?php echo $item->item_type; ?></option>
                                            <?php }
                                                } ?>
                                        </select>
                                        <label for="item-name">ITEM NAME</label>
                                    </div>
                                    <div class="input-field col m3 s12">
                                        <input id="challan" type="text" class="validate" autocomplete="off"
                                            name="challan">
                                        <label for="challan">CHALLAN NO</label>
                                    </div>
                                    <div class="input-field col m3  s12">
                                        <select name="vendor_name" id="vendor_name" class="select2" autocomplete="off">
                                            <option></option>
                                            <?php $sql = "SELECT  id,vendor_name from tbl_vendor";
                                                $query = $dbh->prepare($sql);
                                                $query->execute();
                                                $items = $query->fetchAll(PDO::FETCH_OBJ);
                                                $cnt = 1;
                                                if ($query->rowCount() > 0) {
                                                    foreach ($items as $item) {   ?>
                                            <option value="<?php echo $item->id; ?>">
                                                <?php echo $item->vendor_name; ?></option>
                                            <?php }
                                                } ?>
                                        </select>
                                        <label for="item-name">VENDOR NAME</label>
                                    </div>


                                    <div class="input-field col s12">
                                        <button type="submit" name="applyFilter"
                                            class="waves-effect waves-light btn indigo m-b-xs">Apply FIlter</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <button style="margin-left: 174px;margin-top: -148px" onclick="resetFilter()"
                            class="waves-effect waves-light btn  m-b-xs">Reset
                            Filter</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col s12 m12 l12">
                <div class="card">
                    <div class="card-content">
                        <span class="headc">FILTERED DATA</span>
                        <hr>
                        <?php if ($msg) { ?><div class="succWrap"><strong>SUCCESS</strong> :
                            <?php echo htmlentities($msg); ?> </div><?php } ?>
                        <table id="filterTable" class="display responsive-table ">
                            <thead>
                                <tr>
                                    <th>Item Name</th>
                                    <th>Vendor Name</th>
                                    <th>Challan No.</th>
                                    <th>Quantity Issued</th>
                                    <th>Total Received</th>
                                    <th>Last Quantity Received</th>
                                    <th>Defective Item</th>
                                    <th>Total Item Left</th>
                                    <th>Issued Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php
                                    if (isset($_POST['applyFilter'])) {
                                        $from_date = $_POST['from_date'];
                                        $to_date = $_POST['to_date'];
                                        $item_name = $_POST['item_name'];
                                        $challan = $_POST['challan'];
                                        $vendor_name = $_POST['vendor_name'];

                                        $query = "SELECT
                                    iitv.*,
                                    irfv.*,
                                    tbl_vendor.vendor_name,
                                    GROUP_CONCAT(tit.item_type SEPARATOR ', ')as item_name
                                    FROM 
                                    item_issued_to_vendor iitv
                                    LEFT JOIN item_received_from_vendor irfv ON 
                                    irfv.issued_id = iitv.id 
                                    LEFT JOIN tbl_item_type tit ON 
                                    FIND_IN_SET(tit.id, iitv.item_name_issued) > 0 
                                    LEFT JOIN tbl_vendor ON 
                                    tbl_vendor.id = iitv.vendor_issued 
                                    WHERE 
                                    iitv.item_name_issued IS NOT NULL";

                                        if ($item_name != "") {
                                            $query .= " AND FIND_IN_SET('$item_name', iitv.item_name_issued)";
                                        }
                                        if ($vendor_name != "") {
                                            $query .= " AND iitv.vendor_issued IN ($vendor_name) ";
                                        }
                                        if ($challan != "") {
                                            $query .= " AND iitv.challan_issued LIKE '%$challan%'";
                                        }
                                        if ($from_date != "" && $to_date == "") {
                                            $query .= " AND iitv.date_issued BETWEEN '$from_date' AND CURDATE() ";
                                        }
                                        if ($from_date != "" && $to_date != "") {
                                            $query .= " AND iitv.date_issued BETWEEN '$from_date' AND '$to_date'";
                                        }
                                        $query .= " GROUP BY iitv.id ORDER BY iitv.challan_issued DESC  ";

                                        $qdis = mysqli_query($connection, $query);

                                        if (mysqli_num_rows($qdis) > 0) {
                                            foreach ($qdis as $result) {
                                    ?>
                                <tr style="color:#000">
                                    <td> <?php echo $result['item_name']; ?> </td>
                                    <td> <?php echo $result['vendor_name']; ?> </td>
                                    <td> <a target="_blank"
                                            href="item-by-challan.php?challan_id=<?php echo $result['challan_issued']; ?>&vendor_name=<?php echo $result['vendor_name']; ?>&date=<?php echo $result['date_issued']; ?>"><?php echo $result['challan_issued']; ?></a>
                                    </td>
                                    <td style="color: green;"> <?php echo $result['quantity_issued']; ?> </td>
                                    <td> <?php echo $result['total_received']; ?> </td>
                                    <td style="color: purple;"> <?php echo $result['last_quantity_received']; ?> </td>
                                    <td style="color: orange;"> <?php echo $result['defective_item']; ?> </td>
                                    <td style="color: red;"> <?php echo $result['total_item_left']; ?> </td>
                                    <td> <?php echo $result['date_issued']; ?> </td>
                                    <td> <a class="btn btn-success"
                                            href="item-received-update-vendor.php?id=<?php echo $result['id']; ?>">ADD
                                            RECEIVED</a>
                                    </td>
                                </tr>

                                <?php
                                            }
                                        } else {
                                            ?>
                                <script>
                                Swal.fire({
                                    title: 'No Record Found',
                                    text: "Search Something Different",
                                    icon: "error",
                                    timer: 3000,
                                });
                                </script>
                                <?php
                                        }
                                    } ?>
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
$('#filterTable').DataTable({
    "pageLength": 50,
    "bLengthChange": false,
});

$(".select2").select2();


function resetFilter() {
    $('#filterForm').trigger('reset');
}
</script>
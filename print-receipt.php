<?php
session_start();
// error_reporting(0);
include('includes/config.php');
include('includes/layout.php');
if (strlen($_SESSION['emplogin']) == 0) {
    header('location:index.php');
} else {

    $challan_id=$_GET['challan_id'];
    $vendor_name=$_GET['vendor_name'];
    $date=$_GET['date'];

    ?>

<!DOCTYPE html>
<html lang="en">

<head>

    <!-- Title -->
    <title>SANTLAL&SONS | RECEIPT</title>

</head>

<body>

    <main class="mn-inner">
        <div class="row">
            <div class="col s12">
            </div>
            <div class="col s12 m12 l12">
                <div class="card">
                    <div class="card-content">
                        <span class="headc">
                            <center>SANT LAL & SONS</center>
                            <hr>
                            <h6>Challan Number : <?php echo $challan_id ?></h6>
                            <h6>Vendor Name : <?php echo $vendor_name ?></h6>
                            <h6>Date Of Issue : <?php echo $date ?></h6>
                            <hr>
                        </span>
                        <table id="itemCompany" class="display responsive-table ">
                            <thead>
                                <tr>
                                    <th>Sr no</th>
                                    <th>Item Name</th>
                                    <th>Quantity Issue</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php $sql = "SELECT
                                        	iitv.*,
                                        	tit.item_type  
                                        FROM
                                        	item_issued_to_vendor iitv
                                        	LEFT JOIN tbl_item_type tit  ON
                                        	tit.id = iitv.item_name_issued 
                                        WHERE
                                        	iitv.challan_issued = '$challan_id'";
                                    $query = $dbh->prepare($sql);
                                    $query->execute();
                                    $results = $query->fetchAll(PDO::FETCH_OBJ);
                                    $cnt = 1;
                                    if ($query->rowCount() > 0) {
                                        foreach ($results as $result) {
                                    ?>
                                <tr>
                                    <td> <?php echo htmlentities($cnt); ?></td>
                                    <td><?php echo htmlentities($result->item_type); ?></td>
                                    <td><?php echo htmlentities($result->quantity_issued); ?></td>

                                </tr>
                                <?php $cnt++;
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
window.print();
</script>
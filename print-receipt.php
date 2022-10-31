<?php
session_start();
error_reporting(0);
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/paper-css/0.3.0/paper.css">
    <style>
    @page {
        size: A5
    }
    </style>

</head>

<body class="A5">

    <main class="mn-inner">
        <div class="row">
            <div class="col s12">
            </div>
            <div class="col s12 m12 l12">
                <div class="card">
                    <div class="card-content">
                        <div class="container-fluid">
                            <div class="row">
                                <span style="font-size:10px">GSTIN : 07AAAPS8130K1ZJ</span>
                                <span style="font-size:10px; float:right">(O) 011-45721409</span>
                            </div>
                            <div class="row" style="margin-top:-26px">
                                <span style="font-size:10px">KATHURA WALE</span>
                                <span style="font-size:10px; float:right">(m) 9654273740</span>
                            </div>
                        </div>
                        <center style="margin-top: -52px; margin-left:72px"><img src="assets/images/logo.png" alt="logo"
                                height="50px">
                        </center>
                        <center><small><u>CHALLAN</u></small></center>
                        <span class="headc">
                            <center>SANT LAL & SONS</center>
                            <center><small style="font-size: 13px;">4307 (First Floor) Gali Vhairon Wali, Jogiwara, Nai
                                    Sarak, Chandni Chowk, Delhi - 110006</small></center>
                            <hr>
                        </span>
                        <span>
                            <div>
                                <h6 style="font-size:10px">Challan Number : <?php echo $challan_id ?></h6>
                                <h6 style="font-size:10px">Vendor Name : <?php echo $vendor_name ?></h6>
                                <h6 style="font-size:10px">Date Of Issue : <?php echo $date ?></h6>
                            </div>
                            <hr>
                        </span>
                        <table id="itemCompany" class="display responsive-table " style="font-size: 9px;">
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
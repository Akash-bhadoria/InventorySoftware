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
    <title>SANTLAL&SONS | RECEIPT</title>

</head>

<body>

    <main class="mn-inner">
        <div class="row">
            <div class="col s12">
            </div>
            <div class="col s12 m12 l6">
                <div class="card">
                    <div class="card-content">
                        <span class="headc">
                            <center>SANT LAL & SONS
                        </span>
                        <hr>
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
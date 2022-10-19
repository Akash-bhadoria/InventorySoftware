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
if(isset($_POST['update'])){
    $lid=intval($_GET['lid']);
    $vendorName=$_POST['vendorName'];
    $vendorMobile=$_POST['vendorMobile'] ?? null;

    $sql="UPDATE tbl_vendor SET vendor_name=:vName,vendor_mobile=:vMobile WHERE id=:lid";
    $query = $dbh->prepare($sql);
    $query->bindParam(':vName',$vendorName,PDO::PARAM_STR);
    $query->bindParam(':vMobile',$vendorMobile,PDO::PARAM_STR);
    $query->bindParam(':lid',$lid,PDO::PARAM_STR);
    $query->execute();
    ?>
<script>
window.location.href = "add-vendor.php";
</script>
<?php
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <!-- Title -->
    <title>SANTLAL&SONS | UPDATE ITEM</title>

</head>

<body>
    <?php include('includes/header.php');?>

    <?php include('includes/sidebar.php');?>
    <main class="mn-inner">
        <div class="row">
            <div class="col s12">
            </div>
            <div class="col s12 m12 l6">
                <div class="card">
                    <div class="card-content">
                        <span class="headc">VENDOR UPDATE</span>
                        <hr>
                        <div class="row">
                            <form class="col s12" name="chngpwd" method="post">
                                <?php if($error){?><div class="errorWrap"><strong>ERROR</strong> :
                                    <?php echo htmlentities($error); ?> </div><?php }
                                    else if($msg){?><div class="succWrap"><strong>SUCCESS</strong> :
                                    <?php echo htmlentities($msg); ?>
                                </div><?php }?>
                                <?php
                                $lid=intval($_GET['lid']);
                                $sql = "SELECT * from tbl_vendor where id=:lid";
                                $query = $dbh -> prepare($sql);
                                $query->bindParam(':lid',$lid,PDO::PARAM_STR);
                                $query->execute();
                                $results=$query->fetchAll(PDO::FETCH_OBJ);
                                $cnt=1;
                                if($query->rowCount() > 0){
                                    foreach($results as $result){               
                                ?>

                                <div class="row">
                                    <div class="input-field col s12">
                                        <input id="vendorName" type="text" class="validate" autocomplete="off"
                                            name="vendorName" value="<?php echo htmlentities($result->vendor_name);?>"
                                            required>
                                        <label class="active" for="vendorName">Vendor Name</label>
                                    </div>

                                    <div class="input-field col s12">
                                        <input id="vendorMobile" type="tel" class="validate" autocomplete="off"
                                            name="vendorMobile"
                                            value="<?php echo htmlentities($result->vendor_mobile);?>">
                                        <label class="active" for="vendorMobile">Vendor Mobile</label>
                                    </div>

                                    <?php }} ?>

                                    <div class="input-field col s12">
                                        <button type="submit" name="update"
                                            class="waves-effect waves-light btn indigo m-b-xs">Update</button>

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
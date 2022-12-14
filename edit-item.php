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
    $itemtype=$_POST['itemtype'];
    $description=$_POST['description'];
    $sql="UPDATE tblitemtype SET item_type=:itemtype,description=:description WHERE id=:lid";
    $query = $dbh->prepare($sql);
    $query->bindParam(':itemtype',$itemtype,PDO::PARAM_STR);
    $query->bindParam(':description',$description,PDO::PARAM_STR);
    $query->bindParam(':lid',$lid,PDO::PARAM_STR);
    $query->execute();
    ?>
<script>
window.location.href = "add-item.php";
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
                        <span class="headc">ITEM UPDATE</span>
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
                                $sql = "SELECT * from tblitemtype where id=:lid";
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
                                        <input id="itemtype" type="text" class="validate" autocomplete="off"
                                            name="itemtype" value="<?php echo htmlentities($result->item_type);?>"
                                            required>
                                        <label class="active" for="phone">Item Type</label>
                                    </div>


                                    <div class="input-field col s12">
                                        <textarea id="textarea1" name="description" class="materialize-textarea"
                                            name="description"
                                            length="500"><?php echo htmlentities($result->description);?></textarea>
                                        <label class="active" for="phone">Description</label>
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
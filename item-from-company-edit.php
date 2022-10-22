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
    $lid=intval($_GET['id']);
    $itemtype=$_POST['item_name'];
    $quantity=$_POST['item_quantity'];
    $date=$_POST['item_date'];

    $sql="UPDATE item_from_company SET item_name=:item_name,item_quantity=:quantity, item_date=:item_date, updated_at = NOW() WHERE id=:lid";
    $query = $dbh->prepare($sql);
    $query->bindParam(':item_name',$itemtype,PDO::PARAM_STR);
    $query->bindParam(':quantity',$quantity,PDO::PARAM_STR);
    $query->bindParam(':item_date',$date,PDO::PARAM_STR);
    $query->bindParam(':lid',$lid,PDO::PARAM_STR);
    $query->execute();
    ?>
<script>
window.location.href = "item-from-company.php";
</script>
<?php
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <!-- Title -->
    <title>SANTLAL&SONS | UPDATE COMPANY ITEM</title>

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
                        <span class="headc">COMPANY ITEM UPDATE</span>
                        <hr>
                        <div class="row">
                            <form class="col s12" name="chngpwd" method="post">
                                <?php if($error){?><div class="errorWrap"><strong>ERROR</strong> :
                                    <?php echo htmlentities($error); ?> </div><?php }
                                    else if($msg){?><div class="succWrap"><strong>SUCCESS</strong> :
                                    <?php echo htmlentities($msg); ?>
                                </div><?php }?>
                                <?php
                                $lid=intval($_GET['id']);
                                $sql = "SELECT item_from_company.* ,tbl_item_type.item_type from item_from_company LEFT JOIN tbl_item_type ON tbl_item_type.id = item_from_company.item_name where item_from_company.id=:id";
                                $query = $dbh -> prepare($sql);
                                $query->bindParam(':id',$lid,PDO::PARAM_STR);
                                $query->execute();
                                $results=$query->fetchAll(PDO::FETCH_OBJ);
                                $cnt=1;
                                if($query->rowCount() > 0){
                                    foreach($results as $result){               
                                ?>

                                <div class="row">
                                    <div class="input-field col  s12">
                                        <select name="item_name" autocomplete="off" required>
                                            <option value="<?php  echo $result->item_name?>">
                                                <?php  echo $result->item_type ?></option>
                                            <?php $sql = "SELECT  id,item_type from tbl_item_type";
                                                                $query = $dbh -> prepare($sql);
                                                                $query->execute();
                                                                $items=$query->fetchAll(PDO::FETCH_OBJ);
                                                                $cnt=1;
                                                                if($query->rowCount() > 0)
                                                                {
                                                                foreach($items as $item)
                                                                {   ?>
                                            <option value="<?php echo htmlentities($item->id);?>">
                                                <?php echo htmlentities($item->item_type);?></option>
                                            <?php }} ?>
                                        </select>
                                    </div>
                                    <div class="input-field col s12">
                                        <input id="item_quantity" type="number" class="validate" autocomplete="off"
                                            name="item_quantity"
                                            value="<?php echo htmlentities($result->item_quantity);?>" required></input>
                                        <label class="active" for="item_quantity">Item Quality</label>
                                    </div>


                                    <div class="input-field col s12">
                                        <input type="date" id="item_date" name="item_date"
                                            value="<?php echo htmlentities($result->item_date);?>"></input>
                                        <label class=" active" for="item_date">Item Date</label>
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
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
    if(isset($_POST['add'])){
    $item_name=$_POST['item_name'];
    $quantity=$_POST['quantity'];
    $date=$_POST['date'];
    

    $sql="INSERT INTO item_from_company(item_name,item_quantity,item_date,created_at) VALUES(:item_name,:item_quantity,:item_date,NOW())";
    $query = $dbh->prepare($sql);
    $query->bindParam(':item_name',$item_name,PDO::PARAM_STR);
    $query->bindParam(':item_quantity',$quantity,PDO::PARAM_STR);
    $query->bindParam(':item_date',$date,PDO::PARAM_STR);
    $query->execute();
    $lastInsertId = $dbh->lastInsertId();
    if($lastInsertId){
        $msg="Details Saved Successfully";
                ?>
<script>
window.setTimeout(function() {
    window.location = "item-from-company.php";
}, 2000);
</script>
<?php
    }else{
        $error="Something went wrong. Please try again";
                ?>
<script>
window.setTimeout(function() {
    window.location = "item-from-company.php";
}, 2000);
</script>
<?php
    }
}

if(isset($_GET['del'])){
    $id=$_GET['del'];
    $sql = "delete from  item_from_company  WHERE id=:id";
    $query = $dbh->prepare($sql);
    $query -> bindParam(':id',$id, PDO::PARAM_STR);
    $query -> execute();
    $msg="Item Type Deleted";
    ?>
<script>
window.location.href = 'item-from-company.php';
</script>
<?php
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
                        <form id="companyItem" method="post" name="addemp" onsubmit="return onSubmit()">
                            <div>
                                <span class="headc">COMPANY's ITEM</span>
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
                                                        <select name="item_name" autocomplete="off" required>
                                                            <option value="">Select Item Name...</option>
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
                                                        <label for="quantity">Quantity</label>
                                                        <input type="number" id="quantity" name="quantity" required />
                                                    </div>
                                                    <div class="input-field col m12 s12">
                                                        <label class="active" for="date">Date</label>
                                                        <input type="date" id="date" name="date" required />
                                                    </div>

                                                </div>

                                                <button type="submit" name="add" id="add"
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
                        <span class="headc">MANAGE COMAPNY's ITEM
                            <hr>
                        </span>
                        <table id="itemCompany" class="display responsive-table ">
                            <thead>
                                <tr>
                                    <th>Sr no</th>
                                    <th>Item Name</th>
                                    <th>Quantity</th>
                                    <th>Date</th>
                                    <th>Created At</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php $sql = "SELECT item_from_company.*, tbl_item_type.item_type from item_from_company LEFT JOIN tbl_item_type ON tbl_item_type.id=item_from_company.item_name";
                                $query = $dbh -> prepare($sql);
                                $query->execute();
                                $results=$query->fetchAll(PDO::FETCH_OBJ);
                                $cnt=1;
                                if($query->rowCount() > 0){
                                foreach($results as $result){
                                ?>
                                <tr>
                                    <td> <?php echo htmlentities($cnt);?></td>
                                    <td><?php echo htmlentities($result->item_type);?></td>
                                    <td><?php echo htmlentities($result->item_quantity);?></td>
                                    <td><?php echo htmlentities($result->item_date);?></td>
                                    <td><?php echo htmlentities($result->created_at);?></td>
                                    <td><a href="item-from-company-edit.php?id=<?php echo htmlentities($result->id);?>"><i
                                                class="material-icons">mode_edit</i></a>
                                        <a href="item-from-company.php?del=<?php echo htmlentities($result->id);?>"
                                            onclick="return confirm('Do you want to delete');"> <i
                                                class="material-icons" style="color:red">delete_forever</i></a>
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
$('#itemCompany').DataTable({
    "pageLength": 50,
    "bLengthChange": false,
});
</script>
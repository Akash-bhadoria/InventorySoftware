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
    $itemtype=$_POST['itemType'];
    $description=$_POST['description'];
    $sql="INSERT INTO tbl_item_type(item_type,description) VALUES(:itemtype,:description)";
    $query = $dbh->prepare($sql);
    $query->bindParam(':itemtype',$itemtype,PDO::PARAM_STR);
    $query->bindParam(':description',$description,PDO::PARAM_STR);
    $query->execute();
    $lastInsertId = $dbh->lastInsertId();
    if($lastInsertId){
        $msg="Item Type Sdded Successfully";
        ?>
<script>
window.setTimeout(function() {
    window.location = "add-item.php";
}, 2000);
</script>
<?php
    }else{
        $error="Something went wrong. Please try again";
        ?>
<script>
window.setTimeout(function() {
    window.location = "add-item.php";
}, 2000);
</script>
<?php
    }
}
if(isset($_GET['del'])){
    $id=$_GET['del'];
    $sql = "delete from  tbl_item_type  WHERE id=:id";
    $query = $dbh->prepare($sql);
    $query -> bindParam(':id',$id, PDO::PARAM_STR);
    $query -> execute();
    $msg="Item Type Deleted";
    ?>
<script>
window.location.href = 'add-item.php';
</script>
<?php
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>SANTLAL&SONS | MANAGE ITEM</title>
</head>

<body>
    <?php include('includes/header.php');?>

    <?php include('includes/sidebar.php');?>
    <main class="mn-inner inner">
        <div class="row">
            <div class="col s12 m12 l6">
                <div class="card">
                    <div class="card-content">

                        <div class="row">
                            <form class="col s12" id="itemForm" name="chngpwd" method="post">
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
                                <div class="row">
                                    <span class="headc">ADD ITEM</span>
                                    <hr>
                                    <div class="input-field col s12">
                                        <input id="itemType" type="text" class="validate" autocomplete="off"
                                            name="itemType" required>
                                        <label for="itemType">Item Type</label>
                                    </div>

                                    <div class="input-field col s12">
                                        <textarea id="textarea1" name="description" class="materialize-textarea"
                                            name="description" length="500"></textarea>
                                        <label for="description">Item Description</label>
                                    </div>

                                    <div class="input-field col s12">
                                        <button type="submit" name="add"
                                            class="waves-effect waves-light btn indigo m-b-xs">ADD</button>

                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col s12 m12 l12">
                <div class="card">
                    <div class="card-content">
                        <span class="headc">ITEM TYPE INFO
                            <hr>
                        </span>
                        <table id="itemTable" class="display responsive-table ">
                            <thead>
                                <tr>
                                    <th>Sr no</th>
                                    <th>Leave Type</th>
                                    <th>Description</th>
                                    <th>Creation Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php $sql = "SELECT * from tbl_item_type";
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
                                    <td><?php echo htmlentities($result->description);?></td>
                                    <td><?php echo htmlentities($result->created_at);?></td>
                                    <td><a href="edit-item.php?lid=<?php echo htmlentities($result->id);?>"><i
                                                class="material-icons">mode_edit</i></a>
                                        <a href="add-item.php?del=<?php echo htmlentities($result->id);?>"
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
$('#itemTable').DataTable({
    "pageLength": 50,
    "bLengthChange": false,
});
</script>
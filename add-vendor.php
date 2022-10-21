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
    $vendorName=$_POST['vendorName'];
    $vendorMobile=$_POST['vendorMobile'] ?? null;
    $vendorGST=$_POST['vendorGST'] ?? null;
    $vendorAddress=$_POST['vendorAddress'] ?? null;
    $vendorNotes=$_POST['vendorNotes'] ?? null;

    $sql="INSERT INTO tbl_vendor(vendor_name,vendor_mobile, vendor_gst, vendor_address, vendor_notes) VALUES(:vName,:vMobile, :vGST, :vAddress, :vNotes)";
    $query = $dbh->prepare($sql);
    $query->bindParam(':vName',$vendorName,PDO::PARAM_STR);
    $query->bindParam(':vMobile',$vendorMobile,PDO::PARAM_STR);
    $query->bindParam(':vGST',$vendorGST,PDO::PARAM_STR);
    $query->bindParam(':vAddress',$vendorAddress,PDO::PARAM_STR);
    $query->bindParam(':vNotes',$vendorNotes,PDO::PARAM_STR);
    $query->execute();
    $lastInsertId = $dbh->lastInsertId();
    if($lastInsertId){
        $msg="Vendor added Successfully";
        ?>
<script>
window.setTimeout(function() {
    window.location = "add-vendor.php";
}, 2000);
</script>
<?php
    }else{
        $error="Something went wrong. Please try again";
        ?>
<script>
window.setTimeout(function() {
    window.location = "add-vendor.php";
}, 2000);
</script>
<?php
    }
}
if(isset($_GET['del'])){
    $id=$_GET['del'];
    $sql = "delete from  tbl_vendor  WHERE id=:id";
    $query = $dbh->prepare($sql);
    $query -> bindParam(':id',$id, PDO::PARAM_STR);
    $query -> execute();
    $msg="Item Type Deleted";
    ?>
<script>
window.location.href = 'add-vendor.php';
</script>
<?php
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>SANTLAL&SONS | MANAGE VENDOR</title>
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
                                    <span class="headc">ADD VENDOR</span>
                                    <hr>
                                    <div class="input-field col s12">
                                        <input id="vendorName" type="text" class="validate" autocomplete="off"
                                            name="vendorName" required>
                                        <label for="vendorName">Vendor Name</label>
                                    </div>

                                    <div class="input-field col s12">
                                        <input id="vendorMobile" type="tel" class="validate" autocomplete="off"
                                            name="vendorMobile">
                                        <label for="vendorMobile">Vendor Mobile</label>
                                    </div>

                                    <div class="input-field col s12">
                                        <input id="vendorGST" type="text"
                                            pattern="^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$"
                                            class="validate" autocomplete="off" name="vendorGST">
                                        <label for="vendorGST">Vendor GST</label>
                                    </div>

                                    <div class="input-field col s12">
                                        <textarea id="vendorAddress" type="text" class="materialize-textarea"
                                            class="validate" autocomplete="off" name="vendorAddress"> </textarea>
                                        <label for="vendorAddress">Vendor Address</label>
                                    </div>

                                    <div class="input-field col s12">
                                        <textarea id="vendorNotes" type="text" class="materialize-textarea"
                                            class="validate" autocomplete="off" name="vendorNotes"> </textarea>
                                        <label for="vendorNotes">Vendor Notes</label>
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
                        <span class="headc">VENDOR INFO
                            <hr>
                        </span>
                        <table id="itemTable" class="display responsive-table ">
                            <thead>
                                <tr>
                                    <th>Sr no</th>
                                    <th>Leave Type</th>
                                    <th>Description</th>
                                    <th>Creation Date</th>
                                    <th>Updated Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php $sql = "SELECT * from tbl_vendor";
                                $query = $dbh -> prepare($sql);
                                $query->execute();
                                $results=$query->fetchAll(PDO::FETCH_OBJ);
                                $cnt=1;
                                if($query->rowCount() > 0){
                                foreach($results as $result){
                                ?>
                                <tr>
                                    <td> <?php echo htmlentities($cnt);?></td>
                                    <td><?php echo htmlentities($result->vendor_name);?></td>
                                    <td><?php echo htmlentities($result->vendor_mobile);?></td>
                                    <td><?php echo htmlentities($result->created_at);?></td>
                                    <td><?php echo htmlentities($result->updated_at);?></td>
                                    <td><a href="edit-vendor.php?lid=<?php echo htmlentities($result->id);?>"><i
                                                class="material-icons">mode_edit</i></a>
                                        <a href="add-vendor.php?del=<?php echo htmlentities($result->id);?>"
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
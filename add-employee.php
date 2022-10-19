<?php
session_start();
// error_reporting(0);
include('includes/config.php');
include('includes/layout.php');
if(strlen($_SESSION['emplogin'])==0){
header('location:index.php');
}
else{
if(isset($_POST['add'])){
    $fname=$_POST['firstName'];
    $lname=$_POST['lastName'];
    $email=$_POST['email'];
    $password=md5($_POST['password']);
    $mobileno=$_POST['mobileno'];
    $status=1;

    $sql="INSERT INTO tbl_employees(FirstName,LastName,EmailId,Password,mobile,Status) VALUES(:fname,:lname,:email,:password,:mobileno,:status)";
    $query = $dbh->prepare($sql);
    $query->bindParam(':fname',$fname,PDO::PARAM_STR);
    $query->bindParam(':lname',$lname,PDO::PARAM_STR);
    $query->bindParam(':email',$email,PDO::PARAM_STR);
    $query->bindParam(':password',$password,PDO::PARAM_STR);
    $query->bindParam(':mobileno',$mobileno,PDO::PARAM_STR);
    $query->bindParam(':status',$status,PDO::PARAM_STR);
    $query->execute();
    $lastInsertId = $dbh->lastInsertId();
    if($lastInsertId){
        $msg="Employee record added Successfully";
        ?>
<script>
window.setTimeout(function() {
    window.location = "add-employee.php";
}, 2000);
</script>
<?php
    }else{
    $error="Something went wrong. Please try again";
    ?>
<script>
window.setTimeout(function() {
    window.location = "add-employee.php";
}, 2000);
</script>
<?php
    }
}
if(isset($_GET['del'])){
    $id=$_GET['del'];
    $sql = "DELETE FROM  tbl_employees WHERE id=:id";
    $query = $dbh->prepare($sql);
    $query -> bindParam(':id',$id, PDO::PARAM_STR);
    $query -> execute();
    $msg="Employee Deleted";
     ?>
<script>
window.location.href = 'add-employee.php';
</script>
<?php
}
if(isset($_GET['id'])){
    $id=$_GET['id'];
    $status=1;
    $sql = "update tbl_employees set Status=:status  WHERE id=:id";
    $query = $dbh->prepare($sql);
    $query -> bindParam(':id',$id, PDO::PARAM_STR);
    $query -> bindParam(':status',$status, PDO::PARAM_STR);
    $query -> execute();
    header('location:add-employee.php');
}
if(isset($_GET['inid'])){
    $id=$_GET['inid'];
    $status=0;
    $sql = "update tbl_employees set Status=:status  WHERE id=:id";
    $query = $dbh->prepare($sql);
    $query -> bindParam(':id',$id, PDO::PARAM_STR);
    $query -> bindParam(':status',$status, PDO::PARAM_STR);
    $query -> execute();
    header('location:add-employee.php');
}
}

    ?>

<!DOCTYPE html>
<html lang="en">

<head>

    <!-- Title -->
    <title>SANTLAL&SONS | MANAGE EMPLOYEE</title>
</head>

<body>
    <?php include('includes/header.php');?>
    <?php include('includes/sidebar.php');?>
    <main class="mn-inner inner">
        <div class="row">
            <div class="col s12 m12 l12">
                <div class="card">
                    <div class="card-content">
                        <form id="employeeForm" method="post" name="addemp">
                            <div>
                                <span class="headc">ADD EMPLOYEE</span>
                                <hr>
                                <section>
                                    <div class="wizard-content" style="padding: 17px 0;">
                                        <div class="row">
                                            <div class="col m6">
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

                                                    <div class="input-field col m6 s12">
                                                        <label for="firstName">First name</label>
                                                        <input id="firstName" name="firstName" type="text" required>
                                                    </div>

                                                    <div class="input-field col m6 s12">
                                                        <label for="lastName">Last name</label>
                                                        <input id="lastName" name="lastName" type="text"
                                                            autocomplete="off" required>
                                                    </div>

                                                    <div class="input-field col s12">
                                                        <label for="mobileno">Mobile number</label>
                                                        <input id="mobileno" name="mobileno"
                                                            onBlur="checkAvailabilityMobileNo()" type="tel"
                                                            maxlength="10" autocomplete="off" required>
                                                        <span id="mobileno-availability" style="font-size:12px;"></span>
                                                    </div>

                                                    <div class="input-field col m6 s12">
                                                        <label for="email">Email</label>
                                                        <input name="email" type="email" id="email" autocomplete="off"
                                                            required>
                                                        <span id="emailid-availability" style="font-size:12px;"></span>
                                                    </div>

                                                    <div class="input-field col m6 s12">
                                                        <label for="password">Password</label>
                                                        <input id="password" name="password" type="password"
                                                            autocomplete="off" required>
                                                    </div>

                                                    <div class="input-field col s12">
                                                        <button type="submit" name="add" id="add"
                                                            class="waves-effect waves-light btn indigo m-b-xs">ADD</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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
                        <span class="headc">MANAGE EMPLOYEE</span>
                        <hr>
                        <?php if($msg){?><div class="succWrap"><strong>SUCCESS</strong> :
                            <?php echo htmlentities($msg); ?> </div><?php }?>
                        <table id="employeeTable" class="display responsive-table ">
                            <thead>
                                <tr>
                                    <th>Sr no</th>
                                    <th>Full Name</th>
                                    <th>Mobile No.</th>
                                    <th>EmailId</th>
                                    <th>Status</th>
                                    <th>Created Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php $sql = "SELECT * FROM  tbl_employees";
                                $query = $dbh -> prepare($sql);
                                $query->execute();
                                $results=$query->fetchAll(PDO::FETCH_OBJ);
                                $cnt=1;
                                if($query->rowCount() > 0){
                                foreach($results as $result){               
                                ?>
                                <tr>
                                    <td> <?php echo htmlentities($cnt);?></td>
                                    <td><?php echo htmlentities($result->FirstName);?>&nbsp;<?php echo htmlentities($result->LastName);?>
                                    </td>
                                    <td><?php echo htmlentities($result->EmailId);?></td>
                                    <td><?php echo htmlentities($result->mobile);?></td>
                                    <td><?php $stats=$result->Status;
                                    if($stats){
                                    ?>
                                        <a class="waves-effect waves-green btn-flat m-b-xs">Active</a>
                                        <?php } else { ?>
                                        <a class="waves-effect waves-red btn-flat m-b-xs">Inactive</a>
                                        <?php } ?>
                                    </td>
                                    <td><?php echo htmlentities($result->created_at);?></td>
                                    <td><a href="edit-employee.php?id=<?php echo htmlentities($result->id);?>"><i
                                                class="material-icons">mode_edit</i></a>
                                        <?php if($result->Status==1)
                                    {?>
                                        <a href="add-employee.php?inid=<?php echo htmlentities($result->id);?>"
                                            onclick="return confirm('Are you sure you want to inactive this Employe?');">
                                            <i class="
                                            material-icons" title="Inactive">clear</i>
                                            <?php } else {?>

                                            <a href="add-employee.php?id=<?php echo htmlentities($result->id);?>"
                                                onclick="return confirm('Are you sure you want to active this employee?');"><i
                                                    class="
                                                material-icons" title="Active">done</i>
                                                <?php } ?><a
                                                    href="add-employee.php?del=<?php echo htmlentities($result->id);?>"
                                                    onclick="return confirm('Do you want to delete The Employee');"> <i
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
    <div class="left-sidebar-hover"></div>


    <script>
    $('#employeeTable').DataTable({
        "pageLength": 50,
        "bLengthChange": false,
    });

    function checkAvailabilityMobileNo() {
        $("#loaderIcon").show();
        jQuery.ajax({
            url: "form.php",
            data: 'mobileno=' + $("#mobileno").val(),
            type: "POST",
            success: function(data) {
                $("#mobileno-availability").html(data);
                $("#loaderIcon").hide();
            },
            error: function() {}
        });
    }
    </script>


</body>

</html>
<?php
session_start();
// error_reporting(0);
include('includes/config.php');
include('includes/layout.php');
if(strlen($_SESSION['emplogin'])==0)
    {
header('location:index.php');
}
else{
if(isset($_POST['update']))
{
$did=intval($_GET['id']);
$username = mysqli_real_escape_string($connection, $_POST['username']) ;
$mobileno = mysqli_real_escape_string($connection, $_POST['mobileno']) ;
$emailid = mysqli_real_escape_string($connection, $_POST['emailid']) ;
$pass = mysqli_real_escape_string($connection, $_POST['password']) ;
$password = password_hash($pass, PASSWORD_BCRYPT);



$insertquery = "UPDATE tbl_employees SET FirstName='$username',Password='$password', mobile='$mobileno', EmailId='$emailid' WHERE id=$did  ";

    $iquery = mysqli_query($connection, $insertquery);
    if($iquery){
?>
<script>
window.location.href = "add-employee.php";
</script>
<?php
    }else{
        $msg="Something Went Wrong .....!!!";
    }
}

    ?>

<!DOCTYPE html>
<html lang="en">

<head>

    <!-- Title -->
    <title>SANTLAL&SONS | UPDATE EMPLOYEE</title>

</head>

<body>
    <?php include('includes/header.php');?>

    <?php include('includes/sidebar.php');?>
    <main class="mn-inner">
        <div class="row">
            <div class="container-fluid"
                style="background-color:#f2f2f2; font-family: 'nunito';padding-bottom: 10px; padding-left:13px;">
            </div>
            <div class="col s12 m12 l6">
                <div class="card">
                    <div class="card-content">
                        <span class="headc">EMPLOYEE UPDATE</span>
                        <hr>
                        <div class="row">
                            <form class="col s12" name="chngpwd" method="post">
                                <?php if($error){?><div class="errorWrap">
                                    <strong>ERROR</strong>:<?php echo htmlentities($error); ?>
                                </div><?php }
                                    else if($msg){?><div class="succWrap"><strong>SUCCESS</strong> :
                                    <?php echo htmlentities($msg); ?>
                                </div><?php }?>
                                <?php
                                    $did=intval($_GET['id']);
                                    $sql = "SELECT * FROM tbl_employees WHERE id=:did";
                                    $query = $dbh -> prepare($sql);
                                    $query->bindParam(':did',$did,PDO::PARAM_STR);
                                    $query->execute();
                                    $results=$query->fetchAll(PDO::FETCH_OBJ);
                                    $cnt=1;
                                    if($query->rowCount() > 0){
                                    foreach($results as $result){
                                ?>
                                <div class="row">
                                    <div class="input-field col s12">
                                        <input id="username" type="text" class="validate" autocomplete="off"
                                            name="username" value="<?php echo htmlentities($result->FirstName);?>"
                                            required>
                                        <label for="username">Employee Name</label>
                                    </div>


                                    <div class="input-field col s12">
                                        <input id="emailid" type="text" class="validate" autocomplete="off"
                                            value="<?php echo htmlentities($result->EmailId);?>" name="emailid"
                                            required>
                                        <label for="emailid">Email Id</label>
                                    </div>
                                    <div class="input-field col s12">
                                        <input id="mobileno" type="text" class="validate" autocomplete="off"
                                            value="<?php echo htmlentities($result->mobile);?>" name="mobileno"
                                            required>
                                        <label for="mobileno">Mobile No</label>
                                    </div>
                                    <div class="input-field col s12">
                                        <input id="departmentshortname" type="text" class="validate" autocomplete="off"
                                            name="password" required>
                                        <label for="password">Password</label>
                                    </div>

                                    <?php }} ?>


                                    <div class="input-field col s12">
                                        <button type="submit" name="update"
                                            class="waves-effect waves-light btn indigo m-b-xs">UPDATE</button>

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
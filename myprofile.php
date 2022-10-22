<?php
session_start();
// error_reporting(0);
include('includes/config.php');
include('includes/layout.php');
if(strlen($_SESSION['emplogin'])==0)
    {
header('location:index.php');
}else{
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <!-- Title -->
    <title>SANTLAL&SONS | MY PROFILE</title>

</head>

<body>
    <?php include('includes/header.php');?>

    <?php include('includes/sidebar.php');?>
    <main class="mn-inner">
        <div class="row">
            <!-- <div class="container-fluid" style="background-color:#f2f2f2; font-family: 'nunito';padding-bottom: 10px; padding-left:13px;">
                    <h3 align="">Update Employee Info.</h3>
                  </div> -->
            <div class="col s12 m12 l12">
                <div class="card">
                    <div class="card-content">
                        <form id="example-form" method="post" name="updatemp">
                            <div>
                                <span class="headc"> EMPLOYEE INFO</span>
                                <?php if($error){?><div class="errorWrap">
                                    <strong>ERROR</strong>:<?php echo htmlentities($error); ?>
                                </div><?php }
                                else if($msg){?><div class="succWrap"><strong>SUCCESS</strong> :
                                    <?php echo htmlentities($msg); ?>
                                </div><?php }?>
                                <section>
                                    <div class="wizard-content">
                                        <div class="row">
                                            <div class="col m6">
                                                <div class="row">
                                                    <?php
                                                    $eid=$_SESSION['emplogin'];
                                                    $sql = "SELECT * from  tbl_employees where id=:eid";
                                                    $query = $dbh -> prepare($sql);
                                                    $query -> bindParam(':eid',$eid, PDO::PARAM_STR);
                                                    $query->execute();
                                                    $results=$query->fetchAll(PDO::FETCH_OBJ);
                                                    $cnt=1;
                                                    if($query->rowCount() > 0)
                                                    {
                                                    foreach($results as $result)
                                                    {               ?>



                                                    <div class="input-field col m6 s12">
                                                        <label class="active" for="firstName">First name</label>
                                                        <input id="firstName" name="firstName"
                                                            value="<?php echo htmlentities($result->FirstName);?>"
                                                            type="text" required>
                                                    </div>

                                                    <div class="input-field col m6 s12">
                                                        <label class="active" for="lastName">Last name </label>
                                                        <input id="lastName" name="lastName"
                                                            value="<?php echo htmlentities($result->LastName);?>"
                                                            type="text" autocomplete="off" required>
                                                    </div>

                                                    <div class="input-field col s12">
                                                        <label class="active" for="email">Email</label>
                                                        <input name="email" type="email" id="email"
                                                            value="<?php echo htmlentities($result->EmailId);?>"
                                                            readonly autocomplete="off" required>
                                                        <span id="emailid-availability" style="font-size:12px;"></span>
                                                    </div>

                                                    <div class="input-field col s12">
                                                        <label class="active" for="phone">Mobile number</label>
                                                        <input id="phone" name="mobileno" type="tel"
                                                            value="<?php echo htmlentities($result->mobile);?>"
                                                            maxlength="10" autocomplete="off" readonly required>
                                                    </div>

                                                </div>
                                            </div>
                                            <?php }}?>
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
    </main>
    </div>
    <div class="left-sidebar-hover"></div>

</body>

</html>
<?php } ?>
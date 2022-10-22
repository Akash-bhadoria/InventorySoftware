<?php
session_start();
error_reporting(0);
include('includes/config.php');
include('includes/layout.php');

if(isset($_POST['signin'])){
    $mobileno=$_POST['mobileno'];
    $password=md5($_POST['password']);
    $sql ="SELECT EmailId,Password,Status,id FROM tbl_employees WHERE mobile=:mobileno and Password=:password";
    $query= $dbh -> prepare($sql);
    $query-> bindParam(':mobileno', $mobileno, PDO::PARAM_STR);
    $query-> bindParam(':password', $password, PDO::PARAM_STR);
    $query-> execute();
    $results=$query->fetchAll(PDO::FETCH_OBJ);
    if($query->rowCount() > 0){
        foreach ($results as $result) {
            $status=$result->Status;
            $_SESSION['eid']=$result->id;
        }
        if($status==0){
            $msg="Your account is Inactive. Please contact admin";
        }else{
            $_SESSION['emplogin']=$result->id;
            echo "<script type='text/javascript'> document.location = 'myprofile.php'; </script>";
        } 
    }else{
    echo "<script>alert('Invalid Details');</script>";
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Title -->
    <title>SANTLAL&SONS</title>
</head>

<body>
    <div class="loader-bg"></div>
    <div class="loader">
        <div class="preloader-wrapper big active">
            <div class="spinner-layer spinner-blue">
                <div class="circle-clipper left">
                    <div class="circle"></div>
                </div>
                <div class="gap-patch">
                    <div class="circle"></div>
                </div>
                <div class="circle-clipper right">
                    <div class="circle"></div>
                </div>
            </div>
            <div class="spinner-layer spinner-spinner-teal lighten-1">
                <div class="circle-clipper left">
                    <div class="circle"></div>
                </div>
                <div class="gap-patch">
                    <div class="circle"></div>
                </div>
                <div class="circle-clipper right">
                    <div class="circle"></div>
                </div>
            </div>
            <div class="spinner-layer spinner-yellow">
                <div class="circle-clipper left">
                    <div class="circle"></div>
                </div>
                <div class="gap-patch">
                    <div class="circle"></div>
                </div>
                <div class="circle-clipper right">
                    <div class="circle"></div>
                </div>
            </div>
            <div class="spinner-layer spinner-green">
                <div class="circle-clipper left">
                    <div class="circle"></div>
                </div>
                <div class="gap-patch">
                    <div class="circle"></div>
                </div>
                <div class="circle-clipper right">
                    <div class="circle"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="mn-content fixed-sidebar">
        <header class="mn-header navbar-fixed">
            <nav class="cyan darken-1" style="background-color: #00004d !important;">
                <div class="nav-wrapper row">
                    <section class="material-design-hamburger navigation-toggle">
                        <a href="#" data-activates="slide-out"
                            class="button-collapse show-on-large material-design-hamburger__icon">
                            <span class="material-design-hamburger__layer"></span>
                        </a>
                    </section>
                    <div class="header-title col s3">
                        <span class="chapter-title" style="font-family: monospace;">SANTLAL&SONS |
                            I<small>nventory</small> </span>
                    </div>
                </div>
            </nav>
        </header>



        <main class="mn-inner">
            <div class="" style="padding-left:33%;margin-top:100px">
                <div class="screen">
                    <div class="screen__content">
                        <h3 class="admin">Inventory Login</h3>
                        <form class="login" method="post">
                            <div class="login__field">
                                <input type="tel" class="login__input" placeholder="Mobile No" name="mobileno" required
                                    autocomplete="off">
                            </div>
                            <div class="login__field">
                                <input type="password" class="login__input" placeholder="Password" name="password"
                                    id="password" required autocomplete="off"><i class="bi bi-eye-slash"
                                    id="togglePassword"></i>
                            </div>
                            <button class=" button login__submit" type="submit" name="signin">Log In Now
                            </button>
                        </form>

                    </div>
                    <div class="screen__background">
                        <span class="screen__background__shape screen__background__shape4"></span>
                        <span class="screen__background__shape screen__background__shape3"></span>
                        <span class="screen__background__shape screen__background__shape2"></span>
                        <span class="screen__background__shape screen__background__shape1"></span>
                    </div>
                </div>
            </div>
        </main>

    </div>
    <div class="left-sidebar-hover"></div>

</body>

</html>
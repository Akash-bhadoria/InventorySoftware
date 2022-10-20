<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['emplogin'])==0)
    {
header('location:index.php');
}
else{

 ?>

<head>
    <style>
    .errorWrap {
        padding: 10px;
        margin: 0 0 20px 0;
        background: #fff;
        border-left: 4px solid #dd3d36;
        -webkit-box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
        box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
    }

    .succWrap {
        padding: 10px;
        margin: 0 0 20px 0;
        background: #fff;
        border-left: 4px solid #5cb85c;
        -webkit-box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
        box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
    }
    </style>
</head>
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
                        I<small>nventory</small></span>
                </div>


                <ul id="dropdown1" class="dropdown-content notifications-dropdown">
                    <li class="notificatoins-dropdown-container">
                        <ul>
                            <li class="notification-drop-title">Notifications</li>
                            <?php
                        $eid=$_SESSION['eid'];
                        $sql = "SELECT * FROM tblleaves where status='1' || status='2' AND empid=:eid";
                        $query = $dbh -> prepare($sql);
                        $query->bindParam(':eid',$eid,PDO::PARAM_STR);
                        $query->execute();
                        $results=$query->fetchAll(PDO::FETCH_OBJ);
                        if($query->rowCount() > 0)
                        {
                        foreach($results as $result)
                        {               ?>


                            <li>
                                <a href="leavehistory.php?leaveid=<?php echo htmlentities($result->id);?>">
                                    <div class="notification">
                                        <div class="notification-icon circle cyan"><i class="material-icons">done</i>
                                        </div>
                                        <div class="notification-text">
                                            <p>Your Leave Application of
                                                <br><b><?php echo htmlentities($result->LeaveType);?><br /></b>
                                            <p> Date : <?php echo htmlentities($result->FromDate);?> To
                                                <?php echo htmlentities($result->ToDate);?></p>
                                            <?php $stats=$result->Status;if($stats==1){?>
                                            <span style="color: green">Approved</span>
                                            <?php } if($stats==2)  { ?>
                                            <span style="color: red">Not Approved</span>
                                            <?php } ?></p>
                                        </div>
                                </a>
                            </li>
                            <?php }} }?>


                        </ul>

            </div>
        </nav>
    </header>
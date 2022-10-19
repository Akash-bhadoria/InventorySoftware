     <aside id="slide-out" class="side-nav white fixed">
         <div class="side-nav-wrapper">
             <div class="sidebar-profile">
                 <div class="sidebar-profile-image">
                     <img src="assets/images/profile-image.png" class="circle" alt="">
                 </div>
                 <div class="sidebar-profile-info">
                     <?php
                        $eid=$_SESSION['eid'];
                        $sql = "SELECT FirstName,LastName, mobile from  tbl_employees where id=:eid";
                        $query = $dbh -> prepare($sql);
                        $query->bindParam(':eid',$eid,PDO::PARAM_STR);
                        $query->execute();
                        $results=$query->fetchAll(PDO::FETCH_OBJ);
                        $cnt=1;
                        if($query->rowCount() > 0)
                        {
                        foreach($results as $result)
                        {               ?>
                     <p style="font-family: monospace;">Welcome
                         <?php echo htmlentities($result->FirstName." ".$result->LastName);?></p>
                     <span style="font-family: monospace;"><?php echo htmlentities($result->mobile)?></span>
                     <?php }} ?>
                 </div>
             </div>

             <ul class="sidebar-menu collapsible collapsible-accordion" data-collapsible="accordion">

                 <li class="no-padding"><a class="waves-effect waves-grey" href="myprofile.php"><i
                             class="material-icons">account_box</i>My Profiles</a></li>
                 <li class="no-padding">
                     <a class="collapsible-header waves-effect waves-grey"><i
                             class="material-icons">admin_panel_settings</i>Masters<i
                             class="nav-drop-icon material-icons">keyboard_arrow_right</i></a>
                     <div class="collapsible-body">
                         <ul>
                             <li><a href="add-employee.php">ADD EMPLOYEE</a></li>
                             <li><a href="add-item.php">ADD ITEM</a></li>
                             <li><a href="add-vendor.php">ADD VENDOR</a></li>
                         </ul>
                     </div>
                 </li>
                 <li class="no-padding"><a class="waves-effect waves-grey" href="item-from-company.php"><i
                             class="material-icons">military_tech</i>Company's Item</a></li>
                 <li class="no-padding"><a class="waves-effect waves-grey" href="item-issued-to-vendor.php"><i
                             class="material-icons">send</i>Issued To Vendor</a></li>
                 <li class="no-padding"><a class="waves-effect waves-grey" href="item-received-from-vendor.php"><i
                             class="material-icons">send_and_archive</i>Received From Vendor</a></li>
                 <li class="no-padding"><a class="waves-effect waves-grey" href="full-report.php"><i
                             class="material-icons">summarize</i>Report</a></li>
                 <li class="no-padding">
                     <a class="waves-effect waves-grey" href="logout.php"><i class="material-icons">exit_to_app</i>Sign
                         Out</a>
                 </li>


             </ul>
             <div class="footer">
                 <p class="copyright"><a href="https://santlalandsons.in/" target="_blank">Sant Lal & Sons<br>Copyright
                         ©
                         2022</a></p>

             </div>
         </div>
     </aside>
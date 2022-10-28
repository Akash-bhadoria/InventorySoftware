<?php
session_start();
error_reporting(0);
include('includes/config.php');
include('includes/layout.php');

if (strlen($_SESSION['emplogin']) == 0) {
    header('location:index.php');
} else {
    $cdate = date('Y-m-d');

    $sql = "SELECT MAX(challan_issued) as challan_no from item_issued_to_vendor";
    $query = $dbh->prepare($sql);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);
    $cnt = 1;
    if ($query->rowCount() > 0) {
        foreach ($results as $result) {
            $challan_count = $result->challan_no ?? "1500";
        }
    }


    if (isset($_GET['delId'])) {
        $id = $_GET['delId'];

        $del1 = mysqli_query($connection, "DELETE FROM `item_issued_to_vendor` WHERE id = '$id'");
        $del2 = mysqli_query($connection, "DELETE FROM `item_received_from_vendor` WHERE issued_id = '$id'");

        if ($del1 && $del2) {
            $msg = "Issued vendor Entry Deleted";
?>
            <script>
                window.location.href = 'item-issued-to-vendor.php';
            </script>
    <?php
        } else {
            $error = "Something went wrong. Please try again";
        }
    }
    ?>


    <!-- Title -->
    <title>SANTLAL&SONS</title>

    <body>
        <?php include('includes/header.php'); ?>

        <?php include('includes/sidebar.php'); ?>
        <main class="mn-inner" style="width: 100%;">
            <div class="row">
                <div class="card-content">

                    <span class="stats-counter">
                        <?php if ($errorcasual) { ?><div class="errorWrap1" style="margin-left:12px">
                                <?php echo htmlentities($errorcasual); ?> </div><?php }
                                                                                ?>
                </div>
                <div class="card-content">

                    <span class="stats-counter">
                        <?php if ($errorearned) { ?><div class="errorWrap1" style="margin-left:20px">
                                <?php echo htmlentities($errorearned); ?> </div><?php }
                                                                                ?>
                </div>

            </div>
            <div class="row">
                <div class="col s12 m12 l8">
                    <div class="card">
                        <div class="card-content" style="font-family: monospace;">
                            <form id="vendorForm" name="vendorForm" action="form.php" novalidate>
                                <div>
                                    <span class="headc">ITEM ISSUED TO VENDOR</span>
                                    <span><a style="float:right" class="btn btn-success" href="add-item.php">ADD
                                            ITEM</a></span>
                                    <hr>
                                    <section>
                                        <div class="wizard-content">
                                            <div class="row">
                                                <div class="col m12">
                                                    <div class="row">
                                                        <?php if ($error) { ?><script>
                                                                Swal.fire({
                                                                    title: 'Error',
                                                                    text: "<?php echo htmlentities($error) ?>",
                                                                    icon: "error",
                                                                    timer: 4000,
                                                                });
                                                            </script><?php } else if ($msg) { ?><script>
                                                                Swal.fire({
                                                                    title: 'Success',
                                                                    text: "<?php echo htmlentities($msg) ?>",
                                                                    icon: "success",
                                                                    timer: 3000,
                                                                });
                                                            </script><?php } ?>


                                                        <div class="input-field col  s12">
                                                            <select name="vendor_issued" id="vendor_issued" autocomplete="off" class="form-control" required>
                                                                <option value="">Select Vendor Name...</option>
                                                                <?php $sql = "SELECT  id,vendor_name from tbl_vendor";
                                                                $query = $dbh->prepare($sql);
                                                                $query->execute();
                                                                $results = $query->fetchAll(PDO::FETCH_OBJ);
                                                                $cnt = 1;
                                                                if ($query->rowCount() > 0) {
                                                                    foreach ($results as $result) {   ?>
                                                                        <option value="<?php echo htmlentities($result->id); ?>">
                                                                            <?php echo htmlentities($result->vendor_name); ?>
                                                                        </option>
                                                                <?php }
                                                                } ?>
                                                            </select>
                                                        </div>
                                                        <div class="row_add_on">
                                                            <div class="input-field col m5 s12">
                                                                <select name="item_name_issued[]" id="item_name_issued" autocomplete="off" required>
                                                                    <option value="">Select Item Name...</option>
                                                                    <?php $sql = "SELECT  id,item_type from tbl_item_type";
                                                                    $query = $dbh->prepare($sql);
                                                                    $query->execute();
                                                                    $results = $query->fetchAll(PDO::FETCH_OBJ);
                                                                    $cnt = 1;
                                                                    if ($query->rowCount() > 0) {
                                                                        foreach ($results as $result) {   ?>
                                                                            <option value="<?php echo htmlentities($result->id); ?>">
                                                                                <?php echo htmlentities($result->item_type); ?>
                                                                            </option>
                                                                    <?php }
                                                                    } ?>
                                                                </select>
                                                            </div>
                                                            <div class="input-field col m5 s12">
                                                                <label for="quantity_issued">Quantity</label>
                                                                <input type="number" id="quantity_issued" name="quantity_issued[]" required />
                                                            </div>
                                                            <div class="input-field col m2 s12">
                                                                <button class="btn add_field " style="background-color: green;">ADD</button>
                                                            </div>
                                                        </div>

                                                        <div class="input-field col m6 s12">
                                                            <label class="active" for="vendor">Challan No</label>
                                                            <input type="number" min="1500" id="challan_issued" name="challan_issued" value="<?php echo htmlentities($challan_count + 1); ?>" required />
                                                            <small class="challan">This is automated generated challan
                                                                number</small>
                                                        </div>

                                                        <div class="input-field col m6 s12">
                                                            <label class="active" for="date">Date</label>
                                                            <input type="date" id="date_issued" name="date_issued" value="<?php echo htmlentities($cdate); ?>" required />
                                                        </div>



                                                    </div>

                                                    <button type="submit" name="add_issued" id="add_issued" class="waves-effect waves-light btn indigo m-b-xs">SAVE</button>

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
                            <span class="headc">MANAGE
                                <hr>
                            </span>
                            <table id="issuedVendor" class="display responsive-table ">
                                <thead>
                                    <tr>
                                        <th>Sr no</th>
                                        <th>Item Name</th>
                                        <th>Quantity Issued</th>
                                        <th>Date Of Issued</th>
                                        <th>Challan No</th>
                                        <th>Vendor Name</th>
                                        <th>Item Left</th>
                                        <th>Created At</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php $sql = "SELECT item_issued_to_vendor.*, GROUP_CONCAT(tbl_item_type.item_type SEPARATOR ', ')as item_name ,tbl_vendor.vendor_name FROM item_issued_to_vendor LEFT JOIN tbl_item_type ON FIND_IN_SET(tbl_item_type.id, item_issued_to_vendor.item_name_issued) > 0 LEFT JOIN tbl_vendor on tbl_vendor.id=item_issued_to_vendor.vendor_issued GROUP BY item_issued_to_vendor.id";
                                    $query = $dbh->prepare($sql);
                                    $query->execute();
                                    $results = $query->fetchAll(PDO::FETCH_OBJ);
                                    $cnt = 1;
                                    if ($query->rowCount() > 0) {
                                        foreach ($results as $result) {
                                    ?>
                                            <tr>
                                                <td> <?php echo htmlentities($cnt); ?></td>
                                                <td><?php echo htmlentities($result->item_name); ?></td>
                                                <td style="color: green;">
                                                    <b><?php echo htmlentities($result->quantity_issued); ?></b>
                                                </td>
                                                <td><?php echo htmlentities($result->date_issued); ?></td>
                                                <td><?php echo htmlentities($result->challan_issued); ?></td>
                                                <td><?php echo htmlentities($result->vendor_name); ?></td>
                                                <td style="color: red;"><b><?php echo htmlentities($result->total_item_left); ?></b>
                                                </td>
                                                <td><?php echo $result->created_at ?></td>
                                                <td><a class="btn btn-success" href="item-received-update-vendor.php?id=<?php echo htmlentities($result->id); ?>">ADD
                                                        RECEIVED</a>
                                                    <a class="btn btn-success" style="background-color: red" onclick="return confirm('Do you want to delete vendor entry. Once its done it cant be revert');" href="item-issued-to-vendor.php?delId=<?php echo htmlentities($result->id); ?>">DELETE</a>
                                                </td>
                                            </tr>
                                    <?php $cnt++;
                                        }
                                    } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        </div>
        <div class="left-sidebar-hover"></div>

    <?php } ?>
    <script>
        $('#issuedVendor').DataTable({
            "pageLength": 50,
            "bLengthChange": false,
        });
        $("#item_name_issued, #vendor_issued, .select2").select2();

        $('#vendorForm').submit(function(e) {
            e.preventDefault();
            var formData = $('#vendorForm').serializeArray();
            $.ajax({
                type: "POST",
                dataType: "json",
                url: "save-vendor-issued.php",
                data: formData,
                success: (res) => {
                    window.location = "item-issued-to-vendor.php";
                }
            })
        });

        let row = `

        <div class="input-field col m5 s12">
        <select name="item_name_issued[]" id="item_name_issued" class=" form-control select2 " style="display:block">
            <option value="">Select Item Name...</option>
            <?php $sql = "SELECT  id,item_type from tbl_item_type";
            $query = $dbh->prepare($sql);
            $query->execute();
            $results = $query->fetchAll(PDO::FETCH_OBJ);
            $cnt = 1;
            if ($query->rowCount() > 0) {
                foreach ($results as $result) {   ?>
                    <option value="<?php echo htmlentities($result->id); ?>">
                        <?php echo htmlentities($result->item_type); ?></option>
            <?php }
            } ?>
            </select>
        </div>
        <div class="input-field col m5 s12">
            <label for="quantity_issued">Quantity</label>
            <input type="number" id="quantity_issued" class="form-control" name="quantity_issued[]" required />
        </div>
        `;


        var max_fields = 20;
        var wrapper = $(".row_add_on");
        var add_button = $(".add_field");

        var x = 1;
        $(add_button).click(function(e) {
            e.preventDefault();
            if (x < max_fields) {
                x++;
                $(wrapper).append(row);
            }
        });
    </script>

    </body>

    </html>
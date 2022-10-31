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

    if(isset($_GET['challan_id'])){
    $challan_id=$_GET['challan_id'];
    $sql = "delete from item_issued_to_vendor  WHERE challan_issued=:id";
    $query = $dbh->prepare($sql);
    $query -> bindParam(':id',$challan_id, PDO::PARAM_STR);
    $query -> execute();
    $msg="Item Type Deleted";
    ?>
<script>
window.location.href = 'item-issued-to-vendor.php';
</script>
<?php
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
                                <span><a style="float:right; margin-left:35px" class="btn btn-success"
                                        href="item-issued-to-vendor.php">GENERATE NEW
                                        CHALLAN</a></span>
                                <span><a style="float:right" target="_blank" class="btn btn-success" href="add-item.php"
                                        onclick="return confirm('Thi will take you to the new issue page cannot revert');">ADD
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
                                                        <select name="vendor_issued" id="vendor_issued"
                                                            autocomplete="off" class="form-control select2" required>
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

                                                    <div class="input-field col m6 s12" style="margin-top: 35px">
                                                        <label class="active" for="vendor">Challan No (<small
                                                                class="challan">This is automated generated challan
                                                                number</small>)</label>
                                                        <input type="number" min="1500" id="challan_issued"
                                                            name="challan_issued"
                                                            value="<?php echo htmlentities($challan_count + 1); ?>"
                                                            required readonly />
                                                    </div>

                                                    <div class="input-field col m6 s12" style="margin-top: 35px">
                                                        <label class="active" for="date">Date</label>
                                                        <input type="date" id="date_issued" name="date_issued"
                                                            value="<?php echo htmlentities($cdate); ?>" required />
                                                    </div>

                                                    <div class="row_add_on">
                                                        <div class="input-field col m5 s12">
                                                            <select name="item_name_issued[]" id="item_name_issued"
                                                                autocomplete="off" class="select2" required>
                                                                <option value="">Select Item Name...</option>
                                                                <?php $sql = "SELECT  id,item_type from tbl_item_type";
                                                                    $query = $dbh->prepare($sql);
                                                                    $query->execute();
                                                                    $results = $query->fetchAll(PDO::FETCH_OBJ);
                                                                    $cnt = 1;
                                                                    if ($query->rowCount() > 0) {
                                                                        foreach ($results as $result) {   ?>
                                                                <option
                                                                    value="<?php echo htmlentities($result->id); ?>">
                                                                    <?php echo htmlentities($result->item_type); ?>
                                                                </option>
                                                                <?php }
                                                                    } ?>
                                                            </select>
                                                        </div>
                                                        <div class="input-field col m5 s12">
                                                            <label for="quantity_issued">Quantity</label>
                                                            <input type="number" id="quantity_issued"
                                                                name="quantity_issued[]" required />
                                                        </div>
                                                    </div>

                                                </div>

                                                <button type="submit" name="add_issued" id="add_issued"
                                                    class="waves-effect waves-light btn indigo m-b-xs">ADD</button>
                                                <span><a style="float:right" class="btn btn-success"
                                                        onclick="return confirm('Once Confirm, all challan entries may confirm and added sucessfully');"
                                                        href="item-issued-to-vendor.php">CONFIRM ENTRIES</a></span>

                                            </div>
                                        </div>
                                        <div class="container" id="itemTable" style="display: none;">
                                            <table>
                                                <thead>
                                                    <tr>
                                                        <th>Item Name</th>
                                                        <th>Quantity Issued</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="showItems">

                                                </tbody>
                                            </table>
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
                        <span class="headc">MANAGE CHALLAN
                            <hr>
                        </span>
                        <table id="issuedVendor" class="display responsive-table ">
                            <thead>
                                <tr>
                                    <th>Challan No</th>
                                    <th>Vendor Name</th>
                                    <th>Total Quantity</th>
                                    <th>Total Item</th>
                                    <th>Date Of Issue</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php $sql = "SELECT
                                            	iitv.*,
                                            	tv.vendor_name,
                                            	sum(iitv.quantity_issued) AS total_quantity,
	                                            count(iitv.item_name_issued) AS total_item
                                            FROM
                                            	item_issued_to_vendor iitv
                                            LEFT JOIN tbl_vendor tv ON
                                            	tv.id = iitv.vendor_issued
                                            GROUP BY
                                            	iitv.challan_issued";
                                    $query = $dbh->prepare($sql);
                                    $query->execute();
                                    $results = $query->fetchAll(PDO::FETCH_OBJ);
                                    $cnt = 1;
                                    if ($query->rowCount() > 0) {
                                        foreach ($results as $result) {
                                    ?>
                                <tr>
                                    <td><?php echo htmlentities($result->challan_issued); ?></td>
                                    <td><?php echo htmlentities($result->vendor_name); ?></td>
                                    <td><?php echo htmlentities($result->total_quantity); ?></td>
                                    <td><?php echo htmlentities($result->total_item); ?></td>
                                    <td><?php echo htmlentities($result->date_issued); ?></td>
                                    <td><a class="btn btn-success" style="background-color: blue"
                                            href="item-by-challan.php?challan_id=<?php echo $result->challan_issued ?>&vendor_name=<?php echo $result->vendor_name ?>&date=<?php echo $result->date_issued ?>">OPEN
                                            CHALLAN</a>
                                        <a class="btn btn-success" style="background-color: orange" target="_blank"
                                            href="print-receipt.php?challan_id=<?php echo $result->challan_issued ?>&vendor_name=<?php echo $result->vendor_name ?>&date=<?php echo $result->date_issued ?>">RECEIPT</a>
                                        <a class="btn btn-success" style="background-color: red"
                                            onclick="return confirm('Do you want to delete  challan  Once its done it cant be revert');"
                                            href="item-issued-to-vendor.php?challan_id=<?php echo $result->challan_issued ?>">DELETE</a>
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
    const item_name = [];
    $('#issuedVendor').DataTable({
        "pageLength": 50,
        "bLengthChange": false,
    });
    $(".select2").select2();

    $('#vendorForm').submit(function(e) {
        e.preventDefault();

        var itemname = document.getElementById('item_name_issued');
        var text = itemname.options[itemname.selectedIndex].text;
        var quantity = $('#quantity_issued').val();
        item_name.push({
            'name': text,
            'quantity': quantity
        });


        var formData = $('#vendorForm').serializeArray();
        $('#add_issued').prop("disabled", true);
        $('#add_issued').html("Please Wait...");
        $.ajax({
            type: "POST",
            dataType: "json",
            url: "save-vendor-issued.php",
            data: formData,
            success: (res) => {
                $('#item_name_issued').val('0');
                $('#item_name_issued').trigger('change');
                $('#quantity_issued').val("");
                $('#add_issued').prop("disabled", false);
                $('#add_issued').html("ADD MORE ITEM");
                apendItem(item_name);
                $('#itemTable').show();

                Swal.fire({
                    title: 'success',
                    text: "Item Added Successfully To Challan No " + $('#challan_issued')
                        .val(),
                    icon: "success",
                    timer: 4000,
                });
            }
        })
    });

    function apendItem(data) {
        let option = "";
        data.forEach(item => {
            option += `<tr><td>${item.name}</td><td>${item.quantity}</td></tr>`;
        });
        $('#showItems').html(option);
    }



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
        $(".select2").select2();
    });
    </script>

</body>

</html>
<?php 
require_once("includes/config.php");


/**
 * Code for mobile number availablity
 */
if(!empty($_POST["mobileno"])) {
	$mobileno=$_POST["mobileno"];
		
	$sql ="SELECT mobile FROM tbl_employees WHERE mobile=:mobileno";
	$query= $dbh->prepare($sql);
	$query-> bindParam(':mobileno',$mobileno, PDO::PARAM_STR);
	$query-> execute();
	$results = $query -> fetchAll(PDO::FETCH_OBJ);
	if($query->rowCount() > 0){
		echo "<span style='color:red'> Mobile No. already exists .</span>";
		echo "<script>$('#add').prop('disabled',true);</script>";
	}else{	
		echo "<span style='color:green'> Mobile No. available for Registration .</span>";
		echo "<script>$('#add').prop('disabled',false);</script>";
	}
}

?>
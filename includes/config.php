<?php


$hostname     = "localhost"; 
$password     = "";
$username     = "root";                
$databasename = "inventory"; 

$connection = mysqli_connect($hostname,$username,$password,$databasename);
$dbh = new PDO("mysql:host=localhost;dbname=inventory", "root", "");



?>
<?php

//This file takes the color from the client.php
//Uses that color to query the database
//And returns a JSON object containing the votes collected

$color = $_GET['color'];

$db = mysqli_connect('localhost', 'dbUser','password','testDB') or die ("Unable to Connect to Server..");
$q = "SELECT votes FROM Votes where color='$color'";

$t = mysqli_query($db,$q) or die ("SQL ERROR");
$votes = array();
foreach($t as $x){
	
	array_push($votes,$x['votes']);
	
}

echo json_encode($votes);
?>

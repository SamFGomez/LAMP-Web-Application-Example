<?php 

function displayColors(){
	//Query MYSQL DB to obtain list of colors
	$db = mysqli_connect('localhost', 'dbUser', 'password', 'testDB');
	
	$q = 'SELECT * FROM Colors';
	$t = mysqli_query($db, $q) or die("SQL ERROR");
	
	//Create & Return a table containing 2 Columns: left) Hyperlink leading to AJAX Function. right) div where total votes will be populated 
	$colorTable = "<div class='container'><center><table class='table table-striped table-dark' style='width:70%;word-wrap:break-word;table-layout:fixed'>";
	$colorTable.= "<tr><th>Color</th><th>Votes</th></tr>";
	foreach($t as $row){
		$colorTable .= "<tr><td><a href='javascript:getVotes(\"".$row['color']."\")'>".$row['color']."</href></td>
			<td><div id=".$row['color']."></div></td></th>";
	}
	$colorTable.="</table></center></div>";
	return $colorTable;
}
?>

<script>
var total = 0;		//Stores the total amount of votes obtained from MySQL DB
var colors = [];	//Used to verify that Colors' total votes do not get counted twice

function getVotes(color){
	//Verify colors totals have not been collected yet
	if(!colors.includes(color)){
		//AJAX call to getVoteNumbers.php
		var xhttp = new XMLHttpRequest();
		xhttp.open("GET", "getVoteNumbers.php?color="+color , true);
		xhttp.send();
		xhttp.onload = function() {
			var votes = JSON.parse(xhttp.responseText);
			var totalVotes = 0;
			for(i=0;i<votes.length;i++){
				totalVotes += parseInt(votes[i]);
			}
			//Add the total votes to the total 
			total += totalVotes;
			console.log(total);
			//Display the totalVotes in the table
			document.getElementById(color).innerHTML = totalVotes;
		}
		//add the current color to the colors array to prevent it from being processed again
		colors.push(color);
	} 
	console.log(colors);
	
}
//Return the total amount of votes
function returnTotal(){
	document.getElementById('displayTotal').innerHTML = "Total Votes: "+total;
}
</script>

<style>

#table{
	opacity:0.0;
}
#displayTotal{
	display:none;
}

</style>

<html>
<head>
	<title>Color Votes</title>
	
	<!--- Bootstrap CSS -->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

</head>
<body class="bg-info">
	<div id="header">
		<center> <h1>Colors</h1> </center>
	</div>
	<br>	
	<div id="table">
	<?php echo displayColors(); ?>
	</div>
	<br>
	<center>
	<button id="total" class="btn btn-primary" onclick="javascript:returnTotal()"> Total </button> <button id="show" class="btn btn-primary" > toggle </button>
	<br><br>
	<div id="displayTotal">Total Votes: </div>
	</center>

</body>
<script src ="bower_components/jquery/dist/jquery.js"></script>		
<script>
	$(document).ready(function(){
		$("#show").click(function(){
			if($("#table").css("opacity") == 0.0){
				$("#table").css("opacity","1.0").hide();
				$("#table").fadeIn("slow");
			} else {
				$("#table").fadeOut("slow",function(){
					$("#table").show().css("opacity","0.0");
				});
				
			}
		});
		
		$("#total").mouseenter(function(){
			$("#displayTotal").fadeIn("fast");
		});
		$("#total").mouseleave(function(){
			$('#displayTotal').fadeOut("fast");
		});	
	});
</script>















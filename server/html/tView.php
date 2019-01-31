<!DOCTYPE html>
<html>
<link rel="stylesheet" type="text/css" href="./css/netgrok.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

	<head>
		<link rel="shortcut icon" href="./images/nglogoFavicon.ico" />
		<title>NetGrok | Text View</title>
	<style>
	table {
       		font-family: arial, sans-serif;
	        border-collapse: collapse;
        	width: 100%;
	}
	td, th {
        	border: 1px solid #dddddd;
	        text-align: left;
        	padding: 8px;
	}
</style>

	</head>

<script>

    document.getElementById('powerbutton').onclick = changeColor;   

    function changeColor() {
        document.body.style.color = "green";
        return false;
    }

</script>


        <body onload="startTime()">

		<div class="titleLogo">

			<a href="tView.php?clear=true" onclick=("changeColor()")><i id="powerbutton" style="font-size:36px; float:right;" class="fa fa-power-off" align="right"></i></a>;
			<br>
			<img src="./images/titleLogo.PNG" style="max-width:60%; max-height:60%;">
			<div class="navbar">
				<a href="gView.php" class="navbarButton">Graphic View</a>
	                	<a href="tView.php" class="navbarButton">Text View</a>
        	        	<a href="about.html" class="navbarButton">About</a>
				<div id="time">
				<script src="./scripts/shell.js"></script>
				</div>

			</div>
		</div>



		<div class="main">
                <?php

	                $filename = '/home/ubuntu/test/db/netgrok.db';
        	        $db = new SQLite3($filename);
			$results = $db->query('SELECT * FROM NETGROK');
			echo "<table style='width:100%'";
			echo "<tr><th>ID</th><th>Source IP</th><th>Source Port</th><th>Destination IP</th><th>Destination Port</th><th>Time Start</th><th>Time End</th><th>Download</th><th>Upload</th><th>Protocol</th><th>Host</th></tr>";
			while ($row = $results->fetchArray()) {
				echo "<tr><td>";
        	                var_export($row[0]);
				echo "<td>";
				var_export($row[1]);
				echo "</td>";
				echo "<td>";
                                var_export($row[2]);
                                echo "</td>";
				echo "<td>";
                                var_export($row[3]);
                                echo "</td>";
				echo "<td>";
                                var_export($row[4]);
                                echo "</td>";
				echo "<td>";
                                var_export($row[5]);
                                echo "</td>";
				echo "<td>";
                                var_export($row[6]);
                                echo "</td>";
				echo "<td>";
                                var_export($row[7]);
                                echo "</td>";
				echo "<td>";
                                var_export($row[8]);
                                echo "</td>";
				echo "<td>";
                                var_export($row[9]);
                                echo "</td>";
				echo "<td>";
                                var_export($row[10]);
                                echo "</td>";
				echo "</td></td>";
                       	}
			echo "</table>";
                ?>
		</div>
        </body>
</html>


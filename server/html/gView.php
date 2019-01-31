<!DOCTYPE html>
<html>
<head>
  <link rel="shortcut icon" href="./images/nglogoFavicon.ico"/> 
  <link rel="stylesheet" type="text/css" href="./css/netgrok.css">
  <title>NetGrok | Graphic View</title>
</head>
 <div class="titleLogo">
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


<link="content-type" content="text/html; charset=UTF-8">
  <script type="text/javascript" src="./scripts/vis.js"></script><style>@media print {#ghostery-purple-box {display:none !important}}</style>
  <link href="./vis-network.css" rel="stylesheet" type="text/css">



<script type='text/javascript'>
	function drawNodes(nodejson, ipjson) {
        // create an array with nodes
	var nodeArray = [];
	var ipArray = [];
	for(var i in nodejson) {
		nodeArray.push(i, nodejson[i]);
	}
	for(var i in ipjson) {
		ipArray.push(i, ipjson[i]);
	}
	
	
        var nodes = new vis.DataSet();
	for(var j=0; j < nodeArray.length; j+=2) {
		nodes.add([
		{label: nodeArray[j+1],image:'https://' + nodeArray[j+1] + '/favicon.ico', shape: 'image',  group: "sites"}
		]);	
	}

	for(var j=0; j<ipArray.length; j+=2) {
		nodes.add([
		{label: ipArray[j+1], image: "./images/image.png", shape: 'image',  group: "hosts"}
		]);
	}
 

        // create a network
        var container = document.getElementById('mynetwork');
        var data = {
                nodes: nodes,
        };
        var options = {
		nodes: {
		  brokenImage: "./images/Question.png",
		},
		layout: {
			randomSeed:2
		},
		interaction: {
			navigationButtons: true,
			keyboard: true,
		},
		groups: {
			hosts: {
				brokenImage: "./images/image.png",
				physics: true,
				color: 'red',
			},
			sites: {
				physics: true,			
			}
		}
	};
        var network = new vis.Network(container, data, options);
	
	network.on('click',function(properties) {
		var ids = properties.nodes;
		var clickedNodes = nodes.get(ids);
		document.getElementById('info').innerHTML = 'Info:' + JSON.stringify(clickedNodes);
		console.log('clicked nodes: ', clickedNodes)
	});
	};

</script>



  <style type="text/css">
    #mynetwork {
      position: absolute;
      top:1;
      left:1;
      width: 100%;
      height: 100%;
      border: 3px gray; 
   }
  </style>



        <body onload="startTime()">



		<script>
			function startTime() {
  			var today = new Date();
  			var h = today.getHours();
  			var m = today.getMinutes();
  			var s = today.getSeconds();
  			m = checkTime(m);
  			s = checkTime(s);
  			document.getElementById('time').innerHTML =
  			h + ":" + m + ":" + s;
  			var t = setTimeout(startTime, 500);
			}
			function checkTime(i) {
  			if (i < 10) {i = "0" + i};  // add zero in front of numbers < 10
  			return i;
			}
		</script>
		
		<div id="time"></div>
	
                <?php

                $filename = '/home/ubuntu/test/db/netgrok.db';

                $db = new SQLite3($filename);
		$results = $db->query('SELECT * FROM NETGROK');
		
		$ips = array();
		$hosts = array();
	
		while ($row = $results->fetchArray()) {
                        array_push($ips, $row['SRC_IP']);
			array_push($hosts, $row['HOST']);	
		}	
		echo '<br><br>';
		
		$ips = array_unique($ips);
		$hosts = array_unique($hosts);
		
		$ips = json_encode($ips);
		$hosts = json_encode($hosts);
	
		echo '<div id="mynetwork"><div class="vis-network" style="position: fixed; overflow: hidden; top:50%; left:50%; touch-action: pan-y; -moz-user-select: none; width: 100%; height: 100%;" tabindex="900"><canvas style="position: fixed; top:50%; left:50%; touch-action: none; -moz-user-select: none; width: 100%; height: 100%;" margin-top:-100px; margin-left:-200px;width="2000"; height="2500"></canvas></div></div>

		<script>
		drawNodes('.$hosts.','.$ips.');
		</script>';
		?>

		<div id="info"></div>



        </body>
</html>


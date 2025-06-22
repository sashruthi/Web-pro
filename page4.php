<html>
<br><br>
<center>
<h2 style="color:white"> summary report</h2>
</center>
<?php
$con1=mysqli_connect('localhost','root','preethi123','departmentalstore');
$query="select * from count_prod";
$test=array();
$count=0;
$result=mysqli_query($con1,$query);
while($row=mysqli_fetch_array($result))
{
  $test[$count]['label']=$row['prod_name'];
  $test[$count]['y']=$row['count'];
$count=$count+1;
}
?>
<script>
window.onload = function() {
 
var chart = new CanvasJS.Chart("chartContainer", {
        backgroundColor: "gray",
	animationEnabled: true,
	theme: "light2",
	 title: {
            text: "Sales Report",
            fontColor: "black",
            fontSize: 20,
             
        },
        axisY: {
            title: "Sales Report (in percentage)",
            labelFontColor: "black",
            titleFontColor: "black"
        },
        axisX: {
            labelFontColor: "black",
            titleFontColor: "black"
        },
	data: [{
        type: "column",
        yValueFormatString: "#,##0.##",
         toolTipContent: "Count: {y}",
        dataPoints: <?php echo json_encode($test, JSON_NUMERIC_CHECK); ?>
    }]
});
chart.render();
 
}
</script>
<body style="background-color:black;">
<div id="chartContainer" style=" position:absolute;top:22%;left:20%;height:50%;width:60%;"></div>
<script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
</body>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
a {
  text-decoration: none;
  display: inline-block;
  padding: 8px 16px;
}

a:hover {
  position:absolute;
  background-color: #ddd;
  color: black;
}

.previous {
  position:absolute;
  bottom:5%;
  left:40%;
  background-color:gray;
  color: black;
}

.next {
  position:absolute;
  bottom:5%;
  right:45%;
  background-color:gray;
  color: white;
}

.round {
  border-radius: 50%;
}
</style>
</head>
<body>

<a href="page3.php" class="previous round">&#8249;</a>
<a href="#" class="next round">&#8250;</a>
  
</body> 
</html>
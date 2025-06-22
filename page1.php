<html>
<style>
#discount-table{
width:100%;
text-align:center;
background-color:gray;
color:white;
}
#price-div {
  position: absolute;
  top: 0;
  left:0;
  height: 100%;
  width: 30%;
  padding: 3px;
  background-color: black;
 border:3px solid white;
}
#whitep {
  position: relative;
  top: 15%;
  left: 15%;
  text-align: center;
  height: 60%;
  width: 70%;
  background-color:gray;
}
input[type=submit] {
  background-color:red;
  color: white;
  padding: 5px solid black;
}
#discount-div{
position: absolute;
  top: 0;
  right:0;
  height: 100%;
border:3px solid white;
  width: 30%;
  padding: 5px solid white;
  background-color: black;
}
#welcome{
position:absolute;
top:0;
left:30%;
height:50%;
width:39.2%;
background-color:black;
text-align:center;
overflow:scroll;
border:3px solid white;
}
</style>
<body>
<div id="price-div">
  <center><h4 style="color:white;">PRICE FIXING</h4></center><br>
  <div id="whitep">
    <form name="price-form" method="POST">
      <br><br><br>
      <input type="text" name="pid" placeholder="product id" id="pid"><br><br><br>
      <input type="text" name="pn" placeholder="product name" id="pn"><br><br><br>
      <input type="text" name="price" placeholder="intended price" id="price"><br><br><br>
      <input type="submit" value="fix price" name="mysubmit" id="mysubmit"><br>
    </form>
  </div>
</div>

<?php
$con = mysqli_connect('localhost', 'root', 'preethi123', 'departmentalstore');
if (isset($_POST['mysubmit'])) {
    $pid = $_POST['pid'];
    $pn = $_POST['pn'];
    $price = $_POST['price'];

    
    
    $query = "UPDATE prod_det SET initial_price='$price' WHERE prod_id='$pid'";
    $result = mysqli_query($con, $query);
    if ($result) {
        echo "<script>alert('price has been fixed sucessfully')</script>";
    } 

    
}
?>
<div id="discount-div">
<center><h4 style="color:white;">DISCOUNT FIXING</h4></center><br>
 <div id="whitep">
<form name="discount-form" method="post">
<br><br><br>
      <input type="text" name="pid" placeholder="product id" id="pid"><br><br><br>
      <input type="text" name="pn" placeholder="product name" id="pn"><br><br><br>
      <input type="number" name="dper" placeholder="intended discount" id="dper"><br><br><br>
     <input type="submit" value="create discount" name="submitdiscount" id="mydiscount">&nbsp &nbsp
<input type ="submit" value="delete discount" name="deletediscount" id="deletediscount">
</form>
</div>
</div>
<?php
   
    if (isset($_POST['submitdiscount']))
      {
        $productid = $_POST['pid'];
        $pn=$_POST['pn'];
        $discount = $_POST['dper'];

    
            $query = "INSERT INTO dprod VALUES('$productid','$pn','$discount')";
            $query2="UPDATE prod_det set discount='$discount' WHERE prod_id='$productid'";
            $result2=mysqli_query($con,$query2);
            $result = mysqli_query($con, $query);

            if ($result) {
                echo "<script>alert('DISCOUNT CREATED SUCCESSFULLY');</script>";}
}
if(isset($_POST['deletediscount']))
{
  $pid=$_POST['pid'];
$query="DELETE FROM dprod WHERE prod_id='$pid'";
$query1="UPDATE prod_det set discount='0' WHERE prod_id='$pid'";
$result1=mysqli_query($con,$query1);
$result=mysqli_query($con,$query);
if($result)
{
  echo "<script>alert('discount deleted')</script>";
}
}

?>
<style>
#expiry-prod{
position:absolute;
top:50%;
left:30%;
height:51.1%;
width:39.2%;
border:3px solid white;
background-color:black;
color:white;
overflow:scroll;
}
#expiry-table{
width:100%;
text-align:center;
background-color:gray;
color:white;
}
#bluep{
position:relative;
top: 25%;
left: 15%;
border-radius: 25px;
text-align: center;
height: 50%;
width: 70%;
background-color:lightblue;
color:white;

}
</style>
<head>
    <title>Expiring Products</title>
</head>
<body>
<div id="expiry-prod"><br>
<center><h4 style="color:white;">PRODUCTS EXPIRING WITHIN 30 DAYS</h4></center><br><br>
<table id="expiry-table" border="1">
<tr>
    <th>Product ID</th>
    <th>Product Name</th>
    <th>Quantity</th>
    <th>Expiry Date</th>
</tr>
<?php
$con = mysqli_connect('localhost', 'root', 'preethi123', 'departmentalstore');


$query = "SELECT 
    prod_id,       
    prod_name,
    quantity,      
    expiry_date
FROM 
    prod_det
WHERE 
    expiry_date <= CURRENT_DATE + INTERVAL 30 DAY
AND 
    expiry_date > CURRENT_DATE";

$result = mysqli_query($con, $query);

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>
                <td>{$row['prod_id']}</td>
                <td>{$row['prod_name']}</td>
                <td>{$row['quantity']}</td>
                <td>{$row['expiry_date']}</td>
              </tr>";
    }
} else {
    echo "Error: " . mysqli_error($con);
}

?>
</table>
</div>
<div id="welcome">
<h4 style="color:white;">DEALS OF THE MONTH</h4>
<table id="discount-table" border=1>
<tr><th>product id</th><th>discount%</th></tr>
<?php
$query="select * from dprod";
$result=mysqli_query($con,$query);
while($row=mysqli_fetch_assoc($result))
{
  echo "<tr><td>".$row['prod_id']."</td><td>".$row['prod_discount']."</td></tr>";
}
mysqli_close($con);
?>
</table>
</div>
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

<a href="#" class="previous round">&#8249;</a>
<a href="page2.php" class="next round">&#8250;</a>
  
</body> 

</html>



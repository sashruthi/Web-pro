<!DOCTYPE html>
<html>
<head>
<style>
#prod-catlog {
  position: absolute;
  top: 0;
  height: 100%;
  width: 100%;
  left: 0;
  background-color: black;
  color: white;
}

#prod-table {
  color: white;
  width: 100%;
  padding: 2px;
  background-color: gray;
  text-align: center;
  column-gap: 1px;
}

input[type=button] {
  background-color: gray;
  color: white;
  padding: 6px;
}
</style>
</head>
<body>
<div id="prod-catlog">
  <center>
    <h3 style="color:white;"> PRODUCT-CATLOG </h3><br>
    <table id="prod-table" border=1>
      <tr>
        <th>Product ID</th>
        <th>Product Name</th>
        <th>Quantity</th>
        <th>Packaging Mode</th>
        <th>Manufacturing Date</th>
        <th>Expiry Date</th>
        <th>Initial Price</th>
        <th>Discount</th>
        <th>Final Price</th>
      </tr>
      <?php
      $con = mysqli_connect('localhost', 'root', 'preethi123', 'departmentalstore');
      
      $query = "SELECT * FROM prod_det";
      $result = mysqli_query($con, $query);

      if($result){
        while ($row = mysqli_fetch_assoc($result)) {
          echo "<tr><td>" . $row['prod_id'] . "</td><td>" . $row['prod_name'] . "</td><td>" . $row['quantity'] . "</td><td>" . $row['packaging_mode'] . "</td><td>" . $row['manufacturing_date'] . "</td><td>" . $row['expiry_date'] . "</td><td>" . $row['initial_price'] . "</td><td>" . $row['discount'] . "</td><td>" . $row['final_price'] . "</td></tr>";
        }
      }
      mysqli_close($con);
      ?>
    </table>
  </center>
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

<a href="page2.php" class="previous round">&#8249;</a>
<a href="#" class="next round">&#8250;</a>
  
</body> 
</html>

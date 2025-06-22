<!DOCTYPE html>
<html>
<head>
    <title>Product Management</title>
    <style>
        #display-div {
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            background-color: black;
            width: 30%;
            border: 3px solid white;
            overflow: scroll;
            z-index: 1;
        }

        #display-table {
            width: 100%;
            text-align: center;
            background-color: gray;
            color: white;
        }

        #request-div {
            position: absolute;
            top: 0;
            left: 30%;
            height: 100%;
            width: 35%;
            background-color: black;
            text-align: center;
            border: 3px solid white;
            z-index: 1;
        }

        #whitep {
            position: relative;
            top: 4%;
            left: 15%;
            height: 73%;
            width: 70%;
            background-color: gray;
        }

        input[type=submit] {
            background-color: red;
            color: white;
            padding: 5px solid black;
        }

        #request-history {
            position: absolute;
            top: 0;
            right: 0;
            height: 50%;
            width: 34.3%;
            border: 3px solid white;
            background-color: gray;
            color: white;
            overflow: scroll;
            z-index: 1;
        }

        #alerts {
            position: absolute;
            top: 50.3%;
            right: 0;
            height: 48.8%;
            width: 33.5%;
            padding: 5px;
            border: 3px solid white;
            background-color: gray;
            overflow: scroll;
            color: white;
            z-index: 1;
        }

        a {
            text-decoration: none;
            display: inline-block;
            padding: 8px 16px;
        }

        a:hover {
            position: absolute;
            background-color: #ddd;
            color: black;
        }

        .previous {
            position: absolute;
            bottom: 3%;
            left: 40%;
            background-color: gray;
            color: black;
            z-index: 10;
        }

        .next {
            position: absolute;
            bottom: 3%;
            right: 45%;
            background-color: gray;
            color: white;
            z-index: 10;
        }

        .round {
            border-radius: 50%;
        }
    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
<div id="display-div">
    <center><h4 style="color:white;"> PRODUCTS ON DISPLAY</h4></center>
    <table id="display-table" border="1">
        <tr>
            <th>Product ID</th>
            <th>Product name</th>
            <th>Quantity</th>
            <th>No of product on display</th>
        </tr>
        <?php
        $con = mysqli_connect('localhost', 'root', 'preethi123', 'departmentalstore');
        $query = "SELECT * FROM on_display";
        $result = mysqli_query($con, $query);
        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr><td>" . $row['prod_id'] . "</td><td>" . $row['prod_name'] . "</td><td>" . $row['quantity'] . "</td><td>" . $row['display_count'] . "</td></tr>";
            }
        }
        mysqli_close($con);
        ?>
    </table>
</div>
<div id="request-div">
    <h3 style="color:white;">PRODUCT REQUEST</h3>
    <div id="whitep"><br>
        <form name="request-form" method="post">
            <br><br><br><br><br><br>
            <input type="number" placeholder="Product ID" name="pid" id="pid"><br><br>
            <input type="text" placeholder="Product name" name="pn" id="pn"><br><br>
            <input type="text" placeholder="Quantity" name="qn" id="qn"><br><br>
            <input type="number" placeholder="Count" name="count" id="count"><br><br><br>
            <input type="submit" value="Send request" name="submit"><br>
        </form>
    </div>
</div>
<?php
$con = mysqli_connect('localhost', 'root', 'preethi123', 'departmentalstore');
if (isset($_POST['submit'])) {
    $pid = $_POST['pid'];
    $pn = $_POST['pn'];
    $qn = $_POST['qn'];
    $count = $_POST['count'];
    $query = "INSERT INTO stock_request(prod_id, prod_name, quantity, count) VALUES ('$pid', '$pn', '$qn', '$count')";
    $result = mysqli_query($con, $query);
    if (!$result) {
        die("Error: " . mysqli_error($con));
    }
}
?>
<div id="request-history">
    <center><h3> REQUEST HISTORY </h3></center><br><br>
    <?php
    $query1 = "SELECT * FROM stock_request";
    $result = mysqli_query($con, $query1);
    $query2="select * from stock_history";
    $result1=mysqli_query($con,$query2);
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<p>" . $row['count'] . " packets of " . $row['prod_name'] . " (" . $row['quantity'] . ") has been requested on " . $row['request_time'] . "</p>";
            
        }
    }
 if ($result1 && mysqli_num_rows($result1) > 0) {
        while ($row = mysqli_fetch_assoc($result1)) {
            echo "<p>" . $row['count'] . " packets of " . $row['prod_name'] . " (" . $row['quantity'] . ") has been sent on " . $row['request_time'] . "</p>";
            
        }
    }
    ?>
</div>
<div id="alerts">
    <center><h3>ALERT MESSAGES</h3></center>
    <?php
    $query2 = "SELECT prod_name, prod_id FROM on_display WHERE display_count < 5";
    $result = mysqli_query($con, $query2);
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo " Alert: The stock for product " . $row['prod_name'] . " (" . $row['prod_id'] . ") is running low. Please restock immediately to ensure availability.<br>";
        }
    }
    ?>
</div>

<a href="sales_manager_login.php" class="previous round">&#8249;</a>
<a href="page3.php" class="next round">&#8250;</a>

<script>
    $(document).ready(function() {
        // Fetch product details when product ID is entered in request form
        $('#pid').on('blur', function() {
            let productId = $(this).val();
            if (productId) {
                $.ajax({
                    url: 'fp2.php',
                    method: 'POST',
                    data: { pid: productId },
                    success: function(response) {
                        let data = JSON.parse(response);
                        $('#pn').val(data.prod_name);
                        $('#qn').val(data.quantity);
                    },
                    error: function(xhr, status, error) {
                        console.log("Error:", error);
                    }
                });
            }
        });
    });
</script>

</body>
</html>

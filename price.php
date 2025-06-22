<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Price Management</title>
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            min-height: 100vh;
        }
        #sidebar {
            background:white;
            color: #333;
            min-width: 250px;
            max-width: 250px;
            min-height: 100vh;
            padding: 20px;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
            display: flex;
            flex-direction: column;
            justify-content: space-between; /* Ensure the button is at the bottom */
        }
        #sidebar .nav-link {
            color: #333;
        }
        #sidebar .nav-link:hover {
            background: #e9ecef; /* Light gray background */
            color: #000;
        }
        #sidebar .btn-logout {
            color: #fff;
            background-color: #dc3545;
            border: none;
            width: 100%;
            margin-top: auto; /* Pushes the button to the bottom */
        }
        #content {
            flex: 1;
            padding: 20px;
            background: #f8f9fa;
        }
        #price-form {
            max-width: 500px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div id="sidebar">
        <br><br>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="home.php">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="price.php">Price</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="discounts.php">Discount</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="product_request.php">Product Request</a>
            </li>
            <li class="nav-item">
                    <a class="nav-link" href="product_catlog.php">Product catlog</a>
                </li>
        </ul>
       <button class="btn btn-logout" onclick="window.location.href='logout.php'">Logout</button>
    </div>

    <!-- Main Content -->
    <div id="content">
        <center><h6>Please fill out the form below to set the selling prices for the listed products.</h6></center><br><br>
        <div id="price-form" class="bg-white p-4 rounded shadow">
            <form name="price-form" method="POST">
                <div class="form-group">
                    <label for="pid">Product ID</label>
                    <input type="text" name="pid" class="form-control" id="pid" placeholder="Product ID">
                </div>
                <div class="form-group">
                    <label for="pn">Product Name</label>
                    <input type="text" name="pn" class="form-control" id="pn" placeholder="Product Name" readonly>
                </div>
                <div class="form-group">
                    <label for="price">MRP</label>
                    <input type="text" name="price" class="form-control" id="price" placeholder="MRP">
                </div>
                <div class="form-group">
                    <label for="price">Selling price</label>
                    <input type="text" name="price" class="form-control" id="price" placeholder="Selling Price">
                </div>
                <center>
                <button type="submit" class="btn btn-secondary" name="mysubmit" id="mysubmit">Fix Price</button></center>
            </form>
        </div>
    </div>
   <?php
$con = mysqli_connect('localhost', 'root', 'preethi123', 'departmentalstore');

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['mysubmit'])) {
    $pid = $_POST['pid'];
    $price = $_POST['price'];

    // Check if price is less than or equal to MRP
    $query = "SELECT mrp FROM prod_det WHERE prod_id='$pid'";
    $result = mysqli_query($con, $query);
    $row = mysqli_fetch_assoc($result);

    if ($price <= $row['mrp']) {
        // Update retailer price
        $update_query = "UPDATE prod_det SET retailer_price='$price' WHERE prod_id='$pid'";
        $update_result = mysqli_query($con, $update_query);

        if ($update_result) {
            echo "<script>alert('Price has been fixed successfully')</script>";
        } else {
            echo "<script>alert('Error updating price')</script>";
        }
    } else {
        echo "<script>alert('Price cannot be greater than MRP')</script>";
    }
}

mysqli_close($con);
?>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
       $(document).ready(function() {
    // Fetch product details when product ID is entered in price form
    $('#pid').on('blur', function() {
        let productId = $(this).val();
        if (productId) {
            $.ajax({
                url: 'fp.php',
                method: 'POST',
                data: { pid: productId },
                success: function(response) {
                    console.log(response); // Log the response for debugging
                    let data = JSON.parse(response);
                    $('#pn').val(data.prod_name);
                    $('#price').val(data.mrp); // Automatically fill MRP field
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error: ', status, error);
                }
            });
        }
    });
});

    </script>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Product Request System</title>
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            min-height: 100vh;
        }
        #sidebar {
            background: white;
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
        #request-form {
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
        <center><h6>Please utilize the following form when the quantity of displayed products is running low or when you need additional products.</h6></center><br><br>
        <div id="request-form" class="bg-white p-4 rounded shadow">
            <form name="request-form" method="post">
                <div class="form-group">
                    <label for="pid">Product ID</label>
                    <input type="number" class="form-control" placeholder="Product ID" name="pid" id="pid">
                </div>
                <div class="form-group">
                    <label for="pn">Product Name</label>
                    <input type="text" class="form-control" placeholder="Product Name" name="pn" id="pn" readonly>
                </div>
                <div class="form-group">
                    <label for="qn">Net weight</label>
                    <input type="text" class="form-control" placeholder="Net weight" name="qn" id="qn" readonly>
                </div>
                 <div class="form-group">
                    <label for="qn">unit of measurement</label>
                    <input type="text" class="form-control" placeholder="UOM" name="uom" id="uom" readonly>
                </div>
                <div class="form-group">
                    <label for="count">Quantity</label>
                    <input type="number" class="form-control" placeholder="Quantity" name="count" id="count">
                </div>
                <center>
                <button type="submit" class="btn btn-secondary" name="submit">Send Request</button></center>
            </form>
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
    if ($result) {
         echo "<script>alert('request has been sent!')</script>";
    }
}
?>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
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
                            $('#qn').val(data.net_weight);
                            $('#uom').val(data.uom);
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

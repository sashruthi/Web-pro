<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Discount Management</title>
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
        #discount-form {
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
        <center><h6>The following form can be utilized to establish discounts for the specified products</h6></center><br><br>
        <div id="discount-form" class="bg-white p-4 rounded shadow">
            <form name="discount-form" method="post">
                <div class="form-group">
                    <label for="d_pid">Product ID</label>
                    <input type="text" name="pid" class="form-control" id="d_pid" placeholder="Product ID">
                </div>
                <div class="form-group">
                    <label for="d_pn">Product Name</label>
                    <input type="text" name="pn" class="form-control" id="d_pn" placeholder="Product Name" readonly>
                </div>
                <div class="form-group">
                    <label for="dper"> Discount percentage</label>
                    <input type="number" name="dper" class="form-control" id="dper" placeholder="Discount">
                </div>
                <center>
                    <button type="submit" class="btn btn-secondary" name="submitdiscount">Create Discount</button>&nbsp;&nbsp;
                    <button type="submit" class="btn btn-secondary" name="deletediscount">Delete Discount</button>
                </center>
            </form>
        </div>
    </div>

    <?php
    $con = mysqli_connect('localhost', 'root', 'preethi123', 'departmentalstore');
    if (isset($_POST['submitdiscount'])) {
        $productid = $_POST['pid'];
        $pn = $_POST['pn'];
        $discount = $_POST['dper'];

        $query = "INSERT INTO dprod VALUES('$productid', '$discount')";
        $query2 = "UPDATE prod_det SET discount='$discount' WHERE prod_id='$productid'";
        $result2 = mysqli_query($con, $query2);
        $result = mysqli_query($con, $query);

        if ($result) {
            echo "<script>alert('DISCOUNT CREATED SUCCESSFULLY');</script>";
        }
    }

    if (isset($_POST['deletediscount'])) {
        $pid = $_POST['pid'];
        $query = "DELETE FROM dprod WHERE prod_id='$pid'";
        $query1 = "UPDATE prod_det SET discount='0' WHERE prod_id='$pid'";
        $result1 = mysqli_query($con, $query1);
        $result = mysqli_query($con, $query);
        if ($result) {
            echo "<script>alert('DISCOUNT DELETED');</script>";
        }
    }
    ?>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            // Fetch product details when product ID is entered in discount form
            $('#d_pid').on('blur', function() {
                let productId = $(this).val();
                if (productId) {
                    $.ajax({
                        url: 'fp.php',
                        method: 'POST',
                        data: { pid: productId },
                        success: function(response) {
                            let data = JSON.parse(response);
                            $('#d_pn').val(data.prod_name);
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>

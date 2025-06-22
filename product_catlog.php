<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Catalog</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa; /* Light gray background */
            display: flex; /* Use flexbox for layout */
            justify-content: flex-start; /* Align content from the start */
        }

        #sidebar {
            background: white;
            color: #333;
            min-width: 250px;
            max-width: 250px;
            height: 100vh; /* Full height sidebar */
            padding: 20px;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        #sidebar .nav-link {
            color: #333;
        }

        #sidebar .nav-link:hover {
            background: #dcdcdc; /* Light gray background on hover */
            color: black;
        }

        #sidebar .btn-logout {
            color: #fff;
            background-color: #dc3545;
            border: none;
            width: 100%;
            margin-top: auto; /* Push logout button to the bottom */
        }

        #content {
            flex: 1; /* Fill remaining space */
            padding: 20px;
        }

        #prod-catlog {
            background-color: #ffffff; /* White background */
            padding: 20px; /* Padding for content */
            border-radius: 10px; /* Rounded corners */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Shadow effect */
        }

        #prod-table {
            width: 100%; /* Full width table */
        }

        #prod-table th, #prod-table td {
            text-align: center; /* Center align text in cells */
            vertical-align: middle; /* Middle align content */
            font-size: 12px; /* Smaller font size */
        }

        #prod-table th {
            background-color: #f1f1f1; /* Light gray background for header */
            color: black; /* Black text */
        }

        #prod-table tr:nth-child(even) {
            background-color: #f9f9f9; /* Light gray background for even rows */
        }

        #prod-table tr:hover {
            background-color: #f1f1f1; /* Light gray background on hover */
        }

        .expired {
    background-color: #ff9999 !important; /* Light red for expiring soon */
}

.nearing-expiry {
    background-color: #ffcc99 !important; /* Light orange for nearing expiry */
}



.legend {
    background-color: #e9ecef;
    border: 1px solid #ced4da;
    border-radius: 5px;
    padding: 10px;
    margin-bottom: 20px;
    box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 10px;
    font-size: 14px;
}

.legend div {
    display: flex;
    align-items: center;
}

.legend span {
    display: inline-block;
    width: 20px;
    height: 20px;
    margin-right: 10px;
    border: 1px solid #ccc;
}

    </style>
</head>
<body>

<!-- Sidebar -->
<div id="sidebar">
    <ul class="nav flex-column"><br><br>
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
            <a class="nav-link" href="product_catlog.php">Product Catalog</a>
        </li>
    </ul>
    <button class="btn btn-logout" onclick="window.location.href='logout.php'">Logout</button>
</div>

<!-- Main Content -->
<div id="content">
    <div id="prod-catlog">
        <div class="container">

            <!-- Legend -->
            <div class="legend">
               
                <div>
                    <span style="background-color: #ffcc99;"></span> Nearing Expiry &nbsp &nbsp
                </div> &nbsp &nbsp
                <div>
                    <span style="background-color: #ff9999;"></span> Expired
                </div>
            </div>

            <table id="prod-table" class="table table-bordered">
                <thead>
                    <tr>
                        <th>Product ID</th>
                        <th>Product Name</th>
                        <th>Net Weight</th>
                        <th>Unit Of Measurement</th>
                        <th>Packaging Mode</th>
                        <th>Manufacturing Date</th>
                        <th>Expiry Date</th>
                        <th>MRP</th>
                        <th>Retailer Price</th>
                        <th>Discount</th>
                        <th>Selling Price</th>
                    </tr>
                </thead>
                <tbody>
                   <?php
$con = mysqli_connect('localhost', 'root', 'preethi123', 'departmentalstore');
$query = "SELECT * FROM prod_det";
$result = mysqli_query($con, $query);

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $expiry_date = new DateTime($row['expiry_date']);
        $current_date = new DateTime();
        $interval = $current_date->diff($expiry_date);
        $days_until_expiry = $interval->days;
        $expiry_class = '';

        if ($expiry_date < $current_date) {
            $expiry_class = 'expired';
        } elseif ($days_until_expiry <= 15) {
            $expiry_class = 'nearing-expiry';
        }
         


        $discount = $row['discount'] > 0 ? '⭐ ' . $row['discount'] : $row['discount'];
        echo "<tr>
                <td>{$row['prod_id']}</td>
                <td>{$row['prod_name']}</td>
                <td>{$row['net_weight']}</td>
                <td>{$row['uom']}</td>
                <td>{$row['packaging_mode']}</td>
                <td>{$row['manufacturing_date']}</td>
                <td class='$expiry_class'>{$row['expiry_date']}</td>
                <td>{$row['mrp']}</td>
                <td>{$row['retailer_price']}</td>
                <td>{$discount}</td>
                <td>{$row['selling_price']}</td>
              </tr>";
    }
}
mysqli_close($con);
?>

                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>

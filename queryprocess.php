<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            color: #343a40;
        }
        table, th, td {
            border: 1px solid black;
            padding: 8px;
        }
        th {
            background-color: rgba(0,0,0,0.1);
        }
        td {
            background-color:#f8f9fa;
        }
    </style>
</head>
<body>
<?php
$con = mysqli_connect("localhost", "root", "preethi123", "departmentalstore");

if (mysqli_connect_errno()) {
    die("Connection failed: " . mysqli_connect_error());
}

$option_id = isset($_POST['option_id']) ? $_POST['option_id'] : '';

if ($option_id == '1') {
    $query = "SELECT * FROM stock_det";
    $result = mysqli_query($con, $query);

    if (mysqli_num_rows($result) > 0) {
        echo "<table><tr><th>Product ID</th><th>Product Name</th><th>Net Weight </th><th>UOM</th><th>Stock Availability</th><th>Stock Price</th></tr>";
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr><td>" . $row["prod_id"] . "</td><td>" . $row["prod_name"] . "</td><td>" . $row["net_weight"] . "</td><td>" . $row["uom"] . "</td><td>" . $row["stock"] . "</td><td>" . $row["price"] . "</td></tr>";
        }
        echo "</table>";
    } else {
        echo "No products found";
    }
} elseif ($option_id == '2') {
    $input1 = $_POST['input1'];
    $input2 = $_POST['input2'];
    $input3 = $_POST['input3'];
    $input4 = $_POST['input4'];
    $input5 = $_POST['input5'];
    $input6 = $_POST['input6'];
    $input7 = $_POST['input7'];
    $input8 = $_POST['input8'];
    $input9 = $_POST['input9'];
    $input10 = $_POST['input10'];
    $input11 = $_POST['input11'];

    if ($input1 && $input2 && $input3 && $input4 && $input5 && $input6 && $input7 && $input8 && $input9 && $input10 && $input11) {
        $check_query = "SELECT * FROM stock_det WHERE prod_id = $input1";
        $check_result = $con->query($check_query);

        if ($check_result->num_rows > 0) {
            $update_query = "UPDATE stock_det SET stock = stock + $input10 WHERE prod_id = $input1";
            if ($con->query($update_query) === TRUE) {
                $price_query = "SELECT price FROM stock_det WHERE prod_id = $input1";
                $price_result = $con->query($price_query);
                if ($price_result->num_rows > 0) {
                    $price_row = $price_result->fetch_assoc();
                    $price = (float)$price_row['price'];
                    $total_amount = (float)$input10 * $price;

                    $check_invoice_query = "SELECT * FROM stock_invoice WHERE prod_id = $input1";
                    $check_invoice_result = $con->query($check_invoice_query);

                    if ($check_invoice_result->num_rows > 0) {
                        $update_invoice_query = "UPDATE stock_invoice SET quantity = quantity + $input10, total_amount = total_amount + ($input10 * $price) WHERE prod_id = $input1";
                        if ($con->query($update_invoice_query) !== TRUE) {
                            echo "Error updating record in stock_invoice: " . $con->error;
                        }
                    } else {
                        $insert_invoice_query = "INSERT INTO stock_invoice (prod_id, prod_name, quantity, price, total_amount) VALUES ($input1, '$input2', $input10, $price, $total_amount)";
                        if ($con->query($insert_invoice_query) !== TRUE) {
                            echo "Error inserting record into stock_invoice: " . $con->error;
                        }
                    }

                    $query = "SELECT * FROM stock_det";
                    $result = mysqli_query($con, $query);

                    if (mysqli_num_rows($result) > 0) {
                        echo "<table class='table'><tr><th>Product ID</th><th>Product Name</th><th>Net Weight</th><th>UOM</th><th>Stock</th><th>Price</th></tr>";
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr><td>" . $row["prod_id"] . "</td><td>" . $row["prod_name"] . "</td><td>" . $row["net_weight"] . "</td><td>" . $row["uom"] . "</td><td>" . $row["stock"] . "</td><td>" . $row["price"] . "</td></tr>";
                        }
                        echo "</table>";
                    } else {
                        echo "No products found";
                    }
                } else {
                    echo "Error retrieving price for prod_id: $input1";
                }
            } else {
                echo "Error updating stock: " . $con->error;
            }
        } else {
            $insert_query = "INSERT INTO stock_det (prod_id, prod_name, net_weight, uom, stock, price) VALUES ($input1, '$input2', $input3, '$input4', $input10, $input11)";
            $insert_query1 = "INSERT INTO prod_det (prod_id, prod_name, net_weight, uom, category, packaging_mode, manufacturing_date, expiry_date, mrp) VALUES ($input1, '$input2', $input3, '$input4', '$input5', '$input6', STR_TO_DATE('$input7', '%Y-%m-%d'), STR_TO_DATE('$input8', '%Y-%m-%d'), $input9)";
            $insert_query2 = "INSERT INTO count_prod (prod_id, prod_name) VALUES ($input1, '$input2')";

            $con->query($insert_query1);
            $con->query($insert_query2);

            if ($con->query($insert_query) === TRUE) {
                $total_amount = (int)$input10 * $input11;

                $check_invoice_query = "SELECT * FROM stock_invoice WHERE prod_id = $input1";
                $check_invoice_result = $con->query($check_invoice_query);

                if ($check_invoice_result->num_rows > 0) {
                    $update_invoice_query = "UPDATE stock_invoice SET quantity = quantity + $input10, total_amount = total_amount + ($input10 * $input11) WHERE prod_id = $input1";
                    if ($con->query($update_invoice_query) !== TRUE) {
                        echo "Error updating record in stock_invoice: " . $con->error;
                    }
                } else {
                    $insert_invoice_query = "INSERT INTO stock_invoice (prod_id, prod_name, quantity, price, total_amount) VALUES ($input1, '$input2', $input10, $input11, $total_amount)";
                    if ($con->query($insert_invoice_query) !== TRUE) {
                        echo "Error inserting record into stock_invoice: " . $con->error;
                    }
                }

                $query = "SELECT * FROM stock_det";
                $result = mysqli_query($con, $query);

                if (mysqli_num_rows($result) > 0) {
                    echo "<table class='table'><tr><th>Product ID</th><th>Product Name</th><th>Net Weight</th><th>UOM</th><th>Stock</th><th>Price</th></tr>";
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr><td>" . $row["prod_id"] . "</td><td>" . $row["prod_name"] . "</td><td>" . $row["net_weight"] . "</td><td>" . $row["uom"] . "</td><td>" . $row["stock"] . "</td><td>" . $row["price"] . "</td></tr>";
                    }
                    echo "</table>";
                } else {
                    echo "No products found";
                }
            } else {
                echo "Error inserting record: " . $con->error;
            }
        }
    }
} elseif ($option_id == '3_show') {
    $query = "SELECT * FROM stock_request";
    $result = $con->query($query);

    if ($result->num_rows > 0) {
        echo "<table><tr><th>Product ID</th><th>Product Name</th><th>Quantity</th><th>Count</th><th>Request Time</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr><td>" . $row["prod_id"] . "</td><td>" . $row["prod_name"] . "</td><td>" . $row["quantity"] . "</td><td>" . $row["count"] . "</td><td>" . $row["request_time"] . "</td></tr>";
        }
        echo "</table>";
    } else {
        echo "No requests found";
    }
    
} elseif ($option_id == '3_remove') {
    $query = "DELETE FROM stock_request";
    if ($con->query($query) === TRUE) {
        echo "All requests removed successfully.";
    } else {
        echo "Error removing requests: " . $con->error;
    }
    
} elseif ($option_id == '3_insert') {

    $input1 = $_POST['input1']; 
    $input2 = $_POST['input2']; 
    $input3 = $_POST['input3']; 
    $input4 = $_POST['input4']; 
    $input5 = $_POST['input5']; 

    if ($input1 && $input2 && $input3 && $input4 && $input5) {
        mysqli_autocommit($con, false);
        $error = false;

        $check_query = "SELECT * FROM on_display WHERE prod_id = $input1";
        $check_result = $con->query($check_query);

        if ($check_result->num_rows > 0) {
            $update_query = "UPDATE on_display SET quantity = quantity + $input5 WHERE prod_id = $input1";
            if ($con->query($update_query) !== TRUE) {
                $error = true;
                echo "Error updating record in on_display table: " . $con->error;
            }
        } else {
            $insert_query = "INSERT INTO on_display (prod_id, prod_name, net_weight, uom, quantity) VALUES ($input1, '$input2', $input3, '$input4', $input5)";
            if ($con->query($insert_query) !== TRUE) {
                $error = true;
                echo "Error inserting record into on_display table: " . $con->error;
            }
        }

        if (!$error) {
            $stock_check_query = "SELECT stock FROM stock_det WHERE prod_id = $input1";
            $stock_check_result = $con->query($stock_check_query);
            if ($stock_check_result->num_rows > 0) {
                $stock_row = $stock_check_result->fetch_assoc();
                $current_stock = (int)$stock_row['stock'];
                if ($current_stock >= $input5) {
                    $update_stock_query = "UPDATE stock_det SET stock = stock - $input5 WHERE prod_id = $input1";
                    if ($con->query($update_stock_query) !== TRUE) {
                        $error = true;
                        echo "Error updating stock in stock_det table: " . $con->error;
                    }
                } else {
                    $error = true;
                    echo "Error: Not enough stock available. Current stock: $current_stock.";
                }
            } else {
                $error = true;
                echo "Error: Product ID $input1 not found in stock_det.";
            }
        }

        if ($error) {
            mysqli_rollback($con);
        } else {
            mysqli_commit($con);
            echo "Transaction successfully completed.";
        }

        mysqli_autocommit($con, true);
    } else {
        echo "Missing input values";
    }

    mysqli_close($con);
}
?>
</body>
</html>

<?php
session_start();

$conn = new mysqli("localhost", "root", "preethi123", "departmentalstore");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION['products'])) {
    $_SESSION['products'] = array();
}

$show_bill = false;
$total_amount = 0;
$bill_number = 0;

$selected_category = isset($_POST['category']) ? $_POST['category'] : '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['myid'])) {
        $id = $_POST["text1"];
        $quantity = $_POST["quantity"];

        $query_check = "SELECT quantity FROM on_display WHERE prod_id = '$id'";
        $result_check = $conn->query($query_check);

        if ($result_check && $result_check->num_rows > 0) {
            $row_check = $result_check->fetch_assoc();
            if ($row_check['quantity'] >= $quantity) {
                $query_update = "UPDATE on_display SET quantity = quantity - '$quantity' WHERE prod_id = '$id'";
                $result_update = $conn->query($query_update);

                if ($result_update) {
                    $query = "SELECT prod_id, prod_name, net_weight, uom, selling_price AS price, discount FROM prod_det WHERE prod_id = '$id'";
                    $result = $conn->query($query);

                    if ($result && $result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $row['quantity'] = $quantity;
                            $row['final_price'] = $row['price'] * $quantity - (($row['discount'] / 100) * $row['price'] * $quantity);
                            $_SESSION['products'][] = $row;
                        }
                    }
                }
            } else {
                echo "<script>alert('Insufficient stock. Only " . $row_check['quantity'] . " items available.')</script>";
            }
        } else {
            echo "<script>alert('Product not found in display.')</script>";
        }
    }

    if (!isset($_SESSION['username'])) {
        header("Location: login.php");
        exit();
    }

    $_SESSION['cashier_id'] = $_SESSION['username'];

    if (isset($_POST['generate_bill'])) {
        $show_bill = true;
        $total_amount = 0;

        foreach ($_SESSION['products'] as $product) {
            $total_amount += $product['final_price'];
        }

        $bill_number = rand(1000, 9999);
        $timestamp = date('Y-m-d H:i:s');
        $_SESSION['bill_number'] = $bill_number;
        $_SESSION['timestamp'] = $timestamp;

        $cashier_id = isset($_SESSION['cashier_id']) ? $_SESSION['cashier_id'] : '';

        header("Location: paymentpage2.php?bill_number=$bill_number&timestamp=$timestamp&total_amount=$total_amount&cashier_id=$cashier_id");
        exit();
    }

    if (isset($_POST['clear'])) {
        // Increase stock for each product before clearing the cart
        foreach ($_SESSION['products'] as $product) {
            $prod_id = $product['prod_id'];
            $quantity = $product['quantity'];
            $query_increase_stock = "UPDATE on_display SET quantity = quantity + '$quantity' WHERE prod_id = '$prod_id'";
            $conn->query($query_increase_stock);
        }
        $_SESSION['products'] = array();
    }

    if (isset($_POST['delete_last'])) {
        if (!empty($_SESSION['products'])) {
            $last_product = array_pop($_SESSION['products']);
            $prod_id = $last_product['prod_id'];
            $quantity = $last_product['quantity'];

            // Increase stock for the removed product
            $query_increase_stock = "UPDATE on_display SET quantity = quantity + '$quantity' WHERE prod_id = '$prod_id'";
            $conn->query($query_increase_stock);
        }
    }

    if (isset($_POST['delete_product'])) {
        $delete_id = $_POST['delete_id'];
        foreach ($_SESSION['products'] as $key => $product) {
            if ($product['prod_id'] == $delete_id) {
                // Increase stock for the removed product
                $prod_id = $product['prod_id'];
                $quantity = $product['quantity'];
                $query_increase_stock = "UPDATE on_display SET quantity = quantity + '$quantity' WHERE prod_id = '$prod_id'";
                $conn->query($query_increase_stock);

                unset($_SESSION['products'][$key]);
                $_SESSION['products'] = array_values($_SESSION['products']);
                break;
            }
        }
    }

    if (isset($_POST['add_to_cart'])) {
        $product_id = $_POST['product_id'];

        $query = "SELECT prod_id, prod_name, net_weight, uom, selling_price AS price FROM prod_det WHERE prod_id = '$product_id'";
        $result = $conn->query($query);

        if ($result && $result->num_rows > 0) {
            $product = $result->fetch_assoc();
            $product['quantity'] = 1; // Set default quantity

            $_SESSION['products'][] = $product;
        }
    }

    if (isset($_POST['add_to_actual'])) {
        $product_id = $_POST['product_id'];

        foreach ($_SESSION['products'] as $key => $product) {
            if ($product['prod_id'] == $product_id) {
                $prod_id = $product['prod_id'];
                $prod_name = $product['prod_name'];
                $quantity = $product['quantity'];
                $final_price = $product['final_price'];

                $insert_query = "INSERT INTO actual_product (prod_id, prod_name, quantity, final_price) VALUES ('$prod_id', '$prod_name', '$quantity', '$final_price')";
                $insert_result = $conn->query($insert_query);

                if ($insert_result) {
                    unset($_SESSION['products'][$key]);
                    $_SESSION['products'] = array_values($_SESSION['products']);
                }
                break;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <style>
        /* Add your existing styles here */
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .container {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            margin: 20px;
        }

        .product-id-list, .search-section {
            margin: 20px;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .product-id-list {
            width: 25%;
        }

        .product-id-list .product-item {
            padding: 10px;
            border: 1px solid #ddd;
            margin-bottom: 10px;
            border-radius: 4px;
            cursor: pointer;
            background-color: #f9f9f9;
            font-size: 14px;
            transition: all 0.3s ease;
            border: 1px solid #dc3545;
        }

        .product-id-list .product-item:hover {
            background-color: #e9ecef;
            border-color: #c82333;
        }

        .form-container {
            display: flex;
            flex-direction: column;
        }

        .form-container form {
            margin-bottom: 20px;
        }

        .form-container input[type="text"],
        .form-container input[type="number"],
        .form-container input[type="submit"] {
            padding: 10px;
            margin: 5px 0;
            width: 100%;
            box-sizing: border-box;
            border-radius: 4px;
            border: 1px solid #ccc;
        }

        .form-container input[type="submit"] {
            background-color: #dc3545;
            color: white;
            border: none;
            cursor: pointer;
        }

        .form-container input[type="submit"]:hover {
            background-color: #c82333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #ffffff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        table th, table td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        table th {
            background-color: #f2f2f2;
        }

        .billing-actions {
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
        }

        .billing-actions form {
            margin: 0;
        }

        .billing-actions button {
            padding: 10px;
            background-color: #dc3545;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .billing-actions button:hover {
            background-color: #c82333;
        }

        .delete {
            background-color: #dc3545;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .delete:hover {
            background-color: #c82333;
        }

        /* Style for the category dropdown */
        select#category {
            background-color: #f9f9f9;
            color: #333;
            padding: 10px;
            font-size: 14px;
            border: 1px solid #ddd;
            border-radius: 4px;
            transition: all 0.3s ease;
        }

        select#category:hover {
            background-color: #e9ecef;
            border-color: #ccc;
        }

        select#category:focus {
            outline: none;
            border-color: #dc3545;
            box-shadow: 0 0 5px rgba(220, 53, 69, 0.5);
        }

        select#category option {
            padding: 10px;
        }
    </style>
    <script>
        function fillForm(productId, productName, productPrice, productNetWeight, productUom, productQuantity) {
            document.getElementById('text1').value = productId;
            document.getElementById('product_name').value = productName;
            document.getElementById('product_price').value = productPrice;
            document.getElementById('quantity').value = productQuantity;
            document.getElementById('product_netweight').value = productNetWeight + ' ' + productUom;
        }
    </script>
</head><body>
    <div class="container">
        <div class="product-id-list">
            <h3>Product List</h3>
            <div class="form-container">
                <form method="POST">
                    <label for="category">Select Category:</label>
                    <select name="category" id="category" onchange="this.form.submit()">
                        <option value="">All Categories</option>
                        <?php
                        $category_query = "SELECT DISTINCT category FROM prod_det";
                        $category_result = $conn->query($category_query);
                        if ($category_result->num_rows > 0) {
                            while ($row = $category_result->fetch_assoc()) {
                                $selected = ($selected_category == $row['category']) ? 'selected' : '';
                                echo "<option value=\"" . $row['category'] . "\" $selected>" . $row['category'] . "</option>";
                            }
                        }
                        ?>
                    </select>
                </form>
                <?php
                $product_query = "SELECT prod_id, prod_name, net_weight, uom, selling_price AS price, discount FROM prod_det";
                if ($selected_category) {
                    $product_query .= " WHERE category = '$selected_category'";
                }
                $product_result = $conn->query($product_query);
                if ($product_result->num_rows > 0) {
                    while ($product = $product_result->fetch_assoc()) {
                        $net_weight_uom = $product['net_weight'] . ' ' . $product['uom'];
                        echo "<div class='product-item' onclick=\"fillForm('" . $product['prod_id'] . "', '" . $product['prod_name'] . "', '" . $product['price'] . "', '" . $product['net_weight'] . "', '" . $product['uom'] . "', 1)\">";
                        echo "<b>ID:</b> " . $product['prod_id'] . "<br>";
                        echo "<b>Name:</b> " . $product['prod_name'] . "<br>";
                        echo "<b>Net Weight:</b> " . $net_weight_uom . "<br>";
                        echo "<b>Price:</b> " . $product['price'] . "<br>";
                        echo "</div>";
                    }
                }
                ?>
            </div>
        </div>

        <div class="search-section">
            <h3>Billing Section</h3>
            <div class="form-container">
                <form method="POST">
                    <label for="text1">Product ID:</label>
                    <input type="text" id="text1" name="text1" required>
                    <label for="product_name">Product Name:</label>
                    <input type="text" id="product_name" name="product_name" readonly>
                    <label for="quantity">Quantity:</label>
                    <input type="number" id="quantity" name="quantity" required>
                    <input type="submit" name="myid" value="Add Product">
                </form>
            </div>

            <h3>Cart</h3>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Discount</th>
                    <th>Quantity</th>
                    <th>Final Price</th>
                    <th>Action</th>
                </tr>
                <?php
                foreach ($_SESSION['products'] as $product) {
                    echo "<tr>";
                    echo "<td>" . $product['prod_id'] . "</td>";
                    echo "<td>" . $product['prod_name'] . "</td>";
                    echo "<td>" . $product['price'] . "</td>";
                    echo "<td>" . $product['discount'] . "%</td>";
                    echo "<td>" . $product['quantity'] . "</td>";
                    echo "<td>" . $product['final_price'] . "</td>";
                    echo "<td>";
                    echo "<form method='POST' style='display:inline;'>";
                    echo "<input type='hidden' name='delete_id' value='" . $product['prod_id'] . "'>";
                    echo "<input type='submit' name='delete_product' class='delete' value='Delete'>";
                    echo "</form>";
                    echo "</td>";
                    echo "</tr>";
                }
                ?>
            </table>

            <div class="billing-actions">
                <form method="POST">
                    <button type="submit" name="generate_bill">Generate Bill</button>
                </form>
                <form method="POST">
                    <button type="submit" name="clear">Clear Cart</button>
                </form>
                <form method="POST">
                    <button type="submit" name="delete_last">Delete Product</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>

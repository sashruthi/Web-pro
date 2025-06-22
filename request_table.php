<?php
session_start();

$conn = new mysqli("localhost", "root", "preethi123", "departmentalstore");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['delete_id'])) {
    $delete_id = $_POST['delete_id'];
    $delete_query = "DELETE FROM stock_request WHERE prod_id = ?";
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $stmt->close();
}

$selected_category = isset($_POST['category']) ? $_POST['category'] : '';
?>

<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .product-id-list, .search-section {
            margin: 20px;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .product-id-list .product-item {
            padding: 10px;
            border: 1px solid #ddd;
            margin-bottom: 10px;
            border-radius: 4px;
            cursor: pointer;
            background-color: #f9f9f9;
            font-size: 14px;
        }

        .product-id-list .product-item:hover {
            background-color: #e9ecef;
        }

        .container {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            margin: 20px;
            width: 100%;
        }

        .product-id-list {
            width: 50%;
            margin-right: 20px;
        }

        .search-section {
            width: 50%;
        }

        .delete-btn {
            color: #d9534f;
            cursor: pointer;
            text-decoration: none;
            font-weight: bold;
        }

        .delete-btn:hover {
            text-decoration: underline;
        }
    </style>
    <script>
        function fillForm(productId, productName, productPrice, productNetWeight, productUom, productQuantity) {
            document.getElementById('text1').value = productId;
            document.getElementById('product_name').value = productName;
            document.getElementById('quantity').value = productQuantity;
            document.getElementById('product_count').value = productCount;
        }

        function deleteRequest(productId) {
            if (confirm('Are you sure you want to delete this request?')) {
                document.getElementById('delete_id').value = productId;
                document.getElementById('delete_form').submit();
            }
        }
    </script>
</head>
<body>
    <div class="container">
        <div class="product-id-list">
            <h4>Requests</h4>
            <div class="form-container">
                <?php
                $product_query = "SELECT prod_id, prod_name, count, request_time FROM stock_request";
                $product_result = $conn->query($product_query);
                if ($product_result->num_rows > 0) {
                    while ($product = $product_result->fetch_assoc()) {
                        echo "<div class='product-item' onclick=\"fillForm('" . $product['prod_id'] . "', '" . $product['prod_name'] . "', '" . $product['count'] . "', '" . $product['request_time'] . "', 1)\">";
                        echo "Product ID:  " . $product['prod_id'] . "<br>";
                        echo "Name:  " . $product['prod_name'] . "<br>";
                        echo "Quantity: " . $product['count'] . "<br>";
                        echo "Request Time: " . $product['request_time'] . "<br>";
                        echo "<a href='#' class='delete-btn' onclick=\"deleteRequest('" . $product['prod_id'] . "')\">Remove Request</a>";
                        echo "</div>";
                    }
                }
                ?>
            </div>
        </div>

        <form id="delete_form" method="POST">
            <input type="hidden" name="delete_id" id="delete_id" value="">
        </form>
    </div>
</body>
</html>
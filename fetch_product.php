<?php
$servername = "localhost";
$username = "root";
$password = "preethi123";
$dbname = "departmentalstore";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];
    $stmt = $conn->prepare("SELECT pd.prod_name, pd.net_weight, pd.uom, pd.category, pd.packaging_mode, sd.price ,pd.mrp
                            FROM prod_det pd
                            JOIN stock_det sd ON pd.prod_id = sd.prod_id
                            WHERE pd.prod_id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $stmt->bind_result($prod_name, $net_weight, $uom, $category, $packaging_mode, $price,$mrp);
 
    if ($stmt->fetch()) {
        echo json_encode([
            "prod_name" => $prod_name,
            "net_weight" => $net_weight,
            "uom" => $uom,
            "category" => $category,
            "packaging_mode" => $packaging_mode,
            "price" => $price,
            "mrp" => $mrp
        ]);
    } else {
        echo json_encode(["error" => "Product details not found for product ID: " . $product_id]);
    }
    $stmt->close();
}

$conn->close();
?>

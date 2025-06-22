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
    $stmt = $conn->prepare("SELECT prod_name, net_weight, uom FROM prod_det WHERE prod_id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $stmt->bind_result($prod_name, $net_weight, $uom);
 
    if ($stmt->fetch()) {
        echo json_encode([
            "prod_name" => $prod_name,
            "net_weight" => $net_weight,
            "uom" => $uom
        ]);
    } else {
        echo json_encode(["error" => "Product details not found for product ID: " . $product_id]);
    }
    $stmt->close();
}

$conn->close();
?>

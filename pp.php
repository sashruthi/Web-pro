<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Details</title>
    <style>
        /* Your existing CSS styles */
        /* Ensure the CSS styles from index.php are also included here */
    </style>
</head>
<body>
<div class="container">
    <h1>Payment Details</h1>

    <?php
    // Database connection
    $servername = "localhost";
    $username = "root";
    $password = "preethi123";
    $database = "departmentalstore";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $database);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Retrieve bill number from query string
    if (isset($_GET['billno'])) {
        $billno = $_GET['billno'];

        // Fetch UPI payment details
        $sql_upi = "SELECT * FROM payment_details_upi WHERE billno = $billno";
        $result_upi = $conn->query($sql_upi);

        // Fetch card payment details
        $sql_card = "SELECT * FROM payment_details_card WHERE billno = $billno";
        $result_card = $conn->query($sql_card);

        // Fetch cash payment details
        $sql_cash = "SELECT * FROM payment_details_cash WHERE billno = $billno";
        $result_cash = $conn->query($sql_cash);

        // Display payment details
        if ($result_upi->num_rows > 0) {
            echo "<h2>UPI Payment Details</h2>";
            echo "<table>";
            echo "<tr><th>Bill Number</th><th>Payment Method</th><th>UPI ID</th></tr>";
            while ($row = $result_upi->fetch_assoc()) {
                echo "<tr><td>" . $row['billno'] . "</td><td>" . $row['payment_method'] . "</td><td>" . $row['upi_id'] . "</td></tr>";
            }
            echo "</table>";
        }

        if ($result_card->num_rows > 0) {
            echo "<h2>Card Payment Details</h2>";
            echo "<table>";
            echo "<tr><th>Bill Number</th><th>Payment Method</th><th>Card Number</th><th>Expiry Date</th><th>CVV</th></tr>";
            while ($row = $result_card->fetch_assoc()) {
                echo "<tr><td>" . $row['billno'] . "</td><td>" . $row['payment_method'] . "</td><td>" . $row['card_no'] . "</td><td>" . $row['card_expiry_date'] . "</td><td>" . $row['cvv'] . "</td></tr>";
            }
            echo "</table>";
        }

        if ($result_cash->num_rows > 0) {
            echo "<h2>Cash Payment Details</h2>";
            echo "<table>";
            echo "<tr><th>Bill Number</th><th>Payment Method</th><th>Tendered Amount</th><th>Change Amount</th></tr>";
            while ($row = $result_cash->fetch_assoc()) {
                echo "<tr><td>" . $row['billno'] . "</td><td>" . $row['payment_method'] . "</td><td>" . $row['tendered_amount'] . "</td><td>" . $row['change_amount'] . "</td></tr>";
            }
            echo "</table>";
        }

    } else {
        echo "<p>No bill number specified.</p>";
    }

    // Close connection
    $conn->close();
    ?>

    <br>
    <a href="index.php">Go Back</a>
</div>
</body>
</html>

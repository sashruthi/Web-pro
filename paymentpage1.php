<?php
session_start();

// Check if bill details are set in session
if (!isset($_SESSION['bill_number']) || !isset($_SESSION['timestamp']) || !isset($_SESSION['total_amount']) || !isset($_SESSION['cashier_id'])) {
    die("Bill details are missing.");
}

// Retrieve bill details from session
$bill_number = $_SESSION['bill_number'];
$timestamp = $_SESSION['timestamp'];
$total_amount = $_SESSION['total_amount'];
$cashier_id = $_SESSION['cashier_id'];

// Check if form is submitted via POST method
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $payment_method = isset($_POST['payment_method']) ? $_POST['payment_method'] : null;

    // Validate payment method and process accordingly
    if ($payment_method == 'cash' || $payment_method == 'card' || $payment_method == 'upi') {
        // Database connection
        $conn = new mysqli("localhost", "root", "preethi123", "departmentalstore");
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Prepare SQL statement
        $query = "INSERT INTO cashier (cashier_id, billno, date_of_bill, tot_amt, payment_method) VALUES (?, ?, ?, ?, ?)";
        
        // Prepare and bind parameters
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssss", $cashier_id, $bill_number, $timestamp, $total_amount, $payment_method);

        // Execute query
        if ($stmt->execute()) {
            // Payment successful
            echo '<div style="text-align: center;">
                    <h1>Payment successful. Bill details saved.</h1>
                </div>';
            
            // Optionally destroy session after successful payment
            session_destroy();
        } else {
            // Error in saving bill details
            echo '<div style="text-align: center;">
                    <h1>Error: Unable to save bill details. Error: ' . $stmt->error . '</h1>
                </div>';
        }

        // Close statement and connection
        $stmt->close();
        $conn->close();
    } else {
        // Invalid payment method selected
        echo '<div style="text-align: center;">
                <h1>Invalid payment method selected.</h1>
            </div>';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .form-container {
            width: 50%;
            margin: auto;
            text-align: center;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        .form-container h2 {
            margin-bottom: 20px;
        }
        .form-container p {
            margin: 10px 0;
        }
        .form-container input[type="radio"] {
            margin: 10px 5px;
        }
        .form-container input[type="text"],
        .form-container input[type="number"],
        .form-container input[type="submit"] {
            width: calc(100% - 22px);
            padding: 10px;
            margin: 10px 0;
            box-sizing: border-box;
            background-color: #fff;
            color: #000;
            border: 1px solid #ddd;
            border-radius: 3px;
        }
        .form-container input[type="submit"] {
            background-color: #000;
            color: #fff;
            border: none;
            cursor: pointer;
        }
        .form-container input[type="submit"]:hover {
            background-color: #555;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Payment Page</h2>
        <form method="post" action="process_payment.php">
            <p>Bill Number: <?php echo $_SESSION['bill_number']; ?></p>
            <p>Total Amount: <?php echo $_SESSION['total_amount']; ?></p>
            <p>Timestamp: <?php echo $_SESSION['timestamp']; ?></p>
            
            <p>
                <label>
                    <input type="radio" name="payment_method" value="cash"> Cash
                </label>
                <label>
                    <input type="radio" name="payment_method" value="card"> Card
                </label>
                <label>
                    <input type="radio" name="payment_method" value="upi"> UPI
                </label>
            </p>
            
            <input type="submit" value="Submit">
        </form>
    </div>
    <a href="login.php" class="previous round">&#8249; Back to Login</a>
</body>
</html>

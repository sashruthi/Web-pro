<?php
session_start();
$conn = new mysqli("localhost", "root", "preethi123", "departmentalstore");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION['bill_number']) || !isset($_SESSION['timestamp']) || !isset($_GET['total_amount']) || !isset($_GET['cashier_id'])) {
    die("Bill details are missing.");
}

$bill_number = $_SESSION['bill_number'];
$timestamp = $_SESSION['timestamp'];
$total_amount = $_GET['total_amount'];
$cashier_id = $_GET['cashier_id'];

$change = 0;
$payment_details = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $payment_method = $_POST['payment_method'];

    if ($payment_method == 'cash') {
        $tendered_amount = $_POST['tendered_amount'];
        if ($tendered_amount < $total_amount) {
            echo "<script>window.alert('Tendered amount is less than total amount. Please provide sufficient amount.');</script>";
        } else {
            $change = $tendered_amount - $total_amount;
            $payment_details = "Tendered Amount: $tendered_amount, Change: $change";
        }
    } elseif ($payment_method == 'upi') {
        $upi_id = $_POST['upi_id'];
        $payment_details = "UPI ID: $upi_id";
    } elseif ($payment_method == 'card') {
        $card_number = $_POST['card_number'];
        $card_holder = $_POST['card_holder'];
        $expiry_date = $_POST['expiry_date'];
        $payment_details = "Card Number: $card_number, Card Holder: $card_holder, Expiry Date: $expiry_date";
    }

    // Insert payment details into database
    $query = "INSERT INTO cashier (cashier_id, billno, date_of_bill, tot_amt, payment_method, payment_details) 
              VALUES ('$cashier_id', $bill_number, '$timestamp', $total_amount, '$payment_method', '$payment_details')";

    if ($conn->query($query) === TRUE) {
        // Display JavaScript alert after successful payment
        window.alert('Payment successful.');";
    } else {
        echo "Error: " . $query . "<br>" . $conn->error;
    }

    session_destroy();
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
            background-color: #fff;
            color: #000;
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

        .payment-details {
            display: none;
        }

        .change-amount {
            display: block;
        }
    </style>
    <script>
        function showPaymentDetails() {
            var paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;
            var paymentDetails = document.querySelectorAll('.payment-details');
            paymentDetails.forEach(function(detail) {
                detail.style.display = 'none';
            });

            if (paymentMethod === 'cash') {
                document.getElementById('cash-details').style.display = 'block';
            } else if (paymentMethod === 'upi') {
                document.getElementById('upi-details').style.display = 'block';
            } else if (paymentMethod === 'card') {
                document.getElementById('card-details').style.display = 'block';
            }
        }

        function calculateChange() {
            var totalAmount = <?php echo $total_amount; ?>;
            var tenderedAmount = document.getElementById('tendered_amount').value;
            var change = tenderedAmount - totalAmount;

            if (change >= 0) {
                document.getElementById('change-amount').value = change.toFixed(2);
            } else {
                document.getElementById('change-amount').value = '';
            }
        }
    </script>
</head>
<body>

<div class="form-container">
    <h2>Payment Page</h2>
    <p>Bill Number: <?php echo $bill_number; ?></p>
    <p>Total Amount: <?php echo $total_amount; ?></p>
    <p>Date: <?php echo $timestamp; ?></p>

    <form method="post" action="">
        <label>
            <input type="radio" name="payment_method" value="cash" required onclick="showPaymentDetails()"> Cash
        </label><br>
        <label>
            <input type="radio" name="payment_method" value="upi" required onclick="showPaymentDetails()"> UPI
        </label><br>
        <label>
            <input type="radio" name="payment_method" value="card" required onclick="showPaymentDetails()"> Card
        </label><br>

        <div id="cash-details" class="payment-details">
            <label for="tendered_amount">Tendered Amount:</label>
            <input type="number" id="tendered_amount" name="tendered_amount" required min="0" step="0.01" oninput="calculateChange()"><br>
            <label for="change-amount">Change:</label>
            <input type="text" id="change-amount" name="change_amount" readonly><br>
        </div>

        <div id="upi-details" class="payment-details">
            <label for="upi_id">UPI ID:</label>
            <input type="text" id="upi_id" name="upi_id" required>
        </div>

        <div id="card-details" class="payment-details">
            <label for="card_number">Card Number:</label>
            <input type="text" id="card_number" name="card_number" required>
            <label for="card_holder">Card Holder Name:</label>
            <input type="text" id="card_holder" name="card_holder" required>
            <label for="expiry_date">Expiry Date:</label>
            <input type="text" id="expiry_date" name="expiry_date" required placeholder="MM/YY">
        </div>

        <input type="submit" value="PAY">
    </form>
</div>

</body>
</html>

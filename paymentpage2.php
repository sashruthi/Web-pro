<?php
session_start();

if (!isset($_GET['bill_number']) || !isset($_GET['timestamp']) || !isset($_GET['total_amount']) || !isset($_GET['cashier_id'])) {
    die("Bill details not provided.");
}

$bill_number = $_GET['bill_number'];
$timestamp = $_GET['timestamp'];
$total_amount = $_GET['total_amount'];
$cashier_id = $_GET['cashier_id'];

$date = date('Y-m-d', strtotime($timestamp));
$time = date('H:i:s', strtotime($timestamp));

$payment_successful = false;
$payment_method = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_payment'])) {
    $payment_method = $_POST['payment_method'];

    $conn = new mysqli("localhost", "root", "preethi123", "departmentalstore");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Insert into cashier table
    $query_cashier = "INSERT INTO cashier (cashier_id, billno, date_of_bill, tot_amt, payment_method) 
                      VALUES ('$cashier_id', $bill_number, '$timestamp', $total_amount, '$payment_method')";
    
    if ($conn->query($query_cashier) === TRUE) {
        // Handle different payment methods
        if ($payment_method == 'cash') {
            $tendered_amount = $_POST['tendered_amount'];
            $change = $tendered_amount - $total_amount;
            $query_payment = "INSERT INTO payment_details_cash (billno, payment_method, tendered_amount, change_amount) 
                              VALUES ($bill_number, '$payment_method', $tendered_amount, $change)";
        } elseif ($payment_method == 'card') {
            $card_number = $_POST['card_number'];
            $card_expiry_date = $_POST['card_expiry_date'];
            $cvv = $_POST['cvv'];
            $query_payment = "INSERT INTO payment_details_card (billno, payment_method, card_no, card_expiry_date, cvv) 
                              VALUES ($bill_number, '$payment_method', '$card_number', '$card_expiry_date', $cvv)";
        } elseif ($payment_method == 'upi') {
            $upi_id = $_POST['upi_id'];
            $query_payment = "INSERT INTO payment_details_upi (billno, payment_method, upi_id) 
                              VALUES ($bill_number, '$payment_method', '$upi_id')";
        }

        if ($conn->query($query_payment) === TRUE) {
            $payment_successful = true;
        } else {
            echo "Error: " . $query_payment . "<br>" . $conn->error;
        }
    } else {
        echo "Error: " . $query_cashier . "<br>" . $conn->error;
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Payment Page</title>
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #f8f9fa;
            margin: 0;
        }
        .form-container {
            max-width: 500px;
            width: 100%;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .form-container h2 {
            text-align: center;
        }
        .form-container label {
            display: block;
            margin-bottom: 10px;
        }
        .form-container input[type="submit"] {
            padding: 10px 20px;
            background-color: #dc3545;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 10px;
            width: 100%;
        }
        .form-container input[type="submit"]:hover {
            background-color: #c82333;
        }
        .success-message {
            color: green;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
        }
        .additional-fields {
            display: none;
            margin-top: 20px;
        }
        .additional-fields label {
            display: block;
            margin-bottom: 5px;
        }
        .additional-fields input[type="text"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .payment-buttons {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .payment-button {
            flex: 1;
            padding: 10px;
            margin: 0 5px;
            text-align: center;
            cursor: pointer;
            border-radius: 4px;
            border: 1px solid #ccc;
            background-color: #e9ecef;
            color: #333;
        }
        .payment-button.selected {
            border: 2px solid #000;
        }
        .logout-button {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #ffffff;
            background-color: #dc3545;
            padding: 10px;
            border-radius: 4px;
            border: none;
            cursor: pointer;
            width: 100%;
        }
        .logout-button:hover {
            background-color: #c82333;
        }
    </style>
    <script>
        function selectPaymentMethod(method) {
            var paymentMethodInput = document.getElementById('payment_method');
            var paymentButtons = document.querySelectorAll('.payment-button');
            paymentButtons.forEach(function(button) {
                button.classList.remove('selected');
            });
            var selectedButton = document.querySelector('.payment-button.' + method);
            selectedButton.classList.add('selected');
            paymentMethodInput.value = method;
            showAdditionalFields(method);
        }

        function showAdditionalFields(method) {
            var cashFields = document.getElementById('cash-fields');
            var cardFields = document.getElementById('card-fields');
            var upiFields = document.getElementById('upi-fields');

            cashFields.style.display = 'none';
            cardFields.style.display = 'none';
            upiFields.style.display = 'none';

            if (method === 'cash') {
                cashFields.style.display = 'block';
            } else if (method === 'card') {
                cardFields.style.display = 'block';
            } else if (method === 'upi') {
                upiFields.style.display = 'block';
            }
        }

        function calculateChange() {
            var tenderedAmount = parseFloat(document.getElementById('tendered_amount').value);
            var totalAmount = parseFloat("<?php echo $total_amount; ?>");
            var change = tenderedAmount - totalAmount;
            document.getElementById('change_amount').value = change.toFixed(2);
        }
    </script>
</head>
<body>
<div class="form-container">
    <?php if ($payment_successful): ?>
        <div class="success-message">
            <p>Payment Successful</p>
        </div>
        <h2>Bill Details</h2>
        <div>
            <p><strong>Bill Number:</strong> <?php echo $bill_number; ?></p>
            <p><strong>Date:</strong> <?php echo $date; ?></p>
            <p><strong>Time:</strong> <?php echo $time; ?></p>
            <p><strong>Total Amount:</strong> ₹ <?php echo $total_amount; ?></p>
            <p><strong>Payment Method:</strong> <?php echo ucfirst($payment_method); ?></p>
        </div>
        <form method="post" action="logout.php">
            <button type="submit" class="logout-button">Logout</button>
        </form>
    <?php else: ?>
        <h2>Payment Details</h2>
        <div>
            <p><strong>Bill Number:</strong> <?php echo $bill_number; ?></p>
            <p><strong>Date:</strong> <?php echo $date; ?></p>
            <p><strong>Time:</strong> <?php echo $time; ?></p>
            <p><strong>Total Amount:</strong> ₹ <?php echo $total_amount; ?></p>
        </div>

        <div class="payment-buttons">
            <div class="payment-button cash" onclick="selectPaymentMethod('cash')">Cash</div>
            <div class="payment-button card" onclick="selectPaymentMethod('card')">Card</div>
            <div class="payment-button upi" onclick="selectPaymentMethod('upi')">UPI</div>
        </div>

        <form method="post" action="">
            <input type="hidden" id="payment_method" name="payment_method" value="" required>
            
            <div id="cash-fields" class="additional-fields">
                <label for="tendered_amount">Tendered Amount</label>
                <input type="text" id="tendered_amount" name="tendered_amount" oninput="calculateChange()">
                <label for="change_amount">Change Amount</label>
                <input type="text" id="change_amount" name="change_amount" readonly>
            </div>

            <div id="card-fields" class="additional-fields">
                <label for="card_number">Card Number</label>
                <input type="text" id="card_number" name="card_number">
                <label for="card_expiry_date">Card Expiry Date</label>
                <input type="text" id="card_expiry_date" name="card_expiry_date">
                <label for="cvv">CVV</label>
                <input type="text" id="cvv" name="cvv">
            </div>

            <div id="upi-fields" class="additional-fields">
                <label for="upi_id">UPI ID</label>
                <input type="text" id="upi_id" name="upi_id">
            </div>

            <input type="submit" name="submit_payment" value="Submit Payment">
        </form>
    <?php endif; ?>
</div>
</body>
</html>

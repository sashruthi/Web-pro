<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Accountant Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            min-height: 100vh;
        }
        #sidebar {
            background: white;
            color: #333;
            min-width: 250px;
            max-width: 250px;
            min-height: 100vh;
            padding: 20px;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        #sidebar .btn-user {
            color: #333;
            background: #e9ecef;
            border: none;
            margin-bottom: 10px;
            width: 100%;
            text-align: left;
            padding: 10px;
            border-radius: 5px;
        }
        #sidebar .btn-user:hover {
            background: #ddd;
        }
        #sidebar .btn-logout {
            color: #fff;
            background-color: #dc3545;
            border: none;
            width: 100%;
            margin-top: auto;
        }
        #content {
            flex: 1;
            padding: 20px;
            background: #f8f9fa;
        }
        .form-container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .form-container input[type="submit"] {
            background-color: #6c757d;
            color: #fff;
            border: none;
        }
        .form-container input[type="submit"]:hover {
            background-color: #5a6268;
        }
        table {
            width: 100%;
            background-color: white;
            color: #333;
            margin-top: 20px;
        }
        table th, table td {
            padding: 10px;
            border: 1px solid #dee2e6;
        }
        table th {
            background-color: #e9ecef;
        }
        .summary {
            max-width: 600px;
            margin: auto;
            margin-top: 20px;
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .summary ul {
            padding-left: 20px;
        }
        .summary li {
            font-size: 18px;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div id="sidebar">
        <h4>Users:</h4>
        <div id="user-buttons">
            <?php
            $con = mysqli_connect('localhost', 'root', 'preethi123', 'departmentalstore');

            if (!$con) {
                die("Database connection failed: " . mysqli_connect_error());
            }

            $query = "SELECT username FROM cashier_login";
            $result = mysqli_query($con, $query);

            if (!$result) {
                die("Error executing users query: " . mysqli_error($con));
            }

            while ($row = mysqli_fetch_assoc($result)) {
                $username = $row['username'];
                echo "<button class='btn btn-user' onclick=\"fillEmployeeId('$username')\">$username</button>";
            }
            ?>
        </div>
        <button class="btn btn-logout" onclick="window.location.href='logout.php'">Logout</button>
    </div>

    <!-- Main Content -->
    <div id="content">
        <div class="form-container">
            <h1>Accountant Dashboard</h1>
            <form method="post" action="">
                <div class="form-group">
                    <label for="mytxt1">Cashier ID</label>
                    <input type="text" name="mytxt1" id="mytxt1" class="form-control" placeholder="Enter Cashier ID">
                </div>
                <div class="form-group">
                    <label for="date_of_bill">Date of Bill</label>
                    <input type="date" name="date_of_bill" id="date_of_bill" class="form-control" placeholder="Enter Date of Bill">
                </div>
                <div class="form-group">
                    <input type="submit" value="Show Summary" name="mysubmit" class="btn btn-secondary btn-block">
                </div>
            </form>
        </div>

        <?php
        // Initialize variables
        $totalcashpayment = 0;
        $totalupipayment = 0;
        $totalcreditcardpayment = 0;
        $totalamount = 0;
        $billcount = 0;

        // Fetch payments data
        if (isset($_POST['mysubmit'])) {
            $cashier_id = $_POST['mytxt1'];
            $date_of_bill = $_POST['date_of_bill'];

            // Cash Payments Query
            $cash_query = "SELECT billno,tot_amt,date_of_bill FROM cashier WHERE cashier_id='$cashier_id' AND date_of_bill='$date_of_bill' AND payment_method='cash'";
            $cash_result = mysqli_query($con, $cash_query);
            $cash_payments = mysqli_fetch_all($cash_result, MYSQLI_ASSOC);

            // UPI Payments Query
            $upi_query = "SELECT billno,tot_amt,date_of_bill FROM cashier WHERE cashier_id='$cashier_id' AND date_of_bill='$date_of_bill' AND payment_method='upi'";
            $upi_result = mysqli_query($con, $upi_query);
            $upi_payments = mysqli_fetch_all($upi_result, MYSQLI_ASSOC);

            // Credit Card Payments Query
            $credit_card_query = "SELECT billno,tot_amt,date_of_bill FROM cashier WHERE cashier_id='$cashier_id' AND date_of_bill='$date_of_bill' AND payment_method='card'";
            $credit_card_result = mysqli_query($con, $credit_card_query);
            $credit_card_payments = mysqli_fetch_all($credit_card_result, MYSQLI_ASSOC);

            // Calculate Totals
            foreach ($cash_payments as $payment) {
                $totalcashpayment += $payment['tot_amt'];
                $billcount++;
            }
            foreach ($upi_payments as $payment) {
                $totalupipayment += $payment['tot_amt'];
                $billcount++;
            }
            foreach ($credit_card_payments as $payment) {
                $totalcreditcardpayment += $payment['tot_amt'];
                $billcount++;
            }

            // Total Amount Collected
            $totalamount = $totalcashpayment + $totalupipayment + $totalcreditcardpayment;
        }
        ?>

        <!-- Display cash payments table -->
        <?php if (!empty($cash_payments)): ?>
            <h2>Cash Payments</h2>
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                                              <th>Bill Number</th>
                        <th>Date of Bill</th>
                        <th>Total Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cash_payments as $row): ?>
                        <tr>
                                                       <td><?php echo htmlspecialchars($row['billno']); ?></td>
                            <td><?php echo htmlspecialchars($row['date_of_bill']); ?></td>
                            <td><?php echo htmlspecialchars(number_format($row['tot_amt'], 2)); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php elseif (isset($_POST['mysubmit']) && empty($cash_payments)): ?>
            <p>No cash payments found for the given ID and date.</p>
        <?php endif; ?>

        <!-- Display UPI payments table -->
        <?php if (!empty($upi_payments)): ?>
            <h2>UPI Payments</h2>
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                                                <th>Bill Number</th>
                        <th>Date of Bill</th>
                        <th>Total Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($upi_payments as $row): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['billno']); ?></td>
                            <td><?php echo htmlspecialchars($row['date_of_bill']); ?></td>
                            <td><?php echo htmlspecialchars(number_format($row['tot_amt'], 2)); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php elseif (isset($_POST['mysubmit']) && empty($upi_payments)): ?>
            <p>No UPI payments found for the given ID and date.</p>
        <?php endif; ?>

        <!-- Display credit card payments table -->
        <?php if (!empty($credit_card_payments)): ?>
            <h2>Credit Card Payments</h2>
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Bill Number</th>
                        <th>Date of Bill</th>
                        <th>Total Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($credit_card_payments as $row): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['billno']); ?></td>
                            <td><?php echo htmlspecialchars($row['date_of_bill']); ?></td>
                            <td><?php echo htmlspecialchars(number_format($row['tot_amt'], 2)); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php elseif (isset($_POST['mysubmit']) && empty($credit_card_payments)): ?>
            <p>No credit card payments found for the given ID and date.</p>
        <?php endif; ?>

        <!-- Summary section -->
        <?php if (isset($_POST['mysubmit'])): ?>
            <div class="summary">
                <h2>Summary</h2>
                <ul>
                    <li>Total Cash Payments: <?php echo number_format($totalcashpayment, 2); ?></li>
                    <li>Total UPI Payments: <?php echo number_format($totalupipayment, 2); ?></li>
                    <li>Total Credit Card Payments: <?php echo number_format($totalcreditcardpayment, 2); ?></li>
                    <li>Total Amount Collected: <?php echo number_format($totalamount, 2); ?></li>
                    <li>Total Bills: <?php echo $billcount; ?></li>
                </ul>
            </div>
        <?php endif; ?>

    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        function fillEmployeeId(username) {
            document.getElementById('mytxt1').value = username;
        }
    </script>
</body>
</html>

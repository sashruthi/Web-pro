<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Black and Gray Sidebar</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
        }

        #sidebar {
            background: white;
            color: #333;
            min-width: 250px;
            max-width: 250px;
            min-height: 100vh;
            padding: 20px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        #sidebar .nav-link {
            color: #333;
        }

        #sidebar .nav-link:hover {
            background: #dcdcdc;
            color: black;
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

        #display-div {
            background-color: white;
            width: 100%;
            border: 3px solid white;
            overflow: auto;
            padding: 20px;
            box-sizing: border-box;
            margin-top: 20px;
        }

        #display-div h4 {
            color: white;
        }

        #display-table {
            width: 100%;
            background-color: white;
            color: black;
        }

        #display-table th, #display-table td {
            padding: 10px;
            border: 1px solid black;
            text-align: center;
        }

        /* Simplified styles for the legend */
        .legend {
            margin-bottom: 20px;
            font-size: 14px;
        }

        .legend div {
            display: flex;
            align-items: center;
            margin-bottom: 5px;
        }

        .legend span {
            display: inline-block;
            width: 15px;
            height: 15px;
            margin-right: 5px;
            border: 1px solid #ccc;
        }

        .msg-button {
            position: fixed;
            top: 20px;
            right: 70px;
            width: 30px;
            height: 30px;
            background-color: #6c757d;
            color: #ffffff;
            border: none;
            border-radius: 50%;
            font-size: 18px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            cursor: pointer;
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        .msg-button:hover {
            background-color: #5a6268;
        }

        .message-div {
            position: fixed;
            top: 34px;
            right: 87px;
            width: 300px;
            padding: 20px;
            background-color: #f8f9fa;
            border: 1px solid #ced4da;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            display: none;
            animation: fadeEffect 0.5s;
            font-size: 14px;
            line-height: 1.6;
        }

        .message-div h3 {
            font-size: 18px;
            margin-bottom: 10px;
        }

        .message-div p {
            margin-bottom: 8px;
        }

        .message-div .btn-secondary {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 14px;
        }

        .round-button {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background-color: #6c757d;
            color: white;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            position: fixed;
            top: 20px;
            right: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }

        .round-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div id="sidebar">
        <div>
            <br><br>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link" href="home.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="price.php">Price</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="discounts.php">Discount</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="product_request.php">Product Request</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="product_catlog.php">Product catlog</a>
                </li><br>
               <button class="btn btn-logout" onclick="window.location.href='logout.php'">Logout</button>
            </ul>
        </div>
    </div>
    <div id="content"><br>
        <center><h6>Check out the table below to see the products currently on display along with their details</h6></center>

        <!-- Legend -->
        <div class="legend">
            <div>
                <span style="background-color: #ff9999;"></span> Low Stock
            </div>
            <div>
                <span style="background-color: #ffff99;"></span> Medium Stock
            </div>
            <div>
                <span style="background-color: #99ff99;"></span> High Stock
            </div>
        </div>

        <div id="display-div">
            <table id="display-table" class="table table-dark table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Product ID</th>
                        <th>Product Name</th>
                        <th>Net weight</th>
                        <th>Unit Of Measurement</th>
                        <th>quantity</th>
                    </tr>
                </thead>
                <tbody>
                   <?php
                   $con = mysqli_connect('localhost', 'root', 'preethi123', 'departmentalstore');
                   if (!$con) {
                       die("Connection failed: " . mysqli_connect_error());
                   }

                   $query = "SELECT * FROM on_display";
                   $result = mysqli_query($con, $query);
                   if ($result) {
                       while ($row = mysqli_fetch_assoc($result)) {
                           $background_color = '';
                           if ($row['quantity'] <= 20) {
                               $background_color = '#ff9999'; // Light red
                           } elseif ($row['quantity'] > 20 && $row['quantity'] <= 40) {
                               $background_color = '#ffff99'; // Light yellow
                           } else {
                               $background_color = '#99ff99'; // Light green
                           }
                           echo "<tr style='background-color: $background_color;'><td>".$row['prod_id']."</td><td>".$row['prod_name']."</td><td>".$row['net_weight']."</td><td>".$row['uom']."</td><td>".$row['quantity']."</td></tr>";
                       }
                   } else {
                       echo "Error: " . mysqli_error($con);
                   }
                   mysqli_close($con);
                   ?>
                </tbody>
            </table>
        </div>
    </div>
    <button class="round-button" onclick="viewHistory()">
        <i class="fas fa-history"></i>
    </button>

    <script>
        function viewHistory() {
            window.location.href = 'request_history.php';
        }
    </script>
    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    
    <!-- Message Button -->
    <button class="msg-button" onclick="toggleMessage()">
        <span>&#9993;</span> <!-- Message icon -->
    </button>

    <!-- Message Div -->
    <div id="messageDiv" class="message-div">
        <h3>ALERT MESSAGES</h3>
        <?php
        // Establish connection to MySQL database
        $con = mysqli_connect('localhost', 'root', 'preethi123', 'departmentalstore');

        // Check connection
        if (mysqli_connect_errno()) {
            echo "Failed to connect to MySQL: " . mysqli_connect_error();
            exit();
        }

        // SQL query to select products with low stock
        $query = "SELECT prod_name, prod_id FROM on_display WHERE quantity <= 20";
        $result = mysqli_query($con, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                // Truncate product name if it's too long
                $prod_name = strlen($row['prod_name']) > 20 ? substr($row['prod_name'], 0, 20) . '...' : $row['prod_name'];
                
                // Display the alert message
                echo "<p>Alert: The stock for product <strong>" . $prod_name . "</strong> (" . $row['prod_id'] . ") is running low.</p>";
            }
        } else {
            echo "<p>No low stock alerts found.</p>";
        }

        // Close MySQL connection
        mysqli_close($con);
        ?>
        <button class="btn btn-secondary" onclick="toggleMessage()">Close</button>
    </div>

    <script>
        function toggleMessage() {
            var messageDiv = document.getElementById("messageDiv");
            messageDiv.style.display = (messageDiv.style.display === "none") ? "block" : "none";
        }
    </script>
</body>
</html>

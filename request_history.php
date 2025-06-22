<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Request History</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa; /* Light background */
            padding: 20px;
        }
        .history-heading {
            color: #343a40; /* Dark gray heading color */
            font-weight: bold;
            margin-bottom: 20px;
        }
        .section-heading {
            color: #495057; /* Medium gray heading color */
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 10px;
        }
        .request-item {
            border: 1px solid #dee2e6; /* Light border around each request item */
            border-radius: .25rem;
            padding: 10px;
            margin-bottom: 10px;
            background-color: #ffffff; /* White background for each request item */
        }
        .previous, .next {
            text-decoration: none;
            display: inline-block;
            padding: 8px 16px;
            position: absolute;
            background-color: #6c757d; /* Gray background */
            color: white;
            z-index: 10;
            border-radius: .25rem;
        }
        .previous {
            bottom: 3%;
            right: 3%;
        }
        .next {
            bottom: 3%;
            right: 45%;
        }
        .previous:hover, .next:hover {
            background-color: #5a6268; /* Darker gray on hover */
        }
    </style>
</head>
<body>

<div class="container request-history">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h3 class="history-heading">Request History</h3>
            
            <h5 class="section-heading">Requests Sent</h5>
            <?php
            // Assuming $con is your mysqli connection object
            $con = mysqli_connect('localhost', 'root', 'preethi123', 'departmentalstore');
            
            $query1 = "SELECT * FROM stock_request";
            $result = mysqli_query($con, $query1);
            
            if ($result && mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<div class='request-item'><p>" . $row['count'] . " packets of " . $row['prod_name'] . " (" . $row['quantity'] . ") has been requested on " . $row['request_time'] . "</p></div>";
                }
            }
else
{
  echo "no request has been sent!";
}
            ?>
            
            <hr>
            
            <h5 class="section-heading">Products received</h5>
            <?php
            $query2 = "SELECT * FROM stock_history";
            $result1 = mysqli_query($con, $query2);
            
            if ($result1 && mysqli_num_rows($result1) > 0) {
                while ($row = mysqli_fetch_assoc($result1)) {
                    echo "<div class='request-item'><p>" . $row['count'] . " packets of " . $row['prod_name'] . " (" . $row['quantity'] . ") has been received on " . $row['request_time'] . "</p></div>";
                }
            }
else
{
  echo "no products has been received !";
}
            ?>
        </div>
    </div>
</div>
<a href="home.php" class="previous">&#8249;</a>

<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>

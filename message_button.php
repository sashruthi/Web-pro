<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Message Button with Fading Div</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Styles for the message button */
        .msg-button {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 40px; /* Smaller width */
            height: 40px; /* Smaller height */
            background-color: #6c757d; /* Gray background */
            color: #ffffff; /* White text */
            border: none;
            border-radius: 50%;
            font-size: 18px; /* Larger font size */
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            cursor: pointer;
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999; /* Ensure it's above other content */
        }
        .msg-button:hover {
            background-color: #5a6268; /* Darker gray on hover */
        }

        /* Styles for the message div */
        .message-div {
            position: fixed;
            bottom: 70px; /* Adjusted position */
            right: 30px;
            width: 300px; /* Increased width */
            padding: 20px; /* Increased padding */
            background-color: #f8f9fa; /* Light gray background */
            border: 1px solid #ced4da; /* Gray border */
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            display: none; /* Initially hidden */
            animation: fadeEffect 0.5s; /* Fade animation */
            font-size: 14px; /* Smaller font size for text */
            line-height: 1.6; /* Line height for better readability */
        }
        .message-div h3 {
            font-size: 18px; /* Larger heading font size */
            margin-bottom: 10px; /* Space below heading */
        }
        .message-div p {
            margin-bottom: 8px; /* Space below paragraphs */
        }
        .message-div .btn-secondary {
            position: absolute;
            top: 10px; /* Adjusted top position */
            right: 10px; /* Adjusted right position */
            font-size: 14px; /* Button font size */
        }
    </style>
</head>
<body>

<!-- Message Button -->
<button class="msg-button" onclick="toggleMessage()">
    <span>&#9993;</span> <!-- Message icon -->
</button>

<!-- Message Div -->
<div id="messageDiv" class="message-div">
    <h3>ALERT MESSAGES</h3>
    <?php
    // Establish connection to MySQL database
    $con = mysqli_connect('localhost', 'root', 'sql123', 'departmentalstore');

    // Check connection
    if (mysqli_connect_errno()) {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
        exit();
    }

    // SQL query to select products with low stock
    $query = "SELECT prod_name, prod_id FROM on_display WHERE display_count < 5";
    $result = mysqli_query($con, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            // Truncate product name if it's too long
            $prod_name = strlen($row['prod_name']) > 20 ? substr($row['prod_name'], 0, 20) . '...' : $row['prod_name'];
            
            // Display the alert message
            echo "<p>Alert: The stock for product <strong>" . $prod_name . "</strong> (" . $row['prod_id'] . ") is running low. Please restock immediately to ensure availability.</p>";
        }
    } else {
        echo "<p>No low stock alerts found.</p>";
    }

    // Close MySQL connection
    mysqli_close($con);
    ?>
    <button class="btn btn-secondary" onclick="toggleMessage()">Close</button>
</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    function toggleMessage() {
        var messageDiv = document.getElementById("messageDiv");
        messageDiv.style.display = (messageDiv.style.display === "none") ? "block" : "none";
    }
</script>

</body>
</html>

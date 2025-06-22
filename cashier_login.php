<?php
$conn = new mysqli("localhost", "root", "preethi123", "departmentalstore");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Insert new login record
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['new_username']) && isset($_POST['new_password'])) {
    $new_username = $_POST['new_username'];
    $new_password = $_POST['new_password'];

    $stmt = $conn->prepare("INSERT INTO cashier_login (username, pass_word) VALUES (?, ?)");
    if (!$stmt) {
        die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
    }

    if (!$stmt->bind_param("ss", $new_username, $new_password)) {
        die("Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error);
    }

    // Execute statement
    if ($stmt->execute()) {
        header("Location: cashier_login_records.php"); // Redirect to self after adding new login
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Fetch existing records
$sql = "SELECT username FROM cashier_login";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Records</title>
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            min-height: 100vh;
            align-items: center; /* Center vertically */
            justify-content: center; /* Center horizontally */
            background-color: #f8f9fa;
            margin: 0;
        }
        .container {
            display: flex;
            gap: 60px; /* Increased gap between columns */
            max-width: 1200px; /* Restrict max-width for better layout */
            width: 100%;
        }
        .left-col, .right-col {
            flex: 1;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            height: 500px; /* Set fixed height for divs */
            overflow-y: auto; /* Enable scrolling if content overflows */
        }
        .left-col {
            border-right: 1px solid #ddd;
        }
        .user-record {
            margin-bottom: 20px; /* Increased space between user buttons */
        }
        .btn-user {
            width: 100%;
            text-align: left;
            padding: 10px;
            border-radius: 5px;
            background-color: #e9ecef;
            color: #333;
            border: none;
            display: block;
        }
        .btn-user:hover {
            background-color: #ddd;
        }
        .btn-add {
            background-color: #dc3545;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px;
            width: 100%;
            cursor: pointer;
        }
        .btn-add:hover {
            background-color: #c82333;
        }
        form {
            margin-top: 20px;
        }
        form input[type="text"],
        form input[type="password"],
        form input[type="submit"] {
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            width: 100%;
            box-sizing: border-box;
        }
        form input[type="submit"] {
            background-color: #dc3545;
            color: #fff;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        form input[type="submit"]:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Existing Records -->
        <div class="left-col">
            <h2>Existing Login Records</h2>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $username = $row['username'];
                    echo "<div class='user-record'><button class='btn btn-user' onclick=\"location.href='login_check.php?username=$username'\">$username</button></div>";
                }
            } else {
                echo "<p>No records found.</p>";
            }
            ?>
        </div>

        <!-- Add New Login -->
        <div class="right-col">
            <h2>Add New Login</h2>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="new_username">Username</label>
                    <input type="text" name="new_username" id="new_username" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="new_password">Password</label>
                    <input type="password" name="new_password" id="new_password" class="form-control" required>
                </div>
                <input type="submit" value="Add Login" class="btn-add">
            </form>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

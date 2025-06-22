<!-- sidebar.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Sidebar</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
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
            background: #dcdcdc; /* Light gray background */
            color: black;
        }
        #sidebar .btn-logout {
            color: #fff;
            background-color: #dc3545;
            border: none;
            width: 100%;
            margin-top: auto;
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
            </ul>
        </div>
        <button class="btn btn-logout">Logout</button>
    </div>
</body>
</html>

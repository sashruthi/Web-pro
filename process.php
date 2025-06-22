<?php
if (isset($_POST['submit'])){
    $username = $_POST["username"];
    $password = $_POST["password"];

    $con = mysqli_connect("localhost", "root", "preethi123", "departmentalstore");


    $query = "SELECT * FROM login WHERE username = '$username' AND pass_word = '$password'";
    $result = mysqli_query($con,$query);

    if ($result->num_rows == 1) {
        $row = mysqli_fetch_assoc($result);
        $role = $row["username"];

        switch ($role) {
            case 'accountant':
                header("Location: accountant.php");
                break;
            case 'cashier':
                header("Location: billingsys.php");
                break;
            case 'sales_manager':
                header("Location: manager.php");
                break;
            case 'stockist':
                header("Location: stockist.php");
                break;
        }
        exit();
    } else {
        $error = "Invalid username or password";
    }

}
?>
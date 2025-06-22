<?php
$con = mysqli_connect('localhost', 'root', 'preethi123', 'departmentalstore');

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['pid'])) {
    $pid = $_POST['pid'];
    $query = "SELECT prod_name,net_weight,uom FROM prod_det WHERE prod_id='$pid'";
    $result = mysqli_query($con, $query);

    if ($result) {
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            echo json_encode($row);
        } else {
            echo json_encode(['prod_name' => '', 'net_weight' => '', 'uom' => '']);
        }
    } else {
        echo json_encode(['error' => mysqli_error($con)]);
    }
} else {
    echo json_encode(['error' => 'No pid parameter found']);
}

mysqli_close($con);
?>

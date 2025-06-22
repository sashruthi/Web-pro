<?php
$con = mysqli_connect('localhost', 'root', 'preethi123', 'departmentalstore');
if (isset($_POST['pid'])) {
    $pid = $_POST['pid'];
    $query = "SELECT prod_name, mrp FROM prod_det WHERE prod_id='$pid'";
    $result = mysqli_query($con, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        echo json_encode($row);
    } else {
        echo json_encode(['prod_name' => '', 'mrp' => '']);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dynamic Content Loading</title>
    <!-- Include Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
      <style>
        .btn-outline-dark {
            border: 1px solid #343a40;
            color: #343a40;
            background-color: transparent;
        }
        .btn-outline-dark:hover {
            color: #fff;
            background-color: #343a40;
        }
        .btn-logout {
            background-color: #dc3545; /* Red color */
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
            width: 100%; /* Full width */
        }
        .btn-logout:hover {
            background-color: #c82333; /* Darker red on hover */
        }
        .inputs {
            margin: 20px 0;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .table {
            margin: 20px 0;
        }
        .container-flex {
            display: flex;
            align-items: flex-start;
            width: 100%;    
        }
        .content {
            flex-grow: 1;
            padding: 15px;
            background-color: white;
        }
        .sidebar {
            width: 250px;
            padding: 20px;
            background-color: #f8f9fa;
            border-right: 1px solid #dee2e6;
            display: flex;
            flex-direction: column;
            align-items: flex-start; /* Aligns buttons to the start (left) */
        }
        .option {
            margin-bottom: 10px; /* Adds space between the buttons */
            width: 100%; /* Ensures buttons stretch to full width of sidebar */
        }
        .inputs {
            padding: 15px;
            background-color: white;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            margin-bottom: 15px; /* Add space below the inputs */
        }
    </style>
<script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll(".option").forEach(function(element) {
                element.addEventListener("click", function() {
                    var optionId = this.getAttribute("data-id");
                    var div2 = document.getElementById("div2");
                    div2.innerHTML = "";

                    if (optionId === "1") {
                        fetchResults(optionId);
                    } else if (optionId === "2") {
    div2.innerHTML = `
        <div class="inputs">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="input1Option2">Product ID:</label>
                        <input type="text" id="input1Option2" class="form-control" placeholder="Enter Product ID" onblur="fetchProductDetails(this.value)">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="input2Option2">Product Name:</label>
                        <input type="text" id="input2Option2" class="form-control" placeholder="Enter Product Name">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="input3Option2">Net Weight:</label>
                        <input type="text" id="input3Option2" class="form-control" placeholder="Enter Net Weight">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="input4Option2">Unit of Measure:</label>
                        <input type="text" id="input4Option2" class="form-control" placeholder="Enter Unit of Measure">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="input5Option2">Category:</label>
                        <input type="text" id="input5Option2" class="form-control" placeholder="Enter Category">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="input6Option2">Packaging Mode:</label>
                        <input type="text" id="input6Option2" class="form-control" placeholder="Enter Packaging Mode">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="input7Option2">Date of Manufacture:</label>
                        <input type="date" id="input7Option2" class="form-control">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="input8Option2">Date of Expiry:</label>
                        <input type="date" id="input8Option2" class="form-control">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="input9Option2">MRP:</label>
                        <input type="text" id="input9Option2" class="form-control" placeholder="Enter MRP">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="input10Option2">Stock:</label>
                        <input type="text" id="input10Option2" class="form-control" placeholder="Enter Stock">
                    </div>
                </div>
            </div>
            <div class="col-md-6">
            <div class="form-group">
                <label for="input11Option2">Stock Price:</label>
                <input type="text" id="input11Option2" class="form-control" placeholder="Enter Stock Price" max-width=30px>
            </div>
            </div>
            <button class="btn btn-primary" onclick="submitData(2)">Add</button>
        </div>
    `;
}
else if (optionId === "3") {
                div2.innerHTML = `
                    <div class="row">
                        <div class="col-md-6">
                            <div class="inputs">
                                <!-- Input fields for option 3 -->
                                <div class="form-group">
                                    <label for="input1Option3">Product ID:</label>
                                    <input type="text" id="input1Option3" class="form-control" placeholder="Enter Product ID" onblur="fetchProductDetails2(this.value)">
                                </div>
                                <div class="form-group">
                                    <label for="input2Option3">Product Name:</label>
                                    <input type="text" id="input2Option3" class="form-control" placeholder="Enter Product Name">
                                </div>
                                <div class="form-group">
                                    <label for="input3Option3">Net Weight:</label>
                                    <input type="text" id="input3Option3" class="form-control" placeholder="Enter Net Weight" >
                                </div>
                                <div class="form-group">
                                    <label for="input4Option3">Unit of Measurement:</label>
                                    <input type="text" id="input4Option3" class="form-control" placeholder="Enter UOM" >
                                </div>
                                <div class="form-group">
                                    <label for="input5Option3">Quantity:</label>
                                    <input type="text" id="input5Option3" class="form-control" placeholder="Enter Quantity" >
                                </div>
                                <!-- Add more input fields as needed -->
                                <button class="btn btn-outline-dark" onclick="submitData(3)">Send</button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <iframe src="request_table.php" style="width:100%; height:500px; border:none;"></iframe>
                        </div>
                    </div>
                `;
            }
        });
    });
});


        function fetchProductDetails(productId) {
            if (productId.length === 3) {
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "fetch_product.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                            var response = JSON.parse(xhr.responseText);
                            if (!response.error) {
                                document.getElementById("input2Option2").value = response.prod_name;
                                document.getElementById("input3Option2").value = response.net_weight;
                                document.getElementById("input4Option2").value = response.uom;
                                document.getElementById("input5Option2").value = response.category;
                                document.getElementById("input6Option2").value = response.packaging_mode;
                                document.getElementById("input9Option2").value = response.mrp;
                                document.getElementById("input11Option2").value = response.price;
                            } else {
                                console.log(response.error);
                                alert(response.error);
                            }
                        }
                    }
                };
                xhr.send("product_id=" + productId);
            }
        

        function fetchProductDetails2(productId) {
            if (productId.length === 3) {
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "fetch_product2.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        var response = JSON.parse(xhr.responseText);
                        if (!response.error) {
                            document.getElementById("input2Option3").value = response.prod_name;
                            document.getElementById("input3Option3").value = response.net_weight;
                            document.getElementById("input4Option3").value = response.uom;
                        } else {
                            console.log(response.error);
                            alert(response.error);
                        }
                    }
                };
                xhr.send("product_id=" + productId);
            }
        }

        function fetchResults(optionId, input1 = null, input2 = null, input3 = null, input4 = null, input5 = null, input6 = null, input7 = null, input8 = null, input9 = null, input10 = null, input11 = null) {
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "queryprocess.php", true);

            var formData = new FormData();
            formData.append("option_id", optionId);

            if (optionId === 2 && input1 !== null && input2 !== null && input3 !== null && input4 !== null && input5 !== null && input6 !== null && input7 !== null && input8 !== null && input9 !== null && input10 !== null && input11 !== null) {
                formData.append("input1", input1);
                formData.append("input2", input2);
                formData.append("input3", input3);
                formData.append("input4", input4);
                formData.append("input5", input5);
                formData.append("input6", input6);
                formData.append("input7", input7);
                formData.append("input8", input8);
                formData.append("input9", input9);
                formData.append("input10", input10);
                formData.append("input11", input11);
            } else if (optionId === "3_insert" && input1 !== null && input2 !== null && input3 !== null && input4 !== null && input5 !== null) {
                formData.append("input1", input1);
                formData.append("input2", input2);
                formData.append("input3", input3);
                formData.append("input4", input4);
                formData.append("input5", input5);
            }

            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200) {
                        document.getElementById("div2").innerHTML = xhr.responseText;
                        styleTables();
                    } else {
                        console.error("Failed to fetch data. Status: " + xhr.status);
                        document.getElementById("div2").innerHTML = "An error occurred while fetching data. Please try again.";
                    }
                }
            };
            xhr.send(formData);
        }

        function submitData(optionId) {
            if (optionId === 2) {
                var input1 = document.getElementById("input1Option2").value;
                var input2 = document.getElementById("input2Option2").value;
                var input3 = document.getElementById("input3Option2").value;
                var input4 = document.getElementById("input4Option2").value;
                var input5 = document.getElementById("input5Option2").value;
                var input6 = document.getElementById("input6Option2").value;
                var input7 = document.getElementById("input7Option2").value;
                var input8 = document.getElementById("input8Option2").value;
                var input9 = document.getElementById("input9Option2").value;
                var input10 = document.getElementById("input10Option2").value;
                var input11 = document.getElementById("input11Option2").value;
                if (input1 !== "" && input2 !== "" && input3 !== "" && input4 !== "" && input5 !== "" && input6 !== "" && input7 !== "" && input8 !== "" && input9 !== "" && input10 !== "" && input11 !== "") {
                    fetchResults(optionId, input1, input2, input3, input4, input5, input6, input7, input8, input9, input10, input11);
                } else {
                    alert("Please fill in all inputs for Option 2.");
                }
            } else if (optionId === 3) {
                var input1 = document.getElementById("input1Option3").value;
                var input2 = document.getElementById("input2Option3").value;
                var input3 = document.getElementById("input3Option3").value;
                var input4 = document.getElementById("input4Option3").value;
                var input5 = document.getElementById("input5Option3").value;

                if (input1 !== "" && input2 !== "" && input3 !== "" && input4 !== "" && input5 !== "") {
                    fetchResults('3_insert', input1, input2, input3, input4, input5);
                } else {
                    alert("Please fill in all inputs for Option 3.");
                }
            }
        }
function logout() {
            // Redirect to the login URL
            window.location.href = "login.php";
        }
        function styleTables() {
            document.querySelectorAll("table").forEach(function(table) {
                table.classList.add("table", "table-striped", "table-bordered", "bg-light", "text-dark");
            });
        }
    </script>
</head>
<body class="bg-light text-dark">
    <div id="div3" class="bg-light text-center p-3 mb-4 border-bottom">
        Departmental Store Billing System - Stockist
    </div>
    <div id="container" class="d-flex">
        <div id="div1" class="bg-light text-center p-3 border-right d-flex flex-column">
            <div class="option btn btn-outline-dark mb-3" data-id="1">View stock</div>
            <div class="option btn btn-outline-dark mb-3" data-id="2">Add stock</div>
            <div class="option btn btn-outline-dark" data-id="3">Send stock</div><BR>
            <button class="btn-logout" onclick="logout()">Logout</button>       
         </div>
        <div id="div2" class="flex-grow-1 p-3 bg-white">
            <!-- Results will be displayed here -->
        </div>
    </div>

    <!-- Include Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

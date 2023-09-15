<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (
        isset($_POST['product-name'], $_POST['category'], $_POST['product-description'], $_POST['product-price'], $_POST['stock-quantity'], $_POST['manufacturer'], $_FILES['image'])
        && !empty($_POST['product-name']) && !empty($_POST['category']) && !empty($_POST['product-description']) && !empty($_POST['product-price']) && !empty($_POST['stock-quantity']) && !empty($_POST['manufacturer'])
    ) {
        $product_name = $_POST['product-name'];
        $category = $_POST['category'];
        $product_description = $_POST['product-description'];
        $product_price = $_POST['product-price'];
        $stock_quantity = $_POST['stock-quantity'];
        $manufacturer = $_POST['manufacturer'];
        $is_discontinued = isset($_POST['is-discontinued']) ? 1 : 0;

        $image = $_FILES['image'];
        $image_name = $image['name'];
        $image_tmp = $image['tmp_name'];

        // Move the uploaded image to a desired location (You may want to store it in a proper directory)
        $upload_directory = 'product_images/';
        move_uploaded_file($image_tmp, $upload_directory . $image_name);

        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "agriculture";

        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

    $current_date = date('Y-m-d H:i:s');

    $sql = "INSERT INTO products (product_name, category, product_description, product_price, stock_quantity, manufacturer, product_image, is_discontinued, date_added) 
            VALUES ('$product_name', '$category', '$product_description', '$product_price', '$stock_quantity', '$manufacturer', '$image_name', '$is_discontinued', '$current_date')";

        if ($conn->query($sql) === TRUE) {
            echo "Product details have been successfully stored in the database.";
             header("Location: success.php");
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }

        $conn->close();
    } else {
        echo "Please fill in all the required fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>sell</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }
        body {
            background-color: #f0f0f0;
        }
        header {
            background-color: #19253b;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            color: #fff;
            padding: 1rem;
        }
         nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 80%;
            margin: auto;
        }
        .logo {
            font-size: 1.3rem;
            font-weight: 800;
        }
        .logo a {
            color: #fff;
            text-decoration: none;
        }
        ul {
            list-style-type: none;
            margin: 0;
            padding: 0;
            display: flex;
        }
        li {
            padding: 0rem 1.1rem;
        }
        li a {
            color: #fff;
            text-decoration: none;
            font-size: 1rem;
            font-weight: 600;
            letter-spacing: .7px;
        }
        li a:hover {
            color: #91b2ff;
            transition: all .3s ease-in-out
        }
        .sell-container {
            max-width: 600px;
            margin: 2rem auto;
            background-color: #fff;
            padding: 2rem;
            border-radius: 4px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        .form-group label {
            display: block;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .image-upload-group {
            display: flex;
            align-items: center;
        }

        .image-upload-group label {
            margin-right: 1rem;
        }
        #preview-image {
            max-width: 100%;
            max-height: 300px;
            object-fit: contain;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .button-container {
            display: flex;
            justify-content: center;
        }
        .button-container button {
            font-weight: 500;
            color: #fff;
            border: none;
            background-color: #19253b;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            cursor: pointer;
        }
        .button-container button:hover {
            background-color: #152939;
            transition: all .3s ease-in-out;
        }
    </style>
</head>

<body>
    <header>
        <nav>
            <div class="logo">
                <a href="#">Agro Tech E-commerce.</a>
            </div>
        </nav>
    </header>
    <div class="sell-container">
        <h2>Sell Your Product</h2>
        <form action="" method="POST" enctype="multipart/form-data">

            <div class="form-group">
                <label for="product-name">Product Name</label>
                <input type="text" id="product-name" name="product-name" required>
            </div>
            <div class="form-group">
                <label for="category">Category</label>
                <select id="category" name="category" required>
                    <option value="">Select category</option>
                    <option value="vegetables">Vegetables</option>
                    <option value="fruits">Fruits</option>
                    <option value="grains">Grains</option>
                    <option value="dairy">Dairy</option>
                </select>
            </div>
            <div class="form-group">
                <label for="product-description">Product Description</label>
                <textarea id="product-description" name="product-description" rows="4" required></textarea>
            </div>
            <div class="form-group">
                <label for="product-price">Product Price (in Rupees)</label>
                <input type="number" id="product-price" name="product-price" required>
            </div>
            <div class="form-group">
                <label for="stock-quantity">Stock Quantity</label>
                <input type="number" id="stock-quantity" name="stock-quantity" required>
            </div>
            <div class="form-group">
                <label for="manufacturer">Manufacturer Name</label>
                <input type="text" id="manufacturer" name="manufacturer" required>
            </div>
            <div class="form-group">
                <label for="image">Product Image</label>
                <input type="file" id="image" name="image" accept="image/*" required>
            </div>
            <div class="form-group">
                <label for="is-discontinued">Is Discontinued?</label>
                <input type="checkbox" id="is-discontinued" name="is-discontinued">
            </div>

            <div class="button-container">
                <button type="submit">Sell Now</button>
            </div>
        </form>
    </div>
    <script>
    const imageInput = document.getElementById('image');
    const previewImage = document.getElementById('preview-image');

    imageInput.addEventListener('change', () => {
        const file = imageInput.files[0];
        const reader = new FileReader();

        reader.onload = () => {
            previewImage.src = reader.result;
        }
        if (file) {
            reader.readAsDataURL(file);
        } else {
            previewImage.src = "#";
        }
    });
</script>

</body>

</html>

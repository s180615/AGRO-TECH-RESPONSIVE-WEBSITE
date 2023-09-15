<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Details</title>
    <style>
        /* Your CSS styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            background-color: #f0f0f0;
        }

        header {
            background-color: #19253b;
            color: #fff;
            text-align: center;
            padding: 1rem;
        }

        header h1 {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
        }

        header p {
            font-size: 1.2rem;
        }

        /* Add your custom styles here */
        .product-details {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 2rem;
        }

        .product-details img {
            max-width: 100%;
            height: auto;
            border-radius: 4px;
            margin-bottom: 1rem;
            box-shadow: 0 4px 4px rgba(0, 0, 0, 0.1); /* Shadow effect */
        }

        .product-details p {
            margin-bottom: 0.5rem;
            text-align: left;
        }

        .product-details strong {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <header>
        <h1>Product Details</h1>
        <p>Explore the details of the product.</p>
    </header>

    <main>
        <?php
        // Establish database connection (You should already have this code)
        $servername = "localhost";
        $username_db = "root";
        $password_db = "";
        $dbname = "agriculture";

        $conn = new mysqli($servername, $username_db, $password_db, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Check if the product ID is provided in the URL
        if (isset($_GET['id'])) {
            $product_id = $_GET['id'];

            // Fetch the product details from the database
            $sql = "SELECT * FROM products WHERE product_id = $product_id";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                echo '<div class="product-details">';
                echo '<img src="product_images/' . $row['product_image'] . '" alt="' . $row['product_name'] . '">';
                echo '<p><strong>Product Name:</strong> ' . $row['product_name'] . '</p>';
                echo '<p><strong>Category:</strong> ' . $row['category'] . '</p>';
                echo '<p><strong>Description:</strong> ' . $row['product_description'] . '</p>';
                echo '<p><strong>Price:</strong> ₹' . $row['product_price'] . '</p>';
                echo '<p><strong>Stock Quantity:</strong> ' . $row['stock_quantity'] . '</p>';
                echo '<p><strong>Manufacturer:</strong> ' . $row['manufacturer'] . '</p>';
                echo '<p><strong>Is Discontinued:</strong> ' . ($row['is_discontinued'] ? 'Yes' : 'No') . '</p>';
                echo '<p><strong>Date Added:</strong> ' . $row['date_added'] . '</p>';
                echo '</div>';
            } else {
                echo '<p>Product not found.</p>';
            }
        } else {
            echo '<p>Product ID not provided.</p>';
        }

        // Close the database connection
        $conn->close();
        ?>
    </main>
</body>
</html>

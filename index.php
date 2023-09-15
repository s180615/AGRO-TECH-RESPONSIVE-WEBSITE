<?php
session_start();

$isLoggedIn = isset($_SESSION['username']);
$username = $_SESSION['username'] ?? '';

// If the user is not logged in, redirect to the login page
if (!$isLoggedIn) {
    header("Location: login.php");
    exit;
}

$servername = "localhost";
$username_db = "root";
$password_db = "";
$dbname = "agriculture";

$conn = new mysqli($servername, $username_db, $password_db, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch products from the database based on the search query if provided
if (isset($_GET['query'])) {
    $searchQuery = trim($_GET['query']);
    $searchQuery = $conn->real_escape_string($searchQuery);

    $sql = "SELECT * FROM products WHERE product_name LIKE '%$searchQuery%'";
    $result = $conn->query($sql);
} else {
    // If no search query is provided, fetch all products
    $sql = "SELECT * FROM products";
    $result = $conn->query($sql);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background-image: url('images/sdr.jpg');
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center;
            height: 100vh; 
        }

        header {
            background-color: #19253b59;
            box-shadow: 0 0 36px 0;
            backdrop-filter: blur(3px);
        }

        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 80%;
            margin: auto;
            padding: 1.2rem 0rem;
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
            padding: 0;
            background-color: transparent;
            overflow: hidden;
        }

        li {
            padding: 0rem 1.1rem;
            float: left;
        }

        li a {
            color: #fff;
            text-decoration: none;
            font-family: 'Poppins', sans-serif;
            font-size: 1rem;
            font-weight: 600;
            letter-spacing: .7px;
        }

        li a:hover {
            color: #91b2ff;
            transition: all .3s ease-in-out
        }

        .button {
            display: flex;
            gap: 1rem;
        }

        .button button {
            font-weight: 500;
            color: #fff;
            border: 1px solid #fff;
            background-color: transparent;
            padding: .5rem 1rem;
            border-radius: 20%;
            cursor: pointer;
        }

        .button button:hover {
            background-color: #fff;
            color: #152939;
            transition: all .3s ease-in-out;
        }

        .product-list {
    max-width: 1200px;
    margin: 2rem auto;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 5px; 
    padding: 0; 
}

.product-item {
    background-color: #fff;
    padding: 1rem;
    border-radius: 4px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    text-align: center;
    width: 150px;
    display: inline-block;
}

.product-item img {
    max-width: 100%;
    height: auto;
    border-radius: 4px;
    margin-bottom: 1rem;
    width: 100px;
    height: 100px;
}

.product-item h2 {
    font-size: 1.2rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.product-item p {
    font-size: 12px;
    margin-bottom: 1rem;
}

.product-item .price {
    font-size: 1rem;
    font-weight: 600;
    color: #19253b;
    margin-bottom: 1rem;
    display: block;
}

.product-item a {
    font-size: 12px;
    text-decoration: none;
    color: #19253b;
    display: inline-block;
    background-color: #f0f0f0;
    padding: 0.5rem 1rem;
    border-radius: 4px;
}

.product-item a:hover {
    background-color: #19253b;
    color: #fff;
    transition: all 0.3s ease-in-out;
}
</style>
</head>
<body>
    <header>
        <nav>
            <div class="logo">
                <a href="#">AgroTech E-commerce</a>
            </div>
            <ul>
                <li>
                <form class="search-form" action="index.php" method="get">
                <input type="text" name="query" placeholder="Search by product name..." />
                <button type="submit">Search</button>
            </form>
                </li>
                <?php
                if ($isLoggedIn) {
                    echo '<li><a href=sell.php><b>Become a seller</b></a></li>';
                    echo '<li><a href="http://127.0.0.1:5000"><b>Scanner</b></a></li>';
                    echo '<li><a href="#"><b>' . htmlspecialchars($username) . '</b></a></li>';
                    echo '<li><a href=cart.php><b>cart</b></a></li>';
                }
                ?>
            </ul>
        </nav>
    </header>

    <main>
        <section class="product-list">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    if (isset($row['product_id'], $row['product_name'], $row['product_description'], $row['product_price'], $row['product_image'])) {
                        $product_id = $row['product_id'];
                        $product_name = $row['product_name'];
                        $product_description = $row['product_description'];
                        $product_price = $row['product_price'];
                        $product_image = $row['product_image'];

                        echo '<div class="product-item">';
                        echo '<img src="product_images/' . $product_image . '" alt="' . $product_name . '">';
                        echo '<h2>' . $product_name . '</h2>';
                        echo '<p>' . $product_description . '</p>';
                        echo '<span class="price">₹' . $product_price . '</span>';
                        echo '<a href="view_details.php?id=' . $product_id . '">View Details</a>';
                        echo '<form action="cart.php" method="post">';
                        echo '<input type="hidden" name="product_id" value="' . $product_id . '">';
                        echo '<input type="hidden" name="product_name" value="' . $product_name . '">';
                        echo '<input type="hidden" name="product_price" value="' . $product_price . '">';
                        echo '<button type="submit">Buy Now</button>';
                        echo '</form>';
                        echo '</div>';
                    } else {
                        echo '<p>Product details are missing or invalid.</p>';
                    }
                }
            } else {
                echo '<p>No products found.</p>';
            }
            ?>
        </section>
    </main>

</body>
</html>

<?php
$conn->close();
?>

<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "agriculture";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $sql_check_user = "SELECT * FROM users WHERE email = ?";
    $stmt_check_user = $conn->prepare($sql_check_user);
    $stmt_check_user->bind_param("s", $email);
    $stmt_check_user->execute();
    $result_check_user = $stmt_check_user->get_result();

    if ($result_check_user->num_rows === 1) {
        // User found, verify the password
        $row = $result_check_user->fetch_assoc();
        if (password_verify($password, $row["password"])) {
            // Password is correct, login successful
            $_SESSION['username'] = $row["name"];

            header("Location: index.php");
            exit;
        } else {
            // Incorrect password
            $_SESSION['message'] = 'Invalid email or password. Please try again.';
            header("Location: login.php");
            exit;
        }
    } else {
        $_SESSION['message'] = 'User not found. Please create a new account or check your email.';
        header("Location: login.php");
        exit;
    }

    $stmt_check_user->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
     * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    body {
      font-family: Arial, sans-serif;
      background-color: #f4f4f4;
      background-image:url("/images/login.png");
    }
    .login-section {
      max-width: 400px;
      margin: 50px auto;
      padding: 20px;
      background-color: #fff;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
    .login-section h2 {
      text-align: center;
      margin-bottom: 20px;
    }
    .login-form input[type="text"],
    .login-form input[type="password"] {
      width: 100%;
      padding: 10px;
      margin-bottom: 10px;
      border: 1px solid #ccc;
      border-radius: 4px;
    }
    .login-form button {
      width: 100%;
      padding: 10px;
      background-color: #06132c;
      color: #fff;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }
    .login-form button:hover {
      background-color: #053c84;
    }
    .login-form .create-account {
      background-color: #2e7d32;
    }
    .login-form .create-account:hover {
      background-color: #265c27;
    }
    .login-form .social-login {
      display: flex;
      justify-content: space-between;
      margin-top: 10px;
    }
    .login-form .social-login button {
      flex-basis: 48%;
    }
  </style>
</head>

<body>
    <div class="login-section">
        <h2>Login</h2>
        <form class="login-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <input type="text" placeholder="Email" name="email">
            <input type="password" placeholder="Password" name="password">
            <button type="submit">Login</button>
        </form>
        <br>
        <div class="social-login">
            <center><button class="create-account"><a href="/register">Create New Account</a></button></center>
        </div>
        <br>
        <center><a href="rp.html">Reset Password</a></center>
    </div>

    <?php
    if (isset($_SESSION['message'])) {
        echo '<p style="color: red;">' . $_SESSION['message'] . '</p>';
        unset($_SESSION['message']);
    }
    ?>
</body>

</html>

<?php
// Establish database connection
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
    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $confirmPassword = $_POST["confirm-password"];

    if ($password !== $confirmPassword) {
        $_SESSION['message'] = 'Password and confirm password do not match.';
        header("Location: register.php?error=password_mismatch");
        exit;
    }


    if (!isValidPassword($password)) {
        $_SESSION['message'] = 'Password must be at least 8 characters with one uppercase letter, one special symbol, and one number.';
        header("Location: register.php?error=invalid_password");
        exit;
    }

    $sql_check_user = "SELECT * FROM users WHERE email = ?";
    $stmt_check_user = $conn->prepare($sql_check_user);
    $stmt_check_user->bind_param("s", $email);
    $stmt_check_user->execute();
    $result_check_user = $stmt_check_user->get_result();

    if ($result_check_user->num_rows > 0) {
        $_SESSION['message'] = 'User already exists. Please log in with your credentials.';
        header("Location: login.php");
        exit;
    }

    // Hash the password before storing it in the database
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $name, $email, $hashed_password);

    if ($stmt->execute()) {
        // Registration successful
        $_SESSION['success_message'] = 'Registration successful. You can now log in with your credentials.';
        header("Location: login.php");
        exit;
    } else {
        // Registration failed
        header("Location: register.php?error=registration_failed");
        exit;
    }
    $stmt->close();
}

$conn->close();

function isValidPassword($password)
{
    // Password must be at least 8 characters long
    if (strlen($password) < 8) {
        return false;
    }

    // Password must contain at least one uppercase letter, one special symbol, and one number
    if (!preg_match('/[A-Z]/', $password) || !preg_match('/[!@#$%^&*()\-_=+{};:,<.>]/', $password) || !preg_match('/[0-9]/', $password)) {
        return false;
    }

    return true;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
    <div class="container">
        <h2>Registration Form</h2>
        <?php
        if (isset($_SESSION["message"])) {
            echo '<p style="color: red;">' . $_SESSION["message"] . '</p>';
            unset($_SESSION["message"]);
        }
        ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <label for="confirm-password">Confirm Password:</label>
            <input type="password" id="confirm-password" name="confirm-password" required>
            <input type="submit" value="Submit">
        </form>
    </div>
    <script>
        const passwordInput = document.getElementById("password");
        const confirmPasswordInput = document.getElementById("confirm-password");

        passwordInput.addEventListener("input", validatePassword);
        confirmPasswordInput.addEventListener("input", validatePassword);

        function validatePassword() {
            const password = passwordInput.value;
            const confirmPassword = confirmPasswordInput.value;

            // Check if the password meets the requirements
            const isValid = /^(?=.*[A-Z])(?=.*[!@#$%^&*()\-_=+{};:,<.>])(?=.*[0-9]).{8,}$/.test(password);

            if (isValid) {
                confirmPasswordInput.setCustomValidity("");
            } else {
                confirmPasswordInput.setCustomValidity("Password must be at least 8 characters with one uppercase letter, one special symbol, and one number.");
            }
        }
    </script>
</body>

</html>

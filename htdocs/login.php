<?php
session_start();

$servername = "localhost";
$username = "admin";
$password = "Database123!";
$database = "budget_tracker";

try {
    // Establish database connection
    $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if form is submitted
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        // Retrieve username and password from form
        if (!empty($_POST['username']) && !empty($_POST['password'])) {
            $username = trim($_POST['username']);
            $password = $_POST['password'];
            
            // Prepare SQL statement to fetch user credentials
            $stmt = $conn->prepare("SELECT user_id, password FROM users WHERE username = :username");
            $stmt->bindParam(":username", $username);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Verify password and set session variables
            if ($user && password_verify($password, $user["password"])) {
                $_SESSION["user_id"] = $user['user_id'];
                header("Location: welcome.php"); // Redirect to welcome page on successful login
                exit;
            } else {
                $error = "Login failed. Incorrect username or password.";
            }
        } else {
            $error = "Please provide both username and password.";
        }
    }
} catch (PDOException $e) {
    $error = "Connection failed: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Budget Tracker - Log In</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Budget Tracker - Log In</h1>
    </header>
    <main>
        <section class="login-form">
            <h2>Log In</h2>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit">Log In</button>
            </form>
            <?php if (!empty($error)): ?>
                <p class="error"><?php echo $error; ?></p>
            <?php endif; ?>
            <p>Don't have an account? <a href="signup.php">Sign up</a></p>
        </section>
    </main>
    <footer>
        <p>&copy; 2024 Budget Tracker</p>
    </footer>
</body>
</html>

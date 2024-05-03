<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$servername = "localhost";
$username = "admin";
$password = "Database123!";
$database = "budget_tracker";

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $user_id = $_SESSION['user_id'];
        $name = $_POST['expense_name'];
        $amount = $_POST['expense_amount'];
        $category = $_POST['category'];

        $sql = "INSERT INTO expenses (user_id, expense_name, expense_amount, category) VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute([$user_id, $name, $amount, $category])) {
            $_SESSION['expense_added'] = true;  // Set a session flag
            header("Location: expense.php");  // Redirect to avoid re-post on refresh
            exit();
        } else {
            $error = "Failed to add expense.";
        }
    }
} catch (PDOException $e) {
    $error = "Connection failed: " . $e->getMessage();
}

$expenseAdded = isset($_SESSION['expense_added']);  // Check the session flag
unset($_SESSION['expense_added']);  // Clear the flag immediately after checking
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Budget Tracker - Add Expense</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Budget Tracker</h1>
        <nav>
            <ul>
                <li class="nav-item"><a href="dashboard.php">Dashboard</a></li>
                <li class="nav-item"><a href="welcome.php">Home</a></li>
                <li class="nav-item"><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>
    <main>
    <h2>Add New Expense</h2>
<form method="post">
    <label for="category">Category:</label>
    <select id="category" name="category" required>
        <option value="">Select Category</option>
        <option value="Housing">Housing</option>
        <option value="Food">Food</option>
        <option value="Transportation">Transportation</option>
        <option value="Utilities">Utilities</option>
        <option value="Entertainment">Entertainment</option>
        <option value="Other">Other</option>
    </select>

    <label for="expense_name">Expense Name:</label>
    <input type="text" id="expense_name" name="expense_name" required>

    <label for="expense_amount">Amount:</label>
    <div class="input-group">
        <input type="text" id="expense_amount" name="expense_amount" placeholder="$0.00" required>
    </div>

    <button type="submit">Add Expense</button>
</form>

    </main>
    <footer>
        <p>&copy; 2024 Budget Tracker</p>
    </footer>
</body>
</html>



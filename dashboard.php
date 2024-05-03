<?php
session_start();

if (empty($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

require 'db_connection.php';

$categories_dropdown = [];
$selected_category = $_GET['category'] ?? '';
$expenses = []; // Expenses to be shown in the table
$expenses_pie = []; // For the pie chart showing all categories
$details_labels = [];
$details_totals = [];

// Fetch categories for dropdown
try {
    $stmt = $conn->prepare("SELECT DISTINCT category FROM expenses WHERE user_id = :userid");
    $stmt->execute(['userid' => $_SESSION['user_id']]);
    $categories_dropdown = $stmt->fetchAll(PDO::FETCH_COLUMN);
} catch (PDOException $e) {
    echo "Database query failed: " . $e->getMessage();
}

// Fetch expenses for the pie chart showing all categories
try {
    $stmt = $conn->prepare("SELECT category, SUM(expense_amount) AS total FROM expenses WHERE user_id = :userid GROUP BY category");
    $stmt->execute(['userid' => $_SESSION['user_id']]);
    $expenses_pie = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $categories = array_column($expenses_pie, 'category');
    $totals_pie = array_column($expenses_pie, 'total');
} catch (PDOException $e) {
    echo "Database query failed: " . $e->getMessage();
}

// Fetch detailed expenses for selected category or all expenses if no category is selected
try {
    if (!empty($selected_category)) {
        $stmt = $conn->prepare("SELECT expense_name, SUM(expense_amount) AS total FROM expenses WHERE user_id = :userid AND category = :category GROUP BY expense_name");
        $stmt->execute(['userid' => $_SESSION['user_id'], 'category' => $selected_category]);
        $expenses = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $stmt = $conn->prepare("SELECT expense_name, SUM(expense_amount) AS total FROM expenses WHERE user_id = :userid GROUP BY expense_name");
        $stmt->execute(['userid' => $_SESSION['user_id']]);
        $expenses = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} catch (PDOException $e) {
    echo "Database query failed: " . $e->getMessage();
}

// Prepare JSON data for the JavaScript chart
$labelsJSON = json_encode(empty($selected_category) ? $categories : array_column($expenses, 'expense_name'));
$totalsJSON = json_encode(empty($selected_category) ? $totals_pie : array_column($expenses, 'total'));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Budget Tracker - Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>
    <style>
        body { text-align: center; }
        main {
            display: flex;
            justify-content: space-between; /* Ensures space between sections */
            align-items: flex-start; /* Align items at the top */
            padding: 20px;
        }
        section {
            padding: 10px;
            box-sizing: border-box;
        }
        .table-section {
            flex: 1; /* Flex grow to use available space */
            max-width: 50%; /* Limits the width to half of its parent */
        }
        .graph-section {
            flex: 1; /* Flex grow to use available space */
            max-width: 50%; /* Limits the width to half of its parent */
            display: flex;
            flex-direction: column;
            align-items: center; /* Center the items vertically */
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }
        th {
            background-color: #007bff;
            color: #ffffff;
        }
        tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        canvas {
            width: 100%; /* Full width of its container */
            max-width: 400px; /* Maximum width */
            height: auto; /* Height is auto to maintain aspect ratio */
        }
    </style>
</head>
<body>
    <header>
        <h1>Budget Tracker Dashboard</h1>
        <nav>
            <ul>
                <li><a href="expense.php">Add Expense</a></li>
                <li><a href="welcome.php">Home</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <section class="table-section">
            <h2>Current Expenses:</h2>
            <form method="GET">
                <label for="category">Select Category:</label>
                <select name="category" id="category">
                    <option value="">All Categories</option>
                    <?php foreach ($categories_dropdown as $category): ?>
                        <option value="<?= htmlspecialchars($category) ?>" <?= $selected_category == $category ? 'selected' : '' ?>>
                            <?= htmlspecialchars($category) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button type="submit">Show Expenses</button>
            </form>
            <table>
                <thead>
                    <tr>
                        <th>Expense Name</th>
                        <th>Expense Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($expenses)): ?>
                        <?php foreach ($expenses as $expense): ?>
                            <tr>
                                <td><?= htmlspecialchars($expense['expense_name']) ?></td>
                                <td>$<?= number_format($expense['total'], 2) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="2">No expenses recorded.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>
        <section class="graph-section">
            <h2>Expense Analysis</h2>
            <canvas id="expense-chart"></canvas>
        </section>
    </main>
    <footer>
        <p>&copy; 2024 Budget Tracker</p>
    </footer>
    <script>
document.addEventListener('DOMContentLoaded', function() {
    var ctx = document.getElementById('expense-chart').getContext('2d');
    var expenseChart; // Define the chart variable outside to check if it needs to be updated or created

    // Expanded color palette to accommodate more categories
    var colors = [
        '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#FDB45C', '#949FB1', '#4D5360', 
        '#AC64AD', '#76B041', '#FF9F40', '#4A536B', '#2D87BB', '#2E7D32', '#C2185B', 
        '#AD1457', '#6A1B9A', '#00695C', '#FF8F00', '#E65100', '#BF360C', '#3F51B5', 
        '#009688', '#795548', '#673AB7', '#CDDC39', '#F44336', '#E91E63', '#9C27B0', 
        '#3F51B5', '#00BCD4', '#009688', '#8BC34A', '#CDDC39', '#FFEB3B', '#FFC107', 
        '#FF9800', '#FF5722', '#795548', '#9E9E9E', '#607D8B'
    ];

    function updateChart(labels, data) {
        if (expenseChart) { // If chart exists, destroy and recreate with new data
            expenseChart.destroy();
        }
        expenseChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    data: data,
                    backgroundColor: colors.slice(0, labels.length), // Slice the color array to match the number of labels
                    hoverBackgroundColor: colors.slice(0, labels.length).map(color => shadeColor(color, -0.2)) // Darkened hover colors
                }]
            },
            options: {
                responsive: true,
                legend: {
                    position: 'top',
                },
                animation: {
                    animateScale: true,
                    animateRotate: true
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                var label = context.label || '';
                                var value = context.parsed.toFixed(2);
                                return label + ': $' + value;
                            }
                        }
                    }
                }
            }
        });
    }

    // Shade color function to darken for hover states
    function shadeColor(color, percent) {
        let f = parseInt(color.slice(1), 16),
            t = percent < 0 ? 0 : 255,
            p = percent < 0 ? percent * -1 : percent,
            R = f >> 16,
            G = f >> 8 & 0x00FF,
            B = f & 0x0000FF;
        return "#" + (0x1000000 + (Math.round((t - R) * p) + R) * 0x10000 + (Math.round((t - G) * p) + G) * 0x100 + (Math.round((t - B) * p) + B)).toString(16).slice(1);
    }

    // Initialize chart with PHP provided JSON data
    updateChart(JSON.parse('<?= $labelsJSON ?>'), JSON.parse('<?= $totalsJSON ?>'));
});
</script>

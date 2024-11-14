<?php
// dashboard.php - Dashboard page
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

include 'db.php';

// Query to join products with supply types and inventory logs
$stmt = $pdo->prepare("
    SELECT products.product_name, products.quantity, supply_types.type_name,
           inventory_logs.action_type, inventory_logs.change_quantity, inventory_logs.timestamp,
           users.username
    FROM products
    LEFT JOIN supply_types ON products.type_id = supply_types.id
    LEFT JOIN inventory_logs ON products.id = inventory_logs.product_id
    LEFT JOIN users ON inventory_logs.user_id = users.id
    ORDER BY inventory_logs.timestamp DESC
");
$stmt->execute();
$inventory_data = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/styles.css">
    <title>Inventory Dashboard</title>
    <style>
        /* Basic reset */
        body, h2, table, th, td {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        /* Page styling */
        body {
            background-color: #f4f7fb;
            color: #333;
            padding: 20px;
        }

        h2 {
            text-align: center;
            color: #4CAF50;
            margin-bottom: 20px;
        }

        a {
            display: inline-block;
            background-color: #f44336;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 4px;
            margin-bottom: 20px;
        }

        a:hover {
            background-color: #d32f2f;
        }

        /* Table styling */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 12px 15px;
            text-align: left;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #ddd;
        }

        td {
            color: #555;
        }

        /* Add responsive design */
        @media (max-width: 768px) {
            table {
                font-size: 14px;
            }

            th, td {
                padding: 10px;
            }

            h2 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <h2>Inventory Dashboard</h2>
    <a href="logout.php">Logout</a>
    <table>
        <thead>
            <tr>
                <th>Product Name</th>
                <th>Quantity</th>
                <th>Type</th>
                <th>Change Type</th>
                <th>Change Quantity</th>
                <th>Timestamp</th>
                <th>Updated By</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($inventory_data as $row): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['product_name']); ?></td>
                <td><?php echo htmlspecialchars($row['quantity']); ?></td>
                <td><?php echo htmlspecialchars($row['type_name']); ?></td>
                <td><?php echo htmlspecialchars($row['action_type']); ?></td>
                <td><?php echo htmlspecialchars($row['change_quantity']); ?></td>
                <td><?php echo htmlspecialchars($row['timestamp']); ?></td>
                <td><?php echo htmlspecialchars($row['username']); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>

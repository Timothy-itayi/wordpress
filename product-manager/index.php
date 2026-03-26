<?php

declare(strict_types=1);

require 'database.php';

$stmt = $db->query('SELECT * FROM products ORDER BY created_at DESC');
$products = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Manager</title>
    <style>
        body { font-family: system-ui, sans-serif; max-width: 800px; margin: 40px auto; padding: 0 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
        a { color: #0066cc; text-decoration: none; }
        a:hover { text-decoration: underline; }
        .btn { display: inline-block; padding: 8px 16px; background: #0066cc; color: white; border-radius: 4px; }
        .btn-danger { background: #cc3333; }
        .empty { color: #888; padding: 40px 0; text-align: center; }
    </style>
</head>
<body>
    <h1>Product Manager</h1>
    <a href="create.php" class="btn">+ Add Product</a>

    <?php if (empty($products)): ?>
        <p class="empty">No products yet. Add one above.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product): ?>
                <tr>
                    <td><?= htmlspecialchars((string) $product['id']) ?></td>
                    <td><?= htmlspecialchars($product['name']) ?></td>
                    <td>$<?= number_format((float) $product['price'], 2) ?></td>
                    <td><?= htmlspecialchars($product['created_at']) ?></td>
                    <td>
                        <a href="edit.php?id=<?= $product['id'] ?>">Edit</a> |
                        <a href="delete.php?id=<?= $product['id'] ?>"
                           onclick="return confirm('Delete this product?')">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>
</html>
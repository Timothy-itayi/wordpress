<?php

declare(strict_types=1);

require 'database.php';

$id = (int) ($_GET['id'] ?? 0);
$error = '';

$stmt = $db->prepare('SELECT * FROM products WHERE id = ?');
$stmt->execute([$id]);
$product = $stmt->fetch();

if (!$product) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $price = $_POST['price'] ?? '';

    if ($name === '' || $price === '') {
        $error = 'Name and price are required.';
    } elseif (!is_numeric($price) || (float) $price < 0) {
        $error = 'Price must be a positive number.';
    } else {
        $stmt = $db->prepare('UPDATE products SET name = ?, price = ? WHERE id = ?');
        $stmt->execute([$name, (float) $price, $id]);
        header('Location: index.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <style>
        body { font-family: system-ui, sans-serif; max-width: 500px; margin: 40px auto; padding: 0 20px; }
        label { display: block; margin-top: 16px; font-weight: bold; }
        input { width: 100%; padding: 8px; margin-top: 4px; box-sizing: border-box; }
        button { margin-top: 20px; padding: 10px 20px; background: #0066cc; color: white; border: none; border-radius: 4px; cursor: pointer; }
        .error { color: #cc3333; margin-top: 12px; }
        a { color: #0066cc; }
    </style>
</head>
<body>
    <h1>Edit Product</h1>
    <a href="index.php">&larr; Back to list</a>

    <?php if ($error): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST">
        <label for="name">Product Name</label>
        <input type="text" id="name" name="name"
               value="<?= htmlspecialchars($_POST['name'] ?? $product['name']) ?>">

        <label for="price">Price ($)</label>
        <input type="number" id="price" name="price" step="0.01" min="0"
               value="<?= htmlspecialchars((string) ($_POST['price'] ?? $product['price'])) ?>">

        <button type="submit">Update Product</button>
    </form>
</body>
</html>
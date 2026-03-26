<?php

declare(strict_types=1);

require 'database.php';

$id = (int) ($_GET['id'] ?? 0);

if ($id > 0) {
    $stmt = $db->prepare('DELETE FROM products WHERE id = ?');
    $stmt->execute([$id]);
}

header('Location: index.php');
exit;
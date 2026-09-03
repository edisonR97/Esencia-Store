<?php
$pdo = new PDO('mysql:host=127.0.0.1;dbname=esencia_store;charset=utf8mb4', 'root', '');
$rows = $pdo->query('SELECT slug, source_page, image FROM products ORDER BY source_page, id')->fetchAll(PDO::FETCH_ASSOC);
file_put_contents(__DIR__.'/../storage/app/catalog/products-for-images.json', json_encode($rows, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
echo count($rows).PHP_EOL;
echo 'missing_images='.count(array_filter($rows, fn($row) => empty($row['image']))).PHP_EOL;

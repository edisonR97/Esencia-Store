<?php
$pdo = new PDO('mysql:host=127.0.0.1;dbname=esencia_store;charset=utf8mb4', 'root', '');
$update=$pdo->prepare('UPDATE products SET image=? WHERE slug=?');
$items=json_decode(file_get_contents(__DIR__.'/../storage/app/catalog/product-images.json'),true);
$count=0; foreach($items as $item){if(isset($item['generated_image'])){$update->execute([$item['generated_image'],$item['slug']]);$count++;}}
echo $count.PHP_EOL;

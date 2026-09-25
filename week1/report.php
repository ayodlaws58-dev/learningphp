<?php
echo phpversion();
$file = fopen("sales.csv", "r");

$header = fgetcsv($file, 0, ",", "\"", "\\"); // read header row
$rows = [];
$grandTotal = 0;

while (($data = fgetcsv($file, 1000, ",", "\"", "\\")) !== false) {
    [$date, $product, $qty, $price] = $data;

    $qty = (int)$qty;
    $price = (float)$price;
    $total = $qty * $price;

    $grandTotal += $total;

    $rows[] = [
        "date" => $date,
        "product" => $product,
        "quantity" => $qty,
        "price" => $price,
        "total" => $total
    ];
}

fclose($file);

echo json_encode([
    "items" => $rows,
    "grand_total" => $grandTotal
], JSON_PRETTY_PRINT);
?>

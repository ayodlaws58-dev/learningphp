<?php
declare(strict_types=1);

function loadCsv(string $path): array {
    $rows = [];

    if (!file_exists($path)) {
        return $rows; // guard clause
    }

    $handle = fopen($path, 'r');

    // Correct delimiter + correct escape character for PHP 8.3+
    while (($data = fgetcsv($handle, 0, ",", "\"", "\"")) !== false) {

        // Skip empty lines
        if ($data === [null] || $data === false) {
            continue;
        }

        // Trim whitespace from each column
        $data = array_map('trim', $data);

        // Remove BOM from first column if present
        if (isset($data[0])) {
            $data[0] = preg_replace('/^\xEF\xBB\xBF/', '', $data[0]);
        }

        $rows[] = $data;
    }

    fclose($handle);
    return $rows;
}

function parseSales(array $rows): array {
    return array_map(
        fn(array $row) => [
            'date'   => $row[0],
            'item'   => $row[1],
            'qty'    => (int)$row[2],
            'price'  => (float) str_replace(',', '.', $row[3]),
        ],
        $rows
    );
}

function totalRevenue(array $sales): float {
    return array_reduce(
        $sales,
        fn(float $carry, array $sale) =>
            $carry + ($sale['qty'] * $sale['price']),
        0.0
    );
}

// Usage
$rows   = loadCsv('sales.csv');
$sales  = parseSales($rows);
$total  = totalRevenue($sales);

echo "Total revenue: $total\n";
?>

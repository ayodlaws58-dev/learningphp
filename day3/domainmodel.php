<?php
declare(strict_types=1);

enum Status: string {
    case Draft = 'draft';
    case Paid = 'paid';
    case Cancelled = 'cancelled';
}

class LineItem {
    public function __construct(
        public string $product,
        public int $quantity,
        public float $price
    ) {
        if ($quantity <= 0) {
            throw new InvalidQuantityException("Quantity must be positive");
        }
    }

    public function total(): float {
        return $this->quantity * $this->price;
    }
}

class InvalidQuantityException extends \Exception {}

class Invoice {
    private array $items = [];
    public Status $status = Status::Draft;

    public function __construct(
        public readonly string $customer
    ) {}

    public function addItem(LineItem $item): void {
        $this->items[] = $item;
    }

    public function subtotal(): float {
        return array_sum(array_map(fn($i) => $i->total(), $this->items));
    }

    public function markPaid(): void {
        $this->status = Status::Paid;
    }
}

// Usage
try {
    $invoice = new Invoice("Ayodeji");
    $invoice->addItem(new LineItem("Keyboard", 3, 49.99));
    $invoice->addItem(new LineItem("Mouse", 5, 19.99));

    echo "Subtotal: " . $invoice->subtotal() . PHP_EOL;
    $invoice->markPaid();
    echo "Status: " . $invoice->status->value . PHP_EOL;
} catch (InvalidQuantityException $e) {
    echo "Error: " . $e->getMessage();
}

<?php
interface LoggerInterface {
    public function log(string $message): void;
}

class FileLogger implements LoggerInterface {
    public function log(string $message): void {
        file_put_contents('app.log', $message.PHP_EOL, FILE_APPEND);
    }
}

class DatabaseLogger implements LoggerInterface {
    public function log(string $message): void {
        // Imagine saving to DB
        echo "Saved to DB: $message";
    }
}

class UserService {
    private $logger;
    public function __construct(LoggerInterface $logger) {
        $this->logger = $logger;
    }
    public function createUser(string $name) {
        // business logic...
        $this->logger->log("User $name created.");
    }
}

// Swap loggers easily:
$service = new UserService(new FileLogger());
$service->createUser("Ayodeji");

$service = new UserService(new DatabaseLogger());
$service->createUser("Ayodeji");
?>
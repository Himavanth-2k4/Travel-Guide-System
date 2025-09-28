<?php
/**
 * Database Connection & Query Helper
 * Provides a clean wrapper around mysqli with prepared statement helpers.
 */
class Database {
    private static ?mysqli $instance = null;

    public static function getConnection(): mysqli {
        if (self::$instance === null) {
            $host = getenv('MYSQL_HOST') ?: 'localhost';
            $user = getenv('MYSQL_USER') ?: 'root';
            $pass = getenv('MYSQL_PASSWORD') !== false ? getenv('MYSQL_PASSWORD') : '';
            $db   = getenv('MYSQL_DATABASE') ?: 'travel_guide';

            $conn = new mysqli($host, $user, $pass);
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $conn->query("CREATE DATABASE IF NOT EXISTS `$db`");
            $conn->select_db($db);
            $conn->set_charset("utf8mb4");

            self::$instance = $conn;
        }

        return self::$instance;
    }

    /**
     * Executes a parameterized query and returns the prepared statement.
     */
    public static function execute(string $sql, string $types = "", array $params = []): mysqli_stmt {
        $conn = self::getConnection();
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new RuntimeException("Prepare failed: " . $conn->error);
        }

        if ($types && !empty($params)) {
            $stmt->bind_param($types, ...$params);
        }

        if (!$stmt->execute()) {
            throw new RuntimeException("Execute failed: " . $stmt->error);
        }

        return $stmt;
    }
}
?>

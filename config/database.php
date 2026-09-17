<?php
/**
 * NCB Website - Database Configuration
 * Edit these constants to match your server.
 */

define('DB_HOST', 'localhost');
define('DB_NAME', 'ncb_website');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

/**
 * PDO Connection - Single instance
 */
function getDB() {
    static $pdo = null;
    if ($pdo === null) {
        try {
            $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE   => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES     => false,
            ];
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            // In production, log this and show a friendly error
            die('Database connection failed. Please check your configuration.');
        }
    }
    return $pdo;
}

<?php
$host = 'localhost';
$user = 'root';
$pass = ''; // Default XAMPP password is empty

try {
    // 1. Connect without database
    $pdo = new PDO("mysql:host=$host", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Connected to MySQL server.<br>";

    // 2. Create database
    $pdo->exec("CREATE DATABASE IF NOT EXISTS phppro");
    echo "Database 'phppro' created or already exists.<br>";

    // 3. Select database
    $pdo->exec("USE phppro");

    // 4. Read and execute schema
    $schema = file_get_contents(__DIR__ . '/sql/schema.sql');
    
    // Split by semicolon, but be careful with triggers/procedures if any (none here)
    $queries = explode(';', $schema);
    
    foreach ($queries as $query) {
        $query = trim($query);
        if ($query) {
            $pdo->exec($query);
        }
    }

    echo "Schema imported successfully!<br>";
    echo "<strong>Setup Complete.</strong> You can now use the application.";

} catch (PDOException $e) {
    die("Database setup failed: " . $e->getMessage());
}
?>

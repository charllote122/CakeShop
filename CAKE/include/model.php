<?php
$dsn = "mysql:host=127.0.0.1;dbname=cake";
$dbusername='root';
$dbpassword="";
try {
 $pdo = new PDO($dsn,$dbusername,$dbpassword);
 $pdo -> setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    echo "connection failed: ".$e ->getMessage(); 
}

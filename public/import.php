<?php
$host = "127.0.0.1"; 
$user = "root";       
$pass = "";          
$db   = "vodicko";

$conn = new mysqli($host, $user, $pass);

if ($conn->connect_error) {
    die("Greška u konekciji: " . $conn->connect_error);
}

$sql = "CREATE DATABASE IF NOT EXISTS `$db` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
if ($conn->query($sql) === TRUE) {
    echo "Baza '$db' kreirana ili već postoji.<br>";
} else {
    die("Greška prilikom kreiranja baze: " . $conn->error);
}

$conn->select_db($db);

$sqlDump = file_get_contents("vodicko_dump.sql"); 
if ($conn->multi_query($sqlDump)) {
    echo "SQL import uspešan.";
} else {
    echo "Greška prilikom importa: " . $conn->error;
}
?>

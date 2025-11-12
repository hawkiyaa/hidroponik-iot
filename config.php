<?php
$host = "localhost";
$user = "root";        // default Laragon
$pass = "";            // default Laragon
$db   = "UTS_IOT";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>
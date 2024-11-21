<?php
$host = 'localhost';     // Host database
$dbname = 'user_db';  // Nama database Anda
$username = 'root';      // Username database Anda
$password = '';          // Password database Anda, jika ada

try {
    // Menghubungkan ke database menggunakan PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Mengatur mode kesalahan PDO ke exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Menangani error jika koneksi gagal
    echo 'Connection failed: ' . $e->getMessage();
}

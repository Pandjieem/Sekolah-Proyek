<?php
// Fungsi untuk membaca data admin dari file JSON
function getAdminData() {
    $file = 'data/admin.json';
    if (!file_exists($file)) {
        die("File $file tidak ditemukan!");
    }

    $jsonData = file_get_contents($file);
    $data = json_decode($jsonData, true);

    if ($data === null) {
        die("Data JSON tidak valid!");
    }

    return $data;
}

// Proses login
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil input dari form
    $username = htmlspecialchars($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    // Baca data admin dari file JSON
    $adminData = getAdminData();

    // Cek apakah username dan password sesuai
    if (isset($adminData['admin']) &&
        $adminData['admin']['username'] === $username &&
        password_verify($password, $adminData['admin']['password'])) {
        // Login berhasil
        session_start();
        $_SESSION['is_admin'] = true;
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Username atau password salah!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 50px;
        }
        form {
            max-width: 400px;
            margin: auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        .error {
            color: red;
            font-weight: bold;
            text-align: center;
        }
    </style>
</head>
<body>
    <h1 style="text-align:center;">Login Admin</h1>
    <form method="post" action="">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
        
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        
        <button type="submit">Login</button>
    </form>
    <?php if ($error): ?>
        <p class="error"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>
</body>
</html>

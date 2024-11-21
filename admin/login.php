<?php
session_start();
include('includes/db.php');  // Pastikan file db.php sudah terhubung dengan benar

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query untuk mencari user berdasarkan username
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username LIMIT 1");
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Cek apakah username ditemukan dan password cocok
    if ($user) {
        // Langsung membandingkan password plaintext
        if ($password == $user['password']) {
            // Jika password cocok, login berhasil
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header('Location: dashboard.php');
            exit();
        } else {
            $error = "Invalid password.";  // Jika password salah
        }
    } else {
        $error = "Username not found.";  // Jika username tidak ditemukan
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Link to Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <style>
        body {
            background-color: #f0f9f0;
            /* Light green background */
        }

        .card {
            border: 1px solid #28a745;
            /* Green border for card */
            height: 600px;
        }

        .card-title {
            color: #28a745;
            /* Green title */
        }

        .btn-primary {
            background-color: #28a745;
            /* Green button */
            border-color: #28a745;
        }

        .btn-primary:hover {
            background-color: #218838;
            /* Darker green on hover */
            border-color: #1e7e34;
        }

        .alert-danger {
            background-color: #f8d7da;
            /* Light red alert for errors */
            color: #721c24;
        }
    </style>
</head>

<body class="d-flex align-items-center justify-content-center min-vh-100">

    <div class="card shadow-lg" style="max-width: 400px; width: 100%;">
        <div class="card-body p-4">
            <h5 class="card-title text-center mb-4 mt-5">Login Admin</h5>

            <?php if (isset($error)) {
                echo "<div class='alert alert-danger'>$error</div>";
            } ?>

            <form method="POST" action="" class="mt-5">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" id="username" name="username" class="form-control" required>
                </div>
                <div class="mb-4">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary w-100 py-2">Login</button>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz4fnFO9gybBqkAPU8eoyhdf7p5ru4tZgQlJ6XbdwQFj8gC52yJbR9V6gD" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-pzjw8f+ua7Kw1TIq0pA+L4t8/+tWjVtusHxyOyyMl5ZFmVVk6pG6l1H9C8ht+5sd" crossorigin="anonymous"></script>
</body>

</html>
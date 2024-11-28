<?php
session_start();

// Jika pengguna belum login, arahkan mereka ke halaman login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Koneksi ke database
$conn = new mysqli("localhost", "root", "", "user_db");
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Fungsi untuk menghindari pengiriman berulang
function redirect_to_dashboard()
{
    header("Location: dashboard.php");
    exit();
}

// Menambahkan atau memperbarui cover image
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_cover'])) {
    $title = htmlspecialchars($_POST['title']);
    $image = $_FILES['cover_image']['name'];
    $target = "../assets/cover/" . basename($image);

    // Cek apakah folder img ada
    if (!is_dir('../assets/cover/')) {
        echo "Folder gambar tidak ditemukan.";
    } else {
        // Validasi tipe file gambar
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $image_type = $_FILES['cover_image']['type'];
        if (!in_array($image_type, $allowed_types)) {
            echo "Hanya file gambar yang diperbolehkan (JPEG, PNG, GIF).";
            exit();
        }

        // Jika ada gambar baru, unggah gambar baru
        if (move_uploaded_file($_FILES['cover_image']['tmp_name'], $target)) {
            // Cek apakah cover image sudah ada di database
            $sql = "SELECT * FROM cover_image WHERE id = 1";  // Hanya satu record untuk cover
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // Jika sudah ada, update cover image
                $row = $result->fetch_assoc();
                if (file_exists($row['image_path'])) {
                    unlink($row['image_path']); // Hapus gambar lama
                }

                $sql = "UPDATE cover_image SET title='$title', image_path='$target' WHERE id=1";
            } else {
                // Jika belum ada, insert baru
                $sql = "INSERT INTO cover_image (title, image_path) VALUES ('$title', '$target')";
            }

            if ($conn->query($sql)) {
                redirect_to_dashboard(); // Redirect ke halaman dashboard setelah update
            } else {
                echo "Gagal memperbarui cover image.";
            }
        } else {
            echo "Gagal mengunggah gambar.";
        }
    }
}

// Ambil data cover image
$cover = $conn->query("SELECT * FROM cover_image WHERE id = 1")->fetch_assoc();

// Tambah Gambar
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_image'])) {
    $title = htmlspecialchars($_POST['title']);
    $description = htmlspecialchars($_POST['description']);
    $instagram = htmlspecialchars($_POST['instagram']);
    $image = $_FILES['image']['name'];
    $target = "../assets/img/" . basename($image);
    $img = basename($image);

    // Cek apakah folder img ada
    if (!is_dir('../assets/img/')) {
        echo "Folder gambar tidak ditemukan.";
    } else {
        // Validasi tipe file gambar
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $image_type = $_FILES['image']['type'];
        if (!in_array($image_type, $allowed_types)) {
            echo "Hanya file gambar yang diperbolehkan (JPEG, PNG, GIF).";
            exit();
        }

        if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            $sql = "INSERT INTO images (title, description, instagram, image_path, img_name) 
                    VALUES ('$title', '$description', '$instagram', '$target', '$img')";
            $conn->query($sql);
            redirect_to_dashboard(); // Redirect ke halaman dashboard setelah menambah gambar
        } else {
            echo "Gagal mengunggah gambar.";
        }
    }
}

// Update Gambar
if (isset($_POST['update_image']) && isset($_POST['image_id'])) {
    $image_id = $_POST['image_id'];
    $title = htmlspecialchars($_POST['title']);
    $description = htmlspecialchars($_POST['description']);
    $instagram = htmlspecialchars($_POST['instagram']);

    // Ambil path gambar lama
    $sql = "SELECT image_path FROM images WHERE id=$image_id";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $old_image = $row['image_path'];

    // Jika ada gambar baru
    if ($_FILES['image']['name']) {
        // Hapus gambar lama dari server
        if (file_exists($old_image)) {
            unlink($old_image);
        }

        // Upload gambar baru
        $image = $_FILES['image']['name'];
        $target = "../assets/img/" . basename($image);

        // Validasi tipe file gambar
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $image_type = $_FILES['image']['type'];
        if (!in_array($image_type, $allowed_types)) {
            echo "Hanya file gambar yang diperbolehkan (JPEG, PNG, GIF).";
            exit();
        }

        if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            $image_path = $target;
        } else {
            echo "Gagal mengunggah gambar.";
            exit();
        }
    } else {
        // Jika tidak ada gambar baru, gunakan gambar lama
        $image_path = $old_image;
    }

    // Update data gambar di database
    $img_name = basename($image);
    $sql = "UPDATE images SET title='$title', description='$description', instagram='$instagram', image_path='$image_path', img_name='$img_name' WHERE id=$image_id";
    if ($conn->query($sql)) {
        redirect_to_dashboard();
    } else {
        echo "Gagal memperbarui gambar.";
    }
}

// Hapus Gambar
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "SELECT image_path FROM images WHERE id=$id";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();

    if (file_exists($row['image_path'])) {
        unlink($row['image_path']);
    }

    $conn->query("DELETE FROM images WHERE id=$id");
    redirect_to_dashboard();
}

// Ambil semua gambar
$images = $conn->query("SELECT * FROM images");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>

<body>
    <div id="main">
        <span id="openBtn" style="font-size:40px;cursor:pointer;color: rgb(0, 0, 0);" onclick="openNav()">&#9776;</span>
        <div class="d-flex">
            <div id="mySidenav" class="sidenav">
                <a href="#" class="sidebar-link py-3 px-4">Beranda</a>
                <a href="#profile-sekolah" class="sidebar-link py-3 px-4">Modifikasi Foto Ke Galeri</a>
            </div>

            <!-- Content Area -->
            <div class="content flex-grow-1">
                <div class="cover-section">
                    <div class="container">
                        <h1 class="display-4 font-weight-bold">Halaman Admin</h1>
                        <p class="lead">Memodifikasi Halaman Utama</p>
                        <a href="logout.php" class="btn btn-lg font-weight-bold"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
                    </div>
                </div>

                <!-- Form untuk Update Cover Image -->
                <section id="profile-sekolah" class="py-5">
                    <div class="container">
                        <h2 class="text-center mb-4">Update Cover Image</h2>
                        <form action="dashboard.php" method="POST" enctype="multipart/form-data" class="mb-4">
                            <div class="mb-3">
                                <label for="title" class="form-label">Tanggal Cover Image</label>
                                <input type="date" name="title" id="title" class="form-control" value="<?= $cover['title'] ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="cover_image" class="form-label">Pilih Gambar Baru</label>
                                <input type="file" name="cover_image" id="cover_image" class="form-control" required>
                            </div>
                            <button type="submit" name="update_cover" class="btn btn-success">Update Cover Image</button>
                        </form>
                    </div>
                </section>

                <!-- Menampilkan Cover Image -->
                <section id="current-cover" class="py-5">
                    <div class="container text-center">
                        <h2 class="mb-4">Cover Image Saat Ini</h2>
                        <img src="<?= $cover['image_path'] ?>" alt="Cover Image" class="img-fluid" style="max-height: 400px; object-fit: cover;">
                    </div>
                </section>

                <!-- Form Tambah Gambar -->
                <section id="profile-sekolah" class="py-5">
                    <div class="container">
                        <h2 class="text-center mb-4">Tambahkan Gambar ke Galeri</h2>
                        <form action="dashboard.php" method="POST" enctype="multipart/form-data" class="mb-4">
                            <div class="mb-3">
                                <label for="title" class="form-label">Judul Gambar</label>
                                <input type="text" name="title" id="title" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Deskripsi Gambar</label>
                                <input type="text" name="description" id="description" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="instagram" class="form-label">Link Instagram</label>
                                <input type="text" name="instagram" id="instagram" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="image" class="form-label">Pilih Gambar</label>
                                <input type="file" name="image" id="image" class="form-control" required>
                            </div>
                            <button type="submit" name="add_image" class="btn btn-primary">Tambah Gambar</button>
                        </form>
                    </div>
                </section>


                <!-- Galeri Gambar -->
                <section id="galeri" class="py-5">
                    <div class="container">
                        <h2 class="text-center mb-4">Galeri Gambar</h2>
                        <div class="row">
                            <?php while ($row = $images->fetch_assoc()): ?>
                                <div class="col-md-4 mb-3">
                                    <div class="card">
                                        <img src="<?= $row['image_path'] ?>" class="card-img-top" alt="<?= $row['title'] ?>">
                                        <div class="card-body">
                                            <h5 class="card-title"><?= $row['title'] ?></h5>
                                            <p class="card-text"><?= $row['description'] ?></p>
                                            <a href="<?= $row['instagram'] ?>" target="_blank" class="btn btn-primary">Lihat Instagram</a>
                                            <a href="dashboard.php?delete=<?= $row['id'] ?>" class="btn btn-danger"
                                                onclick="return confirm('Hapus gambar ini?')">Hapus</a>
                                            <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#updateModal<?= $row['id'] ?>">Ganti Gambar</button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal untuk Mengubah Gambar -->
                                <div class="modal fade" id="updateModal<?= $row['id'] ?>" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="updateModalLabel">Ganti Gambar</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="dashboard.php" method="POST" enctype="multipart/form-data">
                                                    <input type="hidden" name="image_id" value="<?= $row['id'] ?>">
                                                    <div class="mb-3">
                                                        <label for="title" class="form-label">Judul Gambar</label>
                                                        <input type="text" name="title" id="title" class="form-control" value="<?= $row['title'] ?>" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="description" class="form-label">Deskripsi Gambar</label>
                                                        <input type="text" name="description" id="description" class="form-control" value="<?= $row['description'] ?>" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="instagram" class="form-label">Link Instagram</label>
                                                        <input type="text" name="instagram" id="instagram" class="form-control" value="<?= $row['instagram'] ?>">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="image" class="form-label">Pilih Gambar Baru</label>
                                                        <input type="file" name="image" id="image" class="form-control">
                                                    </div>
                                                    <button type="submit" name="update_image" class="btn btn-success">Update Gambar</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
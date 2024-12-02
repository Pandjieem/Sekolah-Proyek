<?php
require_once '../config.php';
require_once '../includes/db.php';

// Inisialisasi Session dan cek login
$session = new Session();
if (!$session->isLoggedIn()) {
    $session->redirect('../login.php');
}

// Inisialisasi Database dan ImageHandler
$db = new Database();
$imageHandler = new ImageHandler($db);


// Get all images
$images = $imageHandler->getAllImages();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galery</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>

<body>
    <div id="main">
    <!--TOMBOL LOGOUT DAN KEMBALI -->
    <a href="../logout.php" class="btnpages btn btn-lg font-weight-bold logout"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
    <a href="../dashboard.php" class="btnpages btn btn-lg font-weight-bold logout"><i class="fa-solid fa-right-from-bracket"></i> Kembali</a>
        <div class="d-flex">
            <div id="mySidenav" class="sidenav">
                <a href="#" class="sidebar-link py-3 px-4">Beranda</a>
                <a href="#" class="sidebar-link py-3 px-4">Modifikasi Foto Ke Galeri</a>
            </div>

            <!-- Content Area -->
            <div class="content flex-grow-1">
                <!-- Tampilkan pesan error jika ada -->
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?= $error ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <!-- Form Tambah Gambar -->
                <section id="profile-sekolah" class="py-5">
                    <div class="container">
                        <h2 class="text-center mb-4">Tambahkan Gambar ke Galeri</h2>
                        <form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST" enctype="multipart/form-data" class="mb-4">
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
                                <input type="file" name="image" id="image" class="form-control" accept="image/jpeg,image/png,image/gif" required>
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
                                        <img src="<?= htmlspecialchars($row['image_path']) ?>" class="card-img-top" alt="<?= htmlspecialchars($row['title']) ?>">
                                        <div class="card-body">
                                            <h5 class="card-title"><?= htmlspecialchars($row['title']) ?></h5>
                                            <p class="card-text"><?= htmlspecialchars($row['description']) ?></p>
                                            <a href="<?= htmlspecialchars($row['instagram']) ?>" target="_blank" class="btn btn-primary">Lihat Instagram</a>
                                            <a href="?delete=<?= $row['id'] ?>" class="btn btn-danger" onclick="return confirm('Hapus gambar ini?')">Hapus</a>
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
                                                <form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST" enctype="multipart/form-data">
                                                    <input type="hidden" name="image_id" value="<?= $row['id'] ?>">
                                                    <div class="mb-3">
                                                        <label for="title" class="form-label">Judul Gambar</label>
                                                        <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($row['title']) ?>" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="description" class="form-label">Deskripsi Gambar</label>
                                                        <input type="text" name="description" class="form-control" value="<?= htmlspecialchars($row['description']) ?>" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="instagram" class="form-label">Link Instagram</label>
                                                        <input type="text" name="instagram" class="form-control" value="<?= htmlspecialchars($row['instagram']) ?>">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="image" class="form-label">Pilih Gambar Baru</label>
                                                        <input type="file" name="image" class="form-control" accept="image/jpeg,image/png,image/gif">
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
    <script src="../script.js"></script>
</body>
</html>

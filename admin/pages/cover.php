<?php
require_once '../config.php';
require_once '../includes/db.php';

$session = new Session();
if (!$session->isLoggedIn()) {
    $session->redirect('login.php');
}

$db = new Database();
$imageHandler = new ImageHandler($db);

// Ambil data cover dan images
$cover = $imageHandler->getCover();
$images = $imageHandler->getAllImages();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
    
<body>
    <div id="main">
        <!-- TOMBOL LOGOUT DAN KEMBALI -->
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

                <!-- Form untuk Update Cover Image -->
                <section id="profile-sekolah" class="py-5">
                    <div class="container">
                        <h2 class="text-center mb-4">Update Cover Image</h2>
                        <form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST" enctype="multipart/form-data" class="mb-4">
                            <div class="mb-3">
                                <label for="title" class="form-label">Tanggal Cover Image</label>
                                <input type="date" 
                                       name="title" 
                                       id="title" 
                                       class="form-control" 
                                       value="<?= $cover ? $cover['title'] : '' ?>" 
                                       required>
                            </div>
                            <div class="mb-3">
                                <label for="cover_image" class="form-label">Pilih Gambar Baru</label>
                                <input type="file" 
                                       name="cover_image" 
                                       id="cover_image" 
                                       class="form-control" 
                                       accept="image/jpeg,image/png,image/gif"
                                       required>
                            </div>
                            <button type="submit" name="update_cover" class="btn btn-success">Update Cover Image</button>
                        </form>
                    </div>
                </section>

                <!-- Menampilkan Cover Image -->
                <section id="current-cover" class="py-5">
                    <div class="container text-center">
                        <h2 class="mb-4">Cover Image Saat Ini</h2>
                        <?php if ($cover && isset($cover['image_path'])): ?>
                            <img src="<?= htmlspecialchars($cover['image_path']) ?>" 
                                 alt="Cover Image" 
                                 class="img-fluid" 
                                 style="max-height: 400px; object-fit: cover;">
                        <?php else: ?>
                            <p>Belum ada cover image</p>
                        <?php endif; ?>
                    </div>
                </section>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../script.js"></script>
</body>

</html>

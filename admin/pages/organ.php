<?php
require_once '../config.php';
require_once '../includes/db.php';

// Inisialisasi Session dan cek login
$session = new Session();
if (!$session->isLoggedIn()) {
    $session->redirect('../login.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_organ'])) {
    try {
        $organId = (int)$_POST['organ_id'];
        if ($imageHandler->updateOrgan($_POST, $_FILES, $organId)) {
            $_SESSION['success_message'] = "Data organ berhasil diperbarui!";
            $session->redirect($_SERVER['PHP_SELF']);
        } else {
            $error = "Gagal mengupdate data organ";
        }
    } catch (Exception $e) {
        $_SESSION['error_message'] = $e->getMessage();
        $session->redirect($_SERVER['PHP_SELF']);
    }
}

// Inisialisasi Database dan ImageHandler
$db = new Database();
$imageHandler = new ImageHandler($db);

// Get organ data
$pembina = $imageHandler->getOrgan(1);
$pengurus = $imageHandler->getOrgan(2);
$pengawas = $imageHandler->getOrgan(3);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Organ Yayasan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<style>
    .card img{
        max-height: 540px;
    }
</style>

<body>
    <div id="main">
        <!--TOMBOL LOGOUT DAN KEMBALI -->
        <a href="../logout.php" class="btnpages btn btn-lg font-weight-bold logout"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
        <a href="../dashboard.php" class="btnpages btn btn-lg font-weight-bold logout"><i class="fa-solid fa-right-from-bracket"></i> Kembali</a>
        <div class="d-flex">
            <div id="mySidenav" class="sidenav">
                <a href="#" class="sidebar-link py-3 px-4">Beranda</a>
                <a href="#" class="sidebar-link py-3 px-4">Modifikasi Organ Yayasan</a>
            </div>

            <!-- Content Area -->
            <div class="content flex-grow-1">
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?= $error ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <!-- Tampilan Organ Yayasan Saat Ini -->
                <section id="current-organ" class="py-5">
                    <div class="container">
                        <h2 class="text-center mb-4">Organ Yayasan Saat Ini</h2>
                        <div class="row">
                            <!-- Pembina -->
                            <div class="col-md-4 mb-4">
                                <div class="card">
                                    <img src="<?= htmlspecialchars($pembina['image_path'] ?? 'assets/organ/default.jpg') ?>"
                                        class="card-img-top"
                                        alt="Pembina">
                                    <div class="card-body">
                                        <h5 class="card-title">Pembina</h5>
                                        <p class="card-text"><?= htmlspecialchars($pembina['title'] ?? 'Belum diatur') ?></p>
                                        <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#updateModal1">
                                            Update Pembina
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Pengurus -->
                            <div class="col-md-4 mb-4">
                                <div class="card">
                                    <img src="<?= htmlspecialchars($pengurus['image_path'] ?? 'assets/organ/default.jpg') ?>"
                                        class="card-img-top"
                                        alt="Pengurus">
                                    <div class="card-body">
                                        <h5 class="card-title">Pengurus</h5>
                                        <p class="card-text"><?= htmlspecialchars($pengurus['title'] ?? 'Belum diatur') ?></p>
                                        <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#updateModal2">
                                            Update Pengurus
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Pengawas -->
                            <div class="col-md-4 mb-4">
                                <div class="card">
                                    <img src="<?= htmlspecialchars($pengawas['image_path'] ?? 'assets/organ/default.jpg') ?>"
                                        class="card-img-top"
                                        alt="Pengawas">
                                    <div class="card-body">
                                        <h5 class="card-title">Pengawas</h5>
                                        <p class="card-text"><?= htmlspecialchars($pengawas['title'] ?? 'Belum diatur') ?></p>
                                        <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#updateModal3">
                                            Update Pengawas
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Modal untuk Update Pembina -->
                <div class="modal fade" id="updateModal1" tabindex="-1" aria-labelledby="updateModalLabel1" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="updateModalLabel1">Update Pembina</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST" enctype="multipart/form-data">
                                    <input type="hidden" name="organ_id" value="1">
                                    <div class="mb-3">
                                        <label for="title" class="form-label">Nama Pembina</label>
                                        <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($pembina['title'] ?? '') ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="organ_image" class="form-label">Foto Pembina</label>
                                        <input type="file" name="organ_image" class="form-control" accept="image/jpeg,image/png,image/gif" required>
                                    </div>
                                    <button type="submit" name="update_organ" class="btn btn-success">Update Pembina</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal untuk Update Pengurus -->
                <div class="modal fade" id="updateModal2" tabindex="-1" aria-labelledby="updateModalLabel2" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="updateModalLabel1">Update Pengurus</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST" enctype="multipart/form-data">
                                    <input type="hidden" name="organ_id" value="2">
                                    <div class="mb-3">
                                        <label for="title" class="form-label">Nama Pengurus</label>
                                        <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($pengurus['title'] ?? '') ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="organ_image" class="form-label">Foto Pengurus</label>
                                        <input type="file" name="organ_image" class="form-control" accept="image/jpeg,image/png,image/gif" required>
                                    </div>
                                    <button type="submit" name="update_organ" class="btn btn-success">Update Pengurus</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal untuk Update Pengawas -->
                <div class="modal fade" id="updateModal3" tabindex="-1" aria-labelledby="updateModalLabel3" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="updateModalLabel3">Update Pengawas</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST" enctype="multipart/form-data">
                                    <input type="hidden" name="organ_id" value="3">
                                    <div class="mb-3">
                                        <label for="title" class="form-label">Nama Pengawas</label>
                                        <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($pengawas['title'] ?? '') ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="organ_image" class="form-label">Foto Pengawas</label>
                                        <input type="file" name="organ_image" class="form-control" accept="image/jpeg,image/png,image/gif" required>
                                    </div>
                                    <button type="submit" name="update_organ" class="btn btn-success">Update Pengawas</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../script.js"></script>
</body>

</html>
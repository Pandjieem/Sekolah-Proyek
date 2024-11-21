<?php
session_start();

// Jika pengguna belum login, arahkan mereka ke halaman login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
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
    <style>
        .image-preview {
            width: 100%;
            height: auto;
            object-fit: cover;
            border: 1px solid #ddd;
            margin-left: 20px;
        }

        .btn {
            background-color: green;
            color: white;
        }
    </style>
</head>

<body>
    <div id="main">
        <span id="openBtn" style="font-size:30px;cursor:pointer;color: rgb(0, 0, 0);" onclick="openNav()">&#9776;</span>

        <!-- Loading Animation -->
        <div id="loading" class="loading">
            <div class="loading-bar"></div>
        </div>

        <div class="d-flex">
            <div id="mySidenav" class="sidenav">
                <a href="#" class="sidebar-link py-3 px-4">Beranda</a>
                <a href="#profile-sekolah" class="sidebar-link  py-3 px-4">Modifikasi Foto Ke Galeri</a>
                <a href="#siswa" class="sidebar-link  py-3 px-4">Ganti Foto Cover Website</a>
                <a href="#galeri" class="sidebar-link  py-3 px-4"></a>
            </div>

            <!-- Content Area -->
            <div class="content flex-grow-1">
                <div class="cover-section">
                    <div class="container">
                        <h1 class="display-4 font-weight-bold">Halaman Admin</h1>
                        <p class="lead">Memodifikasi Halaman Utama</p>
                        <a href="#profile-sekolah" class="btn btn-lg font-weight-bold">Modifikasi Foto</a>
                    </div>
                </div>
                <!-- Profil Sekolah Section -->
                <section id="profile-sekolah" class="profile-sekolah py-5">
                    <div class="container">
                        <h2 class="text-center mb-4">Tambahkan Gambar ke Halaman Utama</h2>
                        <div class="row">
                            <div class="text-justify">
                                <div class="container mb-5">
                                    <div class="row justify-content-center">
                                        <div class="col-md-12">
                                            <div class="card shadow-lg">
                                                <div class="card-body p-4">
                                                    <h5 class="card-title text-center mb-4">Tambahkan Gambar Pada Galery Foto</h5>
                                                    <!-- Image Upload Form -->
                                                    <form>
                                                        <div class="mb-3">
                                                            <label for="imageInput" class="form-label">Pilih Gambar</label>
                                                            <input type="file" class="form-control" id="imageInput" accept="image/*" onchange="previewImage(event)">
                                                        </div>
                                                        <div class="mb-3 d-flex justify-content-between align-items-center">
                                                            <!-- Image Preview -->
                                                            <img id="imagePreview" class="image-preview" src="" alt="Image Preview" style="display: none;">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="" class="form-label">Masukkan Judul Gambar</label>
                                                            <input type="text" name="" id="" class="form-control">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="" class="form-label">Masukkan Deksripsi Gambar</label>
                                                            <input type="text" name="" id="" class="form-control">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="" class="form-label">Masukkan Link Instagram</label>
                                                            <input type="text" name="" id="" class="form-control">
                                                        </div>
                                                        <div class="mb-3">
                                                            <button class="form-control mt-2 btn btn-success">Tambahkan Foto</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <h2 class="text-center mb-4 mt-5">Modifikasi Cover Foto</h2>
                            <div class="row">
                                <div class="text-justify">
                                    <div class="container mb-5">
                                        <div class="row justify-content-center">
                                            <div class="col-md-12">
                                                <div class="card shadow-lg">
                                                    <div class="card-body p-4">
                                                        <h5 class="card-title text-center mb-4">Ubah Cover Foto Background</h5>
                                                        <!-- Image Upload Form -->
                                                        <form>
                                                            <div class="mb-3">
                                                                <label for="imageInput" class="form-label">Pilih Gambar</label>
                                                                <input type="file" class="form-control" id="imageInput" accept="image/*" onchange="previewImage(event)">
                                                            </div>
                                                            <div class="mb-3">
                                                                <button class="form-control mt-2 btn btn-success">Ganti Cover Foto</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <h2 class="text-center mb-4 mt-5">Ubah Foto di Galery</h2>
                            <div class="row">
                                <div class="text-justify">
                                    <div class="container">
                                        <div class="row justify-content-center">
                                            <div class="col-md-12">
                                                <div class="card shadow-lg">
                                                    <div class="card-body p-4">
                                                        <!-- Slideshow container -->
                                                        <div class="slideshow-container">

                                                            <!-- Full-width images with number and caption text -->
                                                            <div class="mySlides">
                                                                <div class="numbertext">1 / 3</div>
                                                                <img src="gambar.jpg" style="width:100%">
                                                                <div class="text">Caption Text</div>
                                                            </div>

                                                            <div class="mySlides">
                                                                <div class="numbertext">2 / 3</div>
                                                                <img src="gambar.jpg" style="width:100%">
                                                                <div class="text">Caption Two</div>
                                                            </div>

                                                            <div class="mySlides">
                                                                <div class="numbertext">3 / 3</div>
                                                                <img src="gambar.jpg" style="width:100%">
                                                                <div class="text">Caption Three</div>
                                                            </div>

                                                            <!-- Next and previous buttons -->
                                                            <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
                                                            <a class="next" onclick="plusSlides(1)">&#10095;</a>
                                                        </div>
                                                        <br>

                                                        <!-- The dots/circles -->
                                                        <div style="text-align:center">
                                                            <span class="dot" onclick="currentSlide(1)"></span>
                                                            <span class="dot" onclick="currentSlide(2)"></span>
                                                            <span class="dot" onclick="currentSlide(3)"></span>
                                                        </div>
                                                        <!-- Image Upload Form -->
                                                        <form>
                                                            <div class="mb-3">
                                                                <label for="imageInput" class="form-label">Pilih Gambar</label>
                                                                <input type="file" class="form-control" id="imageInput" accept="image/*" onchange="previewImage(event)">
                                                            </div>
                                                            <div class="mb-3">
                                                                <button class="form-control mt-2 btn btn-success">Ubah Foto</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                </section>

                <footer class="footer bg-black text-center text-md-start">
                    <div class="container py-4">
                        <div class="row">
                            <!-- Section 1: Contact Info -->
                            <div class="col-md-4 mb-3">
                                <h5>Hubungi Kami</h5>
                                <ul class="list-unstyled">
                                    <li><i class="fas fa-phone"></i> +62 81266841237</li>
                                    <li><i class="fas fa-envelope"></i> info@example.com</li>
                                    <li><i class="fas fa-map-marker-alt"></i>Jl. Paya Bakung, Dusun VII Desa No.24, Kec. Sunggal, Kabupaten Deli Serdang, Sumatera Utara 20351</li>
                                </ul>
                            </div>
                
                            <!-- Section 2: Google Map -->
                            <div class="col-md-4 mb-3">
                                <h5>Lokasi Kami</h5>
                                <div class="map-container">
                                    <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d15927.691931491338!2d98.5520034!3d3.6051062!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x30312941ba62814f%3A0x29a9edb53d498e25!2sYPS%20Miftahul%20Falah%20Diski!5e0!3m2!1sid!2sid!4v1732157735641!5m2!1sid!2sid"     width="100%" 
                                        height="100%" 
                                        style="border:0;" 
                                        allowfullscreen="" 
                                        loading="lazy">
                                    </iframe>
                                </div>
                            </div>
                
                            <!-- Section 3: Social Media -->
                            <div class="col-md-4 mb-3">
                                <h5>Follow Us</h5>
                                <div>
                                    <a href="#" class="me-3"><i class="fab fa-facebook fa-lg"></i></a>
                                    <a href="#" class="me-3"><i class="fab fa-instagram fa-lg"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="text-center mt-3">
                            <p class="mb-0">&copy; 2024 YPS Miftahul Falah Diski. All rights reserved.</p>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="script.js"></script>

</html>
<?php
// Koneksi ke database
$conn = new mysqli("localhost", "root", "", "user_db");
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil data cover image, jika ada
$cover = $conn->query("SELECT * FROM cover_image WHERE id = 1");
if ($cover->num_rows > 0) {
    $cover = $cover->fetch_assoc();
    $background_image = $cover['image_path'];  // Ambil path gambar untuk background
} else {
    // Jika belum ada cover image, set gambar default
    $background_image = 'default-cover.jpg';  // Gambar default jika belum ada cover image
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>YPS Miftahul Falah Diski</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/style/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
</head>
<style>
    /* CSS untuk background cover section */
    .cover-section {
        background-image: url('<?php echo $background_image; ?>');
        /* Menggunakan gambar dari database */
        background-size: cover;
        background-position: center;
        height: 300px;
        /* Sesuaikan tinggi sesuai kebutuhan */
        color: white;
        display: flex;
        justify-content: center;
        align-items: center;
        text-align: center;
        height: 100vh;
    }
</style>

<body>
    <div id="main">
        <span id="openBtn" style="font-size:40px;cursor:pointer;color: rgb(0, 0, 0);" onclick="openNav()">&#9776;</span>

        <!-- Loading Animation -->
        <div id="loading" class="loading">
            <div class="loading-bar"></div>
        </div>

        <div class="d-flex">
            <div id="mySidenav" class="sidenav">
                <a href="#" class="sidebar-link py-3 px-4">Beranda</a>
                <a href="#profile-sekolah" class="sidebar-link py-3 px-4">Profil Sekolah</a>
                <a href="#galery-kegiatan" class="sidebar-link py-3 px-4">Galeri Kegiatan</a>
                <a href="#organisasi" class="sidebar-link py-3 px-4">Organisasi</a>
            </div>

            <!-- Content Area -->
            <div class="content flex-grow-1">
                <div class="cover-section text-center">
                    <div class="container">
                        <h1 class="display-4 font-weight-bold">YPS Miftahul Falah Diski</h1>
                        <p class="lead">Menjadi pusat pendidikan Anak Cerdas & Soleh</p>
                        <a href="#profile-sekolah" class="btn btn-lg font-weight-bold">TENTANG KAMI</a>
                    </div>
                </div>

                <!-- Profil Sekolah Section -->
                <section id="profile-sekolah" class="profile-sekolah py-5">
                    <div class="container">
                        <h2 class="text-center mb-4">Visi & Misi Kami</h2>
                        <div class="row">
                            <div class="text-justify">
                                <p class="lead fade-in">Visi Kami, Menjadi Pusat Pendidikan Anak Cerdas dan Soleh, wadah
                                    pembentukan insan cendekia
                                    mukhlisin yang berilmu, beriman, bertaqwa, berperilaku islami dan berwasan
                                    lingkungan dalam pengamalan IPTEK dan IMTAQ untuk kamaslahatan umat.</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="text-justify">
                                <p class="lead fade-in">Misi Kami,</p>
                                <ol class="lead">
                                    <li>Membina kebersamaan antara guru, orang tua, masyarakat dan pemerintah untuk
                                        memajukan pendidikan di madrasah</li>
                                    <li>Melaksanakan proses pendidikan yang dinamis, kreatif dan inovatif berdasarkan
                                        syari’at islam dan perundang-undangan yang berlaku</li>
                                    <li>Menanamkan budi pekerti, sopan santun, kemandirian dan kedisiplinan warga
                                        madrasah</li>
                                    <li>Menciptakan keteladan dalam berperilaku islami dikalangan warga madrasah</li>
                                    <li>Melaksanakan pelayanan administrasi pendidikan yang cepat, tepat dan transparan</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Galeri Kegiatan Section -->
                <section id="galery-kegiatan" class="galery py-5">
                    <h2 class="text-center-galery">Galeri Kegiatan</h2>
                    <div class="d-flex justify-content-center align-items-center position-relative">
                        <span id="prevBtn" class="arrow-btn">
                            <i class="fas fa-chevron-left"></i>
                        </span>
                        <div class="card-container">
                            <div class="card-content-container d-flex">
                                <?php
                                // Tentukan folder tempat menyimpan gambar
                                $folder = "assets/img/";

                                // Pastikan folder ada
                                if (is_dir($folder)) {
                                    // Membaca daftar file dalam folder
                                    $files = scandir($folder);

                                    // Filter hanya file gambar (misalnya .jpg, .jpeg, .png)
                                    $imageFiles = array_filter($files, function ($file) use ($folder) {
                                        return preg_match('/\.(jpg|jpeg|png|gif)$/i', $file) && is_file($folder . $file);
                                    });

                                    // Menampilkan gambar dengan judul dan deskripsi yang sesuai dari database
                                    foreach ($imageFiles as $imageFile):
                                        $imagePath = $folder . $imageFile;

                                        // Query database untuk mendapatkan judul dan deskripsi berdasarkan nama file
                                        $stmt = $conn->prepare("SELECT title, description, instagram FROM images WHERE img_name = ?");
                                        $stmt->bind_param("s", $imageFile);
                                        $stmt->execute();
                                        $result = $stmt->get_result();

                                        if ($result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) {
                                                // Menampilkan setiap gambar dengan data yang relevan
                                ?>
                                                <article class="card-article">
                                                    <div class="card-image-wrapper">
                                                        <img id="img-galery" src="<?= $imagePath ?>" alt="<?= htmlspecialchars($imageFile) ?>" class="card-img">
                                                    </div>
                                                    <div class="card-description-container">
                                                        <h3 class="card-title"><?= htmlspecialchars($row['title']) ?></h3>
                                                        <p class="card-description"><?= htmlspecialchars($row['description']) ?></p>
                                                        <a class="card-link" href="<?= htmlspecialchars($row['instagram']) ?>" target="_blank">Instagram</a>
                                                    </div>
                                                </article>
                                            <?php
                                            }
                                        } else {
                                            // Jika data tidak ditemukan di database
                                            ?>
                                            <article class="card-article">
                                                <div class="card-image-wrapper">
                                                    <img id="img-galery" src="<?= $imagePath ?>" alt="<?= htmlspecialchars($imageFile) ?>" class="card-img">
                                                </div>
                                                <div class="card-description-container">
                                                    <h3 class="card-title">Judul tidak tersedia</h3>
                                                    <p class="card-description">Deskripsi tidak tersedia untuk gambar ini.</p>
                                                </div>
                                            </article>
                                <?php
                                        }
                                    endforeach;
                                } else {
                                    echo "<p class='error-message'>Folder gambar tidak ditemukan.</p>";
                                }
                                ?>
                            </div>
                        </div>
                        <span id="nextBtn" class="arrow-btn">
                            <i class="fas fa-chevron-right"></i>
                        </span>
                    </div>
                </section>


                <!-- Organisasi Section -->
                <section id="organisasi" class="profile-sekolah py-5">
                    <div class="container">
                        <h2 class="text-center mb-4">Organisasi</h2>
                        <div class="row">
                            <div class="text-justify">
                                <ol class="lead">
                                    <li>Raudhatul Athfal (RA)</li>
                                    <li>Madrasah Ibtidaiyah (MI)</li>
                                    <li>Madrasah Tsanawiyah (MTs)</li>
                                    <li>Madrasah Aliyah (MA)</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </section>

                <div class="container-karakter">
                    <div class="header-karakter center-h3">
                        <h2 id="text-karakter">Organ Yayasan</h2>
                    </div>

                    <div class="content-karakter center-h3">
                        <h3 class="center-h3">Pembina</h3>
                        <div class="p-karakter">
                            <img src="assets/img/gambar.jpg" class="img-1 gambarr">
                            <img src="assets/img/gambar.jpg" class="img-2 gambarr">
                            <img src="assets/img/gambar.jpg" class="img-3 gambarr">
                        </div>

                    </div>

                    <p class="kalimat pengawas">Pembina<br></p>
                    <p class="kalimat kepalasekolah">Kepala Sekolah</p>
                    <p class="kalimat pembina">Pembina</p>
                </div>

                <!-- Footer -->
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
                                    <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d15927.691931491338!2d98.5520034!3d3.6051062!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x30312941ba62814f%3A0x29a9edb53d498e25!2sYPS%20Miftahul%20Falah%20Diski!5e0!3m2!1sid!2sid!4v1732157735641!5m2!1sid!2sid" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
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

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
        <script src="script.js"></script>
</body>

</html>
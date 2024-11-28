
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
        <!-- <span id="openBtn" style="font-size:40px;cursor:pointer;color: rgb(0, 0, 0);" onclick="openNav()">&#9776;</span> -->
        <div class="d-flex">
            <div id="mySidenav" class="sidenav">
                <a href="#" class="sidebar-link py-3 px-4">Ganti Cover</a>
                <a href="#" class="sidebar-link py-3 px-4">Modifikasi galeri</a>
            </div>

            <!-- Content Area -->
            <div class="content flex-grow-1">
                <div class="cover-section">
                    <div class="container">
                        <h1 class="display-4 font-weight-bold">Halaman Admin</h1>
                        <p class="lead">Memodifikasi Halaman Utama</p>
                        <a href="logout.php" class="btn btn-lg font-weight-bold logout"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
                        <a href="pages/cover.php" class="btn btn-lg font-weight-bold">Ganti Cover</a>
                        <a href="pages/galery.php" class="btn btn-lg font-weight-bold">Modifikasi galeri</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../script.js"></script>
</body>

</html>
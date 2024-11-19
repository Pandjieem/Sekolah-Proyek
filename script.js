function openNav() {
  // Menampilkan sidebar
  document.getElementById("mySidenav").style.width = "250px";
  // Menambahkan margin pada konten
  document.getElementById("main").style.marginLeft = "250px";
  // Mengubah warna latar belakang
  document.body.style.backgroundColor = "rgba(0,0,0,0.4)";
  // Mengubah ikon tombol menjadi X
  document.getElementById("openBtn").innerHTML = "&times;";
  // Menambahkan event listener untuk menutup menu
  document.getElementById("openBtn").setAttribute("onclick", "closeNav()");
}

function closeNav() {
  // Menutup sidebar
  document.getElementById("mySidenav").style.width = "0";
  // Menghapus margin pada konten
  document.getElementById("main").style.marginLeft = "0";
  // Mengembalikan warna latar belakang ke normal
  document.body.style.backgroundColor = "white";
  // Mengubah ikon tombol kembali ke menu (hamburger)
  document.getElementById("openBtn").innerHTML = "&#9776;";
  // Menambahkan event listener untuk membuka menu
  document.getElementById("openBtn").setAttribute("onclick", "openNav()");
}

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


// Function to preview image
function previewImage(event) {
  var reader = new FileReader();
  var preview = document.getElementById('imagePreview');

  reader.onload = function () {
    preview.src = reader.result;
    preview.style.display = 'block'; // Show the image preview
  };

  if (event.target.files[0]) {
    reader.readAsDataURL(event.target.files[0]);
  }
}

let slideIndex = 1;
showSlides(slideIndex);

// Next/previous controls
function plusSlides(n) {
  showSlides(slideIndex += n);
}

// Thumbnail image controls
function currentSlide(n) {
  showSlides(slideIndex = n);
}

function showSlides(n) {
  let i;
  let slides = document.getElementsByClassName("mySlides");
  let dots = document.getElementsByClassName("dot");
  if (n > slides.length) {
    slideIndex = 1
  }
  if (n < 1) {
    slideIndex = slides.length
  }
  for (i = 0; i < slides.length; i++) {
    slides[i].style.display = "none";
  }
  for (i = 0; i < dots.length; i++) {
    dots[i].className = dots[i].className.replace(" active", "");
  }
  slides[slideIndex - 1].style.display = "block";
  dots[slideIndex - 1].className += " active";
}

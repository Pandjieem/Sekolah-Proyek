function openNav() {
  // Menampilkan sidebar
  document.getElementById("mySidenav").style.width = "250px";
  // Menambahkan margin pada konten
  document.getElementById("main").style.marginLeft = "250px";
  // Mengubah warna latar belakang
  document.body.style.backgroundColor = "rgba(0,0,0,0.4)";
  document.getElementById("opacity-dashboard").style.backgroundColor = "rgba(0,0,0,0.1)";
  document.getElementById("tidur").style.backgroundColor = "rgba(0,0,0,0.1)";
  document.getElementById("gambarpembina").style.opacity = "0.5";
  document.getElementById("gambarkepalasekolah").style.opacity = "0.5";
  document.getElementById("gambarsekretaris").style.opacity = "0.5";
  

  // Mengubah ikon tombol menjadi X
  document.getElementById("openBtn").innerHTML = "&times;";
  document.getElementById("openBtn").style.transform = "translateX(175px)";
  // Menambahkan event listener untuk menutup menu
  document.getElementById("openBtn").setAttribute("onclick", "closeNav()");
}

function closeNav() {
  // Menutup sidebar
  document.getElementById("mySidenav").style.width = "0";
  // Menghapus margin pada konten
  document.getElementById("main").style.marginLeft = "0";
  // Mengembalikan warna latar belakang ke normal
  document.getElementById("openBtn").style.transform = "translateX(15px)";
  document.body.style.backgroundColor = "white";
  // Mengubah ikon tombol kembali ke menu (hamburger)
  document.getElementById("openBtn").innerHTML = "&#9776;";
  // Menambahkan event listener untuk membuka menu
  document.getElementById("openBtn").setAttribute("onclick", "openNav()");
  document.getElementById("opacity-dashboard").style.backgroundColor = "rgb(250, 250, 250);";
  document.getElementById("tidur").style.backgroundColor = "rgb(250, 250, 250);";
  document.getElementById("gambarpembina").style.opacity = "1";
  document.getElementById("gambarkepalasekolah").style.opacity = "1";
  document.getElementById("gambarsekretaris").style.opacity = "1";
}




const cardContentContainer = document.querySelector(".card-content-container");
const cards = document.querySelectorAll(".card-article");
const prevBtn = document.querySelector("#prevBtn");
const nextBtn = document.querySelector("#nextBtn");

let currentIndex = 0;

// Hitung lebar kartu untuk memastikan pergeseran tepat
const calculateCardWidth = () => {
  const cardWidth = cards[0].offsetWidth;
  return cardWidth + parseFloat(getComputedStyle(cardContentContainer).gap);
};

// Geser ke kartu tertentu
const slideTo = (index) => {
  const cardWidth = calculateCardWidth();
  cardContentContainer.style.transform = `translateX(${-cardWidth * index}px)`;
  currentIndex = index;
};

// Tombol "Next"
nextBtn.addEventListener("click", () => {
  if (currentIndex < cards.length - 3 ) {
    slideTo(currentIndex + 1);
  } else {
    slideTo(0); // Kembali ke awal
  }
});

// Tombol "Prev"
prevBtn.addEventListener("click", () => {
  if (currentIndex > 0) {
    slideTo(currentIndex - 1);
  } else {
    slideTo(cards.length - 3); // Kembali ke akhir
  }
});

// Atur ulang posisi awal
slideTo(currentIndex);

 window.onscroll = function() {
   const button = document.getElementById("backToTop");
    if (document.body.scrollTop > 100 || document.documentElement.scrollTop > 100) { 
      button.style.display = "block"; 
    } else { button.style.display = "none"; 

    } 
  }; 
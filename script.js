const cardContentContainer = document.querySelector(".card-content-container");
const cards = document.querySelectorAll(".card-article");
const prevBtn = document.querySelector("#prevBtn");
const nextBtn = document.querySelector("#nextBtn");

let currentIndex = 0;

// Hitung lebar kartu untuk memastikan pergeseran tepat
const calculateCardWidth = () => {
  const cardWidth = cards[0].offsetWidth + parseFloat(getComputedStyle(cardContentContainer).gap);
  return cardWidth;
};

// Hitung jumlah kartu yang ditampilkan berdasarkan lebar layar
const getCardsVisibleCount = () => {
  const containerWidth = window.innerWidth;
  const cardWidth = calculateCardWidth();
  return Math.floor(containerWidth / cardWidth); // Jumlah kartu yang bisa ditampilkan
};

// Sesuaikan jumlah kartu yang ditampilkan dan lakukan pergeseran
const slideTo = (index) => {
  const cardWidth = calculateCardWidth();
  cardContentContainer.style.transform = `translateX(${-cardWidth * index}px)`;
  currentIndex = index;
};

// Tombol "Next"
nextBtn.addEventListener("click", () => {
  const visibleCount = getCardsVisibleCount();
  if (currentIndex < cards.length - visibleCount) {
    slideTo(currentIndex + 1);
  } else {
    slideTo(0); // Kembali ke awal
  }
});

// Tombol "Prev"
prevBtn.addEventListener("click", () => {
  const visibleCount = getCardsVisibleCount();
  if (currentIndex > 0) {
    slideTo(currentIndex - 1);
  } else {
    slideTo(cards.length - visibleCount); // Kembali ke akhir
  }
});

// Atur ulang posisi awal saat halaman dimuat
window.onload = () => slideTo(currentIndex);

// Mengatur ulang ketika ukuran jendela berubah
window.onresize = () => slideTo(currentIndex);

// Mengatur jumlah kartu yang ditampilkan ketika menggulir
window.onscroll = function() {
   const button = document.getElementById("backToTop");
   if (document.body.scrollTop > 100 || document.documentElement.scrollTop > 100) {
     button.style.display = "block";
   } else {
     button.style.display = "none";
   }
};

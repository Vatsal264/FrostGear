// =======================
// Product Carousel (Home Page) with Loop
// =======================
document.addEventListener("DOMContentLoaded", () => {
  const track = document.querySelector(".carousel-track");
  const prevBtn = document.getElementById("prevBtn");
  const nextBtn = document.getElementById("nextBtn");
  const products = document.querySelectorAll(".product-card");

  if (!track || products.length === 0) return;

  const productWidth = products[0].offsetWidth + 10;
  const visibleProducts = 4;
  const totalProducts = products.length;
  const maxScroll = (totalProducts - visibleProducts) * productWidth;

  let currentPosition = 0;

  // Next button
  if (nextBtn) {
    nextBtn.addEventListener("click", () => {
      currentPosition += productWidth;
      if (currentPosition > maxScroll) currentPosition = 0; // loop to start
      track.style.transform = `translateX(-${currentPosition}px)`;
    });
  }

  // Previous button
  if (prevBtn) {
    prevBtn.addEventListener("click", () => {
      currentPosition -= productWidth;
      if (currentPosition < 0) currentPosition = maxScroll; // loop to end
      track.style.transform = `translateX(-${currentPosition}px)`;
    });
  }
});





// =======================
// Product Carousel (Home Page)
// =======================
// document.addEventListener("DOMContentLoaded", () => {
//   const track = document.querySelector(".carousel-track");
//   const prevBtn = document.getElementById("prevBtn");
//   const nextBtn = document.getElementById("nextBtn");
//   const products = document.querySelectorAll(".product-card");

//   if (!track || products.length === 0) return;

//   // Width of one product card (including margin)
//   const productWidth = products[0].offsetWidth + 10;

//   let currentPosition = 0; // Track scroll position
//   const visibleProducts = 4;
//   const totalProducts = products.length;
//   const maxScroll = (totalProducts - visibleProducts) * productWidth;

//   // Next button
//   if (nextBtn) {
//     nextBtn.addEventListener("click", () => {
//       if (currentPosition < maxScroll) {
//         currentPosition += productWidth;
//         track.style.transform = `translateX(-${currentPosition}px)`;
//       }
//     });
//   }

//   // Previous button
//   if (prevBtn) {
//     prevBtn.addEventListener("click", () => {
//       if (currentPosition > 0) {
//         currentPosition -= productWidth;
//         track.style.transform = `translateX(-${currentPosition}px)`;
//       }
//     });
//   }
// });





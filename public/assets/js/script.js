// // =======================
// // Cart Panel Toggle
// // =======================
// document.addEventListener("DOMContentLoaded", () => {
//   const cartToggle = document.getElementById("cartToggle");
//   const cartClose = document.getElementById("cartClose");
//   const cartPanel = document.getElementById("cartPanel");
//   const cartOverlay = document.getElementById("cartOverlay");

//   if (cartToggle && cartPanel && cartOverlay) {
//     cartToggle.addEventListener("click", () => {
//       cartPanel.classList.add("open");
//       cartOverlay.classList.add("show");
//     });
//   }

//   if (cartClose) cartClose.addEventListener("click", closeCart);
//   if (cartOverlay) cartOverlay.addEventListener("click", closeCart);

//   function closeCart() {
//     cartPanel.classList.remove("open");
//     cartOverlay.classList.remove("show");
//   }
// });


// // =======================
// // Cart System
// // =======================
// document.addEventListener("DOMContentLoaded", () => {
//   const cartItemsList = document.getElementById("cartItems");
//   const cartTotal = document.getElementById("cartTotal");
//   let cart = [];

//   // Listen to Add to Cart buttons
//   document.querySelectorAll(".fg-btn-add").forEach(btn => {
//     btn.addEventListener("click", () => {
//       const product = {
//         id: btn.dataset.id,
//         name: btn.dataset.name,
//         price: parseInt(btn.dataset.price),
//         img: btn.dataset.img,
//         qty: 1
//       };

//       addToCart(product);
//     });
//   });

//   // Add product to cart
//   function addToCart(product) {
//     const existing = cart.find(item => item.id === product.id);
//     if (existing) {
//       existing.qty++;
//     } else {
//       cart.push(product);
//     }
//     renderCart();
//   }

//   // Render cart in panel
//   function renderCart() {
//     cartItemsList.innerHTML = "";
//     let total = 0;

//     cart.forEach(item => {
//       total += item.price * item.qty;

//       const li = document.createElement("li");
//       li.innerHTML = `
//         <img src="${item.img}" alt="${item.name}" width="40">
//         <span>${item.name} (x${item.qty})</span>
//         <strong>Rs.${item.price * item.qty}</strong>
//       `;
//       cartItemsList.appendChild(li);
//     });

//     cartTotal.textContent = `Total: Rs.${total}`;
//   }
// });


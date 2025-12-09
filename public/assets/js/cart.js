document.addEventListener("DOMContentLoaded", function () {
  const cartForm = document.getElementById("fgCartForm");
  if (!cartForm) return;

  const qtyInputs = cartForm.querySelectorAll(".fg-cart-qty-input");
  const subtotalEl = document.getElementById("fgCartSubtotal");
  const totalEl = document.getElementById("fgCartTotal");

  let updateTimer = null;

  function formatPrice(num) {
    // Simple Indian-like format: Rs.12345 -> Rs.12,345
    return "Rs." + Math.round(num).toLocaleString("en-IN");
  }

  function recalcLine(row) {
    const price = parseFloat(row.dataset.price || "0");
    const qtyInput = row.querySelector(".fg-cart-qty-input");
    const lineTotalEl = row.querySelector(".fg-cart-line-total-value");
    const qty = Math.max(0, parseInt(qtyInput.value || "0", 10));

    const lineTotal = price * qty;
    if (lineTotalEl) {
      lineTotalEl.textContent = formatPrice(lineTotal);
    }
  }

  function recalcSummary() {
    let subtotal = 0;
    const rows = cartForm.querySelectorAll(".fg-cart-row");
    rows.forEach((row) => {
      const price = parseFloat(row.dataset.price || "0");
      const qtyInput = row.querySelector(".fg-cart-qty-input");
      const qty = Math.max(0, parseInt(qtyInput.value || "0", 10));
      subtotal += price * qty;
    });

    if (subtotalEl) {
      subtotalEl.textContent = formatPrice(subtotal);
    }
    if (totalEl) {
      totalEl.textContent = formatPrice(subtotal); // same as subtotal for now
    }
  }

  function scheduleAutoSubmit() {
    if (!cartForm) return;
    if (updateTimer) clearTimeout(updateTimer);
    // Auto-submit after 600ms of no change
    updateTimer = setTimeout(() => {
      cartForm.submit();
    }, 600);
  }

  // ----- Qty change: live update + auto-submit -----
  qtyInputs.forEach((input) => {
    input.addEventListener("input", function () {
      const row = this.closest(".fg-cart-row");
      if (!row) return;

      // Clamp value
      let val = parseInt(this.value || "0", 10);
      if (isNaN(val) || val < 0) val = 0;
      this.value = val;

      recalcLine(row);
      recalcSummary();
      scheduleAutoSubmit();
    });
  });

  // ----- Smooth fade on remove -----
  const removeLinks = cartForm.querySelectorAll(".fg-cart-remove-link");
  removeLinks.forEach((link) => {
    link.addEventListener("click", function (e) {
      e.preventDefault();

      // Ask for confirmation here (single source of truth)
      const ok = confirm("Remove this item from your cart?");
      if (!ok) return;

      const href = this.getAttribute("href");
      const row = this.closest(".fg-cart-row");

      if (!row) {
        window.location.href = href;
        return;
      }

      row.classList.add("fg-cart-row--removing");
      setTimeout(() => {
        window.location.href = href;
      }, 200);
    });
  });
  
  // ----- Smooth fade on clear cart -----
  const clearLink = cartForm.querySelector(".fg-link-danger");
  if (clearLink) {
    clearLink.addEventListener("click", function (e) {
      const href = this.getAttribute("href");
      const rows = cartForm.querySelectorAll(".fg-cart-row");

      if (!confirm("Clear all items from your cart?")) {
        e.preventDefault();
        return;
      }

      e.preventDefault();

      rows.forEach((row) => row.classList.add("fg-cart-row--removing"));

      setTimeout(() => {
        window.location.href = href;
      }, 200);
    });
  }

  // Initial calc on page load
  recalcSummary();
});

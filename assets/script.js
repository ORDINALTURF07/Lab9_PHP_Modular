// assets/js/app.js
document.addEventListener("DOMContentLoaded", function () {
  // 1. Highlight menu navigasi aktif berdasarkan parameter ?page=
  const params = new URLSearchParams(window.location.search);
  const currentPage = params.get("page") || "dashboard";
  const navLinks = document.querySelectorAll("nav a");

  navLinks.forEach(function (link) {
    const href = link.getAttribute("href") || "";
    const match = href.match(/[?&]page=([^&]+)/);
    const linkPage = match ? decodeURIComponent(match[1]) : null;
    if (linkPage && linkPage === currentPage) {
      link.classList.add("active");
    }
  });

  // 2. Preview gambar otomatis saat memilih file pada form tambah/ubah barang
  const fileInput = document.querySelector('input[type="file"][name="file_gambar"]');
  if (fileInput) {
    let previewImg = document.getElementById("preview-gambar");
    if (!previewImg) {
      previewImg = document.createElement("img");
      previewImg.id = "preview-gambar";
      previewImg.alt = "Preview Gambar";
      previewImg.style.display = "none";
      previewImg.style.maxWidth = "120px";
      previewImg.style.marginTop = "8px";
      previewImg.style.borderRadius = "4px";
      fileInput.insertAdjacentElement("afterend", previewImg);
    }

    fileInput.addEventListener("change", function () {
      const file = fileInput.files && fileInput.files[0];
      if (!file) {
        previewImg.style.display = "none";
        previewImg.removeAttribute("src");
        return;
      }

      const reader = new FileReader();
      reader.onload = function (e) {
        previewImg.src = e.target.result;
        previewImg.style.display = "block";
      };
      reader.readAsDataURL(file);
    });
  }

  // 3. Validasi angka sederhana untuk harga_beli, harga_jual, dan stok
  const numericFields = document.querySelectorAll(
    'input[name="harga_beli"], input[name="harga_jual"], input[name="stok"]'
  );
  numericFields.forEach(function (input) {
    input.addEventListener("input", function () {
      this.value = this.value.replace(/[^0-9]/g, "");
    });
  });

  // 4. Konfirmasi sebelum hapus data
  const deleteLinks = document.querySelectorAll('a[href*="page=user/delete"]');
  deleteLinks.forEach(function (delLink) {
    delLink.addEventListener("click", function (event) {
      if (!window.confirm("Yakin ingin menghapus data barang ini?")) {
        event.preventDefault();
      }
    });
  });
});
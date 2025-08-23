document.querySelector(".menu-toggle").addEventListener("click", function () {
  document.querySelector(".sidebar").classList.toggle("active");
});

document.querySelectorAll("[data-submenu]").forEach((item) => {
  item.addEventListener("click", function () {
    let submenuId = "subMenu-" + this.getAttribute("data-submenu");
    document.getElementById("mainMenu").style.display = "none";
    document.getElementById(submenuId).classList.add("active");
  });
});

document.querySelectorAll(".back-btn").forEach((btn) => {
  btn.addEventListener("click", function () {
    document.querySelector(".submenu.active").classList.remove("active");
    document.getElementById("mainMenu").style.display = "block";
  });
});

function toggleDropdownIcon() {
  const dropdown = document.getElementById("userDropdownIcon");
  dropdown.style.display =
    dropdown.style.display === "block" ? "none" : "block";
}

// Ẩn dropdown nếu click ra ngoài vùng user-icon
window.onclick = function (event) {
  if (!event.target.closest(".user-menu-wrapper")) {
    document.getElementById("userDropdownIcon").style.display = "none";
  }
};

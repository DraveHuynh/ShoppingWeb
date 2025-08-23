// Get the modal
var modal = document.getElementById("editModal");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// Close the modal when the user clicks the close button
span.onclick = function () {
  modal.style.display = "none";
};

// Close the modal when clicking outside
window.onclick = function (event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
};

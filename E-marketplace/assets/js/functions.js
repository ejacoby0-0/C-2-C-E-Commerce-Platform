
//1. Model function for the login/register
var modal = document.getElementById("loginModal");

// Function to open the modal
function openModal() {
  modal.style.display = "block";
}

// Function to close the modal
function closeModal() {
  modal.style.display = "none";
}

// Close modal if user clicks outside the content box
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}


//Function for the admin-dashboard/user-dashboard
function showTab(tabId) {
  document.querySelectorAll(".tab-content").forEach(tab => {
    tab.classList.remove("active");
  });

  document.querySelectorAll(".tab").forEach(btn => {
    btn.classList.remove("active");
  });

  document.getElementById(tabId).classList.add("active");

  event.target.classList.add("active");
}


document.addEventListener("DOMContentLoaded", function () {
  const photoInput = document.getElementById("photo");
  const photoPreview = document.getElementById("photoPreview");
  const searchBtn = document.getElementById("searchBtn");
  const searchModal = document.getElementById("searchModal");
  const closeBtn = document.querySelector(".close");
  const searchInput = document.getElementById("searchInput");
  const searchResults = document.getElementById("searchResults");

  // Photo preview
  photoInput.addEventListener("change", function (e) {
    if (e.target.files.length > 0) {
      const file = e.target.files[0];

      // Validate file type and size
      const validTypes = ["image/jpeg", "image/png"];
      const maxSize = 2 * 1024 * 1024; // 2MB

      if (!validTypes.includes(file.type)) {
        alert("Please upload a JPG or PNG file.");
        photoInput.value = "";
        return;
      }

      if (file.size > maxSize) {
        alert("File size exceeds 2MB limit.");
        photoInput.value = "";
        return;
      }

      const reader = new FileReader();
      reader.onload = function (event) {
        photoPreview.innerHTML = `<img src="${event.target.result}" alt="Preview">`;
      };
      reader.readAsDataURL(file);
    }
  });

  // Search modal
  searchBtn.addEventListener("click", function () {
    searchModal.style.display = "block";
    searchInput.focus();
  });

  closeBtn.addEventListener("click", function () {
    searchModal.style.display = "none";
  });

  window.addEventListener("click", function (event) {
    if (event.target === searchModal) {
      searchModal.style.display = "none";
    }
  });

  // Live search
  searchInput.addEventListener("input", function () {
    const searchTerm = searchInput.value.trim();

    if (searchTerm.length >= 2) {
      fetchSearchResults(searchTerm);
    } else {
      searchResults.innerHTML = "";
    }
  });

  function fetchSearchResults(term) {
    fetch(`includes/search.php?term=${encodeURIComponent(term)}`)
      .then((response) => response.json())
      .then((data) => {
        displaySearchResults(data);
      })
      .catch((error) => {
        console.error("Error:", error);
      });
  }

  function displaySearchResults(results) {
    searchResults.innerHTML = "";

    if (results.length === 0) {
      searchResults.innerHTML =
        '<div class="search-result-item">No results found</div>';
      return;
    }

    results.forEach((result) => {
      const item = document.createElement("div");
      item.className = "search-result-item";
      item.innerHTML = `
                <strong>${result.last_name}, ${result.first_name}</strong><br>
                <small>${result.position} | ${result.sex}</small>
            `;
      item.addEventListener("click", function () {
        // You can implement what happens when a result is clicked
        alert(`Selected: ${result.last_name}, ${result.first_name}`);
        searchModal.style.display = "none";
      });
      searchResults.appendChild(item);
    });
  }

  // Form submission feedback
  const form = document.getElementById("registrationForm");
  if (form) {
    form.addEventListener("submit", function (e) {
      // Client-side validation can be added here
      // The actual submission is handled by the form's action attribute
    });
  }

  // Check for success message in URL
  const urlParams = new URLSearchParams(window.location.search);
  if (urlParams.has("success")) {
    alert("Registration saved successfully!");
    // Remove the success parameter from URL
    window.history.replaceState({}, document.title, window.location.pathname);
  }
});

     <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

 <?php if (isset($_SESSION['toast'])): ?>
    <script>
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: '<?= $_SESSION['toast']['type'] ?>',
            title: '<?= $_SESSION['toast']['message'] ?>',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });
    </script>
    <?php unset($_SESSION['toast']); ?>
    <?php endif; ?>

<script src="assets/js/dropdown-content.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    function toggleDropdown() {
      const dropdown = document.getElementById("userDropdown");
      dropdown.style.display = dropdown.style.display === "block" ? "none" : "block";
    }

    // Close dropdown when clicking outside
    window.addEventListener("click", function(e) {
      const dropdown = document.getElementById("userDropdown");
      const userInfo = document.querySelector(".user-info");
      if (!userInfo.contains(e.target)) {
        dropdown.style.display = "none";
      }
    });
  </script>
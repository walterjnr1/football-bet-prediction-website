<?php
if (isset($_SESSION['toast'])):
  $type = $_SESSION['toast']['type'];
  $message = $_SESSION['toast']['message'];
?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  Swal.fire({
    toast: true,
    position: 'top-end',
    icon: '<?= $type ?>',
    title: '<?= $message ?>',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true
  });
</script>
<?php
unset($_SESSION['toast']);
endif;
?>

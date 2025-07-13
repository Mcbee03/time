<!-- ✅ Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

<!-- ✅ Bootstrap 4.6 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

<!-- ✅ Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<!-- ✅ DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

<!-- ✅ Toastr CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

<!-- ✅ Favicon -->
<link rel="icon" href="/assets/images/logo.png" type="image/png" />

<!-- ✅ Custom CSS (only include once) -->
<link rel="stylesheet" href="../../assets/css/adminManagement.css?v=<?= time() ?>">
<link rel="stylesheet" href="../../assets/css/userManagement.css?v=<?= time() ?>">
<link rel="stylesheet" href="../../assets/css/deductionManagement.css?v=<?= time() ?>">
<link rel="stylesheet" href="../../assets/css/monthlyManagement.css?v=<?= time() ?>">

<!-- ✅ JS Libraries -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- ✅ Toastr JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<!-- ✅ DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<!-- ✅ SweetAlert (Optional) -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- ✅ Toastr Config (Optional but helps) -->
<script>
    toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "timeOut": "3000"
    };
</script>
<meta name="csrf-token" content="<?= $_SESSION['csrf_token'] ?>">










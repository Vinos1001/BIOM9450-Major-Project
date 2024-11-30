<script>
    function buttonNav(page) {
        location.href = page;
    }    
</script>

<nav class='nav-header'>
    <img src="../includes/Logo.png" style="width:62px;height:50px;">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <div class='navbar-button' onclick="buttonNav('dashboard.php')">Dashboard</div>
    <div class='navbar-button' onclick="buttonNav('add_patient.php')">Add Patient</div>
    <div class='navbar-button' onclick="buttonNav('about.php')">About Us</div>
    <div class="logged-in-info">
        <a>Logged in as <strong><?php echo htmlspecialchars($username); ?></strong></a>
        <div class='navbar-button' onclick="buttonNav('logout.php')"><i class="fas fa-sign-out-alt"></i> Logout</div>
    </div>
</nav>
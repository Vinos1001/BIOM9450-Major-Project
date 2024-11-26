<nav class='nav-header'>
    <img src="/includes/Logo.png" style="width:62px;height:50px;">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <div class='navbar-button'><a href="dashboard.php">Dashboard</a></div>
    <div class='navbar-button'><a href="add_patient.php">Add Patient</a></div>
    <div class="logged-in-info">
        <a>Logged in as <strong><?php echo htmlspecialchars($username); ?></strong></a>
    </div>
    <div class='navbar-button navbar-last'><a href="logout.php"><i class="fas fa-sign-out-alt"></i>  Logout</a></div>
</nav> 

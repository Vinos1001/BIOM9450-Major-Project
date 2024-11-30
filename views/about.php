<?php
session_start();
// Retrieve session data
$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About DIAGNOSYS</title>
    <link rel="stylesheet" href="/assets/css/styles.css">
</head>
<body>
    <?php include("../includes/header.php"); ?>


    <div class="container">
        <h1>About DIAGNOSYS</h1>
        <p>
            Welcome to <strong>DIAGNOSYS</strong>, your trusted partner in modern patient management and diagnostic reporting. Our mission is to revolutionize healthcare by providing clinicians with a streamlined and intuitive platform that simplifies patient data management, enhances diagnostic accuracy, and supports better clinical decision-making.
        </p>

        <h2>Our Vision</h2>
        <p>
            At DIAGNOSYS, we believe in leveraging technology to empower healthcare professionals. Our vision is to build an ecosystem where patient care is optimized, data is accessible and secure, and diagnoses are supported by actionable insights.
        </p>

        <h2>What We Offer</h2>
        <ul>
            <li><strong>Centralized Patient Management:</strong> Store and access patient demographics, medical history, phenotypes, genetic data, and diagnostic reports—all in one place.</li>
            <li><strong>Advanced Search Capabilities:</strong> Locate patient records using filters like name, diagnosis, phenotypes, or genetic mutations.</li>
            <li><strong>Customizable Reporting:</strong> Generate detailed individual or population-based reports that support audits, research, and clinical decisions.</li>
            <li><strong>Data Visualization:</strong> Utilize charts and graphs to gain insights into diagnostic trends and patient outcomes.</li>
            <li><strong>Secure Login and Access:</strong> Role-based authentication ensures that sensitive patient information is accessed only by authorized users.</li>
        </ul>

        <h2>Why Choose Us?</h2>
        <p>
            DIAGNOSYS stands out because of its commitment to:
        </p>
        <ul>
            <li><strong>User-Friendly Design:</strong> An intuitive interface that saves time and minimizes the learning curve for clinicians.</li>
            <li><strong>Data Security:</strong> We prioritize the privacy and security of patient data with robust encryption and compliance with industry regulations.</li>
            <li><strong>Efficiency and Precision:</strong> Simplify workflows, reduce errors, and focus on what matters most—delivering exceptional patient care.</li>
        </ul>

        <h2>Our Commitment</h2>
        <p>
            We are committed to constantly evolving to meet the changing needs of healthcare professionals. Through innovation and collaboration, DIAGNOSYS is dedicated to making a meaningful difference in the field of healthcare.
        </p>

        <h2>Get in Touch</h2>
        <p>
            If you'd like to learn more about DIAGNOSYS or how it can enhance your practice, feel free to contact us at 
            <a href="mailto:support@diagnosys.com">support@diagnosys.com</a>.
        </p>
    </div>

    <!-- Include the footer -->
    <?php include("../includes/footer.php"); ?>
</body>
</html>
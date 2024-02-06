<?php
include("onlineChecker.php");
?>
 
<!DOCTYPE html>
<html lang="lt">
<head>
    <meta charset="UTF-8">
    <title>Infliacijos Lygintojas</title>
    <!-- styling -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="styling.css">
    <!-- icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
    <style>
    </style>
</head>
<body>
    <?php
        include("header.php");
    ?>
    <main>
        <div class="min-vh-100 py-5 d-flex justify-content-center" style="background-image: radial-gradient(#001835, #00050B)">
            <div>
                <h1 class="my-5" style="color: white">Sveiki atvykę, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></h1>
                <object type="image/svg+xml" data="Graphics/SVGs/pc-phone-greeting.svg">
                    <img src="Graphics/SVGs/pc-phone-greeting.svg">
                </object>
            </div>
        </div>
    </main>
    <?php
        include("footer.php");
    ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
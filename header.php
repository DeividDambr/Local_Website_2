<!-- Header navigation and such -->
<header class="sticky-top">
    <div class="container-fluid bg-dark text-white">
        <nav class="d-flex py-2 align-items-center">
            <div class="col-10">
                <ul class="nav">
                    <li class="nav-item mx-4"><div class="navstyle"><a href="index.php">Pagrindinis</a></li>
                    <?php 
                        if($admin == 1){
                            echo "<li class='nav-item mx-4'><div class='navstyle'><a href='adminIndex.php'>Vartotojai</a></li>";
                        }
                    ?>
                    <li class="nav-item mx-4"><div class="navstyle"><a href="dalykai.php">Išlaidos</a></li>
                    <li class="nav-item mx-4"><div class="navstyle"><a href="statistika.php">Statistika</a></li>
                </ul>
            </div>
            <div class="px-3 d-flex col-2 justify-content-end">
                <a href="logout.php"><button class="btn btn-danger">Atsijungti</button></a>
            </div>
        </nav>
    </div>
</header>
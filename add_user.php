<?php
    include("onlineChecker.php");
    if(!$admin){
        header("location: logout.php");
    }

    //Here name will be the int id instead of str
    $name = $email = $password = $confirm_password = $adminStatus = "";
    $name_err = $email_err = $password_err = $confirm_password_err = $adminStatus_err = "";

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        if(empty(trim($_POST["userName"]))){
            $name_err = "Pasirinkite grupę.";
        }
        else{
            $sql = "SELECT id FROM vartotojai WHERE vardas = ?";
            //stmt is short for statement
            if($stmt = mysqli_prepare($link, $sql)){
                mysqli_stmt_bind_param($stmt, "s", $param_username);
                $param_username = trim($_POST["userName"]);
                if(mysqli_stmt_execute($stmt)){
                    mysqli_stmt_store_result($stmt);
                    if(mysqli_stmt_num_rows($stmt) == 1){
                        $name_err = "Naudotojo vardas jau užimtas."; 
                    }
                    else{
                        $username = trim($_POST["userName"]);
                    }
                }
            }
            else{
                echo "Įvyko klaida. Prašome pabandyti vėliau.";
            }
            mysqli_stmt_close($stmt);
        }

        if(empty(trim($_POST["userEmail"]))){
            $email_err = "Įveskite el. paštą.";     
        } 
        elseif(!empty(trim($_POST["userEmail"])) && !filter_var($_POST["userEmail"], FILTER_VALIDATE_EMAIL)){
            $email_err = "Neteisingai įvestas el. pastas.";
        }
        else{
            $sql = "SELECT id FROM vartotojai WHERE pastas = ?";
            if($stmt = mysqli_prepare($link, $sql)){
                mysqli_stmt_bind_param($stmt, "s", $param_email);
                $param_email = trim($_POST["userEmail"]);
                if(!empty($param_email)){
                if(mysqli_stmt_execute($stmt)){
                    mysqli_stmt_store_result($stmt);
                    if(mysqli_stmt_num_rows($stmt) == 1){
                        $email_err = "Jau yra paskyra su šiuo el. paštu."; 
                    }
                    else{
                        $email = trim($_POST["userEmail"]);
                    }
                }
                else $email = trim($_POST["userEmail"]);
                }
            }
            else{
                echo "Įvyko klaida. Prašome pabandyti vėliau.";
            }
            mysqli_stmt_close($stmt);
        }

        if(empty(trim($_POST["userPass"]))){
            $password_err = "Įveskite slaptažodį.";     
        }
        else{
            $password = trim($_POST["userPass"]);
        }
        
        if(empty(trim($_POST["userPass2"]))){
            $confirm_password_err = "Patvirtinkite slaptažodį.";     
        }
        else{
            $confirm_password = trim($_POST["userPass2"]);
            if(empty($password_err) && ($password != $confirm_password)){
                $confirm_password_err = "Slaptažodiai nesutampa.";
            }
        }

        if(trim($_POST["userAdmin"]) != 0 && empty(trim($_POST["userAdmin"]))){
            $adminStatus_err = "Įveskite naudotojo statusą.";     
        }
        elseif(trim($_POST["userAdmin"]) != 0 && trim($_POST["userAdmin"]) != 1){
            $adminStatus_err = "Naudotojo statusas gali būti tik 0 arba 1.";     
        }
        else{
            $adminStatus = trim($_POST["userAdmin"]);
        }
        
        if(empty($name_err) && empty($email_err) && empty($password_err) && empty($confirm_password_err) && empty($adminStatus_err)){
            $sql = "INSERT INTO vartotojai (vardas, pastas, slaptazodis, adminas) VALUES (?, ?, ?, ?)";
            if($stmt = mysqli_prepare($link, $sql)){
                mysqli_stmt_bind_param($stmt, "sssi", $param_username, $param_email, $param_password, $param_adminStatus);
                $param_username = $username;
                $param_email = $email;
                $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
                $param_adminStatus = $adminStatus;
                if(mysqli_stmt_execute($stmt)){
                    header("location: adminIndex.php");
                }
                else{
                    echo "Įvyko klaida. Prašome pabandyti vėliau.";
                }
                mysqli_stmt_close($stmt);
            }
        }
        mysqli_close($link);
    }
?>

<!DOCTYPE html>
<html lang="lt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
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
        <div class="min-vh-100 py-5 text-white" style="background-image: radial-gradient(#001835, #00050B)">
            <div class="row w-100 justify-content-center">
                <div class="col-9">
                    <h1 class="text-center">Naudotojų kūrimas</h1>
                </div>
                <div class="col-6 mt-5">
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])?>" method="post">
                        <div class="row mb-4">
                        <div class="mt-4 form-group">
                            <label>Naudotojo vardas</label>
                            <input type="text" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" name="userName" value="<?php echo $name; ?>" placeholder="Įveskite naudotojo vardą">
                            <span class="invalid-feedback"><?php echo $username_err; ?></span>
                        </div>
                        <div class="mt-3 form-group">
                            <label>El. paštas</label>
                            <input type="text" class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" name="userEmail" value="<?php echo $email; ?>" placeholder="Įveskite el.paštą">
                            <span class="invalid-feedback"><?php echo $email_err; ?></span>
                        </div>
                        <div class="mt-3 form-group">
                            <label>Slaptažodis</label>
                            <input type="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" name="userPass" value="<?php echo $password; ?>" placeholder="Įveskite slaptažodį">
                            <span class="invalid-feedback"><?php echo $password_err; ?></span>
                        </div>
                        <div class="mt-3 form-group">
                            <label>Slaptažodžio patvirtinimas</label>
                            <input type="password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" name="userPass2" value="<?php echo $confirm_password; ?>" placeholder="Pakartokite slaptažodį">
                            <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
                        </div>
                        <div class="mt-3 form-group">
                            <label>Admino statusas</label>
                            <input type="text" class="form-control <?php echo (!empty($adminStatus_err)) ? 'is-invalid' : ''; ?>" name="userAdmin" value="<?php echo $adminStatus; ?>" placeholder="Įveskite naudotojo statusą">
                            <span class="invalid-feedback"><?php echo $adminStatus_err; ?></span>
                        </div>
                        <div class="d-flex form-group justify-content-center" style="margin-top: 2rem">
                            <a href="dalykai.php" class="submitter noHoverColor me-4">Grįžti</a>
                            <button type="submit" class="submitter ms-4" style="background-color: #0275d8">Pridėti</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
    <?php
        include("footer.php");
    ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
<?php
    require_once "config.php";

    $username = $email = $password = $confirm_password = "";
    $username_err = $email_err = $password_err = $confirm_password_err = "";

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        if(empty(trim($_POST["loginName"]))){
            $username_err = "Įveskite naudotojo vardą.";
        } 
        elseif(!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["loginName"]))){
            $username_err = "Naudotojo vardas gali būti sudarytas tik iš raidžių, skaičių ir apatinio pabraukimo.";
        } 
        else{
            $sql = "SELECT id FROM vartotojai WHERE vardas = ?";
            //stmt is short for statement
            if($stmt = mysqli_prepare($link, $sql)){
                mysqli_stmt_bind_param($stmt, "s", $param_username);
                $param_username = trim($_POST["loginName"]);
                if(mysqli_stmt_execute($stmt)){
                    mysqli_stmt_store_result($stmt);
                    if(mysqli_stmt_num_rows($stmt) == 1){
                        $username_err = "Naudotojo vardas jau užimtas."; 
                    }
                    else{
                        $username = trim($_POST["loginName"]);
                    }
                }
            }
            else{
                echo "Įvyko klaida. Prašome pabandyti vėliau.";
            }
            mysqli_stmt_close($stmt);
            }
        
        if(empty(trim($_POST["loginEmail"]))){
            $email_err = "Įveskite el. paštą.";     
        } 
        elseif(!filter_var($_POST["loginEmail"], FILTER_VALIDATE_EMAIL)){
            $email_err = "Neteisingai įvestas el. pastas.";
        }
        else{
            $sql = "SELECT id FROM vartotojai WHERE pastas = ?";
            if($stmt = mysqli_prepare($link, $sql)){
                mysqli_stmt_bind_param($stmt, "s", $param_email);
                $param_email = trim($_POST["loginEmail"]);
                if(mysqli_stmt_execute($stmt)){
                    mysqli_stmt_store_result($stmt);
                    if(mysqli_stmt_num_rows($stmt) == 1){
                        $email_err = "Jau yra paskyra su šiuo el. paštu."; 
                    }
                    else{
                        $email = trim($_POST["loginEmail"]);
                    }
                }
            }
            else{
                echo "Įvyko klaida. Prašome pabandyti vėliau.";
            }
            mysqli_stmt_close($stmt);
        }

        if(empty(trim($_POST["loginPass"]))){
            $password_err = "Įveskite slaptažodį.";     
        }
        elseif(strlen(trim($_POST["loginPass"])) < 6){
            $password_err = "Slaptažodis turi būti sudarytas bent iš 6 simbolių.";
        }
        else{
            $password = trim($_POST["loginPass"]);
        }
        
        if(empty(trim($_POST["loginPass2"]))){
            $confirm_password_err = "Patvirtinkite slaptažodį.";     
        }
        else{
            $confirm_password = trim($_POST["loginPass2"]);
            if(empty($password_err) && ($password != $confirm_password)){
                $confirm_password_err = "Slaptažodiai nesutampa.";
            }
        }
        
        if(empty($username_err) && empty($email_err) && empty($password_err) && empty($confirm_password_err)){
            $sql = "INSERT INTO vartotojai (vardas, pastas, slaptazodis) VALUES (?, ?, ?)";
            if($stmt = mysqli_prepare($link, $sql)){
                mysqli_stmt_bind_param($stmt, "sss", $param_username, $param_email, $param_password);
                $param_username = $username;
                $param_email = $email;
                $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
                if(mysqli_stmt_execute($stmt)){
                    header("location: index.php");
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
    <title>Infliacijos Lygintojas</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="styling.css">
</head>
<body>
    <div class="min-vh-100 py-5 d-flex justify-content-center" style="background-image: radial-gradient(#001835, #00050B)">
        <div class="col-4">
            <h2 class="d-flex mt-3 text-white justify-content-center">Paskyros registracija</h2>
            <!-- IMPORTANT TO SELF
            php_self can be exploited by entering scripts like "http://www.yourdomain.com/form-action.php/%22%3E%3Cscript%3Ealert('xss')%3C /script%3E%3Cfoo%22" in the search bar, but htmlentities or htmlspecialchars can work around this issue by converting the characters to html entities -->
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="mt-5 form-group">
                    <label>Naudotojo vardas</label>
                    <input type="text" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" name="loginName" value="<?php echo $username; ?>" placeholder="Įveskite naudotojo vardą">
                    <span class="invalid-feedback"><?php echo $username_err; ?></span>
                </div>
                <div class="mt-3 form-group">
                    <label>El. paštas</label>
                    <input type="email" class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" name="loginEmail" value="<?php echo $email; ?>" placeholder="Įveskite el.paštą">
                    <span class="invalid-feedback"><?php echo $email_err; ?></span>
                </div>
                <div class="mt-3 form-group">
                    <label>Slaptažodis</label>
                    <input type="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" name="loginPass" value="<?php echo $password; ?>" placeholder="Įveskite slaptažodį">
                    <span class="invalid-feedback"><?php echo $password_err; ?></span>
                </div>
                <div class="mt-3 form-group">
                    <label>Slaptažodžio patvirtinimas</label>
                    <input type="password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" name="loginPass2" value="<?php echo $confirm_password; ?>" placeholder="Pakartokite slaptažodį">
                    <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
                </div>
                <div class="d-flex form-group justify-content-center flex-wrap" style="margin-top: 1rem">
                    <button type="submit" class="submitter">Registruotis</button>
                    <div class="break"></div>
                    <p style="margin-top: 0.75rem">Jau turi paskyrą? <span style="color: rgb(128, 191, 243); text-decoration: underline; font-weight: 600"><a href="index.php">Prisijunk</a></span></p>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
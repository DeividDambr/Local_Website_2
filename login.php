<?php
    session_start();
    if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
        header("location: index.php");
        exit;
    }

    require_once "config.php";
    
    $username = $password = "";
    $username_err = $password_err = $login_err = "";
    
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        if(empty(trim($_POST["loginName"]))){
            $username_err = "Įveskite naudotojo vardą.";
        } else{
            $username = trim($_POST["loginName"]);
        }

        if(empty(trim($_POST["loginPass"]))){
            $password_err = "Įveskite slaptažodį.";
        } else{
            $password = trim($_POST["loginPass"]);
        }

        if(empty($username_err) && empty($password_err)){
            $sql = "SELECT id, vardas, slaptazodis, adminas FROM vartotojai WHERE vardas = ?";
            //stmt is short for statement
            if($stmt = mysqli_prepare($link, $sql)){
                mysqli_stmt_bind_param($stmt, "s", $param_username);
                $param_username = $username;
                if(mysqli_stmt_execute($stmt)){
                    mysqli_stmt_store_result($stmt);
                    if(mysqli_stmt_num_rows($stmt) == 1){     
                        $sql = "SELECT adminas from vartotojai where vardas = '$username'";
                        $result = mysqli_query($link,$sql);
                        $admin = mysqli_fetch_assoc($result)['adminas'];
                        mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password, $admin);
                        if(mysqli_stmt_fetch($stmt)){
                            if(password_verify($password, $hashed_password)){
                                session_start();
                                $_SESSION["loggedin"] = true;
                                $_SESSION["id"] = $id;
                                $_SESSION["username"] = $username;
                                $_SESSION["admin"] = $admin;
                                if(!$admin) header("location: index.php");
                                else header("location: adminIndex.php");
                            } 
                            else{
                                $login_err = "Neteisingas naudotojo vardas arba slaptažodis.";
                            }
                        }
                    }
                    else{
                        $login_err = "Neteisingas naudotojo vardas arba slaptažodis.";
                    }
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
</head>
<body>
    <div class="min-vh-100 py-5 d-flex justify-content-center" style="background-image: radial-gradient(#001835, #00050B)">
        <div class="col-4">
            <h1 class="d-flex mt-1 text-white justify-content-center">Infliacijos Lygintojas</h1>
            <?php 
                if(!empty($login_err)){
                    echo '<div class="alert alert-danger">' . $login_err . '</div>';
                }        
            ?>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="mt-5 form-group">
                    <label>Naudotojo vardas</label>
                    <input type="text" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" name="loginName" value="<?php echo $username; ?>" placeholder="Įveskite naudotojo vardą">
                    <span class="invalid-feedback"><?php echo $username_err; ?></span>
                </div>
                <div class="mt-3 form-group">
                    <label>Slaptažodis</label>
                    <input type="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" name="loginPass" placeholder="Įveskite slaptažodį">
                    <span class="invalid-feedback"><?php echo $password_err; ?></span>
                    <div style="text-align: right"><small>Neturi paskyros? <span id="registerBtn" style="font-weight: 500"><a href="register.php">Registruokis</a></span></small></div>
                </div>
                <div class="d-flex form-group justify-content-center" style="margin-top: 1rem">
                    <button type="submit" class="submitter" style="background-color: #3781ac">Prisijungti</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
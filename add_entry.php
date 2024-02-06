<?php
    include("onlineChecker.php");
    
    $name = $type = "";
    $name_err = $type_err = "";

    if($_SERVER["REQUEST_METHOD"] == "POST"){

        if(empty(trim($_POST["groupName"]))){
            $name_err = "Įveskite pavadinimą.";
        }
        else{
            $sql = "SELECT * from  dalykai WHERE vartotojo_id = $user_id";
            $result = mysqli_query($link, $sql);
            while($row = mysqli_fetch_assoc($result)){
                $name = $row['pavadinimas'];
                if(trim($_POST["groupName"]) == $name){
                    $name_err = "Toks pavadinimas jau egzistuoja.";
                    break;
                }
            }
            if(empty($name_err)){
                $sql = "SELECT id FROM dalykai WHERE vartotojo_id = $user_id && pavadinimas = ?";
                if($stmt = mysqli_prepare($link, $sql)){
                    mysqli_stmt_bind_param($stmt, "s", $param_name);
                    $param_name = trim($_POST["groupName"]);
                    if(mysqli_stmt_execute($stmt)){
                        mysqli_stmt_store_result($stmt);
                        $name = trim($_POST["groupName"]);
                    }
                }
                else{
                    echo "Įvyko klaida. Prašome pabandyti vėliau.";
                }
                mysqli_stmt_close($stmt);
            }
        }

        if(empty(trim($_POST["groupType"])) && trim($_POST["groupType"]) != 0){
            $type_err = "Nepasirinktas tipas.";
        }
        else{
            $sql = "SELECT id FROM dalykai WHERE vartotojo_id = $user_id && tipas = ?";
            if($stmt = mysqli_prepare($link, $sql)){
                mysqli_stmt_bind_param($stmt, "i", $param_type);
                $param_type = trim($_POST["groupType"]);
                if(mysqli_stmt_execute($stmt)){
                    mysqli_stmt_store_result($stmt);
                    $type = trim($_POST["groupType"]);
                }
            }
            else{
                echo "Įvyko klaida. Prašome pabandyti vėliau.";
            }
            mysqli_stmt_close($stmt);
        }

        if(empty($name_err) && empty($type_err)){
            $sql = "INSERT INTO dalykai (vartotojo_id, pavadinimas, tipas) VALUES (?, ?, ?)";
            if($stmt = mysqli_prepare($link, $sql)){
                mysqli_stmt_bind_param($stmt, "isi", $param_id, $param_name, $param_type);
                $param_id = $user_id;
                $param_name = $name;
                $param_type = $type;
                if(!mysqli_stmt_execute($stmt)){
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
                    <h1 class="text-center">Išlaidų grupės pridėjimas</h1>
                </div>
                <div class="col-6 mt-5">
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])?>" method="post">
                        <div class="row mb-4">
                            <div class="form-group col-md">
                                <label>Pavadinimas</label>
                                <input type ="text" class="form-control <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>" name="groupName" value="<?php echo $name?>" placeholder="Įveskite grupės pavadinimą">
                                <span class="invalid-feedback"><?php echo $name_err; ?></span>
                            </div>
                            <div class="form-group col-md-2">
                                <label>Tipas</label>
                                <select class="form-select <?php echo (!empty($type_err)) ? 'is-invalid' : ''; ?>" name="groupType">
                                    <option value="0">Prekė</option>
                                    <option value="1">Paslauga</option>
                                </select>
                                <span class="invalid-feedback"><?php echo $type_err; ?></span>
                            </div>
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
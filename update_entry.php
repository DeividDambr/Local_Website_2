<?php
    include("onlineChecker.php");
    
    if(isset($_POST['updateId'])){
        $id = $_POST['updateId'];
        $sql = "SELECT * from  dalykai WHERE id = $id";
        $result = mysqli_query($link, $sql);
        $row = mysqli_fetch_assoc($result);
        $id = $row['id'];
        $name = $row['pavadinimas'];
        $type = $row['tipas'];
    }
    else header("location: dalykai.php");
    
    $formFillCheck = $_POST['formFillCheck'];
    $valName = "";
    $name_err = $type_err = "";

    if($_SERVER["REQUEST_METHOD"] == "POST" && $formFillCheck){
        $groupName = trim($_POST["groupName"]);
        $groupType = trim($_POST["groupType"]);

        if(empty($groupName)){
            $name_err = "Įveskite pavadinimą.";
        }
        elseif($groupName == $name){
            $name_err = "Toks pavadinimas jau egzistuoja.";
        }
        else{
            $sql = "SELECT id FROM dalykai WHERE id = $id && pavadinimas = ?";
            if($stmt = mysqli_prepare($link, $sql)){
                mysqli_stmt_bind_param($stmt, "s", $param_name);
                $param_name = $groupName;
                if(mysqli_stmt_execute($stmt)){
                    mysqli_stmt_store_result($stmt);
                    $valName = $groupName;
                }
            }
            else{
                echo "Įvyko klaida. Prašome pabandyti vėliau.";
            }
            mysqli_stmt_close($stmt);
        }

        if(!isset($groupType)){
            $type_err = "Nepasirinktas tipas.";
        }
        else{
            $sql = "SELECT id FROM dalykai WHERE id = $id && tipas = ?";
            if($stmt = mysqli_prepare($link, $sql)){
                mysqli_stmt_bind_param($stmt, "i", $param_type);
                $param_type = $groupType;
                if(mysqli_stmt_execute($stmt)){
                    mysqli_stmt_store_result($stmt);
                    $type = $groupType;
                }
            }
            else{
                echo "Įvyko klaida. Prašome pabandyti vėliau.";
            }
            mysqli_stmt_close($stmt);
        }

        if(empty($name_err) && empty($type_err)){
            $sql = "UPDATE dalykai SET pavadinimas = ?, tipas = ? WHERE id = $id";
            if($stmt = mysqli_prepare($link, $sql)){
                mysqli_stmt_bind_param($stmt, "si", $param_name, $param_type);
                $param_name = $valName;
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
                    <h1 class="text-center">Išlaidų grupės <?php echo '"'.$name.'" duomenys' ?></h1>
                </div>
                <div class="col-6 mt-5">
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])?>" method="post">
                        <div class="row mb-4">
                            <div class="form-group col-md">
                                <label>Pavadinimas</label>
                                <input type ="text" class="form-control <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>" name="groupName" value="<?php echo $valName?>" placeholder="<?php echo $name ?>">
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
                            <button type="submit" class="submitter ms-4" style="background-color: #0275d8">Keisti</button>
                        </div>
                        <input type="hidden" name="updateId" value="<?php echo $id ?>">
                        <input type="hidden" name="formFillCheck" value="1">
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
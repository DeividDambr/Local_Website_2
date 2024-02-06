<?php
    include("onlineChecker.php");

    //Here name will be the int id instead of str
    $name = $year = $month = $price = $amount = $amount_type = $tempName = "";
    $name_err = $year_err = $month_err = $price_err = $amount_err = $overall_err = "";

    if($_SERVER["REQUEST_METHOD"] == "POST"){

        if(empty(trim($_POST["groupName"]))){
            $name_err = "Pasirinkite grupę.";
        }
        else{
            $sql = "SELECT id, pavadinimas from dalykai WHERE vartotojo_id = $user_id";
            $result = mysqli_query($link, $sql);
            $tempCheck = false;
            while($row = mysqli_fetch_assoc($result)){
                $tempName = $row['pavadinimas'];
                if(trim($_POST["groupName"]) == $tempName){
                    $tempCheck = true;
                    $_POST["groupName"] = $row['id'];
                    break;
                }
            }
            if(!$tempCheck){
                $name_err = "Tokia grupė neegzistuoja.";
            }
            if(empty($name_err)){
                $sql = "SELECT id FROM dalykai WHERE pavadinimas = ?";
                if($stmt = mysqli_prepare($link, $sql)){
                    mysqli_stmt_bind_param($stmt, "i", $param_name);
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

        if(empty(trim($_POST["entryYear"]))){
            $year_err = "Įveskite metus.";
        } 
        elseif(!preg_match('/^[0-9]*$/', trim($_POST["entryYear"]))){
            $year_err = "Metai gali būti įvesti tik skaičiais.";
        }
        elseif(intval(trim($_POST["entryYear"])) < 1991 || intval(trim($_POST["entryYear"])) > date("Y")){
            $year_err = "Metai gali būti tik 1991-".date("Y")." tarpe.";
        }
        else{
            $sql = "SELECT dalykai_id FROM dalykai_info WHERE metai = ?";
            if($stmt = mysqli_prepare($link, $sql)){
                mysqli_stmt_bind_param($stmt, "i", $param_year);
                $param_year = trim($_POST["entryYear"]);
                if(mysqli_stmt_execute($stmt)){
                    mysqli_stmt_store_result($stmt);
                    $year = trim($_POST["entryYear"]);
                }
            }
            else{
                echo "Įvyko klaida. Prašome pabandyti vėliau.";
            }
            mysqli_stmt_close($stmt);
        }

        if(empty(trim($_POST["entryMonth"]))){
            $month_err = "Įveskite mėnesį.";
        } 
        elseif(!preg_match('/^[0-9]+$/', trim($_POST["entryMonth"]))){
            $month_err = "Mėnesiai gali būti įvesti tik skaičiais.";
        }
        elseif(intval(trim($_POST["entryMonth"])) < 1 || intval(trim($_POST["entryMonth"]) > 12)){
            $month_err = "Mėnesiai gali būti tik 1-12 tarpe.";
        }
        else{
            $sql = "SELECT dalykai_id FROM dalykai_info WHERE menuo = ?";
            if($stmt = mysqli_prepare($link, $sql)){
                mysqli_stmt_bind_param($stmt, "i", $param_month);
                $param_month = trim($_POST["entryMonth"]);
                if(mysqli_stmt_execute($stmt)){
                    mysqli_stmt_store_result($stmt);
                    $month = trim($_POST["entryMonth"]);
                }
            }
            else{
                echo "Įvyko klaida. Prašome pabandyti vėliau.";
            }
            mysqli_stmt_close($stmt);
        }

        if(empty(trim($_POST["entryPrice"]))){
            $price_err = "Įveskite kainą.";
        }
        elseif(!preg_match('/^(([1-9][0-9]{0,4}[.])\d{2})$/', trim($_POST["entryPrice"]))){
            $price_err = "Kaina gali būti įvesta tik 99.99 formatu ir būti nuo vieno iki milijono.";
        }
        else{
            $sql = "SELECT dalykai_id FROM dalykai_info WHERE kaina = ?";
            if($stmt = mysqli_prepare($link, $sql)){
                mysqli_stmt_bind_param($stmt, "d", $param_price);
                $param_price = trim($_POST["entryPrice"]);
                if(mysqli_stmt_execute($stmt)){
                    mysqli_stmt_store_result($stmt);
                    $price = trim($_POST["entryPrice"]);
                }
            }
            else{
                echo "Įvyko klaida. Prašome pabandyti vėliau.";
            }
            mysqli_stmt_close($stmt);
        }

        if(empty(trim($_POST["entryAmount"]))){
            $amount_err = "Įveskite kiekį.";
        }
        elseif(!preg_match('/^([1-9][0-9]{0,7})$/', trim($_POST["entryAmount"]))){
            $amount_err = "Kiekis gali būti tik nuo vieno iki milijardo.";
        }
        else{
            $sql = "SELECT dalykai_id FROM dalykai_info WHERE kiekis = ?";
            if($stmt = mysqli_prepare($link, $sql)){
                mysqli_stmt_bind_param($stmt, "i", $param_amount);
                $param_amount = trim($_POST["entryAmount"]);
                if(mysqli_stmt_execute($stmt)){
                    mysqli_stmt_store_result($stmt);
                    $amount = trim($_POST["entryAmount"]);
                }
            }
            else{
                echo "Įvyko klaida. Prašome pabandyti vėliau.";
            }
            mysqli_stmt_close($stmt);
        }

        if(empty(trim($_POST["entryAmountType"]))){
            $amount_type = NULL;
        }
        else{
            $sql = "SELECT dalykai_id FROM dalykai_info WHERE kiekio_tipas = ?";
            if($stmt = mysqli_prepare($link, $sql)){
                mysqli_stmt_bind_param($stmt, "s", $param_amountType);
                $param_amountType = trim($_POST["entryAmountType"]);
                if(mysqli_stmt_execute($stmt)){
                    mysqli_stmt_store_result($stmt);
                    $amount_type = trim($_POST["entryAmountType"]);
                }
            }
            else{
                echo "Įvyko klaida. Prašome pabandyti vėliau.";
            }
            mysqli_stmt_close($stmt);
        }

        $sql = "SELECT dalykai_id, metai, menuo FROM dalykai_info";
        $result = mysqli_query($link, $sql);
        while($tempRow = mysqli_fetch_assoc($result)){
            if($tempRow['dalykai_id'] == $name && $tempRow['metai'] == $year && $tempRow['menuo'] == $month){
                $overall_err = "Toks įrašas jau egzistuoja.";
                break;
            }
        }

        if(empty($name_err) && empty($year_err) && empty($month_err) && empty($price_err) && empty($amount_err) && empty($overall_err)){
            $sql = "INSERT INTO dalykai_info (dalykai_id, metai, menuo, kaina, kiekis, kiekio_tipas, vnt_kaina) VALUES (?, ?, ?, ?, ?, ?, ?)";
            if($stmt = mysqli_prepare($link, $sql)){
                mysqli_stmt_bind_param($stmt, "iiidisd", $param_name, $param_year, $param_month, $param_price, $param_amount, $param_amountType, $param_itemPrice);
                $param_name = $name;
                $param_year = $year;
                $param_month = $month;
                $param_price = $price;
                $param_amount = $amount;
                $param_amountType = $amount_type;
                $param_itemPrice = $price / $amount;
                if(!mysqli_stmt_execute($stmt)){
                    echo "Įvyko klaida. Prašome pabandyti vėliau.";
                }
                mysqli_stmt_close($stmt);
            }
        }
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
                    <h1 class="text-center">Išlaidų grupės duomenų pridėjimas</h1>
                </div>
                <div class="col-6 mt-5">
                    <?php 
                        if(!empty($overall_err)){
                            echo '<div class="alert alert-danger">' . $overall_err . '</div>';
                        }        
                    ?>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])?>" method="post">
                        <div class="row mb-4">
                            <div class="form-group col-md">
                                <label>Pavadinimas</label>
                                <input class="form-control searchBar <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>" list="datalistOptions" name="groupName" value="<?php echo $tempName?>" placeholder="Įveskite grupės pavadinimą" autocomplete="off">
                                <datalist id="datalistOptions">
                                <?php
                                    $sql = "SELECT pavadinimas FROM dalykai WHERE vartotojo_id = $user_id";
                                    $result = mysqli_query($link, $sql);
                                    while($nameRow = mysqli_fetch_assoc($result)){
                                        echo "<option value='".$nameRow['pavadinimas']."'></option>";
                                    }
                                ?>
                                </datalist>
                                <span class="invalid-feedback"><?php echo $name_err; ?></span>
                            </div>
                            <div class="form-group col-md-2">
                                <label>Metai</label>
                                <input type="text" class="form-control w-100 <?php echo (!empty($year_err)) ? 'is-invalid' : ''; ?>" name="entryYear" value="<?php echo $year?>" placeholder="Metai">
                                <span class="invalid-feedback"><?php echo $year_err; ?></span>
                            </div>
                            <div class="form-group col-md-2">
                                <label>Mėnuo</label>
                                <input type="text" class="form-control w-100 <?php echo (!empty($month_err)) ? 'is-invalid' : ''; ?>"name="entryMonth" value="<?php echo $month?>" placeholder="Mėnuo">
                                <span class="invalid-feedback"><?php echo $month_err; ?></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-5">
                                <label>Kaina (Eurais)</label>
                                <input type="text" class="form-control w-100 <?php echo (!empty($price_err)) ? 'is-invalid' : ''; ?>" name="entryPrice" value="<?php echo $price?>" placeholder="Įveskite kainą">
                                <span class="invalid-feedback"><?php echo $price_err; ?></span>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Kiekis</label>
                                <input type="text" class="form-control w-100 <?php echo (!empty($amount_err)) ? 'is-invalid' : ''; ?>" name="entryAmount" value="<?php echo $amount?>" placeholder="Įveskite kiekį">
                                <span class="invalid-feedback"><?php echo $amount_err; ?></span>
                            </div>
                            <div class="form-group col-md-3">
                                <label>Kiekio tipas</label><i class="bi bi-question-circle ms-2" data-bs-toggle="tooltip" title="Palikite šį lauką tuščią, jeigu dalykas kiekio tipo neturi"></i>
                                <input type="text" class="form-control w-100" name="entryAmountType" value="<?php echo $amount_type?>" placeholder="kg, kW/h, m³ ir t.t.">
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
        mysqli_close($link);
    ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
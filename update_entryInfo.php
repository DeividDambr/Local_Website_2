<?php
    include("onlineChecker.php");
    
    if(isset($_POST['rowNumUpdate'])){
        $i = $_POST['rowNumUpdate'];
        $tempIndexer = "updateId".$i;
        $id = $_POST[trim($tempIndexer)];
        $tempIndexer = "year".$i;
        $year = $_POST[trim($tempIndexer)];
        $tempIndexer = "month".$i;
        $month = $_POST[trim($tempIndexer)];
        $sql = "SELECT * from dalykai_info di, dalykai d WHERE di.dalykai_id = $id && di.metai = $year && di.menuo = $month && d.id = $id";
        $result = mysqli_query($link, $sql);
        $row = mysqli_fetch_assoc($result);
        $id = $row['dalykai_id'];
        $year = $row['metai'];
        $month = $row['menuo'];
        $name = $row['pavadinimas'];
        $price = $row['kaina'];
        $amount = $row['kiekis'];
        $month_names = array(
            1 => "sausio",
            2 => "vasario",
            3 => "kovo",
            4 => "balandžio",
            5 => "gegužės",
            6 => "biržėlio",
            7 => "liepos",
            8 => "rugpjūčio",
            9 => "rugsėjo",
            10 => "spalio",
            11 => "lapkričio",
            12 => "gruodžio",
        );
    }
    else header("location: dalykai.php");

    $formFillCheck = $_POST['formFillCheck'];
    $valPrice = $valAmount = $valAmountType = "";
    $year_err = $month_err = $price_err = $amount_err = $overall_err = "";

    if($_SERVER["REQUEST_METHOD"] == "POST" && $formFillCheck){

        $entryPrice = trim($_POST["entryPrice"]);
        $entryAmount = trim($_POST["entryAmount"]);

        if(empty($entryPrice)){
            $price_err = "Įveskite kainą.";
        }
        elseif(!preg_match('/^(([1-9][0-9]{0,4}[.])\d{2})$/', $entryPrice)){
            $price_err = "Kaina gali būti įvesta tik 99.99 formatu ir būti nuo vieno iki milijono.";
        }
        else{
            $sql = "SELECT dalykai_id FROM dalykai_info WHERE dalykai_id = $id && metai = $year && menuo = $month && kaina = ?";
            if($stmt = mysqli_prepare($link, $sql)){
                mysqli_stmt_bind_param($stmt, "d", $param_price);
                $param_price = $entryPrice;
                if(mysqli_stmt_execute($stmt)){
                    mysqli_stmt_store_result($stmt);
                    $valPrice = $entryPrice;
                }
            }
            else{
                echo "Įvyko klaida. Prašome pabandyti vėliau.";
            }
            mysqli_stmt_close($stmt);
        }

        if(empty($entryAmount)){
            $amount_err = "Įveskite kiekį.";
        }
        elseif(!preg_match('/^([1-9][0-9]{0,7})$/', $entryAmount)){
            $amount_err = "Kiekis gali būti tik nuo vieno iki milijardo.";
        }
        else{
            $sql = "SELECT dalykai_id FROM dalykai_info WHERE dalykai_id = $id && metai = $year && menuo = $month && kiekis = ?";
            if($stmt = mysqli_prepare($link, $sql)){
                mysqli_stmt_bind_param($stmt, "i", $param_amount);
                $param_amount = $entryAmount;
                if(mysqli_stmt_execute($stmt)){
                    mysqli_stmt_store_result($stmt);
                    $valAmount = $entryAmount;
                }
            }
            else{
                echo "Įvyko klaida. Prašome pabandyti vėliau.";
            }
            mysqli_stmt_close($stmt);
        }

        if(empty(trim($_POST["entryAmountType"]))){
            $valAmountType = NULL;
        }
        else{
            $sql = "SELECT dalykai_id FROM dalykai_info WHERE kiekio_tipas = ?";
            if($stmt = mysqli_prepare($link, $sql)){
                mysqli_stmt_bind_param($stmt, "s", $param_amountType);
                $param_amountType = trim($_POST["entryAmountType"]);
                if(mysqli_stmt_execute($stmt)){
                    mysqli_stmt_store_result($stmt);
                    $valAmountType = trim($_POST["entryAmountType"]);
                }
            }
            else{
                echo "Įvyko klaida. Prašome pabandyti vėliau.";
            }
            mysqli_stmt_close($stmt);
        }

        if(empty($price_err) && empty($amount_err)){
            $sql = "UPDATE dalykai_info SET kaina = ?, kiekis = ?, kiekio_tipas = ?, vnt_kaina = ? WHERE dalykai_id = $id && metai = $year && menuo = $month";
            if($stmt = mysqli_prepare($link, $sql)){
                mysqli_stmt_bind_param($stmt, "disd", $param_price, $param_amount, $param_amount_type, $param_itemPrice);
                $param_price = $valPrice;
                $param_amount = $valAmount;
                $param_amount_type = $valAmountType;
                $param_itemPrice = $valPrice / $valAmount;
                $formFillCheck = true;
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
                    <h1 class="text-center">Išlaidų grupės <?php echo '"'.$name.'"' ?></h1>
                    <h3 class="text-center"><?php echo $year." m. ".$month_names[$month]?> duomenys</h3>
                </div>
                <div class="col-6 mt-5">
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])?>" method="post">
                        <div class="row mb-4">
                            <div class="form-group col-md">
                                <label>Pavadinimas</label>
                                <input type ="text" class="form-control form-disabled" value="<?php echo $name?>" disabled>
                            </div>
                            <div class="form-group col-md-2">
                                <label>Metai</label>
                                <input type="text" class="form-control w-100 form-disabled" value="<?php echo $year?>" disabled>
                            </div>
                            <div class="form-group col-md-2">
                                <label>Mėnuo</label>
                                <input type="text" class="form-control w-100 form-disabled" value="<?php echo $month?>" disabled>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-5">
                                <label>Kaina (Eurais)</label>
                                <input type="text" class="form-control w-100 <?php echo (!empty($price_err)) ? 'is-invalid' : ''; ?>" name="entryPrice" value="<?php echo $valPrice?>" placeholder="<?php echo $price?>">
                                <span class="invalid-feedback"><?php echo $price_err; ?></span>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Kiekis</label>
                                <input type="text" class="form-control w-100 <?php echo (!empty($amount_err)) ? 'is-invalid' : ''; ?>" name="entryAmount" value="<?php echo $valAmount?>" placeholder="<?php echo $amount?>">
                                <span class="invalid-feedback"><?php echo $amount_err; ?></span>
                            </div>
                            <div class="form-group col-md-3">
                                <label>Kiekio tipas</label><i class="bi bi-question-circle ms-2" data-bs-toggle="tooltip" title="Palikite šį lauką tuščią, jeigu dalykas kiekio tipo neturi"></i>
                                <input type="text" class="form-control w-100" name="entryAmountType" value="<?php echo $valAmountType?>" placeholder="kg, kW/h, m³ ir t.t.">
                            </div>
                        </div>
                        <div class="d-flex form-group justify-content-center" style="margin-top: 2rem">
                            <a href="dalykai.php" class="submitter noHoverColor me-4">Grįžti</a>
                            <button type="submit" class="submitter ms-4" style="background-color: #0275d8">Keisti</button>
                        </div>
                        <input type="hidden" name="rowNumUpdate" value="<?php echo $i ?>">
                        <input type="hidden" name="updateId<?php echo $i ?>" value="<?php echo $id ?>">
                        <input type="hidden" name="year<?php echo $i ?>" value="<?php echo $year ?>">
                        <input type="hidden" name="month<?php echo $i ?>" value="<?php echo $month ?>">
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
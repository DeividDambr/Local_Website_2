<?php
include("onlineChecker.php");

    $tempName = "";
    $name_err = "";

    if(!isset($_POST["year"])) $year = "1991";

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
                    $id = $_POST["groupName"];
                    break;
                }
            }
            if(!$tempCheck){
                $name_err = "Tokia grupė neegzistuoja.";
            }
            if(!empty($name_err)){
                $tempName = "";
            }
        }

        $year = trim($_POST["year"]);
        $temp = array();
        $info = array();

        if(empty($name_err)){
            $sql = "SELECT menuo, vnt_kaina FROM dalykai_info WHERE dalykai_id = $id && metai = $year";
            $result = mysqli_query($link, $sql);
            while($tempRow = mysqli_fetch_assoc($result)){
                $temp[] = $tempRow;
            }
        }
        for($i = 0; $i < 12; $i++){
            $info[$i] = array("menuo" => ($i + 1), "vnt_kaina" => 0);
        }
        foreach($info as $value => $key){
            foreach($temp as $changer){
                if($key['menuo'] == $changer['menuo']) {
                    $info[($value)] = $changer;
                    break;
                }
            }
        }
    }
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
        <div class="min-vh-100 py-5 text-white" style="background-image: radial-gradient(#001835, #00050B)">
            <div class="row w-100 justify-content-center">
                <div class="row justify-content-center">
                    <div class="col-9 mb-5">
                        <h1 class="text-center">Infliacijos statistika</h1>
                    </div>
                </div>
                <div class="row">
                    <div class="d-flex col-3 justify-content-center align-items-center">
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])?>" method="post">
                            <div class="col-12">
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
                            <div class="col-12 mt-4">
                                <label>Metai</label>
                                <select class="form-select" name="year">
                                <?php
                                    $yearSelector = "";
                                    for($i = 1991; $i < date("Y"); $i++){
                                        if($year == $i) $yearSelector = "selected";
                                        else $yearSelector = "";
                                        echo "<option value='".$i."' ".$yearSelector.">".$i."</option>";
                                    }
                                ?>
                                </select>
                            </div>
                            <div class="d-flex w-100 justify-content-center">
                                <button type="submit" class="submitter mt-4" style="background-color: #0275d8">Rodyti</button>
                            </div>
                        </form>
                    </div>
                    <div class="col">
                        <canvas id="myChart" class="w-100" style="background-color: rgba(255, 255, 255, 0.8);"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <?php
        include("footer.php");
    ?>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const ctx = document.getElementById('myChart').getContext('2d');

        <?php 
            if(isset($info)){
                $arr = array();
                $i = 0;
                $inflation = array();

                foreach($info as $value => $key){
                    $arr[] = $key['vnt_kaina'];
                    $inflation[] = 0;
                }
                
                for($i = 1; $i < 12; $i++){
                    if($arr[$i] == 0 || $arr[($i - 1)] == 0) continue;
                    else $inflation[$i] = ($arr[$i] - $arr[($i - 1)]) / $arr[($i - 1)] * 100;
                }
            }
            else{
                for($i = 0; $i < 12; $i++){
                    $inflation[$i] = 0;
                }
            }
            $js_array = json_encode($inflation);
        ?>
        

        new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Sausis', 'Vasaris', 'Kovas', 'Balandis', 'Gegužė', 'Birželis', 'Liepa', 'Rugpjūtis', 'Rugsėjis', 'Spalis', 'Lapkritis', 'Gruodis'],
            datasets: [{
            label: 'Infliacijos %',
            data: <?php echo $js_array ?>,
            borderWidth: 1
            }]
        },
        options: {
            scales: {
            y: {
                beginAtZero: true
            }
            }
        }
        });
    </script>
</body>
</html>
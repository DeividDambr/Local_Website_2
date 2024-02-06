<?php
include("onlineChecker.php");
?>

<!DOCTYPE html>
<html lang="lt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Infliacijos Lygintojas</title>
    <!-- styling -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap5.min.css">
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
        <div class="min-vh-100 py-5 text-white test" style="background-image: radial-gradient(#001835, #00050B)">
            <div class="row w-100 justify-content-center">
                <h1 id="entryTitle" class="text-center mb-5">Išlaidų grupių duomenys</h1>
                <h1 id="groupTitle" class="text-center mb-5" style="display: none;">Išlaidų grupės</h1>
                <div class="col-9 mb-3">
                    <a href="add_entryInfo.php" id="entryAdd" class="btn btn-primary">Pridėti</a>
                    <a href="add_entry.php" id="groupAdd" class="btn btn-primary" style="display: none;">Pridėti</a>
                    <button id="switchTable" class="btn btn-primary ms-2">Kita lentelė</button>
                </div>
                <div class="col-9">
                    <form id="updateEntryInfo" action="update_entryInfo.php" method="post" style="display: none;"></form>
                    <form id="updateEntry" action="update_entry.php" method="post" style="display: none;"></form>
                    <form id="deleteEntryInfo" action="delete_entryInfo.php" method="post" style="display: none;"></form>
                    <form id="deleteEntry" action="delete_entry.php" method="post" style="display: none;"></form>
                    <div id="dalykaiInfoTableContainer">
                        <table id="dalykaiInfoTable" class="table table-dark table-striped text-white" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Nr.</th>
                                        <th>Pavadinimas</th>
                                        <th>Data</th>
                                        <th>Kaina</th>
                                        <th>Kiekis</th>
                                        <th>Vieneto kaina</th>
                                        <th>Operacijos</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // User can only see his own data
                                        $sql = "SELECT * FROM dalykai d, dalykai_info di WHERE d.vartotojo_id = $user_id && di.dalykai_id = d.id";
                                        $result = mysqli_query($link, $sql);
                                        $i = 0;
                                        while($row = mysqli_fetch_assoc($result)){
                                            if($row['menuo'] < 10) $uMonth = "0".strval($row['menuo']);
                                            else $uMonth = strval($row['menuo']);
                                            $uDate = strval($row['metai'])."-".$uMonth;
                                            $i++;
                                            if(!empty($row['kiekio_tipas'])) $amountType = "/".$row['kiekio_tipas'];
                                            else $amountType = NULL;
                                            // Note to self: next time just use Decimal type in phpmyadmin instead of float for prices
                                            echo "<tr>
                                                    <td>".$i."</td>
                                                    <td>".$row['pavadinimas']."</td>
                                                    <td>".$uDate."</td><td>".number_format($row['kaina'], 2, '.', '')." €</td>
                                                    <td>".$row['kiekis']."</td>
                                                    <td>".number_format($row['vnt_kaina'], 2, '.', '')." €".$amountType."</td>
                                                    <td>
                                                            <button form='updateEntryInfo' type='submit' class='btn btn-primary' name='rowNumUpdate' value='".$i."'>Keisti</button>
                                                            <input form='updateEntryInfo' type='hidden' name='updateId".$i."' value='".$row['dalykai_id']."'>
                                                            <input form='updateEntryInfo' type='hidden' name='year".$i."' value='".$row['metai']."'>
                                                            <input form='updateEntryInfo' type='hidden' name='month".$i."' value='".$row['menuo']."'>
                                                            <input form='updateEntryInfo' type='hidden' name='formFillCheck' value='0'>

                                                            <button type='button' class='btn btn-danger' onclick='delEntryInfo(".$i.",\"".$row['pavadinimas']."\",".$row['dalykai_id'].",".$row['metai'].",".$row['menuo'].")' data-bs-toggle='modal' data-bs-target='#checkDelEntryInfo'>Trinti</button>
                                                            </td>
                                                </tr>";
                                        }
                                    ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>Nr.</th>
                                        <th>Pavadinimas</th>
                                        <th>Data</th>
                                        <th>Kaina</th>
                                        <th>Kiekis</th>
                                        <th>Vieneto kaina</th>
                                        <th>Operacijos</th>
                                    </tr>
                                </tfoot>
                            </div>
                        </table>
                    </div>
                    <div id="dalykaiTableContainer" style="display: none;">
                        <table id="dalykaiTable" class="table table-dark table-striped text-white" style="width:100%;">
                                <thead>
                                    <tr>
                                        <th>Nr.</th>
                                        <th>Pavadinimas</th>
                                        <th>Tipas</th>
                                        <th>Operacijos</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // User can only see his own data
                                        $sql = "SELECT * FROM dalykai d WHERE d.vartotojo_id = $user_id";
                                        $result = mysqli_query($link, $sql);
                                        $i = 0;
                                        while($row = mysqli_fetch_assoc($result)){
                                            if($row['tipas'] == true) $type = "Paslauga";
                                            else $type = "Prekė";
                                            $i++;
                                            
                                            echo "<tr>
                                                    <td>".$i."</td>
                                                    <td>".$row['pavadinimas']."</td>
                                                    <td>".$type."</td>
                                                    <td><button form='updateEntry' type='submit' class='btn btn-primary' name='updateId' value='".$row['id']."'>Keisti</button>
                                                    <input form='updateEntry' type='hidden' name='formFillCheck' value='0'>
                                                    
                                                    <button type='button' class='btn btn-danger' onclick='delEntry(".$i.",\"".$row['pavadinimas']."\",".$row['id'].")' data-bs-toggle='modal' data-bs-target='#checkDelEntry'>Trinti</button>
                                                    <input form='deleteEntry' type='hidden' name='deleteId".$i."' value='".$row['id']."'></td>
                                                </tr>";
                                        }
                                    ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>Nr.</th>
                                        <th>Pavadinimas</th>
                                        <th>Tipas</th>
                                        <th>Operacijos</th>
                                    </tr>
                                </tfoot>
                            </div>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class='modal fade' id='checkDelEntryInfo' tabindex='-1' aria-labelledby='deleteConfirmMsg' aria-hidden='true'>
            <div class='modal-dialog'>
                <div class='modal-content'>
                    <div class='modal-header'>
                        <h5 class='modal-title' id='deteleConfirmMsg' style='color: black;'>Ištrynimo patvirtinimas</h5>
                        <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                    </div>
                    <div class='modal-body killCheckText'>
                    </div>
                    <div class='modal-footer'>
                        <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Grįžti</button>
                        <button form='deleteEntryInfo' type='submit' class='btn btn-danger killEntryInfo' name='rowNumDelete' value=''>Trinti</button>
                        <input form='deleteEntryInfo' class='killEntryInfo' type='hidden' name='' value=''>
                        <input form='deleteEntryInfo' class='killEntryInfo' type='hidden' name='' value=''>
                        <input form='deleteEntryInfo' class='killEntryInfo' type='hidden' name='' value=''>
                    </div>
                </div>
            </div>
        </div>
        <div class='modal fade' id='checkDelEntry' tabindex='-1'>
            <div class='modal-dialog'>
                <div class='modal-content'>
                    <div class='modal-header'>
                        <h5 class='modal-title'>Ištrynimo patvirtinimas</h5>
                        <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                    </div>
                    <div class='modal-body killCheckText'>
                    </div>
                    <div class='modal-footer'>
                        <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Grįžti</button>
                        <button form='deleteEntry' type='submit' class='btn btn-danger killEntry' name='rowNumDelete' value=''>Trinti</button>
                    </div>
                </div>
            </div>
        </div>
        <?php
            include("footer.php");
        ?>
    </main>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="dataTables_jquery.js"></script>
    <script src="dataTables_bootstrap.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="datatable.js"></script>
    <script>
        function delEntryInfo(i, name, id, year, month){ 
            var confirmText = "<p class='confirmText'> Ar tikrai norite ištrinti \"" + name + "\" išlaidų grupės " + year + " metų " + month + " mėnesio" + " duomenis?</p>";
            $('p').remove('.confirmText');
            $('.killCheckText').append(confirmText);
            $('.killEntryInfo')[0].setAttribute('value', i);
            $('.killEntryInfo')[1].setAttribute('name', 'deleteId' + i);
            $('.killEntryInfo')[1].setAttribute('value', id);
            $('.killEntryInfo')[2].setAttribute('name', 'year' + i);
            $('.killEntryInfo')[2].setAttribute('value', year);
            $('.killEntryInfo')[3].setAttribute('name', 'month' + i);
            $('.killEntryInfo')[3].setAttribute('value', month);
        }
        function delEntry(i, name, id){ 
            var confirmText = "<p class='confirmText'> Ar tikrai norite ištrinti \"" + name + "\" išlaidų grupę?</p>";
            $('p').remove('.confirmText');
            $('.killCheckText').append(confirmText);
            $('.killEntry')[0].setAttribute('value', i);
        }
    </script>
</body>
</html>
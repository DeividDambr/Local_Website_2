<?php
include("onlineChecker.php");
    if(!$admin){
        header("location: logout.php");
    }
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
        <div class="min-vh-100 py-5 text-white" style="background-image: radial-gradient(#001835, #00050B)">
            <div class="row w-100 justify-content-center">
                <div class="col-9 mb-3">
                    <a href="add_user.php" class="btn btn-primary">Pridėti</a>
                </div>
                <div class="col-9">
                    <form id="deleteUser" action="delete_user.php" method="post" style="display: none;"></form>
                    <div id="dalykaiInfoTableContainer">
                        <table id="dalykaiInfoTable" class="table table-dark table-striped text-white" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Vardas</th>
                                        <th>Paštas</th>
                                        <th>Admino statusas</th>
                                        <th>Prisijungimo laikas</th>
                                        <th>Sukūrimo data</th>
                                        <th>Operacijos</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $sql = "SELECT * FROM vartotojai";
                                        $result = mysqli_query($link, $sql);
                                        while($row = mysqli_fetch_assoc($result)){
                                            if($row['id'] == $user_id || $row['id'] == 0) {
                                                echo "<tr></tr>";
                                                continue;
                                            }
                                            else {
                                            echo "<tr>
                                                    <td>".$row['id']."</td>
                                                    <td>".$row['vardas']."</td>
                                                    <td>".$row['pastas']."</td>
                                                    <td>".$row['adminas']."</td>
                                                    <td>".$row['prisijungimo_laikas']."</td>
                                                    <td>".$row['sukurimo_data']."</td>
                                                    <td>
                                                            <button type='button' class='btn btn-danger' onclick='delUser(\"".$row['vardas']."\", ".$row['id'].")' data-bs-toggle='modal' data-bs-target='#checkDelUser'>Trinti</button></td>
                                                <tr>";
                                            }
                                        }
                                    ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>ID</th>
                                        <th>Vardas</th>
                                        <th>Paštas</th>
                                        <th>Admino statusas</th>
                                        <th>Prisijungimo_laikas</th>
                                        <th>Sukūrimo data</th>
                                        <th>Operacijos</th>
                                    </tr>
                                </tfoot>
                            </div>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class='modal fade' id='checkDelUser' tabindex='-1'>
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
                        <button form='deleteUser' type='submit' class='btn btn-danger killUser' name='deleteId' value=''>Trinti</button>
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
        function delUser(name, id){ 
            var confirmText = "<p class='confirmText'> Ar tikrai norite ištrinti \"" + name + "\" vartotoją?</p>";
            $('p').remove('.confirmText');
            $('.killCheckText').append(confirmText);
            $('.killUser')[0].setAttribute('value', id);
        }
    </script>
</body>
</html>
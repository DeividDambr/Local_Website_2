<?php 
include('config.php');

if(isset($_POST['rowNumDelete'])){
    $i = $_POST['rowNumDelete'];
    $tempIndexer = "deleteId".$i;
    $id = $_POST[trim($tempIndexer)];
    $tempIndexer = "year".$i;
    $year = $_POST[trim($tempIndexer)];
    $tempIndexer = "month".$i;
    $month = $_POST[trim($tempIndexer)];
    
    $sql = "DELETE from dalykai_info WHERE dalykai_id = $id && metai = $year && menuo = $month";
    $result = mysqli_query($link, $sql);
    if($result){
        header('location: dalykai.php');
    }
    else{
        die(mysqli_error($link));
    }
}
else header("location: dalykai.php");
?>
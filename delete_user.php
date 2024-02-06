<?php 
include('onlineChecker.php');
if(!$admin){
    header("location: logout.php");
}

if(isset($_POST['deleteId'])){
    $id = $_POST['deleteId'];
    $sql = "DELETE from vartotojai WHERE id = $id";
    $result = mysqli_query($link, $sql);
    if($result){
        header('location: adminIndex.php');
    }
    else{
        die(mysqli_error($link));
    }
}
?>
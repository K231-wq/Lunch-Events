<?php 

$pdo = new PDO("mysql:host=localhost;port=3306;dbname=events", "root", '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

session_start();

$userInfo = $_SESSION['userInfo'];
var_dump($userInfo);
$id = $_POST['id'];
if($id){
    $deleteStatement = $pdo->prepare("DELETE FROM allevents WHERE id = :id");
    $deleteStatement->bindValue(':id', $id);
    $deleteStatement->execute();
    // echo "success";
    header("Location: profile.php");
}
?>
<?php
    $pdo = new PDO("mysql:host=localhost;port=3306;dbname=events", "root", '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    session_start();

    $userInfo = $_SESSION['userInfo'];
    var_dump($userInfo);
    $id = $_POST['id'];
    $tablename = preg_replace('/[^a-zA-Z0-9_]/','',$userInfo['UniqueId']);
    if($id){
        try{
            $deleteSql1 = "DELETE FROM `$tablename` WHERE eventId = :eventId";
            $deleteStatment = $pdo->prepare($deleteSql1);
            $deleteStatment->bindValue(':eventId', $id);
            $deleteStatment->execute();
        }catch(PDOException $e){
            echo 'Delete Statment: '.$e->getMessage();
        }

        // try{
        //     $deleteEventSql = "DELETE FROM `$tablename` WHERE id = :id";
        //     $delEventStatment = $pdo->prepare($deleteEventSql);
        //     $delEventStatment->bindValue(':id', $id);
        //     $delEventStatment->execute();
        // }catch(PDOException $e){
        //     echo 'Delete a Event: '. $e->getMessage();
        // }
    }
    header("Location: rsvp.php");

?>
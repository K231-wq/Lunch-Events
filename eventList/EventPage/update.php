<?php 
    session_start();
//     echo '<pre>';
// var_dump($_SESSION['userInfo']);
// echo '</pre>';
    $pdo = new PDO('mysql:host=localhost;port=3306;dbname=events', 'root','');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $userInfo = $_SESSION['userInfo'];
    $id = $_GET['id'];
    $errors = [];
    if(!$id){
        header("Location: rsvp.php");
    }
    if(!$userInfo){
        header("Location: SignIn.php");
    }

    $tablename = preg_replace('/[^a-zA-Z0-9_]/', '', $userInfo['UniqueId']);
    $getEvent = [];
    if($id){
        try{
            $eventStatment = $pdo->prepare('SELECT * FROM allevents WHERE id = :id');
            $eventStatment->bindValue(':id', $id);
            $eventStatment->execute();
            $getEvent = $eventStatment->fetch(PDO::FETCH_ASSOC);
        }catch(PDOException $e){
            echo "GetEvent Error ".$e->getMessage();
        }
    }
    // echo '<pre>';
    // var_dump($getEvent);
    // echo '</pre>';

    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        $getId = $getEvent['id'];
        $name = $userInfo['Name'];
        $uniqueId = $userInfo['UniqueId'];
        $address = trim($_POST['address']);
        $time = $_POST['date'];
        $capacity = $_POST['capacity'];
        $description = $_POST['description'];
        $create_at = date('Y:m:d H:i:s');

        if($capacity < 1){
            $errors[] = 'The capacity must at least 1';
        }
        if(is_nan($capacity)){
            $errors[] = 'The capacity must be Integer!!';
        }
        if(strlen($address) < 8){
            $errors[] = 'You must enter the valide Address Number';
        }

        if(empty($errors)){
            try{
                $updateStatement = $pdo->prepare('UPDATE allevents 
                                            SET id = :eventId,
                                            UniqueId = :UniqueId,
                                            name = :name,
                                            address = :address,
                                            time = :time,
                                            capacity = :capacity,
                                            description = :description,
                                            create_at = :create_at
                                            WHERE id = :id'
                                            );
                $updateStatement->bindValue(':eventId', $id);
                $updateStatement->bindValue(':UniqueId', $uniqueId);
                $updateStatement->bindValue(':name', $name);
                $updateStatement->bindValue(':address', $address);
                $updateStatement->bindValue(':time', $time);
                $updateStatement->bindValue(':capacity', $capacity);
                $updateStatement->bindValue(':description', $description);
                $updateStatement->bindValue(':create_at', $create_at);
                $updateStatement->bindValue(':id', $id);
                $updateStatement->execute();
                // echo "success";
                header('Location: rsvp.php');
            }catch(PDOException $e){
                echo 'Update Sql '.$e->getMessage();
            }
        }
        

    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create</title>
    <link rel="stylesheet" href="../../css/update.css">
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous"> -->
</head>
<body>
    <div class="form-container2">
        <div >
            <p style="text-align: center; font-weight: 800; font-size: 1.8rem; color: white;">Update</p>
        </div>
        <form method="post">
            
            <div class="input input1">
                <label for="date">Time</label>
                <div class="input-sub">                 
                    <input type="date" id="date" name="date" required>
                    <i class="fa-solid fa-calendar-days"></i>
                </div>
                
            </div>
            <div class="input input2">
                <label for="capacity">Capacity</label>
                <div class="input-sub">
                    <input type="text" id="capacity" name="capacity" value="<?php echo $getEvent['capacity']; ?>" required placeholder="Capacity">
                    <i class="fa-solid fa-users"></i>
                </div>
                
            </div>
            <div class="input input2">
                <label for="Description">Description</label>
                <div class="input-sub">
                    <textarea name="description" id="description" cols="40" rows="4"><?php echo trim($getEvent['description']); ?></textarea>
                </div>
            </div>
            <div class="input input2">
                <label for="address">Address</label>
                <div class="input-sub">
                    <input type="text" id="address" name="address" value="<?php echo $getEvent['address']; ?>" required placeholder="Address">
                    <i class="fa-solid fa-house"></i>
                </div>
            </div>
            <?php if(!empty($errors)): ?>
                <div>
                    <?php foreach($errors as $error): ?>
                        <span style="color: red; font-size: 1.2rem; font-weight: 700;">
                            <?php echo $error. ' ' ?>
                        </span>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <?php if(!empty($messages)): ?>
                <div>
                    <?php foreach($messages as $message): ?>
                        <span style="color: green; font-size: 1.2rem; font-weight: 700;">
                            <?php echo $message. ' ' ?>
                        </span>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <div class="input-create-btn">
                <button class="create-btn" type="submit">
                    Update
                </button>
            </div>
            
        </form>
    </div>
    <script src="https://kit.fontawesome.com/c5f1dc3da2.js" crossorigin="anonymous"></script>
</body>
</html>
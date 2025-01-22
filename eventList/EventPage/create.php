<?php
    session_start();
    $pdo = new PDO("mysql:host=localhost;port=3306;dbname=events", 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $userInfo = $_SESSION['userInfo'];
    if (!isset($_SESSION['userInfo'])) {
        header("Location: SignIn.php");
        exit;
    }
    // var_dump($userInfo);
    $errors = [];
    $messages = [];
    // echo '<pre>';
    // var_dump($_SERVER);
    // echo '</pre>';

    if($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        if(isset($_POST['date']) &&
        isset($_POST['capacity']) && 
        isset($_POST['description']) &&
        isset($_POST['address'])){

            $uniqueId = $userInfo['UniqueId'];
            $name = $userInfo['Name'];
            $time = trim($_POST['date']);
            $capacity = trim($_POST['capacity']);
            $description = trim($_POST['description']);
            $address = trim($_POST['address']);
            $create_at = date('Y-m-d H:i:s');

            if($capacity < 1){
                $errors[] = 'The capacity at least must have 1';
            }
            if(strlen($address) < 8){
                $errors[] = 'Please enter the valide address';
            }
            if(strlen($description) < 1){
                $errors[] = 'Please Enter The Description!!';
            }
            try{
                $insertSql = $pdo->prepare('INSERT INTO allevents 
                                (UniqueId, name, address, time, capacity, description, create_at) 
                                VALUES (:uniqueId, :name, :address, :time, :capacity, :description, :create_at)
                                ');
                $insertSql->bindValue(':uniqueId', $uniqueId);
                $insertSql->bindValue(':name', $name);
                $insertSql->bindValue(':address', $address);
                $insertSql->bindValue(':time', $time);
                $insertSql->bindValue(':capacity', $capacity);
                $insertSql->bindValue(':description', $description);
                $insertSql->bindValue(':create_at', $create_at);
                $insertSql->execute();
                // echo 'added';

            }catch(PDOException $e){
                $error[] = 'PDO ERRORS '.$e->getMessage();
            }
            $messages[] = 'SuccessFully Added✔✔';
        
        }else{
            $errors[] = "Please Fill All Text Fields😢";
        }
    }
    // var_dump($errors);
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create</title>
    <link rel="stylesheet" href="../../css/create.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <div class="container">
        <div class="blank">
            <p><a href="Home.php">Launch Events</a></p>
        </div>
        <div class="wrapper">
            <div class="button-container js-icon-lobby-btn">
                <ul class="unorder-list-group">
                    <li>
                        <div class="image">
                            <a href="Home.php">
                                <img src="../../img/icon/Icon.jpg" alt="icon">
                            </a>
                        </div>
                    </li>
                    <li>
                        <div class="input-container js-lobby-btn">
                            <i class="fa-solid fa-house"></i>
                            <a href="Home.php">Lobby</a>
                        </div>
                    </li>
                    <li>
                        <div class="input-container js-create-btn">
                            <i class="fa-solid fa-plus"></i>
                            <a href="create.php">Create</a>
                        </div>
                    </li>
                    <li>
                        <div class="input-container js-invite-btn">
                            <i class="fa-solid fa-list"></i>
                            <a href="invite.php">My Invitation</a>
                        </div>
                    </li>
                    <li>
                        <div class="input-container js-rsvp-btn">
                            <i class="fa-solid fa-pen"></i>
                            <a href="rsvp.php">My RSVP</a>
                        </div>
                    </li>
                    <li>
                        <div class="input-container js-profile-btn">
                            <i class="fa-solid fa-circle-user"></i>
                            <a href="profile.php">My Profile</a>
                        </div>  
                    </li>
                    <li>
                        <div class="input-container js-logout-btn">
                            <i class="fa-solid fa-right-to-bracket"></i>
                            <a href="logout.php">Logout</a>
                        </div>
                    </li>
                </ul>
            </div>

            <div class="grid-layout">
                
                <h2 class="grid-title">Create</h2>
                <div class="card-container">
                    <div class="form-container2">
                        <form action="create.php" method="post" enctype="multipart/form-data">
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
                                    <input type="text" id="capacity" name="capacity" required placeholder="Capacity">
                                    <i class="fa-solid fa-users"></i>
                                </div>
                                
                            </div>
                            <div class="input input2">
                                <label for="Description">Description</label>
                                <div class="input-sub">
                                    <textarea name="description" id="description" cols="40" rows="4"></textarea>
                                </div>
                            </div>
                            <div class="input input2">
                                <label for="address">Address</label>
                                <div class="input-sub">
                                    <input type="text" id="address" name="address" required placeholder="Address">
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
                                    Create
                                </button>
                            </div>
                            
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://kit.fontawesome.com/c5f1dc3da2.js" crossorigin="anonymous"></script>
    <!-- <script>
        const requestBtn = document.querySelector('.js-request-btn');
        requestBtn.addEventListener('click', () => {
            if(requestBtn.innerHTML === 'Request'){
                requestBtn.innerHTML = 'Requested';
                requestBtn.style.backgroundColor = 'orange';
            }else{
                requestBtn.style.backgroundColor = 'green';
            }
        });
    </script> -->
</body>
</html>


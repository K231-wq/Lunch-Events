<?php 
session_start();
$pdo = new PDO("mysql:host=localhost;port=3306;dbname=events", "root", '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$userInfo = $_SESSION['userInfo'];
// var_dump($userInfo);
$uniqueId = $userInfo['UniqueId'];
$name = $userInfo['Name'];
$email = $userInfo['Email'];

try{
    $statment1 = $pdo->prepare("SELECT * FROM allevents WHERE UniqueId = :uniqueId");
    $statment1->bindValue(':uniqueId', $uniqueId);
    $statment1->execute();
    $allEvents = $statment1->fetchAll(PDO::FETCH_ASSOC);
}catch(PDOException $e){
    echo $e->getMessage();
}

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $imageFile = $_FILES['newImageFile'] ?? null;
    $imageFilePath = '';
    if(file_exists('../Login/img/' . $uniqueId . '.jpg')){
        unlink('../Login/img/' . $uniqueId . '.jpg');
    }
    if($imageFile && $imageFile['tmp_name']){
        $imageFilePath = '../Login/img/' . $uniqueId . '.jpg';
        move_uploaded_file($imageFile['tmp_name'], $imageFilePath);
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="../../css/profile.css">
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
                <div class="profile-container">
                    <div class="profile-header">
                        <div class="profile-image">
                            <div class="image">
                                <img src="<?php echo '../Login/img/'.$uniqueId.'.jpg' ?>" alt="UserImage">
                            </div>
                        </div>
                        <div class="profile-value-container">
                            <div class="value-container">
                                    <p>User Name: <span class="username"><?php echo $userInfo['Name']; ?></span></p>
                                    <p>Email: <span class="userEmail"><?php echo $userInfo['Email']; ?></span></p>
                                    <div class="row mt-2">
                                        <label class="col-sm-4 label">Upload New Image</label>
                                        <div class="col-sm-7">
                                            <form action="profile.php" method="post" enctype="multipart/form-data">
                                                <input type="file" name="newImageFile" class="form-control">
                                                <button type="submit" class="btn btn-primary mt-2">Submit</button>
                                            </form>
                                        </div>
                                    </div>
                            </div>
                        </div>
                        
                    </div>
                    
                </div>
                <div class="invitation-container">
                        <p class="invite-title text-start ps-2 fs-4 fw-bold font-monospace">Invitation List</p>
                        <div class="grid-container">
                            <div class="card-container">
                                <?php  foreach($allEvents as $i => $event): ?>
                                    <div class="card">
                                        <div class="text-container">
                                            <div class="textform textfrom-address">
                                                <i class="fa-solid fa-location-dot"></i>
                                                <p><?php echo $event['address']; ?></p>
                                            </div>
                                            <div class="textform textfrom-time">
                                                <i class="fa-regular fa-clock"></i>
                                                <p class="grid-title">Time:</p>
                                                <p><?php echo $event['time']; ?></p>
                                            </div>
                                            <div class="textform textfrom-capacity">
                                                <i class="fa-solid fa-users"></i>
                                                <p class="grid-title">Capacity:</p>
                                                <p><?php echo $event['capacity']; ?></p>
                                            </div>
                                            <div class="textform textfrom-des">
                                                <i class="fa-solid fa-circle-exclamation"></i>
                                                <p class="grid-title">Description:</p>
                                                <p><?php echo $event['description']; ?></p>
                                            </div>
                                            <div class="textform textfrom-create-by">
                                                <i class="fa-solid fa-user"></i>
                                                <p class="grid-title">Create By:</p>
                                                <p><?php echo $event['name']; ?></p>
                                            </div>
                                        </div>
                                        <div class="btn-container">
                                            <form action="deleteInvite.php" method="post" style="display: inline-block;">
                                                <input type="hidden" name="id" value="<?php echo $event['id']; ?>">
                                                <button class="request-btn js-request-btn btn btn-danger border-0" type="submit">Delete</button>
                                            </form>
                                            
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://kit.fontawesome.com/c5f1dc3da2.js" crossorigin="anonymous"></script>


</body>
</html>

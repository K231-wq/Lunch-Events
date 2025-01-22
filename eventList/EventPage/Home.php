<?php
session_start();

$pdo = new PDO("mysql:host=localhost;port=3306;dbname=events", 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$statment = $pdo->prepare("SELECT * FROM allevents");
$statment->execute();
$allEvents = $statment->fetchAll(PDO::FETCH_ASSOC);

$errors = [];
$userInfo = $_SESSION['userInfo'];
$tableName = preg_replace('/[^a-zA-Z0-9_]/', '', $userInfo['UniqueId']);
if(!$userInfo){
    $errors[] = 'UserInfo is missing';
}
if (!isset($_SESSION['userInfo'])) {
    header("Location: SignIn.php");
    exit;
}
$id = $_GET['id'] ?? null;
if (!$id) {
    // echo "Id is missing";
}else{
    try {
        // Create the table if it does not exist
        $sql = "CREATE TABLE IF NOT EXISTS `$tableName` (
                    eventId VARCHAR(255) PRIMARY KEY
                )";
        $pdo->exec($sql);
    
        // Check if the id already exists
        $checkSql = "SELECT COUNT(*) FROM `$tableName` WHERE eventId = :id";
        $statement = $pdo->prepare($checkSql);
        $statement->execute(['id' => $id]);
        $idExists = $statement->fetchColumn();
    
        if ($idExists) {
            $errors[] = "The Event is already requested";
        } else {
            // Insert the id if it does not exist
            $insertSql = "INSERT INTO `$tableName` (eventId) VALUES (:id)";
            $insertStatement = $pdo->prepare($insertSql);
            $insertStatement->execute(['id' => $id]);
            // echo "The ID has been successfully added to the table.";
        }
        
    } catch (PDOException $e) {
        $errors[] = 'errors'.$e->getMessage();
    }
    header("refresh:1;url=Home.php");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="../../css/home.css">
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
                
                <h2 class="grid-title">Lobby</h2>
                <?php if(!empty($errors)): ?>
                        <?php foreach($errors as $error): ?>
                            <span style="color: red; font-size: 1.2rem; font-weight: 700; display: inline-block; padding: 5px 30px;">
                                <?php echo $error ?>
                            </span>
                        <?php endforeach; ?>
                    <?php endif; ?>
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
                                <form style="display: inline-block;">
                                    <input type="hidden" name="id" value="<?php echo $event['id']; ?>">
                                    <button class="request-btn js-request-btn btn btn-success border-0" type="submit">Request</button>
                                </form>
                                
                            </div>
                        </div>
                    <?php endforeach; ?>
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

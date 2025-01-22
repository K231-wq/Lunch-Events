<?php

    session_start();

    $pdo = new PDO('mysql:host=localhost;port=3306;dbname=events', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $errors = [];
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        $name = trim($_POST['name']);
        $password = trim($_POST['password']);

        $statement = $pdo->prepare("SELECT * FROM register WHERE Name = :name");
        $statement->bindValue(':name', $name);
        $statement->execute();
        $userInfo = $statement->fetch(PDO::FETCH_ASSOC);
        if(!$userInfo){
            $errors[] = "INVALID NAME😭😭";
        }

        if($userInfo){
            if($userInfo && password_verify($password, trim($userInfo["Password"]))){
                $_SESSION["userInfo"] = $userInfo;
                header("Location: ../EventPage/Home.php");
                exit;
            }else{
                $errors[] = "Invalid name or password";
            }
        }
        

    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign in</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        *{
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body{
            display: flex;
            justify-content: center;
            align-items: center;
            width:100%;
            height: 100vh;
            background-color: #f8f9fa;
        }
        .container-body{
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            width: 100%;
            max-width: 400px;
            padding: 20px;
            background-color: #6c757d;
            border-radius: 10px;
        }
        .alert {
            width: 100%;
        }
    </style>
</head>
<body class="">
    <div class="container-fluid container-body p-4 border rounded bg-secondary text-white" style="width: 400px;">
        <h1 class="text-center">Sign in</h1>
        
        <?php if(!empty($errors)): ?>
            <?php foreach($errors as $error): ?>
                <div class="alert alert-danger">
                    <div><?php echo $error; ?></div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
        

        <form action="<?php htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
        <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="name" class="form-control" id="name" name="name">
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password">
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
            <div class="mt-2 text-center font-monospace">
                <p>If you have a account? Go to <span style="font-weight: 800; font-size: 1.2rem;text-decoration: none;">
                    <a href="Register.php" style="color: blue; text-decoration: none;">Register</a>
                </span>
            </p>
            </div>
        </form>
    </div>
</body>
</html>
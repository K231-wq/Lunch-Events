<?php
    $pdo = new PDO("mysql:host=localhost;port=3306;dbname=events", 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $errors = [];
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);

        if(!$name){
            $errors[] = "Please Enter the name";
        }
        if(!$email){
            $errors[] = "Please Enter the email";
        }
        if(!$password){
            $errors[] = "Please Enter the password";
        }
        if(!is_dir(__DIR__.'/img')){
            mkdir(__DIR__.'/img');
        }

        if(empty($errors)){
            $hashPassword = password_hash($password, PASSWORD_DEFAULT);
            $uniqueId = randomString(12);
            $imageFile = $_FILES['imageFile'] ?? null;
            $imageFilePath = '';
            if($imageFile && $imageFile['tmp_name']){
                $imageFilePath = 'img/'.$uniqueId.'.jpg';
                move_uploaded_file($imageFile['tmp_name'], $imageFilePath);
            }
            
            $statment = $pdo->prepare("INSERT INTO register (UniqueId, Name, Email, Password)
                        VALUES (:uniqueId, :name, :email, :password)
                        ");
            $statment->bindValue('uniqueId', $uniqueId);
            $statment->bindValue(':name', $name);
            $statment->bindValue(':email', $email);
            $statment->bindValue(':password', $hashPassword);

            $statment->execute();
            $userInfo = $statment->fetchAll(PDO::FETCH_ASSOC);
            header('Location: Signin.php');

        }
        
        // echo "<pre>";
        // var_dump($_FILES);
        // echo "</pre>";
    }


    function randomString($length){
        $string = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYX1234567890!@#$%^&*()-+';
        $randomString = '';
        for($i = 0; $i<$length; $i++){
            $index = random_int(0, strlen($string) - 1);
            $randomString .= $string[$index];
        }
        return $randomString;
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        body{
            background-color: darkgrey;
        }
    </style>
</head>
<body>
    <div class="container-fluid p-5 border border-rounded mt-5 bg-dark" style="width: 800px; height: auto; color: white;">
        <p class="text-center mb-4 font-monospace fs-4 fw-bolder">Register</p>
        <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]); ?>"  method="post" enctype="multipart/form-data">
            <div class="row mb-4">
                <label for="image" class="col-sm-4">Image</label>
                <div class="col-sm-5">
                    <input type="file" name="imageFile" required class="form-control">
                </div>
            </div>
            <div class="row mb-4">
                <label for="name" class="col-sm-4">Name</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" name="name" id="name">
                </div>
            </div>
            <div class="row mb-4">
                <label for="email" class="col-sm-4">Email</label>
                <div class="col-sm-8">
                    <input type="email" class="form-control" name="email" id="email">
                </div>
            </div>
            <div class="row mb-4">
                <label for="password" class="col-sm-4">Password</label>
                <div class="col-sm-8">
                    <input type="password" class="form-control" name="password" id="password">
                </div>
            </div>
            <button type="Submit" class="btn btn-primary" style="width: 150px;">Register</button>
            <div class="mt-4 text-start font-monospace">
                <p>If you have a account? Go to <span style="font-weight: 800; font-size: 1.2rem;text-decoration: none;">
                    <a href="SignIn.php">Sign In</a>
                </span>
            </p>
            </div>
     
        </form>
        

    </div>
</body>
</html>
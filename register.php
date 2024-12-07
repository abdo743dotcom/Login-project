<?php
include 'config.php';  // الاتصال بقاعدة البيانات

if (isset($_POST['submit'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, md5($_POST['password']));  // تشفير كلمة المرور
    $confirm_password = mysqli_real_escape_string($conn, md5($_POST['confirm_password']));
    $image = $_FILES['image']['name'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder = 'uploaded_img/' . $image;

    // التحقق إذا كان المستخدم موجودًا
    $select = mysqli_query($conn, "SELECT * FROM user_form WHERE email = '$email'") or die('Query failed');
    if (mysqli_num_rows($select) > 0) {
        $message[] = 'User already exists';
    } else {
        if ($password != $confirm_password) {
            $message[] = 'Passwords do not match';
        } else {
            // إدخال البيانات في قاعدة البيانات
            $insert = mysqli_query($conn, "INSERT INTO user_form (name, email, password, image) VALUES ('$name', '$email', '$password', '$image')") or die('Query failed');
            if ($insert) {
                move_uploaded_file($image_tmp_name, $image_folder);
                $message[] = 'Registered successfully';
                header('location: login.php');
            } else {
                $message[] = 'Registration failed';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="style.css"></head>
<body>
    <?php
    if (isset($message)) {
        foreach ($message as $msg) {
            echo '<div class="message">' . $msg . '</div>';
        }
    }
    ?>
    <form action="" method="POST" enctype="multipart/form-data">
        <input type="text" name="name" placeholder="Enter username" class="box" required> <br>
        <input type="email" name="email" placeholder="Enter email" class="box" required> <br>
        <input type="password" name="password" placeholder="Enter password" class="box" required> <br>
        <input type="password" name="confirm_password" placeholder="Confirm password" class="box" required> <br>
        <input type="file" name="image" accept="image/jpg, image/jpeg, image/png" class="box"> <br>
        <input type="submit" name="submit" value="Register Now" class="btn">
        <p>Already have an account? <a href="login.php">Login now</a></p>
    </form>
</body>
</html>

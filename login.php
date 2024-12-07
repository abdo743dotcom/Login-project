<?php
include 'config.php';  // الاتصال بقاعدة البيانات

session_start();

if (isset($_POST['submit'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, md5($_POST['password']));  // تشفير كلمة المرور

    // التحقق من بيانات المستخدم
    $select = mysqli_query($conn, "SELECT * FROM user_form WHERE email = '$email' AND password = '$password'") or die('Query failed');
    
    if (mysqli_num_rows($select) > 0) {
        $row = mysqli_fetch_assoc($select);
        $_SESSION['user_id'] = $row['id'];  // حفظ id المستخدم في الجلسة
        header('location: home.php');
    } else {
        $message[] = 'Incorrect email or password';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php
    if (isset($message)) {
        foreach ($message as $msg) {
            echo '<div class="message">' . $msg . '</div>';
        }
    }
    ?>
    <form action="" method="POST">
        <input type="email" name="email" placeholder="Enter email" class="box" required> <br>
        <input type="password" name="password" placeholder="Enter password" class="box" required> <br>
        <input type="submit" name="submit" value="Login Now" class="btn">
        <p>Don't have an account? <a href="register.php">Register now</a></p>
    </form>
</body>
</html>

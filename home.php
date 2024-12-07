<?php
include 'config.php';  // الاتصال بقاعدة البيانات

session_start();
if (!isset($_SESSION['user_id'])) {
    header('location: login.php');  // إذا لم يكن المستخدم قد قام بتسجيل الدخول بعد، إعادة توجيهه إلى صفحة تسجيل الدخول
    exit();
}

$user_id = $_SESSION['user_id'];

// استرجاع بيانات المستخدم من قاعدة البيانات
$select = mysqli_query($conn, "SELECT * FROM user_form WHERE id = '$user_id'") or die('Query failed');
if (mysqli_num_rows($select) > 0) {
    $fetch = mysqli_fetch_assoc($select);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="style.css">
<body>
    <h2>Welcome, <?php echo $fetch['name']; ?></h2>
    <div class="profile">
        <img src="uploaded_img/<?php echo $fetch['image']; ?>" alt="Profile Image" class="profile-img">
        <p>Email: <?php echo $fetch['email']; ?></p>
        <p>Username: <?php echo $fetch['name']; ?></p>
    </div>

    <a href="update_profile.php" class="btn">Update Profile</a>
    <a href="login.php" class="btn">login</a>
</body>
</html>

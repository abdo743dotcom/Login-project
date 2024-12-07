<?php
include 'config.php';  // الاتصال بقاعدة البيانات

session_start();
$user_id = $_SESSION['user_id'];  // الحصول على id المستخدم من الجلسة

if (isset($_POST['update_profile'])) {
    $update_name = mysqli_real_escape_string($conn, $_POST['update_name']);
    $update_email = mysqli_real_escape_string($conn, $_POST['update_email']);
    $update_pass = mysqli_real_escape_string($conn, md5($_POST['update_pass']));
    $confirm_pass = mysqli_real_escape_string($conn, md5($_POST['confirm_pass']));

    // تحديث بيانات المستخدم
    if (!empty($update_pass) && !empty($confirm_pass)) {
        if ($update_pass != $confirm_pass) {
            $message[] = 'Passwords do not match';
        } else {
            $update_password_query = mysqli_query($conn, "UPDATE user_form SET password = '$confirm_pass' WHERE id = '$user_id'") or die('Query failed');
            $message[] = 'Password updated successfully';
        }
    }

    // تحديث الصورة الشخصية
    $update_image = $_FILES['update_image']['name'];
    $update_image_tmp_name = $_FILES['update_image']['tmp_name'];
    $update_image_folder = 'uploaded_img/' . $update_image;

    if (!empty($update_image)) {
        $image_update_query = mysqli_query($conn, "UPDATE user_form SET image = '$update_image' WHERE id = '$user_id'") or die('Query failed');
        move_uploaded_file($update_image_tmp_name, $update_image_folder);
        $message[] = 'Image updated successfully';
    }

    // تحديث الاسم والبريد الإلكتروني
    $update_user_query = mysqli_query($conn, "UPDATE user_form SET name = '$update_name', email = '$update_email' WHERE id = '$user_id'") or die('Query failed');
    $message[] = 'Profile updated successfully';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Profile</title>
    <link rel="stylesheet" href="style.css"><body>
    <?php
    if (isset($message)) {
        foreach ($message as $msg) {
            echo '<div class="message">' . $msg . '</div>';
        }
    }
    ?>
    <form action="" method="POST" enctype="multipart/form-data">
        <?php
        // استرجاع بيانات المستخدم من قاعدة البيانات
        $select = mysqli_query($conn, "SELECT * FROM user_form WHERE id = '$user_id'") or die('Query failed');
        if (mysqli_num_rows($select) > 0) {
            $fetch = mysqli_fetch_assoc($select);
        }
        ?>
        <input type="text" name="update_name" value="<?php echo $fetch['name']; ?>" class="box" required> <br>
        <input type="email" name="update_email" value="<?php echo $fetch['email']; ?>" class="box" required> <br>
        <input type="password" name="update_pass" placeholder="Update password" class="box"> <br>
        <input type="password" name="confirm_pass" placeholder="Confirm new password" class="box"> <br>
        <input type="file" name="update_image" class="box"> <br>
        <input type="submit" name="update_profile" value="Update Profile" class="btn">
    </form>
</body>
</html>

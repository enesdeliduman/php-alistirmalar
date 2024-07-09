<?php
require 'functions/email_sender.php';

$errors = array();
if (isset($_POST["submit"])) {
    $username = isset($_POST["username"]) ? $_POST["username"] : null;
    $password = isset($_POST["password"]) ? $_POST["password"] : null;
    $email = isset($_POST["email"]) ? $_POST["email"] : null;
    if (!$username) {
        array_push($errors, "username giriniz");
    } elseif (!$password) {
        array_push($errors, "password giriniz");
    } elseif (!$email) {
        array_push($errors, "email giriniz");
    } else {
        $confirmCode = rand(10000, 99999);
        $currentTime = date('Y-m-d H:i:s');
        $confirmCodeValidyDate = date('Y-m-d H:i:s', strtotime('+5 minutes', strtotime($currentTime)));
        $sorgu = $conn->prepare("INSERT INTO users SET username=?, password=?, email=?, confirmCode=?, confirmCodeValidyDate=?");
        $newUser = $sorgu->execute([$username, openssl_encrypt($password, $env["ENCRYPTION_METHOD"], $env["ENCRYPTION_KEY"]), $email, $confirmCode, $confirmCodeValidyDate]);

        if ($newUser) {
            send_email($email, $username, "Kayıt", "Kayıt başarıyla gerçekleşti. Giriş yaparak hesabınızı doğrulayabilirsiniz. Doğrulama kodunuz: " . $confirmCode, $confirmCodeValidyDate);
            header("Location: index.php?sayfa=login");
        } else {
            echo "Bir hata var";
        }
    }
}
?>

<div class="container">
    <form class="form"  method="post">
        <?php if (count($errors) >= 1) {
            echo "<div class='errors'>";
            foreach ($errors as $error) {
                echo "<p class='error'>$error</p>";
            }
            echo  "</div>";
        } ?>
        <input type="text" class="form-element" placeholder="Kullanıcı adı" id="username" name="username" value="<?php echo isset($_POST["username"]) ? $_POST["username"] : null;  ?>">
        <input type="email" class="form-element" placeholder="Email" id="email" name="email" value="<?php echo isset($_POST["email"]) ? $_POST["email"] : null;  ?>">
        <input type="password" class="form-element" placeholder="Şifre" name="password">
        <input type="hidden" name="submit" value="1">
        <button class="form-element button">Kayıt ol</button>
        <br>
        <span>Zaten hesabın var mı? <a href="index.php?page=login">Giriş yap</a></span>
    </form>
</div>
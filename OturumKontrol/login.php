<?php
$errors = array();

if (isset($_POST["submit"])) {
    $username = isset($_POST["username"]) ? $_POST["username"] : null;
    $password = isset($_POST["password"]) ? $_POST["password"] : null;
    if (!$username) {
        array_push($errors, "username giriniz");
    } elseif (!$password) {
        array_push($errors, "password giriniz");
    } else {
        $sorgu = $conn->prepare("SELECT * FROM users WHERE username=?");
        $sorgu->execute([$username]);
        $user = $sorgu->fetch(PDO::FETCH_ASSOC);
        if ($user) {
            if ($password == openssl_decrypt($user["password"], $env["ENCRYPTION_METHOD"], $env["ENCRYPTION_KEY"])) {
                $_SESSION["username"] = $username;
                $_SESSION["email"] = $user["email"];
                $_SESSION["isConfirm"] = $user["isConfirm"];
                header("Location: index.php");
            } else {
                array_push($errors, "Hatalı şifre");
            }
        } else {
            array_push($errors, "Böyle bir kullanıcı bulunamadı");
        }
    }
}
?>


<div class="container">
    <form method="post" class="form">
        <?php if (count($errors) >= 1) {
            echo "<div class='errors'>";
            foreach ($errors as $error) {
                echo "<p class='error'>$error</p>";
            }
            echo  "</div>";
        } ?>
        <input type="text" class="form-element" placeholder="Kullanıcı adı" id="username" name="username" value="<?php echo isset($_POST["username"]) ? $_POST["username"] : null;  ?>">
        <input type="password" class="form-element" placeholder="Şifre" name="password">
        <input type="hidden" name="submit" value="1">
        <button class="form-element button">Giriş yap</button>
        <br>
        <span>Hesabın yok mu? <a href="index.php?page=register">Kayıt ol</a></span>
    </form>
</div>
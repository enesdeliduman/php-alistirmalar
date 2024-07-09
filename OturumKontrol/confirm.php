<?php
$errors = array();
require 'functions/email_sender.php';
if (isset($_POST["newConfirmCode"])) {
    $confirmCode = rand(10000, 99999);
    $currentTime = date('Y-m-d H:i:s');
    $confirmCodeValidyDate = date('Y-m-d H:i:s', strtotime('+5 minutes', strtotime($currentTime)));
    $sorgu = $conn->prepare("UPDATE users SET confirmCode=?, confirmCodeValidyDate=? WHERE username=?");
    $confirmQuery = $sorgu->execute([$confirmCode, $confirmCodeValidyDate, $_SESSION["username"]]);

    send_email($_SESSION["email"], $_SESSION["username"], "Doğrulama Kodu", "Doğrulama kodunuz: " . $confirmCode);

    if ($confirmQuery) {
        header("Location: index.php");
        exit;
    } else {
        array_push($errors, "Bir hata oluştu.");
    }
}


if (isset($_POST["submit"])) {
    $code = isset($_POST["code"]) ? $_POST["code"] : null;
    if (!$code) {
        array_push($errors, "Lütfen doğrulama kodunu giriniz");
    } else {
        // Kullanıcıyı veritabanından al
        $sorgu = $conn->prepare("SELECT * FROM users WHERE username=?");
        $sorgu->execute([$_SESSION["username"]]);
        $user = $sorgu->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $confirmCode = $user["confirmCode"];
            $confirmCodeValidyDate = $user["confirmCodeValidyDate"];
            $currentTime = date('Y-m-d H:i:s');

            if ($confirmCode === $code && $confirmCodeValidyDate > $currentTime) {
                $sorgu = $conn->prepare("UPDATE users SET isConfirm=? WHERE username=?");
                $confirmQuery = $sorgu->execute([true, $_SESSION["username"]]);

                if ($confirmQuery) {
                    $_SESSION["isConfirm"] = true;
                    header("Location: index.php");
                    exit;
                } else {
                    array_push($errors, "Hesabınız doğrulanırken bir hata oluştu.");
                }
            } else {
                array_push($errors, "Doğrulama kodu yanlış veya süresi geçmiş.");
            }
        }
    }
}
?>

<div class="container">
    <form class="form" method="post">
        <?php if (count($errors) >= 1) {
            echo "<div class='errors'>";
            foreach ($errors as $error) {
                echo "<p class='error'>$error</p>";
            }
            echo  "</div>";
        } ?>
        <input type="text" class="form-element" placeholder="Doğrulama Kodu" name="code" maxlength="5" required>
        <input type="hidden" name="submit" value="1">
        <button type="submit" class="form-element button">Hesabımı doğrula</button>
    </form>
    <hr>
    <form style="max-width: min-content; margin:0; padding:0;" method="post">
        <input type="hidden" name="newConfirmCode" value="1">
        <button href="index.php?page=register" style="min-width: 20rem;" class="button form-element">Tekrar sifre iste</button>
    </form>
</div>
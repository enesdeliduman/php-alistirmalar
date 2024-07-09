<nav>
    <a href="index.php" id="logo"><b>Anasayfa</b></a>
    <div>
        <?php if (isset($_SESSION["username"])) : ?>
            <div>
                <a href="index.php?page=sign-out" style="cursor: pointer;" class="bg">Hoş geldin <?php echo $_SESSION["username"]; ?> | <i class="fa-solid fa-arrow-right-from-bracket"></i></a>
            </div>
        <?php else : ?>
            <a href="index.php?page=login" class="bg">Giriş yap</a>
            <a href="index.php?page=register">Kayıt ol</a>
        <?php endif; ?>
    </div>
</nav>

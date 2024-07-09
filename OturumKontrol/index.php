    <!DOCTYPE html>
    <html lang="tr">

    <head>
        <?php session_start() ?>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Anasayfa</title>
        <link rel="stylesheet" href="./assets/css/style.css">
        <!-- Google Font -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    </head>

    <body>

        <?php
        
        $env = parse_ini_file('.env');
        require_once("db_connection.php");
        require_once("header.php");

        if (isset($_SESSION["username"])) {
            if ($_SESSION["isConfirm"] == 0) {
                require_once("confirm.php");
            }
        }

        if (!isset($_GET["page"])) {
            $_GET["page"] = "index";
        }

        switch ($_GET["page"]) {
            case "register":
                require_once("register.php");
                break;
            case "login":
                require_once("login.php");
                break;
            case "sign-out":
                session_destroy();
                header("Location:index.php");
                break;
        }
        ?>


    </body>

    </html>
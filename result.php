<?php
    require('utility/base-url.php');
    if(!isset($_COOKIE[EMAIL])) {
        header('Location: ' . URL . '/login.php');
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Score</title>
    <link rel="stylesheet" href="css/override.css">
    <link rel="stylesheet" href="css/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="css/common.css">
    <link rel="stylesheet" href="css/result.css">
</head>
<body>
    <?php
    require('utility/base-url.php');
    if(isset($_GET["logout"])) {
        setcookie(EMAIL, "", time() - 300, "/", "", 0);
        header('Location: ' . URL . '/login.php');
    }
    ?>
    <header>
        <div class="wrapper">
            <h1>
                <a href="#" title="Online Quiz">Online Quiz</a>
            </h1>
            <div class="control-buttons">
                <span title="Logout" class="user-email"><?php echo isset($_COOKIE[EMAIL]) ? "Welcome! " . $_COOKIE[EMAIL] : ""; ?></span>
                <a href='<?php echo constant('URL').'/result.php?logout=true'?>' title="Logout">Logout</a>
            </div>
        </div>
    </header>
    <div class='wrapper'>
        <h2>Quiz Result</h2>
    </div>
</body>
</html>
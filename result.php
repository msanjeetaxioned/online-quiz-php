<?php
    require('utility/base-url.php');
    if(!isset($_COOKIE[EMAIL])) {
        header('Location: ' . URL . '/login.php');
    }
    else {
        if(!isset($_COOKIE[RESULT])) {
            header('Location: ' . URL . '/quiz.php');
            // setcookie(RESULT, "", time() - 300, "/", "", 0);
        }
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
        <?php
        $quizID = $_COOKIE[RESULT];
        require('utility/db-connection.php');
        require('utility/result-class.php');
        $result = new Result();
        $result->getResponsesAndCorrectAnswersFromDB($quizID);
        $result->calculateScoreAndInsertInDB();
        echo "<p class='score'>Your Score: " . $result->scoreObtainedOfQuiz . " / " . $result->totalScoreOfQuiz . "</p>";
        ?>
        <div class="login-div">
            <h2>View Quiz Leaderboards!</h2>
            <a href="<?php echo constant('URL').'/leaderboards.php'?>" title="Leaderboards">Leaderboards</a>
        </div>
    </div>
    <script>
    // To prevent Page Refresh from Submitting Form
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
    </script>
</body>
</html>
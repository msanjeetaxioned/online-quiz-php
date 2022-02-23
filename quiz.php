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
    <title>Online Quiz</title>
    <link rel="stylesheet" href="css/override.css">
    <link rel="stylesheet" href="css/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="css/common.css">
    <link rel="stylesheet" href="css/quiz.css">
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
                <a href='<?php echo constant('URL').'/quiz.php?logout=true'?>' title="Logout">Logout</a>
            </div>
        </div>
    </header>
    <div class="wrapper">
        <?php
        require('utility/db-connection.php');
        require('utility/quiz-class.php');
        $quizID = 1;

        $quiz1 = new Quiz($quizID);
        $quiz1->getQuizQuestionsFromDB();

        if(isset($_POST)) {
            if($quiz1->submitQuiz()) {
                setcookie(RESULT, $quizID, time() + 24 * 60 * 60, "/", "", 0);
                header('Location: ' . URL . '/result.php');
            }
        }

        if($quiz1->checkIfUserHasAlreadyAttemptedQuiz($_COOKIE[EMAIL])) {
            setcookie(RESULT, $quizID, time() + 24 * 60 * 60, "/", "", 0);
            echo "<p class='note-attempted'>*Note: You have already attempted this Quiz! Only Viewing Is Allowed Now.</p>
            <h2>Total Marks: " . $quiz1->totalMarks . "</h2>
            <p class='note'>Note: Each Question is for 1 Mark. All Questions are Compulsary.</p>
            <form action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "' method='post'>
                <ul class='quiz'>
                " . $quiz1->displayQuizQuestions() . "
                </ul>
            </form>
            <div class='login-div'>
            <h2>View Your Score!</h2>
            <a href='" . constant('URL'). "/result.php' title='Result'>Result</a>
            </div>
            <div class='login-div'>
            <h2>View Quiz Leaderboards!</h2>
            <a href='" . constant('URL'). "/leaderboards.php' title='Leaderboards'>Leaderboards</a>
            </div>";
        } else {
            echo "<h2>Total Marks: " . $quiz1->totalMarks . "</h2>
            <p class='note'>Note: Each Question is for 1 Mark. All Questions are Compulsary.</p>
            <form action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "' method='post'>
                <ul class='quiz'>
                " . $quiz1->displayQuizQuestions() . "
                </ul>
                <div class='submit-div'>
                    <button type='submit'>submit</button>
                </div>
            </form>";
        }
        ?>
    <div>
    <script>
    // To prevent Page Refresh from Submitting Form
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
    </script>
</body>
</html>
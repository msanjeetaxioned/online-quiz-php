<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Quiz</title>
    <link rel="stylesheet" href="css/override.css">
    <link rel="stylesheet" href="css/common.css">
    <link rel="stylesheet" href="css/quiz.css">
</head>
<body>
    <header>
        <div class="wrapper">
            <h1>
                <a href="#" title="Online Quiz">Online Quiz</a>
            </h1>
        </div>
    </header>
    <div class="wrapper">
        <?php
        require('utility/db-connection.php');
        require('utility/quiz-class.php');
        $quiz1 = new Quiz(1);
        $quiz1->getQuizQuestionsFromDB();

        echo "<h2>Total Marks: " . $quiz1->totalMarks . "</h2>
            <p class='note'>Note: Each Question is for 1 Mark. All Questions are Compulsary.</p>
            <form action='' method='post'>
                <ul class='quiz'>
                " . $quiz1->displayQuizQuestions() . "
                </ul>
                <div class='submit-div'>
                    <button type='submit'>submit</button>
                </div>
            </form>";
        ?>
    <div>
</body>
</html>
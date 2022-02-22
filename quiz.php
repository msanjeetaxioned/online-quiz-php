<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Quiz</title>
</head>
<body>
    <div class="wrapper">
        <h1>Quiz</h1>
        <?php
        require('utility/db-connection.php');
        require('utility/quiz-class.php');
        $quiz1 = new Quiz(1);
        $quiz1->getQuizQuestionsFromDB();

        echo "<h2>Total Marks: </h2>
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
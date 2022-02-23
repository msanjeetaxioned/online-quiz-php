<?php

class Quiz
{
    public $id;
    public $questionIDs;
    public $questionsData = array();
    public $totalQuestions;
    public $totalMarks;
    public $answers = array();

    public function Quiz($quizId)
    {
        DatabaseConnection::startConnection();

        $stmt = DatabaseConnection::$conn->prepare("SELECT * FROM quiz WHERE id=?;");
        $stmt->bind_param("i", $quizId);

        $stmt->execute();
        $result = $stmt->get_result();
        $result = $result->fetch_assoc();

        $this->id = $quizId;
        $this->questionIDs = $result['questions'];
        $this->questionIDs = json_decode($this->questionIDs);
        $this->questionIDs = (array) $this->questionIDs;

        $this->totalMarks = $result['total_marks'];
        $stmt->close();

        DatabaseConnection::closeDBConnection();
    }

    public function getQuizQuestionsFromDB()
    {
        for($i = 0; $i < count($this->questionIDs); $i++) {
            DatabaseConnection::startConnection();

            $stmt = DatabaseConnection::$conn->prepare("SELECT * FROM questions WHERE id=?;");
            $stmt->bind_param("i", $this->questionIDs[$i]);

            $stmt->execute();
            $result = $stmt->get_result();
            $result = $result->fetch_assoc();

            $this->questionsData[$i] = $result;

            $stmt->close();
            DatabaseConnection::closeDBConnection();
        }
    }

    public function displayQuizQuestions()
    {
        $data = "";
        for($i = 0; $i < count($this->questionsData); $i++) {
            $data .= "
                <li class='question'>
                    <h3>Question: " . $this->questionsData[$i]['question'] . "</h3>
                    <div class='option1'>
                        <input type='radio' name='question" . ($i+1) . "' value='" . $this->questionsData[$i]['option1'] . "'>
                        <span>" . $this->questionsData[$i]['option1'] . "</span>
                    </div>
                    <div class='option2'>
                        <input type='radio' name='question" . ($i+1) . "' value='" . $this->questionsData[$i]['option2'] . "'>
                        <span>" . $this->questionsData[$i]['option2'] . "</span>
                    </div>
                    <div class='option3'>
                        <input type='radio' name='question" . ($i+1) . "' value='" . $this->questionsData[$i]['option3'] . "'>
                        <span>" . $this->questionsData[$i]['option3'] . "</span>
                    </div>
                    <div class='option4'>
                        <input type='radio' name='question" . ($i+1) . "' value='" . $this->questionsData[$i]['option4'] . "'>
                        <span>" . $this->questionsData[$i]['option4'] . "</span>
                    </div>
                </li>
            ";
        }
        return $data;
    }

    public function submitQuiz() 
    {
        $allQuestionsAttempted = false;
        
        for($i = 0; $i < count($this->questionsData); $i++) {
            if(!isset($_POST['question'.($i+1)])) {
                return $allQuestionsAttempted;
            }
            else {
                $this->answers[$i] = $_POST['question'.($i+1)];
            }
        }
        $allQuestionsAttempted = true;

        DatabaseConnection::startConnection();

        // Get number of Records in Score Table to set ID for new record
        $stmtSelect = DatabaseConnection::$conn->prepare("SELECT * FROM score;");
        $stmtSelect->execute();
        $stmtSelect->store_result();
        $rows = $stmtSelect->num_rows;
        $rows++;
        $stmtSelect->close();

        $stmt = DatabaseConnection::$conn->prepare("INSERT INTO score (id, user_email, quiz_id, responses) VALUES (?,?,?,?);");
        $id = $this->id;
        $answers = $this->answers;
        $answers = implode(", ", $answers);

        $stmt->bind_param("isis", $rows, $_COOKIE[EMAIL], $id, $answers);

        $stmt->execute();
        $stmt->close();
        DatabaseConnection::closeDBConnection();

        return $allQuestionsAttempted;
    }

    public function checkIfUserHasAlreadyAttemptedQuiz($userEmail) {
        DatabaseConnection::startConnection();
        $stmtSelect = DatabaseConnection::$conn->prepare("SELECT * FROM score where user_email=?;");
        $stmtSelect->bind_param("s", $userEmail);
        $stmtSelect->execute();
        $stmtSelect->store_result();
        $rows = $stmtSelect->num_rows;
        $stmtSelect->close();

        if($rows >= 1) {
            return true;
        }
        return false;
    }
}
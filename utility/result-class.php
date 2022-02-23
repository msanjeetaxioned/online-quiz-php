<?php
class Result
{
    public $quizID;
    public $userResponses = array();
    public $quizQuestionIDs = array();
    public $correctAnswers = array();
    public $scoresArray = array();
    public $scoreObtainedOfQuiz;
    public $totalScoreOfQuiz;

    public function getResponsesAndCorrectAnswersFromDB($quizID)
    {
        $this->quizID = $quizID;
        $userEmail = $_COOKIE[EMAIL];

        DatabaseConnection::startConnection();
        $stmt = DatabaseConnection::$conn->prepare("SELECT * FROM score WHERE quiz_id=? AND user_email=?;");
        $stmt->bind_param("is", $quizID, $userEmail);
        $stmt->execute();
        $result = $stmt->get_result();
        $result = $result->fetch_assoc();
        $this->userResponses = explode(", ", $result['responses']);
        $stmt->close();

        $stmt = DatabaseConnection::$conn->prepare("SELECT questions,total_marks FROM quiz WHERE id=?;");
        $stmt->bind_param("i", $quizID);
        $stmt->execute();
        $result = $stmt->get_result();
        $result = $result->fetch_assoc();
        $this->quizQuestionIDs = (array) json_decode($result['questions']);
        $this->totalScoreOfQuiz = $result['total_marks'];
        $stmt->close();

        for($i = 0; $i < count($this->quizQuestionIDs); $i++) {
            $stmt = DatabaseConnection::$conn->prepare("SELECT correct_answer FROM questions WHERE id=?;");
            $id = $this->quizQuestionIDs[$i];
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $result = $result->fetch_assoc();
            $this->correctAnswers[$i] = $result['correct_answer'];
            $stmt->close();
        }

        DatabaseConnection::closeDBConnection();
    }

    public function calculateScoreAndInsertInDB()
    {
        $this->scoreObtainedOfQuiz = 0;
        for($i = 0; $i < count($this->userResponses); $i++) {
            if($this->userResponses[$i] == $this->correctAnswers[$i]) {
                $this->scoresArray[$i] = 1;
                $this->scoreObtainedOfQuiz++;
            }
            else {
                $this->scoresArray[$i] = 0;
            }
        }

        $scoresPerQuestion = implode(", ", $this->scoresArray);
        $id = $this->quizID;
        $userEmail = $_COOKIE[EMAIL];

        DatabaseConnection::startConnection();
        $stmt = DatabaseConnection::$conn->prepare("UPDATE score set scores_per_question=?, score=? WHERE quiz_id=? AND user_email=?;");
        $stmt->bind_param("siis", $scoresPerQuestion, $this->scoreObtainedOfQuiz, $id, $userEmail);
        $stmt->execute();
        $stmt->close();
        DatabaseConnection::closeDBConnection();
    }
}
?>
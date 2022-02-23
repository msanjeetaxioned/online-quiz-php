<?php

class Leaderboard
{
    public function displayLeaderboard($quizID)
    {
        DatabaseConnection::startConnection();
        $stmt = DatabaseConnection::$conn->prepare("SELECT user_email, score FROM score WHERE quiz_id=? ORDER BY score DESC;");
        $stmt->bind_param("i", $quizID);
        $stmt->execute();
        $result = $stmt->get_result();

        $rank = 1;
        while ($row = $result->fetch_assoc()) {
            echo "<li class='leaderboard-item'>
                    <span>".$rank."</span>
                    <span>".$row['user_email']."</span>
                    <span>".$row['score']."</span>
                </li>";
            $rank++;
        }

        $stmt->close();
        DatabaseConnection::closeDBConnection();
    }
}
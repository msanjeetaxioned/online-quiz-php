<?php

class RegisterUser 
{
    public $name;
    public $email;
    public $mobileNum;
    public $gender;
    public $password;
    public $confirmPass;

    public function validateFields() 
    {
        // Name Validation
        $this->name = $_POST["name"];
        Validation::nameValidation($this->name);

        // Email Validation
        $this->email = $_POST["email"];
        Validation::emailValidation($this->email);
        if(Validation::$emailError == "") {
            Validation::checkIfEmailAlreadyUsedInDB($this->email);
        }

        // Mobile Number Validation
        $this->mobileNum = $_POST["phone-num"];
        Validation::mobileNumValidation($this->mobileNum);

        // Get Gender
        if(isset($_POST["gender"])) {
            $this->gender = $_POST["gender"];
        } else {
            $this->gender = "";
        }
        Validation::genderValidation($this->gender);

        // Password Validation
        $this->password = $_POST["password"];
        Validation::passwordValidation($this->password);

        // Confirm Password Validation
        $this->confirmPass = $_POST["confirm-password"];
        Validation::confirmPasswordValidation($this->password, $this->confirmPass);

        // Reset Form on Successful Submit
        if (Validation::checkIfAllFieldsAreValid()) {
            $_POST = [];
        }
    }

    public function onSubmit()
    {
        if (Validation::checkIfAllFieldsAreValid()) {
            $this->password = hash('sha512', $this->password);
            DatabaseConnection::startConnection();
           
            $stmt = DatabaseConnection::$conn->prepare("INSERT INTO users (name, email, mobile, gender, password) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("ssiss", $this->name, $this->email, $this->mobileNum, $this->gender, $this->password);

            $submittedData = ["name" => $this->name, "mobile" => $this->mobileNum, "gender" => $this->gender];
            setcookie(SUBMIT, json_encode($submittedData), time() + 30 * 24 * 60 * 60, "/", "", 0);

            // set parameters and execute
            $stmt->execute();
            $stmt->close();
            DatabaseConnection::closeDBConnection();
            header('Location: ' . URL . '/submit.php');
        }
    }
}
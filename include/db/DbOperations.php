<?php

class DbOperations {

    private $conn;

    function __construct() {
        $db = new DbConnect();
        $this->conn = $db->connect();
    }

    /* ----------------------- USERS TABLE ---------------------------- */

    function registerUser($name, $email, $password) {
        $password_hash = $this->getEncryptedPassword($password);
        $api_key = $this->generateApiKey();

        if ($this->emailAlreadyExists($email)) {
            return USER_ALREADY_EXISTS;
        }

        $stmt = $this->conn->prepare("INSERT INTO `users`(`name`, `email`, `password_hash`, `api_key`) VALUES(?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $email, $password_hash, $api_key);
        if ($stmt->execute()) {
            return USER_CREATED_SUCCESSFULLY;
        } else {
            return FAILED_TO_CREATE_USER;
        }
    }

    function getUserByEmail($email) {
        $stmt = $this->conn->prepare("SELECT * FROM `users` WHERE `email` = ?");
        $stmt->bind_param("s", $email);
        if ($stmt->execute()) {
            return $stmt->get_result()->fetch_assoc();
        } else {
            return null;
        }
    }

    private function getEncryptedPassword($password) {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    private function generateApiKey() {
        return md5(uniqid(rand(), true));
    }

    private function emailAlreadyExists($email) {
        $stmt = $this->conn->prepare("SELECT `id` FROM `users` WHERE `email` = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        $num_rows = $stmt->num_rows;
        return $num_rows > 0;
    }

    /* ----------------------- USERS TABLE ---------------------------- */

}

?>
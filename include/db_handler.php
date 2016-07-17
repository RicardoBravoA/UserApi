<?php

class DbHandler {

    private $conn;

    function __construct() {
        require_once dirname(__FILE__) . '/db_connect.php';
        $db = new DbConnect();
        $this->conn = $db->connect();
    }


    // All Users
    public function getAllUsers() {

        $response = array();
        $stmt = $this->conn->prepare("SELECT user_id, name, lastname, email FROM user");

        if($stmt->execute()){
            $stmt->bind_result($user_id, $name, $lastname, $email);
            $stmt->store_result();
            if($stmt->num_rows>0){
                $data = array();
                while ($stmt->fetch()) {
                    $tmp = array();
                    $tmp["user_id"] = $user_id;
                    $tmp["name"] = $name;
                    $tmp["lastname"] = $lastname;
                    $tmp["email"] = $email;
                    array_push($data, $tmp);
                }
                $_meta = array();
                $_meta["status"]="success";
                $_meta["code"]="200";
                $response["_meta"] = $_meta;
                $response["data"] = $data;
                $stmt->close();
                return $response;
            }else{
                $meta = array();
                $meta["status"] = "error";
                $meta["code"] = "101";
                $response["_meta"] = $meta;
            }
        }else{
            $meta = array();
            $meta["status"] = "error";
            $meta["code"] = "100";
            $response["_meta"] = $meta;
        }

        return $response;
    }


    // add User
    public function addUser($name, $lastname, $email, $password) {
        $response = array();

        $stmt = $this->conn->prepare("INSERT INTO user(name, lastname, email, password) VALUES(?,?,?,?)");
        $stmt->bind_param("ssss", $name, $lastname, $email, $password);
        $result = $stmt->execute();
        $stmt->close();

        if ($result) {
            $meta = array();
            $meta["status"] = "success";
            $meta["code"] = "200";
            $response["_meta"] = $meta;
        } else {
            $meta = array();
            $meta["status"] = "error";
            $meta["code"] = "100";
            $response["_meta"] = $meta;
        }

        return $response;
    }


    // login
    public function login($email, $password) {
        $response = array();

        $stmt = $this->conn->prepare("SELECT user_id, name, lastname, email FROM user 
                      WHERE email = ? and password = ?");
        $stmt->bind_param("ss", $email, $password);

        if($stmt->execute()){
            $stmt->bind_result($email, $password);
            $stmt->store_result();
            if($stmt->num_rows>0){
                $_meta = array();
                $_meta["status"]="success";
                $_meta["code"]="200";
                $response["_meta"] = $_meta;
                $stmt->close();
                return $response;
            }else{
                $meta = array();
                $meta["status"] = "error";
                $meta["code"] = "101";
                $response["_meta"] = $meta;
            }
        }else{
            $meta = array();
            $meta["status"] = "error";
            $meta["code"] = "100";
            $response["_meta"] = $meta;
        }

        return $response;
    }


    // Get User by Id
    public function getUserById($id) {

        $response = array();
        $stmt = $this->conn->prepare("SELECT user_id, name, lastname, email FROM user WHERE user_id = ?");
        $stmt->bind_param("s", $id);

        if($stmt->execute()){
            $stmt->bind_result($user_id, $name, $lastname, $email);
            $stmt->store_result();
            if($stmt->num_rows>0){
                $data = array();
                while ($stmt->fetch()) {
                    $tmp = array();
                    $tmp["user_id"] = $user_id;
                    $tmp["name"] = $name;
                    $tmp["lastname"] = $lastname;
                    $tmp["email"] = $email;
                    array_push($data, $tmp);
                }
                $_meta = array();
                $_meta["status"]="success";
                $_meta["code"]="200";
                $response["_meta"] = $_meta;
                $response["data"] = $data;
                $stmt->close();
                return $response;
            }else{
                $meta = array();
                $meta["status"] = "error";
                $meta["code"] = "101";
                $response["_meta"] = $meta;
            }
        }else{
            $meta = array();
            $meta["status"] = "error";
            $meta["code"] = "100";
            $response["_meta"] = $meta;
        }


        return $response;
    }


}

?>
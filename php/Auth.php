<?php
require "DBController.php";
date_default_timezone_set('Europe/Istanbul');
$IP_Adresi = $_SERVER["REMOTE_ADDR"];

class Auth
{
    public function edit($table, $id, $edit, $value, $message = "x")
    {
        $db_handle = new DBController();
        $hostsql = "UPDATE $table SET $edit = '$value' WHERE id = $id";
        if (mysqli_query($db_handle->connectDB(), $hostsql)) {
            if ($message != "x") {
                echo "<script>alert('" . $message . "');</script>";
            }
        } else {
            echo "<script>console.log('Error: " . $hostsql . " \n " . mysqli_error($db_handle->connectDB()) . "');</script>";
        }
    }
    public function getMemberByUsername($username)
    {
        $username = $username;
        $db_handle = new DBController();
        $query = "Select * from users where username = ?";
        $result = $db_handle->runQuery($query, 's', array($username));
        return $result;
    }
    public function getMemberByEmail($username)
    {
        $username = $username;
        $db_handle = new DBController();
        $query = "Select * from users where email = ?";
        $result = $db_handle->runQuery($query, 's', array($username));
        return $result;
    }

    public function getMemberById($id)
    {
        $db_handle = new DBController();
        $query = "Select * from users where id = ?";
        $result = $db_handle->runQuery($query, 's', array($id));
        return $result;
    }

    public function getTableById($id, $table)
    {
        $db_handle = new DBController();
        $query = "Select * from $table where id = ?";
        $result = $db_handle->runQuery($query, 's', array($id));
        return $result;
    }

    public function deleteTableById($id, $table)
    {
        $db_handle = new DBController();
        $query = "Delete from $table where id = ?";
        $result = $db_handle->runQuery($query, 's', array($id));
        return $result;
    }

    public function getTableByValues($table, $key, $value)
    {
        $db_handle = new DBController();
        $query = "Select * from $table where $key like ?";
        $result = $db_handle->runQuery($query, 's', array($value));
        return $result;
    }

    public function sqlcode($query)
    {
        $db_handle = new DBController();
        $result = $db_handle->runQuery($query, '', array());
        return $result;
    }

    public function update($query)
    {
        mysqli_query($this->conn, $query);
    }

    public function getTokenByUsername($username, $expired)
    {
        $username = $username;
        $db_handle = new DBController();
        $query = "Select * from tbl_token_auth where username = ? and is_expired = ?";
        $result = $db_handle->runQuery($query, 'si', array($username, $expired));
        return $result;
    }

    public function markAsExpired($tokenId)
    {
        $db_handle = new DBController();
        $query = "UPDATE tbl_token_auth SET member_email = ? WHERE id = ?";
        $expired = "sfsdssg";
        $result = $db_handle->update($query, 'ii', array($expired, $tokenId));
        return $result;
    }

    public function insertToken($username, $random_password_hash, $random_selector_hash, $expiry_date)
    {
        $username = $username;
        $db_handle = new DBController();
        $query = "INSERT INTO tbl_token_auth (username, password_hash, selector_hash, expiry_date) values (?, ?, ?, ?)";
        $result = $db_handle->insert($query, 'ssss', array($username, $random_password_hash, $random_selector_hash, $expiry_date));
        return $result;
    }
}

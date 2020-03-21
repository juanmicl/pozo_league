<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Users_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    public function getUserPass($username)
    {
        $sql = "SELECT `password` FROM users WHERE username = ?";
        return $this->db->query($sql, [$username])->row_array()['password'];
    }

    public function getUserRankByUsername($username)
    {
        $sql = "SELECT `rank` FROM users WHERE username = ?";
        return $this->db->query($sql, [$username])->row_array()['rank'];
    }

    public function getUserRankByToken($token)
    {
        $sql = "SELECT `rank` FROM users WHERE token = ?";
        return $this->db->query($sql, [$token])->row_array()['rank'];
    }

    public function getUserbyUsername($username)
    {
        $sql = "SELECT * FROM users WHERE username = ?";
        return $this->db->query($sql, [$username])->result()[0];
    }

    public function getUser($token)
    {
        $sql = "SELECT * FROM users WHERE token = ?";
        return $this->db->query($sql, [$token])->result()[0];
    }

    public function setUser($username, $hash, $email)
    {
        $sql = "INSERT INTO `users` (`id`, `username`, `password`, `email`, `rank`, `token`, `points`, `telegram`, `avatar`, `style_name`) VALUES (NULL, ?, ?, ?, '0', '0', '0', '', '', '');";
        return $this->db->query($sql, [$username, $hash, $email]);
    }

    public function setUserToken($username, $token)
    {
        $sql = "UPDATE `users` SET `token` = ? WHERE `username` = ?";
        return $this->db->query($sql, [$token, $username]);
    }

    public function getUsername($id)
    {
        $sql = "SELECT username n FROM users WHERE id = ?";
        return $this->db->query($sql, [$id])->row()->n;
    }

    public function getPlayer($id)
    {
        $sql = "SELECT players.* FROM users INNER JOIN players ON players.id = users.player_id WHERE users.id = ?";
        return $this->db->query($sql, [$id])->result()[0];
    }

    public function setPlayerId($id, $player_id)
    {
        $sql = "UPDATE `users` SET `player_id` = ? WHERE `id` = ?";
        return $this->db->query($sql, [$player_id, $id]);
    }

    public function getPoints($id)
    {
        $sql = "SELECT points pts FROM users WHERE id = ?";
        return $this->db->query($sql, [$id])->row()->pts;
    }

    public function setPoints($uid, $value)
    {
        $sql = "UPDATE `users` SET `points` = ? WHERE `id` = ?";
        return $this->db->query($sql, [$value, $uid]);
    }

    public function increasePoints($uid, $value)
    {
        $sql = "UPDATE `users` SET `points` = points + ? WHERE `id` = ?";
        return $this->db->query($sql, [$value, $uid]);
    }

    public function decreasePoints($uid, $value)
    {
        $sql = "UPDATE `users` SET `points` = points - ? WHERE `id` = ?";
        return $this->db->query($sql, [$value, $uid]);
    }

    public function editAvatar($user_id, $avatar)
    {
        $sql = "UPDATE `users` SET `avatar` = ? WHERE `id` = ?;";
        $this->db->query($sql, [$avatar, $user_id]);
        return $this->db->insert_id();
    }

    public function countUser($username)
    {
        $sql = "SELECT COUNT(*) c FROM `users` WHERE `username` = ?";
        return $this->db->query($sql, [$username])->row()->c;
    }

    public function countEmail($email)
    {
        $sql = "SELECT COUNT(*) c FROM `users` WHERE `email` = ?";
        return $this->db->query($sql, [$email])->row()->c;
    }

    public function countToken($token)
    {
        $sql = "SELECT COUNT(*) c FROM `users` WHERE `token` = ?";
        return $this->db->query($sql, [$token])->row()->c;
    }

    public function checkUserPass($username, $password)
    {
        return $this->db->query('SELECT * FROM users')->result();
    }

}
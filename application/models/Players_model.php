<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Players_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    public function getPlayers($limit = null, $offset = null)
    {
        if ($limit != null && $offset = null) {
            $sql = "SELECT * FROM `players` ORDER BY points ASC LIMIT ? OFFSET ?";
            return $this->db->query($sql, [$limit, $offset])->result();
        } else if ($limit != null) {
            $sql = "SELECT * FROM `players` ORDER BY checked ASC LIMIT ?";
            return $this->db->query($sql, [$limit])->result();
        } else {
            return $this->db->query("SELECT * FROM `players` ORDER BY points DESC")->result();
        }
    }

    public function getPlayer($id)
    {
        $sql = "SELECT * FROM players WHERE id = ?";
        return $this->db->query($sql, [$id])->result()[0];
    }

    public function getPlayerByName($name)
    {
        $sql = "SELECT * FROM players WHERE summoner_name = ?";
        return $this->db->query($sql, [$name])->result()[0];
    }

    public function setPlayer($name)
    {   
        $sql = "INSERT INTO `players` (`id`, `summoner_id`, `account_id`, `summoner_name`, `icon_id`, `level`, `league`, `rank`, `wins`, `loses`, `points`, `active`) VALUES (NULL, '', '', ?, 0, 0, '', '', 0, 0, 0, 1);";
        return $this->db->query($sql, [$name]);
    }

    public function updatePlayer($id, $icon_id, $level, $league, $rank) {
        $sql = "UPDATE `players` SET `icon_id` = ?, `level` = ?, `league` = ?, `rank` = ?, `checked` = current_timestamp() WHERE `players`.`id` = ?;";
        return $this->db->query($sql, [$icon_id, $level, $league, $rank, $id]);
    }

    public function updatePlayerFull($id, $summoner_id, $account_id, $icon_id, $level, $league, $rank) {
        $sql = "UPDATE `players` SET `summoner_id` = ?, `account_id` = ?, `icon_id` = ?, `level` = ?, `league` = ?, `rank` = ?, `checked` = current_timestamp() WHERE `players`.`id` = ?;";
        return $this->db->query($sql, [$summoner_id, $account_id, $icon_id, $level, $league, $rank, $id]);
    }

    public function countPlayers()
    {
        $sql = "SELECT COUNT(*) c FROM `players`";
        return $this->db->query($sql)->row()->c;
    }

    public function countPlayer($name)
    {
        $sql = "SELECT COUNT(*) c FROM `players` WHERE `summoner_name` = ?";
        return $this->db->query($sql, [$name])->row()->c;
    }
}
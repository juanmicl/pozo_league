<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Summoners_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    public function getSummoners($limit = null, $offset = null)
    {
        if ($limit != null && $offset = null) {
            $sql = "SELECT * FROM `summoners` ORDER BY points ASC LIMIT ? OFFSET ?";
            return $this->db->query($sql, [$limit, $offset])->result();
        } else if ($limit != null) {
            $sql = "SELECT * FROM `summoners` ORDER BY checked ASC LIMIT ?";
            return $this->db->query($sql, [$limit])->result();
        } else {
            return $this->db->query("SELECT * FROM `summoners` ORDER BY points DESC")->result();
        }
    }

    public function getSummoner($id)
    {
        $sql = "SELECT * FROM summoners WHERE id = ?";
        return $this->db->query($sql, [$id])->result()[0];
    }

    public function getSummonerByName($name)
    {
        $sql = "SELECT * FROM summoners WHERE summoner_name = ?";
        return $this->db->query($sql, [$name])->result()[0];
    }

    public function setSummoner($summoner_name)
    {   
        $sql = "INSERT INTO `summoners` (`id`, `summoner_id`, `account_id`, `summoner_name`, `icon_id`, `level`, `league`, `rank`, `wins`, `loses`, `points`, `active`) VALUES (NULL, '', '', ?, 0, 0, '', '', 0, 0, 0, 0);";
        $this->db->query($sql, [$summoner_name]);
        return $this->db->insert_id();
    }

    public function updateSummoner($id, $icon_id, $level, $league, $rank) {
        $sql = "UPDATE `summoners` SET `icon_id` = ?, `level` = ?, `league` = ?, `rank` = ?, `checked` = current_timestamp() WHERE `summoners`.`id` = ?;";
        return $this->db->query($sql, [$icon_id, $level, $league, $rank, $id]);
    }

    public function updateSummonerFull($id, $summoner_id, $account_id, $icon_id, $level, $league, $rank) {
        $sql = "UPDATE `summoners` SET `summoner_id` = ?, `account_id` = ?, `icon_id` = ?, `level` = ?, `league` = ?, `rank` = ?, `checked` = current_timestamp() WHERE `summoners`.`id` = ?;";
        return $this->db->query($sql, [$summoner_id, $account_id, $icon_id, $level, $league, $rank, $id]);
    }

    public function updatePoints($id, $points) {
        $sql = "UPDATE `summoners` SET `points` = ? WHERE id = ?;";
        return $this->db->query($sql, [$points, $id]);
    }

    public function getActive($id)
    {
        $sql = "SELECT active FROM summoners WHERE id = ?";
        return $this->db->query($sql, [$id])->result()[0];
    }

    public function updateActive($id) {
        $sql = "UPDATE `summoners` SET `active` = current_timestamp() WHERE id = ?;";
        return $this->db->query($sql, [$id]);
    }

    public function countsummoners()
    {
        $sql = "SELECT COUNT(*) c FROM `summoners`";
        return $this->db->query($sql)->row()->c;
    }

    public function countSummoner($name)
    {
        $sql = "SELECT COUNT(*) c FROM `summoners` WHERE `summoner_name` = ?";
        return $this->db->query($sql, [$name])->row()->c;
    }
}
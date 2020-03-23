<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Players_model extends CI_Model
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

    public function setPlayer($match_id, $summoner_id, $data)
    {   
        $sql = "INSERT INTO `players` (`id`, `match_id`, `summoner_id`, `champion_id`, `lane`, `win`, `kills`, `deaths`, `assists`, `first_blood`, `first_tower`, `magic_damage`, `physical_damage`, `total_damage`, `total_damage_taken`, `total_heal`, `minions_killed`, `gold_earned`, `cs_per_min`, `gold_per_min`, `wards_placed`, `wards_killed`, `penta_kills`, `longest_time_alive`, `total_cc_time`) VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
        $this->db->query($sql, [$match_id, $summoner_id, $data['champion_id'], $data['lane'], $data['win'], $data['kills'], $data['deaths'], $data['assists'], $data['first_blood'], $data['first_tower'], $data['magic_damage'], $data['physical_damage'], $data['total_damage'], $data['total_damage_taken'], $data['total_heal'], $data['minions_killed'], $data['gold_earned'], $data['cs_per_min'], $data['gold_per_min'], $data['wards_placed'], $data['wards_killed'], $data['penta_kills'], $data['longest_time_alive'], $data['total_cc_time']]);
        return $this->db->insert_id();
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
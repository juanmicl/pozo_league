<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Players_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    public function getPlayer($summoner_id)
    {
        $sql = "SELECT * FROM `players` WHERE summoner_id = ?";
        return $this->db->query($sql, [$summoner_id])->result();
    }

    public function setPlayer($match_id, $summoner_id, $data)
    {   
        $sql = "INSERT INTO `players` (`id`, `match_id`, `summoner_id`, `champion_id`, `lane`, `win`, `kills`, `deaths`, `assists`, `first_blood`, `first_tower`, `magic_damage`, `physical_damage`, `total_damage`, `total_damage_taken`, `total_heal`, `minions_killed`, `gold_earned`, `cs_per_min`, `gold_per_min`, `wards_placed`, `wards_killed`, `penta_kills`, `longest_time_alive`, `total_cc_time`) VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
        $this->db->query($sql, [$match_id, $summoner_id, $data['champion_id'], $data['lane'], $data['win'], $data['kills'], $data['deaths'], $data['assists'], $data['first_blood'], $data['first_tower'], $data['magic_damage'], $data['physical_damage'], $data['total_damage'], $data['total_damage_taken'], $data['total_heal'], $data['minions_killed'], $data['gold_earned'], $data['cs_per_min'], $data['gold_per_min'], $data['wards_placed'], $data['wards_killed'], $data['penta_kills'], $data['longest_time_alive'], $data['total_cc_time']]);
        return $this->db->insert_id();
    }

    public function getPuntuation($summoner_id)
    {
        $sql = "SELECT sum(if(win=true,0,1)) loses, sum(if(win=true,1,0)) wins FROM `players` WHERE summoner_id = ?";
        return $this->db->query($sql, [$summoner_id])->result()[0];
    }

    public function getTopStatsDesc($stat)
    {
        $sql = "SELECT * FROM `players` ORDER BY ".$stat." DESC LIMIT 1";
        return $this->db->query($sql)->result()[0];
    }

    public function countPlayer($name)
    {
        $sql = "SELECT COUNT(*) c FROM `players` WHERE `summoner_name` = ?";
        return $this->db->query($sql, [$name])->row()->c;
    }
}
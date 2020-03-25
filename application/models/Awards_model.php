<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Awards_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    public function getAwards()
    {
        return $this->db->query("SELECT * FROM `awards`")->result();
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

    public function setSummonerAward($summoner_id, $award_id)
    {   
        $sql = "INSERT INTO `awards_given` (`id`, `summoner_id`, `award_id`) VALUES (NULL, ?, ?)";
        $this->db->query($sql, [$summoner_id, $award_id]);
        return $this->db->insert_id();
    }

    public function delSummonerAwardByAwardId($award_id) {
        $sql = "DELETE FROM `awards_given` WHERE award_id = ?";
        return $this->db->query($sql, [$award_id]);
    }

    public function countSummonerAward($award_id)
    {
        $sql = "SELECT COUNT(*) c FROM `awards_given` WHERE `award_id` = ?";
        return $this->db->query($sql, [$award_id])->row()->c;
    }
}
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {

	function __construct()
    {
		parent::__construct();
		$this->load->helper('session');
		$this->load->model('UsersModel');
	}
	
	public function unlock_item($item_id = null)
	{
		checkToken();

		$this->load->model('Items_model');
		$this->load->model('Transactions_model');

		$user_data = $this->UsersModel->getUser($_COOKIE['token']);
		$item = $this->Items_model->getItem($item_id);

		if ($this->Transactions_model->countTransaction($user_data->id, $item->id) == 0) {
			if ($user_data->points >= $item->points) {
				$this->UsersModel->decreasePoints($user_data->id, $item->points);
				$this->Transactions_model->setTransaction($user_data->id, $item->user_id, $item->id, $item->points);
				$output = ["status" => 200, "msg" => "unlocked sucesfully"];
				echo json_encode($output);
			} else {
				$output = ["status" => 403, "msg" => "you dont have enough funds"];
				echo json_encode($output);
			}
		} else {
			$output = ["status" => 400, "msg" => "already unlocked"];
			echo json_encode($output);
		}	
	}

	public function selly_callback()
	{
		echo('holaaa');
	}
}
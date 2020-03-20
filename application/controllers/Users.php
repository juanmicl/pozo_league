<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller {

	function __construct()
    {
		parent::__construct();
		$this->load->helper('session');
		$this->load->model('UsersModel');
    }

	public function profile($username = null)
	{
		checkToken();
		checkAlert();
		
		$this->load->model('Breaches_model');
		$this->load->model('Combos_model');
		$this->load->model('Transactions_model');
		$this->load->model('Reputations_model');

		if ($this->UsersModel->countUser($username) == 0) {
			header('Location: /404');
            exit();
		}

		$user_data = $this->UsersModel->getUser($_COOKIE['token']);
		$profile_data = $this->UsersModel->getUserbyUsername($username);
		$already_reped = $this->Reputations_model->countReputation($user_data->id, $profile_data->id);

		if ($user_data->id == $profile_data->id) { $can_rep = false; } else { $can_rep = true; }

		$data = [
			'title' => 'Profile of '.$profile_data->username,
			'reputations' => $this->Reputations_model->getReputations($profile_data->id),
			'count_reputation' => $this->Reputations_model->getUserReputation($profile_data->id),
			'already_reped' => $already_reped,
			'can_rep' => $can_rep,
			'breaches' => $this->Breaches_model->countUserBreaches($profile_data->id),
			'combos' => $this->Combos_model->countUserCombos($profile_data->id),
			'profile_data' => $profile_data,
			'userData' => $user_data,
			'loggedIn' => isLoggedIn(),
			'isAdmin' => isAdmin()
		];

		if ($this->input->post('reputation')) {
			// Check empty fields
			if($this->input->post('reputation') || $this->input->post('comment')) {
				if ($already_reped == "0") {
					// add reputation
					if ($this->input->post('reputation') == "1") {
						$rep_points = 2;
					} elseif ($this->input->post('reputation') == "-1") {
						$rep_points = -2;
					} else {
						$rep_points = 0;
					}
					$this->Reputations_model->setReputation(
						$data['userData']->id, $data['profile_data']->id, $rep_points, strip_tags($this->input->post('comment'))
					);
					header("Refresh:0");
					exit('Refresh this shit :)');
				} else {
					// edit reputation
					if ($this->input->post('reputation') == "1") {
						$rep_points = 2;
					} elseif ($this->input->post('reputation') == "-1") {
						$rep_points = -2;
					} else {
						$rep_points = 0;
					}
					$this->Reputations_model->editReputation(
						$data['userData']->id, $data['profile_data']->id, $rep_points, strip_tags($this->input->post('comment'))
					);
					header("Refresh:0");
					exit('Refresh this shit :)');
				}
			} else {
				$data['error'] = "Please enter all fields";
			}
        }
		
		$this->load->view('templates/header', $data);
        $this->load->view('users/profile', $data);
        $this->load->view('templates/footer');
	}
}
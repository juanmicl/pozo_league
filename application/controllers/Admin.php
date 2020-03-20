<?php

use SebastianBergmann\GlobalState\Exception;

defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

	function __construct()
    {
        parent::__construct();
        $this->load->helper('session');
		$this->load->helper('currencies');
        $this->load->model('ProductsModel');
        $this->load->model('OrdersModel');
        $this->load->model('HwidsModel');
		$this->load->model('UsersModel');
    }

	public function orders()
	{
        checkToken();
        checkAlert();
        
        if (!isAdmin()) {
            header('Location: /error/404');
            exit;
        }

        $orders_data = [];
        $orders = $this->OrdersModel->getOrders();

        foreach ($orders as $order) {

            array_push($orders_data, [
                'order_id' => $order->id,
                'date' => $order->created_at,
                'hwids' => $this->HwidsModel->getHwids($order->id),
                'username' => $this->UsersModel->getUsername($order->user_id),
                'product' => $this->ProductsModel->getProduct($order->product_id)
            ]);

        }

		$data = [
			'title' => 'Orders',
            'userData' => $this->UsersModel->getUser($_COOKIE['token']),
            'orders' => json_encode($orders_data),
			'loggedIn' => isLoggedIn(),
			'isAdmin' => isAdmin()
        ];
		
		$this->load->view('templates/header', $data);
        $this->load->view('admin/orders', $data);
        $this->load->view('templates/footer');
	}

}
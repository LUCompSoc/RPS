<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Scoreboard extends CI_Controller
{
	public function index()
	{
		$this->load->helper('url');
		$this->load->model('users_model');

		$data = array
		(
			'players' => $this->users_model->find_by_ranking()
		);

		$this->load->view('scoreboard', $data);
	}
}

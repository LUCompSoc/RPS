<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Register extends CI_Controller
{
	public function index()
	{
		$this->load->helper('url');

		$new_user = false;
		$error = false;
		$user = false;

		if($this->input->post())
		{
			$username = $this->input->post('username');

			if(!ctype_alnum($username))
			{
				$error = 'Invalid username, please only use letters and numbers.';
			}
			else
			{
				$this->load->model('users_model');

				$user = $this->users_model->find_by_username($username);

				if(!$user)
				{
					$new_user = true;
					$this->users_model->create_user($username);
					$user = $this->users_model->find_by_username($username);
				}
				else
				{
					$error = 'Name already taken, sorry!';
				}
			}
		}

		$this->load->view('register', array
		(
			'new_user' => $new_user
		,	'error' => $error
		,	'user' => $user
		));
	}
}
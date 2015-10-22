<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Attempts_model extends CI_Model
{
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}
	
	function save($user_id = null, $user_attempt = null, $computer_attempt = null)
	{
		if(is_null($user_id) || is_null($user_attempt) || is_null($computer_attempt))
		{
			return false;
		}

		$this->db->insert('attempts', array
		(
			'user_id'			=> $user_id
		,	'user_attempt'		=> $user_attempt
		,	'computer_attempt'	=> $computer_attempt
		));
	}

	function last_3_moves($user_id = null)
	{
		if(is_null($user_id))
		{
			return false;
		}

		return $this->db
			->select('user_attempt')
			->where('user_id', $user_id)
			->limit(3)
			->order_by('created_on', 'desc')
			->get('attempts')
			->result();
	}
}
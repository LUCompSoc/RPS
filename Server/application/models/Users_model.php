<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Users_model extends CI_Model
{
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
    
    function create_user($username = null)
    {
        if($this->find_by_username($username))
        {
            return false;
        }

        $data = array
        (
            'username' => $username
        ,   'token' => md5(time() . uniqid())
        );

        $this->db->insert('users', $data);
    }

    function find_by_token($token = null)
    {
        if(is_null($token))
        {
            return false;
        }

        $user = $this->db
            ->where('token', $token)
            ->limit(1)
            ->get('users')
            ->result();

        return empty($user) ? false : $user[0];
    }

    function find_by_username($username = null)
    {
        if(is_null($username))
        {
            return false;
        }

        $user = $this->db
            ->where('username', $username)
            ->limit(1)
            ->get('users')
            ->result();

        return empty($user) ? false : $user[0];
    }

    function find_by_user_id($user_id = null)
    {
        if(is_null($user_id))
        {
            return false;
        }

        $user = $this->db
            ->where('user_id', $user_id )
            ->limit(1)
            ->get('users')
            ->result();

        return empty($user) ? false : $user[0];
    }

    function update_user($user_id = null, $win_increment = 0, $attempt_increment = 0)
    {
        if($attempt_increment == 0)
        {
            return false;
        }

        $user = $this->find_by_user_id($user_id);

        if(!$user)
        {
            return false;
        }
        
        $data = array
        (
            'attempts' => (int)$user->attempts + $attempt_increment
        );

        if($win_increment > 0)
        {
            $data['wins'] = (int)$user->wins + $win_increment;
        }

        $this->db->where('user_id', $user_id);
        $this->db->update('users', $data);
    }


    function find_by_ranking()
    {
        $query = $this->db
            ->order_by('wins', 'desc')
            ->get('users');

        return $query->result();
    }
}
<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Play extends CI_Controller
{
	public function index()
	{
		if(empty($this->input->post()))
		{
			$this->out('Nuh-uh-uh! Only post requests plz.');
		}

		$token = $this->input->post('token');
		$move = $this->input->post('move');
		$moves = array('rock', 'paper', 'scissors');
		$moves_advanced = array('lizard', 'spock');

		if(empty($move))
		{
			$this->out('Feed me data!');
		}

		if(!in_array($move, $moves))
		{
			$this->out('What are you playing?!');
		}

		$this->load->model('users_model');
		$this->load->model('attempts_model');

		if(in_array($move, array($moves_advanced)))
		{
			$moves = array_merge($moves, $moves_advanced);
		}

		// Cool, now let the computer generate a random answer:
		$computer = $moves[array_rand($moves)];
		$user = $this->users_model->find_by_token($token);

		if($user)
		{
			$last3 = $this->attempts_model->last_3_moves($user->user_id);
			if(sizeof($last3) == 3 && ($last3[0]->user_attempt == $last3[1]->user_attempt && $last3[0]->user_attempt == $last3[2]->user_attempt))
			{
				switch($last3[0]->user_attempt)
				{
					case 'rock':
						$computer = 'paper';
						break;
					case 'paper':
						$computer = 'scissors';
						break;
					case 'scissors':
						$computer = 'rock';
						break;
					case 'lizard':
						$computer = 'scissors';
						break;
					case 'spock':
						$computer = 'lizard';
				}
			}
		}

		// Could lose or draw
		$win = false;
		$draw = false;

		if($move == $computer)
		{
			$draw = true;
		}
		else
		{
			if($move == 'rock' && ($computer == 'scissors' || $computer == 'lizard'))
			{
				$win = true;
			}
			if ($move == 'scissors' && ($computer == 'paper' || $computer == 'lizard'))
			{
				$win = true;
			}
			if ($move == 'paper' && ($computer == 'rock' || $computer == 'spock'))
			{
				$win = true;
			}

			// Bonus!
			if ($move == 'lizard' && ($computer == 'spock' || $computer == 'paper'))
			{
				$win = true;
			}
			if ($move == 'spock' && ($computer == 'scissors' || $computer == 'rock'))
			{
				$win = true;
			}
		}

		if($user)
		{
			if($user->attempts == 50)
			{
				$this->out('Game over! You\'re only allowed 50 attempts!');
			}

			$this->users_model->update_user($user->user_id, $win ? 1 : 0, $draw ? 0 : 1);
			$this->attempts_model->save($user->user_id, $move, $computer);
		}

		/*$output = $computer;

		$this->output->set_content_type('text');
		$this->output->set_output(json_encode($output));*/

		$this->out($computer);
	}

	private function out($text = '')
	{
		header('Content-Type: text/plain');
		exit($text);
	}

	/*
	public function test()
	{
		error_reporting(E_ALL);

		// Allow the script to hang around waiting for connections.
		set_time_limit(0);

		// Turn on implicit output flushing so we see what we're getting as it comes in. 
		ob_implicit_flush();

		$address = 'localhost';
		$port = 8081;

		if (($sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP)) === false)
		{
		    echo "socket_create() failed: reason: " . socket_strerror(socket_last_error()) . "\n";
		}

		if (socket_bind($sock, $address, $port) === false)
		{
		    echo "socket_bind() failed: reason: " . socket_strerror(socket_last_error($sock)) . "\n";
		}

		if (socket_listen($sock, 5) === false)
		{
		    echo "socket_listen() failed: reason: " . socket_strerror(socket_last_error($sock)) . "\n";
		}

		do {
		    if (($msgsock = socket_accept($sock)) === false)
		    {
		        echo "socket_accept() failed: reason: " . socket_strerror(socket_last_error($sock)) . "\n";
		        break;
		    }

		    // Send instructions.
		    $msg = "\nWelcome to the PHP Test Server. \n" .
		        "To quit, type 'quit'. To shut down the server type 'shutdown'.\n";
		    socket_write($msgsock, $msg, strlen($msg));

		    do
		    {
		        if (false === ($buf = socket_read($msgsock, 2048, PHP_NORMAL_READ)))
		        {
		            echo "socket_read() failed: reason: " . socket_strerror(socket_last_error($msgsock)) . "\n";
		            break 2;
		        }

		        if (!$buf = trim($buf)) {
		            continue;
		        }

		        if ($buf == 'quit')
		        {
		            break;
		        }

		        if ($buf == 'shutdown') {
		            socket_close($msgsock);
		            break 2;
		        }

		        $talkback = "PHP: You said '$buf'.\n";
		        socket_write($msgsock, $talkback, strlen($talkback));
		        echo "$buf\n";
		    }
		    while (true);

		    socket_close($msgsock);

		}
		while (true);

		socket_close($sock);

	/*
		$address = "localhost";

		// Port to listen
		$port = 8080;

		$mysock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

		socket_bind($mysock,$address, $port) or die('Could not bind to address'); 
		socket_listen($mysock, 5);
		$client = socket_accept($mysock);

		while(true)
		{
			// read 1024 bytes from client
			$input = socket_read($client, 1024);

			if (($msgsock = socket_accept($sock)) === false) {
			    continue;
			}

			socket_write($msgsock, "gay", 3 );

			// write received gprs data to the file
			//writeToFile('gprs.log', $input);

			echo $input;
		}

		socket_close($client);
		socket_close($mysock);
	}
	*/
}

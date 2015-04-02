<?php 
	session_start();
	include_once('new-connection.php');

//-------- "Quasi ROUTES"
	if (isset($_POST['action']) && $_POST['action'] == 'register')
	{
		register_user($_POST);
	}
	elseif (isset($_POST['action']) && $_POST['action'] == 'login')
	{
		log_in($_POST);
	}
	elseif (isset($_POST['action']) && $_POST['action'] == 'post-message')
	{
		add_message($_POST);
	}
	elseif (isset($_POST['action']) && $_POST['action'] == 'post-comment')
	{
		add_comment($_POST);
	}
	else
	{
		session_destroy();
		header('location: index.php');
	}

	function log_in($post)
	{
		//find by email
		$query = "SELECT * FROM users WHERE email='{$post['email']}';";
		$record = fetch_record($query);
		if (count($record) > 0 && $record['password'] == md5($post['password']) )
		{

			$_SESSION['user_id'] = $record['id'];
			$_SESSION['first_name'] = $record['first_name'];
			$_SESSION['logged_in'] = TRUE;
			header('location: success.php');
		}
		else
		{
			$_SESSION['errors'][] = "Can't find a user with those inputs";	
			header('location: index.php');
		}
	}

	function register_user($post)
	{
		$_SESSION['errors'] = array();
		if (empty($post['first_name']))
		{
			$_SESSION['errors'][] = "first name can't be blank";
		}
		if (empty($post['last_name']))
		{
			$_SESSION['errors'][] = "last name can't be blank";
		}
		if (empty($post['email']))
		{
			$_SESSION['errors'][] = "email can't be blank";
		}
		if (empty($post['password']))
		{
			$_SESSION['errors'][] = "password can't be blank";
		}
		if ($post['password'] != $post['confirm'])
		{
			$_SESSION['errors'][] = "confirmed password does not match";
		}
		if(!filter_var($post['email'], FILTER_VALIDATE_EMAIL))
		{
			$_SESSION['errors'][] = "please use a valid email address";
		}

		if (count($_SESSION['errors']) > 0)
		{
			header('location: index.php');
			die();
		}
		else
		{
			$password = md5($post['password']);
			$query = "INSERT INTO users (first_name, last_name, password, email, created_at, updated_at)
					VALUES ('{$post['first_name']}', '{$post['last_name']}', '{$password}', '{$post['email']}',
							NOW(), NOW());";
			$insert = run_mysql_query($query);
			
			if (isset($insert))
			{
				$_SESSION['success'] = 'User added! Please log in';
				header('location: index.php');
				die();
			}
		}
	}

//--------- insert comments and messages


	function add_message($message)
	{

		$clean_mess = escape_this_string($message['message']);

		$query = "INSERT INTO messages (message, users_id, created_at, updated_at)
					VALUES ('{$clean_mess}', '{$_SESSION['user_id']}', NOW(), NOW());";
		// echo $query;
		// die();
		$insert = run_mysql_query($query);
		header('location: success.php');

	}

	function add_comment($comment)
	{
		$clean_comm = escape_this_string($comment['comment']);
		$clean_user_id = escape_this_string($comment['user_id']);
		$clean_mess_id = escape_this_string($comment['message_id']);

		$query = "INSERT INTO comments (comment, users_id, messages_id, updated_at, created_at)
					VALUES ('{$clean_comm}', '{$clean_user_id}', '{$clean_mess_id}' , NOW(), NOW());";

		$insert = run_mysql_query($query);
		header('location: success.php');

	}


 ?>
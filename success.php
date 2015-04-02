<?php  
	session_start();
	require_once('new-connection.php');
	//make sure the user is login in to see the wall
	if(!isset($_SESSION['logged_in']))
	{
		header('location: index.php');
	}

	$messages_query = "SELECT messages.*, users.first_name, users.last_name FROM messages LEFT JOIN users on users.id = messages.users_id ORDER BY messages.created_at DESC;";
	$messages = fetch_all($messages_query);
	//var_dump($messages);

	$comments_query = "SELECT comments.*, users.first_name, users.last_name FROM comments LEFT JOIN users on users.id = comments.users_id;";
	$comments = fetch_all($comments_query);
	//var_dump($comments);


?>

<html>
<head>
	<title>Login Success</title>
</head>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
<style type="text/css">
	.message-box {
		width: 100%;
		height:150px;
		border: 1px solid gray;
		border-radius: 8px
	}


</style>

<body>
	<div class='container'>
		<div class='row'>
			<div class='col-md-12'>
				<h1> Hello <?= $_SESSION['first_name'] ?> </h1>
				<a href="process.php">Log Off</a>
			</div>
		</div>
		<div class='row'>
			<div class='col-md-12'>
				<h2>Post a Message</h2>
				<form action='process.php' method='post'>
					<input type='hidden' name='action' value='post-message'>
					<textarea class='message-box' name='message' placeholder='leave a message here' ></textarea>	
					<div class='text-right'>
						<button type='submit' class='btn btn-default'>Post a Message</button>
					</div>
				</form>
			</div>
		</div>
		
<?php 
	foreach ($messages as $message) {
?>
		<div class='row'>
			<div class='col-md-12'>
				<h4><?= $message['first_name'] ?> <?= $message['last_name'] ?> <?= $message['created_at'] ?> </h4>
				<div class='wall-message'>
					<p> <?= $message['message'] ?> </p>
				</div>
			</div>
		</div>
<?php 
		foreach ($comments as $comment) {
			if($comment['messages_id'] == $message['id'])
			{
?>
		<div class='row'>
			<div class='col-md-offset-1 col-md-11'>
			<h4><?= $comment['first_name'] ?> <?= $comment['last_name'] ?> <?= $comment['created_at'] ?> </h4>	
				<div class='comment-message'>
					<p> <?= $comment['comment'] ?> </p>
				</div>
			</div>
		</div>
<?php 
			}
		}
?>
		<div class='row'>
			<div class='col-md-offset-1 col-md-11'>
				<div class='comment-post'>
					<h4>Post a comment</h4>
					<form action='process.php' method='post'>
						<input type='hidden' name='action' value='post-comment'>
						<input type='hidden' name='message_id' value='<?= $message['id'] ?>' >
						<input type='hidden' name='user_id' value='<?= $_SESSION['user_id'] ?>' >
						<textarea class='message-box' name='comment'></textarea>
						<div class='text-right'>
							<button type='submit' class='btn btn-default'>Post a comment</button>
						</div>
					</form>
				</div>
			</div>
		</div>
<?php 
	}
?>
	</div><!-- end of container -->
	
</body>
</html>
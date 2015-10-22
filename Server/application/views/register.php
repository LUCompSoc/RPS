<!DOCTYPE html>
<html>
	<head>
		<title>LUCSS Hackathon | Rock Paper Scissors Scoreboard</title>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
		<link rel="stylesheet" href="<?php echo site_url('/public/css/core.css'); ?>">
	</head>
	<body>
		<div class="container">
			<div class="header clearfix">
				<nav>
					<ul class="nav nav-pills pull-right">
						<li role="presentation"><a href="<?php echo site_url(); ?>">Home</a></li>
						<li role="presentation" class="active"><a href="<?php echo site_url('register'); ?>">Register</a></li>
					</ul>
				</nav>
				<h3 class="text-muted">Register to compete</h3>
			</div>
			<div class="panel panel-default">
				<div class="panel-body">
					<?php if($error): ?>
						<div class="alert alert-danger" role="alert"><?php echo $error; ?></div>
					<?php elseif($this->input->post() && $new_user): ?>
						Congrats! You are now registered, <?php echo $user->username; ?>.
						<br />
						Your unique token is (please keep this safe!):
						<div class="alert alert-success" role="alert"><?php echo $user->token; ?></div>
						Now claim your <a href="<?php echo site_url('Sample_Bot.zip') ?>">free Resources</a>!
					<?php else: ?>
					<form method="post">
						<div class="form-group">
							<label for="username">What do you (or your team) want to be called?</label>
							<input type="text" name="username" class="form-control" id="username">
						</div>
						<button type="submit" class="btn btn-default">Submit</button>
					</form>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</body>
</html>




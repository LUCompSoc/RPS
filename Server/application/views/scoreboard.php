<!DOCTYPE html>
<html>
	<head>
		<title>LUCSS Hackathon | Rock Paper Scissors Scoreboard</title>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
		<link rel="stylesheet" href="<?php echo site_url('/public/css/core.css'); ?>">
		<script>
			function refresh()
			{
				window.location.reload(true);
			}

			//setTimeout(refresh, 1000);
		</script>
	</head>
	<body>
		<div class="container">
			<div class="header clearfix">
				<nav>
					<ul class="nav nav-pills pull-right">
						<li role="presentation" class="active"><a href="<?php echo site_url(); ?>">Home</a></li>
						<li role="presentation"><a href="<?php echo site_url('register'); ?>">Register</a></li>
					</ul>
				</nav>
				<h3 class="text-muted">Rock Paper Scissors Scoreboard</h3>
			</div>
			<div class="panel panel-default">
				<!-- Table -->
				<table class="table">
					<tr>
						<th>Rank</th>
						<th>Player</th>
						<th>Wins</th>
						<th>Attempts</th>
						<th>Win-rate</th>
					</tr>
					<?php $i = 1; foreach($players as $player): ?>
					<tr>
						<td><strong><?php echo $i; ?></strong></td>
						<td><?php echo $player->username; ?></td>
						<td><?php echo $player->wins; ?></td>
						<td><?php echo $player->attempts; ?></td>
						<td><?php echo ($player->attempts == 0 ? 0 : (((int)$player->wins / (int)$player->attempts) * 100)) . '%'; ?></td>
					</tr>
					<?php $i++; endforeach; ?>
				</table>
			</div>
		</div>
	</body>
</html>




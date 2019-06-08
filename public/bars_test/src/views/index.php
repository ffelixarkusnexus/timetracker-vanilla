<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css">
		<link rel="stylesheet" href="/bars_test/app.css">
		<title>Home Page</title>
	</head>
	<body>
		
		<div class="tz-container">
			
			<div class="tz-header">
				<h1 class="tz-heading">Home Page</h1>
			</div>

			<div class="tz-section">
				<ul class="tz-list-nav">
					<li>Controls</li>
					<li><button id="increase">Increase</button></li>
				</ul>
			</div>

			<?php foreach ($users as $user) : ?>
				<div class="tz-section">

					<div class="tz-bar" data-user="<?php echo htmlspecialchars(json_encode($user), ENT_QUOTES, 'UTF-8') ?>">
					
						<div class="tz-half-bar" style="position: relative;">
							<div class="tz-user"><?php echo $user['name'] ?></div>
							<div class="tz-slider control1"></div>
						</div>

						<div class="tz-half-bar">
							<div class="tz-slider control2"></div>
						</div>
						
					</div>

				</div>
			<?php endforeach; ?>

		</div>

		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
		<script src="/bars_test/app.js"></script>
	</body>
</html>
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="utf-8">
	<title></title>

	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous">
</head>
<body>

<div id="container">
	<h1>Welcome to CodeIgniter!</h1>

	<table>
		<tr>
			<td>AppID</td>
			<td>account_name</td>
			<td>language_id</td>
			<td>function_id</td>
			<td>mode</td>
			<td>followback</td>
			<td>fire_lv</td>
			<td>target_user_id</td>
			<td>search_keyword</td>
			<td>search_option</td>
		</tr>
		<?php foreach($twitterUsers as $twitterUser) { ?>
		<tr>
			<td><?= $twitterUser["app_id"]; ?></td>
			<td><?= $twitterUser["language_id"]; ?></td>
			<td><?= $twitterUser["function_id"]; ?></td>
			<td><?= $twitterUser["mode"]; ?></td>
			<td><?= $twitterUser["followback"]; ?></td>
			<td><?= $twitterUser["fire_lv"]; ?></td>
			<td><?= $twitterUser["target_user_id"]; ?></td>
			<td><?= $twitterUser["search_keyword"]; ?></td>
			<td><?= $twitterUser["search_option"]; ?></td>
		</tr>
		<?php } ?>
	</table>
	<p>.</p>
	<p class="footer">Page rendered in <strong>{elapsed_time}</strong> seconds. <?php echo  (ENVIRONMENT === 'development') ?  'CodeIgniter Version <strong>' . CI_VERSION . '</strong>' : '' ?></p>
</div>

</body>
</html>

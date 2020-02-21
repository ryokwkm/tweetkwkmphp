<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="ja">
<head>
	<title></title>
	<meta charset="utf-8" />
	<link rel="apple-touch-icon" sizes="76x76" href="/assets/img/apple-icon.png">
	<link rel="icon" type="image/png" href="/assets/img/favicon.png">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
	<!--     Fonts and icons     -->
	<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
	<!-- CSS Files -->
	<link href="/assets/css/material-dashboard.css?v=2.1.1" rel="stylesheet" />
	<!-- CSS Just for demo purpose, don't include it in your project -->
	<link href="/assets/demo/demo.css" rel="stylesheet" />
	<style type="text/css">
		thead, tbody {

		}
		th {white-space: nowrap;}

	</style>
</head>

<body class="">
<div class="wrapper ">

<!--	<div class="main-panel">-->

		<!-- End Navbar -->
		<div class="content">
			<div class="container-fluid">
				<div class="row">

					<div class="col-md-12">
						<div class="card">

							<div class="card-header card-header-primary">
								<h4 class="card-title mt-0"> Table on Plain Background</h4>
								<p class="card-category"> Here is a subtitle for this table</p>
							</div>

							<div class="card-body">
								<div class="table-responsive">
									<table class="table table-hover">
										<thead class=" text-primary">
											<th>AppID</th>
											<th>account_name</th>
											<th>function</th>

											<th>対象ユーザーID</th>
											<th>モード</th>
											<th>トレンドサーチ</th>

											<th>search_keyword</th>
											<th>search_option</th>

											<th>実行確率</th>
											<th>言語</th>

											<th>followback</th>
											<th>リプをリツイート</th>
											<th>お返事</th>

											<th>fire_lv</th>
											<th>いいねサーチ</th>
										</thead>
										<tbody>

											<?php foreach($twitterUsers as $twitterUser) { ?>
												<tr>
													<td ><?= $twitterUser["app_id"]; ?></td>
													<td><?= $twitterUser["account_name"]; ?></td>
													<td><?= TwitterFunc($twitterUser["function_id"]); ?></td>

													<td><?= $twitterUser["target_user_id"]; ?></td>
													<td><?= TwitterMode($twitterUser["mode"]); ?></td>
													<td>On仮</td>

													<td><?= $twitterUser["search_keyword"]; ?></td>
													<td><?= $twitterUser["search_option"]; ?></td>

													<td>50%</td>
													<td><?= $twitterUser["language_id"]; ?></td>

													<td><?= OnOffButton($twitterUser["followback"]); ?></td>
													<td><button type="button" class="btn btn-success">かり</button></td>
													<td><button type="button" class="btn btn-success">かり</button></td>

													<td><?= $twitterUser["fire_lv"]; ?></td>
													<td><button type="button" class="btn btn-success">かり</button></td>

												</tr>
											<?php } ?>

										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

<!--	</div>-->
</div>
<?= $jsBase; ?>
</body>

</html>

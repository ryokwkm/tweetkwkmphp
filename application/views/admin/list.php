<style>

	.card {
		margin-bottom: 0px;
	}

</style>



<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-header card-header-primary">
				ユーザーリスト

			</div>
			<div class="card-body" id="status_off">









						<div class="table-responsive">
							<table class="table table-hover">
								<thead class=" text-primary">
								<th>名前</th>
								<th>電源</th>

<!--								<th>実行率</th>-->
<!--								<th>セリフ</th>-->
<!--								<th>トレンドサーチ</th>-->
<!--								<th>ニュース検索</th>-->
								<th>編集</th>
								<th>テスト</th>
								</thead>
								<tbody>

								<?php foreach($twitterUsers as $twitterUser) { ?>
									<tr>
										<td><a target="_blank" href="https://twitter.com/<?= $twitterUser["account_name"]; ?>"><?= $twitterUser["name"]; ?></a></td>
										<td><?= PowerButton($twitterUser["is_deleted"]); ?></td>

										<?php /* if($twitterUser["is_deleted"] == 0) { ?>
											<td> - </td>
											<td> - </td>
											<td> - </td>
											<td> - </td>
										<?php } else { ?>
											<td><?= $twitterUser["exe_rate"]; ?></td>
											<td><?= OnOffButton($twitterUser["character_mode"]); ?></td>
											<td><?= OnOffButton($twitterUser["is_search"]); ?></td>
											<td><?= OnOffButton($twitterUser["is_news"]); ?></td>
										<?php } */ ?>
										<td><a href="/mypage/user/<?= $twitterUser["id"]; ?>"><button type="button" class="btn btn-default">編集</button></a></td>
										<td><a href="/mypage/test_user/<?= $twitterUser["id"]; ?>"><button type="button" class="btn btn-default">テスト</button></a></td>
									</tr>
								<?php } ?>

								</tbody>
							</table>
						</div>









			</div>





			<div class="clearfix"></div>

		</div>
	</div>


</div>

</div>

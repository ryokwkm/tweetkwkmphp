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
								<th>確認</th>
								</thead>
								<tbody>

								<?php foreach($twitterUsers as $twitterUser) { ?>
									<tr>
										<td><a target="_blank" href="https://twitter.com/<?= $twitterUser["account_name"]; ?>"><?= $twitterUser["name"]; ?></a></td>
										<td><?= PowerButton($twitterUser["is_deleted"]); ?></td>
										<td><a href="/general/user/<?= $twitterUser["id"]; ?>"><button type="button" class="btn btn-default">確認</button></a></td>
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


<div class="container-fluid">
	<div class="row">
		<div class="col-md-8 offset-md-2">
			<div class="card">
				<div class="card-header card-header-primary">
					<h4 class="card-title"><?= $title; ?></h4>
					<p class="card-category">アプリを登録</p>
				</div>
				<div class="card-body">
					<form action="/auth/login" method="post">
						<input type="hidden" name="account_name" value="<?= $account_name; ?>">

						<p class="category">
							Botを新たに作成します。<br>
							Bot化したいTwitterアカウントでログインしてください。<br>
							電源ボタンをOffにすることで、Botはいつでも停止させることが出来ます。<br>
						</p>

						<?php if (!empty($sisters)) { ?>

						<div class="form-group">
							コントローラーを選択<BR>
							<select class="form-control " name="parent_id" >
								<?php if (!empty($users)) { ?>
									<?php foreach($users as $user) {	?>
										<option value="<?= $user["id"] ?>" >
										<?= $user["name"] ?>
										</option>
									<?php } ?>
									<?php	} 	?>
								<option value="-1" >親機として登録</option>
							</select>
						</div>

						<?php } ?>




						<button type="submit" class="btn btn-round btn-primary btn-block">Create</button>

						<div class="clearfix"></div>


					</form>
				</div>
			</div>
		</div>

	</div>
</div>


<div class="container-fluid">
	<div class="row">
		<div class="col-md-8 offset-md-2">
			<div class="card">
				<div class="card-header card-header-primary">
					<h4 class="card-title">Login</h4>
					<p class="card-category">ログイン情報を入力</p>
				</div>
				<div class="card-body">
					<form action="/auth/login" method="post">
						<input type="hidden" name="account_name" value="magialogin">

						<p class="category">
							Botを作成済みの場合、 ログインすることで動作を設定できます<br>
						</p>

						<button type="submit" class="btn btn-primary pull-right">Login</button>
						<div class="clearfix"></div>


					</form>
				</div>
			</div>
		</div>

	</div>
</div>

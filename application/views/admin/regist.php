
<div class="container-fluid">
	<div class="row">
		<div class="col-md-8 offset-md-2">
			<div class="card">
				<div class="card-header card-header-primary">
					<h4 class="card-title">Regist</h4>
					<p class="card-category">アプリを登録</p>
				</div>
				<div class="card-body">
					<form action="/auth/login" method="post">


						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label class="bmd-label-floating">対象アカウント</label>
									<input type="text" name="account_name" class="form-control">
								</div>
							</div>
						</div>

<!--						<div class="row">-->
<!--							<div class="col-md-12">-->
<!--								<div class="form-group">-->
<!--									<label class="bmd-label-floating">パスワード</label>-->
<!--									<input type="text" name="password" class="form-control">-->
<!--								</div>-->
<!--							</div>-->
<!--						</div>-->

						<button type="submit" class="btn btn-primary pull-right">Login</button>
						<div class="clearfix"></div>


					</form>
				</div>
			</div>
		</div>

	</div>
</div>

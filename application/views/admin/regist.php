
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
						<input type="hidden" name="account_name" value="createapp">

						<p class="category">
							Botを新たに作成します。<br>
							Bot化したいTwitterアカウントでログインしてください。<br>
							電源ボタンをOffにすることで、Botはいつでも停止させることが出来ます。<br>
						</p>

<!--						<div class="row">-->
<!--							<div class="col-md-12">-->
<!--								<div class="form-group">-->
<!--									<label class="bmd-label-floating">対象アカウント</label>-->
<!--									<input type="text" name="account_name" class="form-control">-->
<!--								</div>-->
<!--							</div>-->
<!--						</div>-->

<!--						<div class="row">-->
<!--							<div class="col-md-12">-->
<!--								<div class="form-group">-->
<!--									<label class="bmd-label-floating">パスワード</label>-->
<!--									<input type="text" name="password" class="form-control">-->
<!--								</div>-->
<!--							</div>-->
<!--						</div>-->

						<button type="submit" class="btn btn-round btn-primary btn-block">Create</button>

						<div class="clearfix"></div>


					</form>
				</div>
			</div>
		</div>

	</div>
</div>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-transparent navbar-absolute fixed-top ">
	<div class="container-fluid">
		<div class="navbar-wrapper">
			<?php
			$pageTitle = "";
			foreach($my_pages as $page) {
				if (strpos($_SERVER['REQUEST_URI'], $page["url"]) === 0) {
					$pageTitle = $page["title"];
				}
			}
			?>
			<a class="navbar-brand" href="/auth/index"><?= $pageTitle; ?></a>
		</div>


		<button class="navbar-toggler" type="button" data-toggle="collapse" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
			<span class="sr-only">Toggle navigation</span>
			<span class="navbar-toggler-icon icon-bar"></span>
			<span class="navbar-toggler-icon icon-bar"></span>
			<span class="navbar-toggler-icon icon-bar"></span>
		</button>

		<div class="collapse navbar-collapse justify-content-end">

			<ul class="navbar-nav">
				<li class="nav-item dropdown">
					<a class="nav-link" href="#pablo" id="navbarDropdownProfile" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<img src="/assets/img/faces/person.png" style="width: 50px; ; border-radius: 300px;" />
						<p class="d-lg-none d-md-block">
							Account
						</p>
					</a>
					<div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownProfile">
						<!--						<a class="dropdown-item" href="#">Profile</a>-->
						<!--						<a class="dropdown-item" href="#">Settings</a>-->
						<!--						<p class="dropdown-item">--><?//= $user_data["display_name"] ?><!--</p>-->
						<!--						<div class="dropdown-divider"></div>-->
						<a class="dropdown-item" href="/mypage/index">ログイン</a>
					</div>
				</li>
			</ul>
		</div>

	</div>
</nav>

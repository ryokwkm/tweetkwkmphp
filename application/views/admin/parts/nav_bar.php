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
			if(empty($pageTitle)) {
				$pageTitle = $site_title;
			}

			?>
			<a class="navbar-brand" ><?= $pageTitle; ?></a>
		</div>


		<button class="navbar-toggler" type="button" data-toggle="collapse" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
			<span class="sr-only">Toggle navigation</span>
			<span class="navbar-toggler-icon icon-bar"></span>
			<span class="navbar-toggler-icon icon-bar"></span>
			<span class="navbar-toggler-icon icon-bar"></span>
		</button>

		<div class="collapse navbar-collapse justify-content-end">

<!--			<form class="navbar-form">-->
<!--				<div class="input-group no-border">-->
<!--					<input type="text" value="" class="form-control" placeholder="Search...">-->
<!--					<button type="submit" class="btn btn-white btn-round btn-just-icon">-->
<!--						<i class="material-icons">search</i>-->
<!--						<div class="ripple-container"></div>-->
<!--					</button>-->
<!--				</div>-->
<!--			</form>-->

			<ul class="navbar-nav">
<!--				<li class="nav-item">-->
<!--					<a class="nav-link" href="#pablo">-->
<!--						<i class="material-icons">dashboard</i>-->
<!--						<p class="d-lg-none d-md-block">-->
<!--							Stats-->
<!--						</p>-->
<!--					</a>-->
<!--				</li>-->
<!--				<li class="nav-item dropdown">-->
<!--					<a class="nav-link" href="http://example.com" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">-->
<!--						<i class="material-icons">notifications</i>-->
<!--						<span class="notification">5</span>-->
<!--						<p class="d-lg-none d-md-block">-->
<!--							Some Actions-->
<!--						</p>-->
<!--					</a>-->
<!--					<div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">-->
<!--						<a class="dropdown-item" href="#">Mike John responded to your email</a>-->
<!--						<a class="dropdown-item" href="#">You have 5 new tasks</a>-->
<!--						<a class="dropdown-item" href="#">You're now friend with Andrew</a>-->
<!--						<a class="dropdown-item" href="#">Another Notification</a>-->
<!--						<a class="dropdown-item" href="#">Another One</a>-->
<!--					</div>-->
<!--				</li>-->


				<li class="nav-item dropdown">
					<?php if($login) {	?>
						<a class="nav-link" href="#pablo" id="navbarDropdownProfile" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<img src="<?= $user_data["image_url"] ?>" style="width: 50px; ; border-radius: 300px;" />
							<p class="d-lg-none d-md-block">
								Account
							</p>
						</a>
						<div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownProfile">
							<a class="dropdown-item" href="/mypage/index">マイページ</a>
							<div class="dropdown-divider"></div>
							<a class="dropdown-item" href="/auth/logout">Log out</a>
						</div>
					<?php } else {	?>
						<a class="nav-link" href="#pablo" id="navbarDropdownProfile" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<img src="/assets/img/faces/person.png" style="width: 50px; ; border-radius: 300px;" />
							<p class="d-lg-none d-md-block">
								Account
							</p>
						</a>
						<div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownProfile">
							<a class="dropdown-item" href="/mypage/index">ログイン</a>
						</div>
					<?php } ?>

				</li>
			</ul>
		</div>

	</div>
</nav>

<?php
include('include/conn.php');

$selquery4Nav = "select * from tbl_app_details where id=1";
$selresult4Nav = mysqli_query($conn,$selquery4Nav);
$selres4Nav = mysqli_fetch_array($selresult4Nav);

$selHome = "select * from tbl_lndn_home_page where id=1";
$selresultHome = mysqli_query($conn,$selHome);
$selres4Home = mysqli_fetch_array($selresultHome);

$selHomeSS = "select * from tbl_lndn_screenshot";
$selresultHomeSS = mysqli_query($conn,$selHomeSS);

$selHomeSt = "select * from tbl_lndn_statistic where id=1";
$selresultHomeSt = mysqli_query($conn,$selHomeSt);
$selres4HomeSt = mysqli_fetch_array($selresultHomeSt);

$selTesti = "select user_name as user_name_testi, comment as comment_testi from tbl_lndn_testimonials";
$selresTesti = mysqli_query($conn,$selTesti);

?>
<!DOCTYPE HTML>
<html lang="zxx">
	<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title><?php echo $selres4Nav['app_name']; ?></title>
		<?php include("include/navbar.php"); ?>
		<!-- hero area start -->
		<section class="hero-area" id="home" style="background-image: url(<?php echo 'admin/'.$selres4Home['home_bg_img']; ?>);">
			<div class="container">
				<div class="row">
					<div class="col-lg-7">
						<div class="hero-area-content">
							<!-- <h1>It’s all about Promoting your Business</h1> -->
							<h1><?php echo $selres4Home['home_title']; ?></h1>
							<p><?php echo $selres4Home['home_desc']; ?></p>
							<a href="<?php echo $selres4Nav['app_url']; ?>" class="appao-btn" target="_blank">Download App</a>
						</div>
					</div>
					<div class="col-lg-5">
					    <div class="hand-mockup text-lg-left text-center">
							<img src="<?php echo 'admin/'.$selres4Home['home_img']; ?>" alt="<?php echo $selres4Nav['app_name']; ?>" style="height:450px;"/>
						</div>
					</div>
				</div>
			</div>
		</section><!-- hero area end -->
		<!-- about section start -->
		<section class="about-area ptb-90">
			<div class="container">
				<div class="row">
					<div class="col-lg-12">
					    <div class="sec-title">
							<h2>About <?php echo $selres4Nav['app_name']; ?><span class="sec-title-border"><span></span><span></span><span></span></span></h2>
							<p><?php echo $selres4Home['app_intro']; ?></p>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-4">
					    <div class="single-about-box">
							<i class="<?php echo $selres4Home['app_intro_icon1']; ?>"></i>
							<h4><?php echo $selres4Home['app_intro_ftr_title1']; ?></h4>
							<p><?php echo $selres4Home['app_intro_feature1']; ?></p>
						</div>
					</div>
					<div class="col-lg-4">
					    <div class="single-about-box active">
							<i class="<?php echo $selres4Home['app_intro_icon2']; ?>"></i>
							<h4><?php echo $selres4Home['app_intro_ftr_title2']; ?></h4>
							<p><?php echo $selres4Home['app_intro_feature2']; ?></p>
						</div>
					</div>
					<div class="col-lg-4">
					    <div class="single-about-box">
							<i class="<?php echo $selres4Home['app_intro_icon3']; ?>"></i>
							<h4><?php echo $selres4Home['app_intro_ftr_title3']; ?></h4>
							<p><?php echo $selres4Home['app_intro_feature3']; ?></p>
						</div>
					</div>
				</div>
			</div>
		</section><!-- about section end -->

		<!-- feature section start -->
		<section class="feature-area ptb-90" id="feature" style="background-image: url(<?php echo 'admin/'.$selres4Home['home_feature_bg']; ?>);">
			<div class="container">
				<div class="row flexbox-center">
					<div class="col-lg-4">
						<div class="single-feature-box text-lg-right text-center">
							<ul>
								<li>
									<div class="feature-box-info">
										<h4><?php echo $selres4Home['home_ftr_title1']; ?></h4>
										<p><?php echo $selres4Home['home_ftr1']; ?></p>
									</div>
									<div class="feature-box-icon">
										<i class="<?php echo $selres4Home['home_ftr_icon1']; ?>"></i>
									</div>
								</li>
								<li>
									<div class="feature-box-info">
										<h4><?php echo $selres4Home['home_ftr_title2']; ?></h4>
										<p><?php echo $selres4Home['home_ftr2']; ?></p>
									</div>
									<div class="feature-box-icon">
										<i class="<?php echo $selres4Home['home_ftr_icon2']; ?>"></i>
									</div>
								</li>
								<li>
									<div class="feature-box-info">
										<h4><?php echo $selres4Home['home_ftr_title3']; ?></h4>
										<p><?php echo $selres4Home['home_ftr3']; ?></p>
									</div>
									<div class="feature-box-icon">
										<i class="<?php echo $selres4Home['home_ftr_icon3']; ?>"></i>
									</div>
								</li>
								<li>
									<div class="feature-box-info">
										<h4><?php echo $selres4Home['home_ftr_title4']; ?></h4>
										<p><?php echo $selres4Home['home_ftr4']; ?></p>
									</div>
									<div class="feature-box-icon">
										<i class="<?php echo $selres4Home['home_ftr_icon4']; ?>"></i>
									</div>
								</li>
							</ul>
						</div>
					</div>
					<div class="col-lg-4">
						<div class="single-feature-box text-center">
							<img src="<?php echo 'admin/'.$selres4Home['home_feature_img']; ?>" alt="feature">
						</div>
					</div>
					<div class="col-lg-4">
						<div class="single-feature-box text-lg-left text-center">
							<ul>
								<li>
									<div class="feature-box-icon">
										<i class="<?php echo $selres4Home['home_ftr_icon5']; ?>"></i>
									</div>
									<div class="feature-box-info">
										<h4><?php echo $selres4Home['home_ftr_title5']; ?></h4>
										<p><?php echo $selres4Home['home_ftr5']; ?></p>
									</div>
								</li>
								<li>
									<div class="feature-box-icon">
										<i class="<?php echo $selres4Home['home_ftr_icon6']; ?>"></i>
									</div>
									<div class="feature-box-info">
										<h4><?php echo $selres4Home['home_ftr_title6']; ?></h4>
										<p><?php echo $selres4Home['home_ftr6']; ?></p>
									</div>
								</li>
								<li>
									<div class="feature-box-icon">
										<i class="<?php echo $selres4Home['home_ftr_icon7']; ?>"></i>
									</div>
									<div class="feature-box-info">
										<h4><?php echo $selres4Home['home_ftr_title7']; ?></h4>
										<p><?php echo $selres4Home['home_ftr7']; ?></p>
									</div>
								</li>
								<li>
									<div class="feature-box-icon">
										<i class="<?php echo $selres4Home['home_ftr_icon8']; ?>"></i>
									</div>
									<div class="feature-box-info">
										<h4><?php echo $selres4Home['home_ftr_title8']; ?></h4>
										<p><?php echo $selres4Home['home_ftr8']; ?></p>
									</div>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</section><!-- feature section end -->
		<!-- screenshots section start -->
		<section class="screenshots-area ptb-90" id="screenshot">
			<div class="container">
				<div class="row">
					<div class="col-lg-12">
					    <div class="sec-title">
							<h2>Screenshot<span class="sec-title-border"><span></span><span></span><span></span></span></h2>
							<!-- <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt </p> -->
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-12">
						<div class="screenshot-wrap">
							<?php while($selres4HomeSS = mysqli_fetch_array($selresultHomeSS)) { ?>
								<div class="single-screenshot">
									<img src="<?php echo 'admin/'.$selres4HomeSS['img_path']; ?>" alt="<?php echo $selres4HomeSS['img_name']; ?>" />
								</div>
							<?php } ?>
						</div>
					</div>
				</div>
			</div>
		</section><!-- screenshots section end -->
		<!-- counter section start -->
		<section class="counter-area ptb-90" style="background: url(<?php echo 'admin/'.$selres4HomeSt['bg_img']; ?>) no-repeat center / cover;">
			<div class="container">
				<div class="row">
					<div class="col-md-3 col-sm-6">
					    <div class="single-counter-box">
							<i class="fas fa-user-astronaut"></i><br>
							<br><h3><span class="counter">
								<?php if($selres4HomeSt['with_live_tp']==1) {
								
								$selMatch = "select count(id) as tot_match from match_details";
								$matchCount = mysqli_query($conn,$selMatch);
								$resMcount = mysqli_fetch_array($matchCount);
								$totMatch = $resMcount['tot_match'];

							 	echo $totMatch; } else { echo $selres4HomeSt['total_tournaments']; } ?></span></h3>
							<p>Tournaments Played</p>
						</div>
					</div>
					<div class="col-md-3 col-sm-6">
					    <div class="single-counter-box">
							<i class="fas fa-users"></i><br>
							<br><h3><span class="counter">
								<?php if($selres4HomeSt['with_live_tparti']==1) {

								$selParti = "select count(id) as tot_parti from participant_details";
								$partiCount = mysqli_query($conn,$selParti);
								$resPcount = mysqli_fetch_array($partiCount);
								$totParti = $resPcount['tot_parti'];
								
								echo $totParti; } else { echo $selres4HomeSt['total_participants']; } ?></span></h3>
							<p>Total Participants</p>
						</div>
					</div>
					<div class="col-md-3 col-sm-6">
					    <div class="single-counter-box">
							<i class="fas fa-dollar-sign"></i><br>
							<br><h3><span class="counter">
								<?php if($selres4HomeSt['with_live_wa']==1) {

								$selWamt = "select sum(won_balance) as tot_wamt from user_details";
								$SumWamt = mysqli_query($conn,$selWamt);
								$resSumWamt = mysqli_fetch_array($SumWamt);
								$totWamt = $resSumWamt['tot_wamt'];

								echo $totWamt; } else { echo $selres4HomeSt['winning_amount']; } ?></span></h3>
							<p>Winning Amount</p>
						</div>
					</div>
					<div class="col-md-3 col-sm-6">
					    <div class="single-counter-box">
							<i class="fas fa-donate"></i><br>
							<br><h3><span class="counter">
								<?php if($selres4HomeSt['with_live_rw']==1) { 
								$selTrefer = "select sum(refer_points) as tot_refer from referral_details";
								$SumselTrefer = mysqli_query($conn,$selTrefer);
								$resSumRefer = mysqli_fetch_array($SumselTrefer);
								$totRefer = $resSumRefer['tot_refer'];
								
								echo $totRefer; } else { echo $selres4HomeSt['rewards']; } ?></span></h3>
							<p>Rewards</p>
						</div>
					</div>
				</div>
			</div>
		</section><!-- counter section end -->
		<!-- testimonial section start -->
		<?php if(mysqli_num_rows($selresTesti)>=2) { ?>
		<section class="testimonial-area ptb-90">
			<div class="container">
				<div class="row">
					<div class="col-lg-12">
					    <div class="sec-title">
							<h2>Testimonials<span class="sec-title-border"><span></span><span></span><span></span></span></h2>
							<!-- <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt </p> -->
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-8 offset-lg-2">
						<div class="testimonial-wrap">
							
							<?php while($resTesti = mysqli_fetch_array($selresTesti)) { ?>
								<div class="single-testimonial-box">
									<h5><?php echo $resTesti['user_name_testi']; ?></h5>
									<p><?php echo $resTesti['comment_testi']; ?></p>
								</div>
							<?php } ?>
							
						</div>
					</div>
				</div>
			</div>
		</section>
		<?php } ?>
		<!-- testimonial section end -->
		<!-- download section start -->
		<section class="download-area ptb-90">
			<div class="container">
				<div class="row">
					<div class="col-lg-12">
					    <div class="sec-title">
							<h2>Download Available<span class="sec-title-border"><span></span><span></span><span></span></span></h2>
							<p>Now play and earn by downloading latest version of <?php echo $selres4Nav['app_name']; ?>.</p>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-12">
						<ul>
							<li>
								<a href="<?php echo $selres4Nav['app_url']; ?>" class="download-btn flexbox-center" target="_blank">
									<div class="download-btn-icon">
										<i class="icofont icofont-brand-android-robot"></i>
									</div>
									<div class="download-btn-text">
										<p>Available App</p>
										<h4>Download Here</h4>
									</div>
								</a>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</section><!-- download section end -->
		<!-- blog section start -->
		
		<a href="#" class="scrollToTop">
			<i class="icofont icofont-arrow-up"></i>
		</a>
		<!-- jquery main JS -->
		<script src="assets/js/jquery.min.js"></script>
		<!-- Bootstrap JS -->
		<script src="assets/js/bootstrap.min.js"></script>
		<!-- Slick nav JS -->
		<script src="assets/js/jquery.slicknav.min.js"></script>
		<!-- Slick JS -->
		<script src="assets/js/slick.min.js"></script>
		<!-- owl carousel JS -->
		<script src="assets/js/owl.carousel.min.js"></script>
		<!-- Popup JS -->
		<script src="assets/js/jquery.magnific-popup.min.js"></script>
		<!-- Counter JS -->
		<script src="assets/js/jquery.counterup.min.js"></script>
		<!-- Counterup waypoints JS -->
		<script src="assets/js/waypoints.min.js"></script>
	    <!-- YTPlayer JS -->
	    <script src="assets/js/jquery.mb.YTPlayer.min.js"></script>
		<!-- jQuery Easing JS -->
		<script src="assets/js/jquery.easing.1.3.js"></script>
		<!-- Gmap JS -->
		<script src="assets/js/gmap3.min.js"></script>
        <!-- Google map api -->
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBnKyOpsNq-vWYtrwayN3BkF3b4k3O9A_A"></script>
		<!-- Custom map JS -->
		<script src="assets/js/custom-map.js"></script>
		<!-- WOW JS -->
		<script src="assets/js/wow-1.3.0.min.js"></script>
		<!-- Switcher JS -->
		<script src="assets/js/switcher.js"></script>
		<!-- main JS -->
		<script src="assets/js/main.js"></script>
	</body>
</html>
<?php
include('include/conn.php');

$selquery4Nav = "select * from tbl_app_details where id=1";
$selresult4Nav = mysqli_query($conn,$selquery4Nav);
$selres4Nav = mysqli_fetch_array($selresult4Nav);

$selHome = "select home_bg_img from tbl_lndn_home_page where id=1";
$selresultHome = mysqli_query($conn,$selHome);
$selres4Home = mysqli_fetch_array($selresultHome);

$selRefer = "select * from tbl_refer_and_earn where id=1";
$selresultRefer = mysqli_query($conn,$selRefer);
$selresRefer = mysqli_fetch_array($selresultRefer);

?>
<!DOCTYPE HTML>
<html lang="zxx">
	<head>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title><?php echo $selres4Nav['app_name']; ?> - Refer and Earn</title>
		<?php include("include/navbar.php"); ?>
		<!-- breadcrumb area start -->
		<section class="hero-area breadcrumb-area" style="background-image: url(<?php echo 'admin/'.$selres4Home['home_bg_img']; ?>);">
			<div class="container">
				<div class="row">
					<div class="col-lg-12">
						<div class="hero-area-content">
							<h1>Refer and Earn</h1>
                            <ul>
                                <li><a href="index">Home</a></li>
                                <li><a href="refer-and-earn">Refer and Earn</a></li>
                            </ul>
						</div>
					</div>
				</div>
			</div>
		</section><!-- breadcrumb area end -->
		<!-- blog section start -->
		<section class="blog-detail" id="blog">
			<div class="container">
				<div class="row">
					<div class="col-lg-12">
					    <?php echo $selresRefer['content']; ?>
					</div>
				</div>
			</div>
		</section><!-- blog section end -->
		
		<?php include("include/footer.php"); ?>
		
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
		<!-- WOW JS -->
		<script src="assets/js/wow-1.3.0.min.js"></script>
		<!-- Switcher JS -->
		<script src="assets/js/switcher.js"></script>
		<!-- main JS -->
		<script src="assets/js/main.js"></script>
	</body>
</html>
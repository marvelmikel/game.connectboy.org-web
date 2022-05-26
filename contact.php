<?php
include('include/conn.php');

$selquery4Nav = "select * from tbl_app_details where id=1";
$selresult4Nav = mysqli_query($conn,$selquery4Nav);
$selres4Nav = mysqli_fetch_array($selresult4Nav);

$selHome = "select home_bg_img from tbl_lndn_home_page where id=1";
$selresultHome = mysqli_query($conn,$selHome);
$selres4Home = mysqli_fetch_array($selresultHome);

$selqueryContact4 = "select * from tbl_contact where contact_id=1";
$selresContact4 = mysqli_query($conn,$selqueryContact4);
$selresCon4 = mysqli_fetch_array($selresContact4);

if(isset($_POST['btnSave']))
{
  $txtName=htmlspecialchars(mysqli_real_escape_string($conn,$_POST['txtName']));
  $txtEmail=htmlspecialchars(mysqli_real_escape_string($conn,$_POST['txtEmail']));
  $txtSubject=htmlspecialchars(mysqli_real_escape_string($conn,$_POST['txtSubject']));
  $txtMessege=htmlspecialchars(mysqli_real_escape_string($conn,$_POST['txtMessege']));

  $txtCdate=date("Y-m-d H:m:s");
  
  $insquery = "insert INTO tbl_visitor_contact(name, email, subject, message, date_created, status) values ('$txtName','$txtEmail','$txtSubject','$txtMessege','$txtCdate','0')";
  if($conn->query($insquery) === TRUE)
  {
    	//header("Location:contact-us.php");
    	echo '<script type="text/javascript">';
        echo 'setTimeout(function () { swal(
                                            "Thank You",
                                            "For contact us, we will get back to you as soon as possible.",
                                            "success"
                                            );';
        echo '}, 1000);</script>';
  }
  else
  {
    	//echo $insquery;
    	echo '<script type="text/javascript">';
        echo 'setTimeout(function () { swal(
                                            "Oops...",
                                            "Something went wrong !!",
                                            "error"
                                            );';
        echo '}, 1000);</script>';
  }

}


?>
<!DOCTYPE HTML>
<html lang="zxx">
	<head>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title><?php echo $selres4Nav['app_name']; ?> - Contact</title>

		<?php include("include/navbar.php"); ?>
		<!-- breadcrumb area start -->
		<section class="hero-area breadcrumb-area" style="background-image: url(<?php echo 'admin/'.$selres4Home['home_bg_img']; ?>);">
			<div class="container">
				<div class="row">
					<div class="col-lg-12">
						<div class="hero-area-content">
							<h1>Contact</h1>
                            <ul>
                                <li><a href="index">Home</a></li>
                                <li><a href="about">Contact</a></li>
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
					    <?php echo $selresCon4['title']; ?>
					</div>
				</div>
				<div class="row">
		            <div class="col-lg-6">
						<div class="contact-form">
							<h4>Get in Touch</h4>
							<p class="form-message"></p>
							<form id="contact-form" action="contact.php" method="POST">
				                <input type="text" name="txtName" placeholder="Enter Your Name">
				                <input type="email" name="txtEmail" placeholder="Enter Your Email">
				                <input type="text" name="txtSubject" placeholder="Your Subject">
				                <textarea placeholder="any query" name="txtMessege"></textarea>
				                <button type="submit" name="btnSave">Send Message</button>
				            </form>
						</div>
		            </div>
		            <div class="col-lg-6">
						<div class="contact-address">
							<h4>Reach Us</h4>
							
							<ul>
								<li>
									<div class="contact-address-icon">
										<i class="icofont icofont-headphone-alt"></i>
									</div>
									<div class="contact-address-info">
										<a href="callto:#"><?php echo $selresCon4['phone']; ?></a>
										<a href="callto:#"><?php echo $selresCon4['whatsapp_no']; ?></a>
									</div>
								</li>
								<li>
									<div class="contact-address-icon">
										<i class="icofont icofont-envelope"></i>
									</div>
									<div class="contact-address-info">
										<a href="mailto:#"><?php echo $selresCon4['email']; ?></a>
									</div>
								</li>
								<li>
									<div class="contact-address-icon">
										<i class="icofont icofont-pin"></i>
									</div>
									<div class="contact-address-info">
										<a href="javascipt:void()"><?php echo $selresCon4['address']; ?></a>
									</div>
								</li>
							</ul>
						</div>
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
		<script src="assets/js/sweetalert2.js"></script>
	</body>
</html>
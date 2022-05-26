<?php
include('include/conn.php');

$selqueryContact = "select * from tbl_contact where contact_id=1";
$selresContact = mysqli_query($conn,$selqueryContact);
$selresCon = mysqli_fetch_array($selresContact);

?>
<!-- footer section start -->
<footer class="footer" id="contact">
	<div class="container">
		<br><br>
		<div class="row">
            <div class="col-lg-12">
				<div class="copyright-area">
					<ul>
						<li><a href="<?php echo $selresCon['fb_follow']; ?>"><i class="icofont icofont-social-facebook"></i></a></li>
						<li><a href="<?php echo $selresCon['twitter_follow']; ?>"><i class="icofont icofont-social-twitter"></i></a></li>
						<li><a href="<?php echo $selresCon['ig_follow']; ?>"><i class="icofont icofont-social-instagram"></i></a></li>
						<li><a href="<?php echo $selresCon['youtube_follow']; ?>"><i class="icofont icofont-social-youtube"></i></a></li>
					</ul>
					<p>
					Copyright &copy; <script>document.write(new Date().getFullYear());</script> All rights reserved</p>
				</div>
            </div>
		</div>
	</div>
</footer><!-- footer section end -->
<a href="#" class="scrollToTop">
	<i class="icofont icofont-arrow-up"></i>
</a>
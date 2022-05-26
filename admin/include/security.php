<?php 
ini_set('session.gc_maxlifetime', 31536600);
session_set_cookie_params(31536600);

session_start();

if(isset($_SESSION['user']))
{
    require('DATA_CONFIG.php');
    require('rn84d6NJhjE.php');
}
else
{
  if(isset($_COOKIE['user_skywinner']))
  {
    require('DATA_CONFIG.php');
    require('rn84d6NJhjE.php');
  }
  else
  {
    header("Location:login");
  }
}

?>
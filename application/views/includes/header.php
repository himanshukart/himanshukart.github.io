<!--
Gossip Challenge

-->

<!--
/*
  @Author - Himanshu Gupta
  @Date -   Sep 3 , 2016
  @Change history-
  @purpose - Header file respponsible for including all retalated javascript and css
 */
-->
<!DOCTYPE html>
<html lang="en-US"  class="no-js">
<head>
	<meta charset="UTF-8">

	<meta content="IE=edge" http-equiv="X-UA-Compatible">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
	<meta name="title" content="<?php echo $meta_title ?>" />

	<!-- Twitter Card -->
	<meta name="twitter:card" content="summary_large_image" />
	<meta property="twitter:image:type" content="image/jpeg" />
	<meta property="twitter:image:width" content="300" />
	<meta property="twitter:image:height" content="400" />
	<!-- End -->

<!--External css-->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.2/css/font-awesome.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/css/bootstrap.min.css">
  <link href="<?php echo base_url(); ?>/assets/css/creative.css" rel="stylesheet" type="text/css">


<!-- External javascript -->
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.1/jquery.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/js/bootstrap.min.js" async></script>
  <script type="text/javascript" src="<?php echo base_url(); ?>/assets/js/creative.js" async></script>
</head>
<body class="">
	<?php if($this->session->userdata('user')['is_logged_in'] && $this->session->userdata('user')['user_id']>0) { ?>
	<div class="container-fluid">
	 <nav class="navbar navbar-inverse navbar-default" role="navigation">
	  <div class="container-fluid">
	    <!-- Brand and toggle get grouped for better mobile display -->
	    <div class="navbar-header">
	      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
	        <span class="sr-only">Toggle navigation</span>
	        <span class="icon-bar"></span>
	        <span class="icon-bar"></span>
	        <span class="icon-bar"></span>
	      </button>
	      <a class="navbar-brand" href="/"><?php echo $this->session->userdata('user')['username']; ?></a>
	    </div>

	    <!-- Collect the nav links, forms, and other content for toggling -->
	    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
	      <ul class="nav navbar-nav navbar-right">
	        <li><a href="/users/notification"><i class="fa-1x fa fa-envelope" aria-hidden="true">
						<span id="notification" class="<?php if(!empty($count) && $count>0) echo 'notification-badge';?>  ">
						<?php if(!empty($count) && $count>0) echo $count; else echo ''; ?></span></i></a></li>
	        <li class="dropdown">
	          <a href="#" class="dropdown-toggle" data-toggle="dropdown">Setting <b class="caret"></b></a>
	          <ul class="dropdown-menu">
	            <li><a href="/users/add_publisher">Add Publisher</a></li>
	              <li><a href="/users/remove_publisher">Remove Publisher</a></li>
	            <li><a href="/users/add_subscriber" >Add subscriber</a></li>
	            <li><a href="/users/remove_subscriber" >remove subscriber</a></li>
	            <li><a href="/users/update">change user_name</a></li>
	            <li><a href="/users/update">change password</a></li>
	            <li><a href="javascript:void(0)" data-toggle="modal" data-target="#deletemodal">delete account</a></li>
	            <li class="divider"></li>
	            <li><a href="<?php echo base_url('users/logout');?>">Log out</a></li>
	          </ul>
	        </li>
	      </ul>
	    </div><!-- /.navbar-collapse -->
	  </div><!-- /.container-fluid -->
	</nav>
	</div><!-- end container -->
	<?php  } ?>

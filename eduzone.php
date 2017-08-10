<?php
	//ob_start();
	session_start();
	include('includes/functions.inc');
	include('includes/header.inc');
	include('includes/constants.inc');
	include('includes/db.inc');
	include('includes/primary_nav.inc');
	keep_online($_SESSION['user']);
?>


<div class='container ' >
	<div class="jumbotron">
		<h1>
			<i class='icon ion-university'></i>EduZone
		</h1>
		<p>
			Find educational materials, ask questions, have them answered, visit our library for past questions and answers,
			 sample term papers and project topics.<br />
			 <a href='' class="btn btn-success ">Library <i class='ion-ios-book'></i></a>
			 <a href='' class="btn btn-info ">Question and Answers <i class='ion-ios-people' style='font-size:1.3em'></i></a>
		</p>
	</div>
	<div style="background:#efefef;padding:20px;">
		<div class="col-sm-6"> <b>Latest Questions</b> </div>
		<div class="col-sm-6"> <b>Recent Materials</b> </div>

	</div>
</div>

<?php
	include 'includes/footer.inc';
?>
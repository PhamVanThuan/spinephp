<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
		<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />

		<title>SpinePHP encountered an error.</title>

		<style type="text/css">
			body {
				font-family: Tahoma;
				font-size: 12pt;
				padding: 0;
				margin: 0;
				background-color: #f3f3f3;
			}

			a {
				color: #3781cd;
				border-bottom: 2px solid #e5e5e5;
				text-decoration: none;
			}

			div#header {
				position: relative;
				width: 100%;
				height: 80px;
				background-color: #231c18;
				border-bottom: 5px solid #19120f;
			}

			div#header-text {
				position: absolute;
				bottom: 5px;
				left: 5px;
				color: #fff;
				font-size: 0.9em;
				font-weight: bold;
			}

			div#container {
				width: 80%;
				margin: 5px auto;
				font-size: 0.8em;
			}

			div.error-box {
				border: 1px solid #CCCC66;
				background-color: #FFFFBB;
				padding: 4px;
				width: 100%;
				margin: 5px 0;
			}

			span.details {
				font-size: 0.8em;
				font-weight: bold;
			}

			div#show-remainder {
				width: 150px;
				text-align: center;
				padding: 6px;
				background-color: #e3e3e3;
				border-radius: 6px;
				-moz-border-radius: 6px;
				-webkit-border-radius: 6px;
				color: #535353;
				font-size: 0.9em;
				cursor: default;
			}

			div#more {
				margin: 25px 0 0 0;
			}

			h1 {
				margin: 0;
				padding: 0;
				font-size: 1.1em;
				font-weight: bold;
			}

			p {
				margin: 5px 0 0 0;
			}
		</style>

		<script type="text/javascript">
			function showHide(parent){
				var remainder = document.getElementById('remainder');
				var display = remainder.style.display;

				if(display == 'none'){
					remainder.style.display = 'block';
					parent.innerHTML = 'Hide Remaining Errors';
				}else{
					remainder.style.display = 'none';
					parent.innerHTML = 'Show Remaining Errors';
				}
			}
		</script>

    </head>

    <body>
		<div id="header">
			<div id="header-text">
				SpinePHP has encountered an error during loading of the requested page.
			</div>
		</div>
		<div id="container">
			SpinePHP encountered <?php echo $tpl['errnum']; ?> errors during processing the page. However only the first encountered error
			will be shown for reasons being that any following errors may be the result of the first error. Attempt to fix the error shown first.

			<div class="error-box">
				<?php
					echo '<h1>' . $tpl['code'] . '</h1><p>' . $tpl['message'] . '</p>';
				?>
				<span class="details""><?php echo $tpl['details']; ?></span>
			</div>
			<?php
				if(isset($tpl['remainder'])){
					$remainder = $tpl['remainder'];
			?>

			<div id="show-remainder" onclick="javascript: showHide(this)">Show Remaining Errors</div>
			
			<div id="remainder" style="display: none;">
				<span style="font-size: 0.8em">Note that these errors may be a result of the above error, please attempt to fix the above error first.</span>
				
				<?php
					foreach($remainder as $error){
				?>

				<div class="error-box">
					<?php
						echo '<h1>' . $error['code'] . '</h1><p>' . $error['message'] . '</p>';
					?>
					<span class="details"">
						<?php
							if(!empty($error['file']) && !empty($error['line'])){
								echo 'Found in ' . $error['file'] . ' on line ' . $error['line'];
							}
						?>
					</span>
				</div>

				<?php
					}
				?>
			</div>

			<?php
				}
			?>

			<div id="more">
				If you're having troubles, please visit <a href="http://www.spinephp.org">SpinePHP</a> or check out the <a href="http://www.spinephp.org/wiki">Wiki</a> and <a href="http://www.spinephp.org/forums">Forums</a>.
			</div>
		</div>
    </body>
</html>
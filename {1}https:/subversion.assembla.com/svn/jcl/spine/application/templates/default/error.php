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

			div#error-box {
				border: 1px solid #CCCC66;
				background-color: #FFFFBB;
				padding: 4px;
				width: 100%;
				margin: 5px 0;
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

    </head>

    <body>
		<div id="header">
			<div id="header-text">
				SpinePHP has encountered an error during loading of the requested page.
			</div>
		</div>
		<div id="container">
			SpinePHP encountered <?php echo $this->tpl['errnum']; ?> errors during processing the page. However only the first encountered error
			will be shown for reasons being that any following errors may be the result of the first error. Attempt to fix the error shown first.

			<div id="error-box">
				<?php
					echo '<h1>' . $this->tpl['code'] . '</h1><p>' . $this->tpl['message'] . '</p>';
				?>
			</div>
		</div>
    </body>
</html>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
		<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />

		<title>
			<?php
				echo $tpl['title'] . ' | Spine PHP, a simple PHP framework!';
			?>
		</title>
		
		<?php
			echo $js;
			echo $css;
		?>
    </head>

    <body>

		<div id="container">
			<div id="top-bar"></div>

			<div id="header-container">
				<div id="header-image"></div>
				<div id="header-navigation">
					<ul id="navigation">
						<li><?php echo $html->link('/', 'Home'); ?></li>
						<li><?php echo $html->link('/about', 'About Spine'); ?></li>
						<li><?php echo $html->link('/downloads', 'Downloads'); ?></li>
						<li><?php echo $html->link('/community', 'Community'); ?></li>
						<li><?php echo $html->link('/docs', 'Documentation'); ?></li>
					</ul>
				</div>
			</div>
			<div id="body-container">
				<div id="body-arrow"></div>

				<div id="body">
					<?php
						echo $tpl['content'];
					?>
				</div>

			</div>
			<div class="footer-height"></div>
			<div id="footer-container" class="footer-height">
				<div id="footer">

					<div class="section">
						<div class="title">A bit about Spine</div>
						Spine is a PHP MVC Framework that aims to provide speedy development of small to large web-based applications and websites.
						Using the power of PHP, it provides many easy-to-use features right out of the box.
					</div>

					<div class="section">
						<div class="title">More information</div>
						If you're after more information about using Spine, downloading Spine or just interested to learn more, be sure
						to visit the website at <a href="http://www.spinephp.com">http://www.spinephp.com</a>.
					</div>

					<div id="copyright">
						Copyright &copy; 2010 Jason Lewis
					</div>
				</div>
			</div>
		</div>


    </body>
</html>
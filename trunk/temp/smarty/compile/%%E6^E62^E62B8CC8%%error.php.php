<?php /* Smarty version 2.6.26, created on 2010-08-02 17:46:26
         compiled from C:/wamp/www/spine/application/templates/default/error.php */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
		<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />

		<title>SpinePHP encountered an error.</title>

		<style type="text/css">
			body 
			a 
			div#header 
			div#header-text 
			div#container 
			div.error-box 
			span.details 
			div#show-remainder 
			div#more 
			h1 
			p 		</style>

		<script type="text/javascript">
			function showHide(parent)else			}
		</script>

    </head>

    <body>
		<div id="header">
			<div id="header-text">
				SpinePHP has encountered an error during loading of the requested page.
			</div>
		</div>
		<div id="container">
			SpinePHP encountered <?php echo '<?php'; ?>
 echo $tpl['errnum']; <?php echo '?>'; ?>
 errors during processing the page. However only the first encountered error
			will be shown for reasons being that any following errors may be the result of the first error. Attempt to fix the error shown first.

			<div class="error-box">
				<?php echo '<?php'; ?>

					echo '<h1>' . $tpl['code'] . '</h1><p>' . $tpl['message'] . '</p>';
				<?php echo '?>'; ?>

				<span class="details""><?php echo '<?php'; ?>
 echo $tpl['details']; <?php echo '?>'; ?>
</span>
			</div>
			<?php echo '<?php'; ?>

				if(isset($tpl['remainder']))<?php echo $this->_tpl_vars['remainder']; ?>

						<?php echo '?>'; ?>

					</span>
				</div>

				<?php echo '<?php'; ?>

					}
				<?php echo '?>'; ?>

			</div>

			<?php echo '<?php'; ?>

				}
			<?php echo '?>'; ?>


			<div id="more">
				If you're having troubles, please visit <a href="http://www.spinephp.org">SpinePHP</a> or check out the <a href="http://www.spinephp.org/wiki">Wiki</a> and <a href="http://www.spinephp.org/forums">Forums</a>.
			</div>
		</div>
    </body>
</html>
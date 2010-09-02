<?php
	if(!defined('BASE_PATH')){
		die('Please do not access install.php directly, run index.php to check environment configuration.');
	}
	/**
	 * install.php
	 *
	 * Checks environment variables and determines if Spine is capable of running
	 * on the current environment.
	 *
	 * Copyright (c) 2010, Jason Lewis, Spine PHP Team (http://www.spinephp.org)
	 *
	 * Licensed under the BSD License.
	 * Redistribution of files must retain the above copyright notice.
	 *
	 * @copyright	Copyright 2010, Jason Lewis, Spine PHP Team
	 * @link		<http://www.spinephp.org>
	 * @license		BSD License <http://www.opensource.org/licenses/bsd-license.php>
	 */

	$failed = false;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
		<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />

		<title>Spine PHP Installation - Environment Tests</title>

		<style type="text/css">
			html, body {
				width: 700px;
				margin: 0 auto;
				padding: 0;
				font-family: Tahoma;
				font-size: 10pt;
				color: #000;
			}
			a {
				color: #000;
				text-decoration: none;
				font-weight: bold;
			}

			th {
				text-align: left;
				width: 30%;
			}

			tr:nth-child(odd) { 
				background: #eee;
			}

			td.pass {
				background: #cef5b4;
				color: #2bad0d;
			}

			td.fail {
				background: #f7cbcb;
				color: #c81818;
			}

			td.overall-pass {
				background: #2bad0d;
				color: #fff;
				font-size: 1.2em;
			}

			td.overall-fail {
				background: #c81818;
				color: #fff;
				font-size: 1.2em;
			}
		</style>
    </head>

    <body>
		<h1>Spine PHP Environment Tests</h1>
		The table below outlines tests that must be passed by your environment to be capable of running Spine. If a test fails
		and you choose to run Spine on your environment it may not function correctly.<br /><br />
		If you are having trouble consult the <a href="http://www.spinephp.org/wiki/SpinePHP:Guide#Troubleshooting">documentation</a> for instructions
		with solving common issues.<br /><br />

		<table border="0" width="100%" cellpadding="4" cellspacing="2">
			<tr>
				<th>PHP Version</th>
				<?php
					if(version_compare(PHP_VERSION, '5.2.0') >= 0){
				?>
				<td class="pass"><?php echo PHP_VERSION; ?></td>
				<?php
					}else{
						$failed = true;
				?>
				<td class="fail">Spine requires PHP 5.2.0 or greater, you are running <?php echo PHP_VERSION; ?>.</td>
				<?php
					}
				?>
			</tr>
			<tr>
				<th>Application Directory</th>
				<?php
					if(is_dir(BASE_PATH . DS . APP_PATH) && is_writable(BASE_PATH . DS . APP_PATH)){
				?>
				<td class="pass"><?php echo BASE_PATH . DS . APP_PATH . DS; ?></td>
				<?php
					}else{
						$failed = true;
				?>
				<td class="fail">Could not locate <?php echo BASE_PATH . DS . APP_PATH . DS; ?></td>
				<?php
					}
				?>
			</tr>
			<tr>
				<th>Library Directory</th>
				<?php
					if(is_dir(BASE_PATH . DS . LIB_PATH) && is_writable(BASE_PATH . DS . LIB_PATH)){
				?>
				<td class="pass"><?php echo BASE_PATH . DS . LIB_PATH . DS; ?></td>
				<?php
					}else{
						$failed = true;
				?>
				<td class="fail">Could not locate <?php echo BASE_PATH . LIB_PATH . DS; ?></td>
				<?php
					}
				?>
			</tr>
			<tr>
				<th>Config Directory</th>
				<?php
					if(is_dir(BASE_PATH . DS . CORE_PATH . DS . 'config' . DS) && is_writable(BASE_PATH . DS . CORE_PATH . DS . 'config' . DS)){
				?>
				<td class="pass"><?php echo BASE_PATH . DS . CORE_PATH . DS . 'config' . DS; ?></td>
				<?php
					}else{
						$failed = true;
				?>
				<td class="fail">Could not locate <?php echo BASE_PATH . DS .  CORE_PATH . DS . 'config' . DS; ?></td>
				<?php
					}
				?>
			</tr>
			<tr>
				<th>Cache Directory</th>
				<?php
					if(is_dir(BASE_PATH . DS . TMP_PATH . DS . 'cache' . DS) && is_writable(BASE_PATH . DS . TMP_PATH . DS . 'cache' . DS)){
				?>
				<td class="pass"><?php echo BASE_PATH . DS . TMP_PATH . DS . 'cache' . DS; ?></td>
				<?php
					}else{
						$failed = true;
				?>
				<td class="fail">Could not locate <?php echo BASE_PATH . DS .  TMP_PATH . DS . 'cache' . DS; ?></td>
				<?php
					}
				?>
			</tr>
			<tr>
				<th>Logs Directory</th>
				<?php
					if(is_dir(BASE_PATH . DS . TMP_PATH . DS . 'log' . DS) && is_writable(BASE_PATH . DS . TMP_PATH . DS . 'log' . DS)){
				?>
				<td class="pass"><?php echo BASE_PATH . DS . TMP_PATH . DS . 'log' . DS; ?></td>
				<?php
					}else{
						$failed = true;
				?>
				<td class="fail">Could not locate <?php echo BASE_PATH . DS . TMP_PATH . DS . 'log' . DS; ?></td>
				<?php
					}
				?>
			</tr>
			<tr>
				<th>URI Available</th>
				<?php
					if(isset($_SERVER['REQUEST_URI']) || isset($_SERVER['PHP_SELF']) || isset($_SERVER['PATH_INFO'])){
				?>
				<td class="pass">Pass</td>
				<?php
					}else{
						$failed = true;
				?>
				<td class="fail">Could not locate URI via REQUEST_URI, PHP_SELF or PATH_INFO.</td>
				<?php
					}
				?>
			</tr>
			<tr>
				<th>Reflection</th>
				<?php
					if(class_exists('ReflectionClass')){
				?>
				<td class="pass">Pass</td>
				<?php
					}else{
						$failed = true;
				?>
				<td class="fail"><a href="http://php.net/reflection">Reflection</a> has not been loaded in PHP.</td>
				<?php
					}
				?>
			</tr>
			<tr>
				<th>Filter</th>
				<?php
					if(extension_loaded('filter')){
				?>
				<td class="pass">Pass</td>
				<?php
					}else{
						$failed = true;
				?>
				<td class="fail">PHP <a href="http://php.net/filter">Filter</a> extension has not been loaded.</td>
				<?php
					}
				?>
			</tr>
			<tr>
				<?php
					if(!$failed){
				?>
				<td colspan="2" class="overall-pass">
					Success! Your environment has passed all the required checks which means Spine can be
					run off this environment. Please rename or delete <code>install.php</code> to begin using Spine.
				</td>
				<?php
					}else{
				?>
				<td colspan="2" class="overall-fail">
					One or more of the above tests failed which means Spine should not be run on your
					environment. Doing so may result in unexpected results. Please fix the above checks and reload
					this page.
				</td>
				<?php
					}
				?>
			</tr>
		</table>

		<p>
			The tests below are optional and only enhance the experience of Spine.
		</p>

		<table border="0" width="100%" cellpadding="4" cellspacing="2">
			<tr>
				<th>Session Directory</th>
				<?php
					if(is_dir(BASE_PATH . DS . TMP_PATH . DS .  'cache/') && is_writable(BASE_PATH . DS . TMP_PATH . DS .  'sessions/')){
				?>
				<td class="pass"><?php echo BASE_PATH . DS .  TMP_PATH . DS .  'sessions' . DS; ?></td>
				<?php
					}else{
				?>
				<td class="fail">Could not locate <?php echo BASE_PATH . TMP_PATH . DS .  'sessions' . DS; ?></td>
				<?php
					}
				?>
			</tr>
			<tr>
				<th>Database Drivers</th>
				<?php
					$supported = array('mysql','mysqli');
					$available = array();
					foreach(get_loaded_extensions() as $ext){
						if(in_array($ext, $supported)){
							$available[] = $ext;
						}
					}
					if(!empty($available)){
				?>
				<td class="pass"><?php echo implode(', ', $available); ?></td>
				<?php
					}else{
				?>
				<td class="fail">No supported database extension found. (<?php echo implode(', ', $supported); ?>)</td>
				<?php
					}
				?>
			</tr>
			<tr>
				<th>PDO Enabled</th>
				<?php
					if(extension_loaded('pdo')){
				?>
				<td class="pass">Pass</td>
				<?php
					}else{
				?>
				<td class="fail">Fail (it is recommended that you use PDO with Databases)</td>
				<?php
					}
				?>
			</tr>
			<tr>
				<th>PDO Available Drivers</th>
				<?php
					if(extension_loaded('pdo')){
						$drivers = PDO::getAvailableDrivers();
						if(!empty($drivers)){
				?>
				<td class="pass"><?php echo implode(', ', $drivers); ?></td>
				<?php
						}else{
				?>
				<td class="fail">PDO is enabled but there are no drivers available.</td>
				<?php
						}
					}else{
				?>
				<td class="fail">Fail</td>
				<?php
					}
				?>
			</tr>
			<tr>
				<th>zlib Enabled</th>
				<?php
					if(extension_loaded('zlib')){
				?>
				<td class="pass">Pass</td>
				<?php
					}else{
				?>
				<td class="fail">Fail</td>
				<?php
					}
				?>
			</tr>
		</table>
	</body>
</html>
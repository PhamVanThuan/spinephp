<?php
	if(!defined('APP_PATH')){
		die('Unauthorized direct access to file.');
	}
?>

NOTICE [2nd August 2010, at 15:58] > Undefined index: content
USER ERROR [2nd August 2010, at 17:12] > Smarty error: [in index.php line 3]: syntax error: unrecognized tag 'while' (Smarty_Compiler.class.php, line 590)
USER ERROR [2nd August 2010, at 17:12] > Smarty error: [in index.php line 6]: syntax error: unrecognized tag '/while' (Smarty_Compiler.class.php, line 590)
WARNING [2nd August 2010, at 17:12] > unlink(application/views/\%%D4^D4F^D4F7EC07%%index.php.php) [<a href='function.unlink'>function.unlink</a>]: No such file or directory
USER ERROR [2nd August 2010, at 17:15] > Smarty error: [in index.php line 3]: syntax error: unrecognized tag 'while' (Smarty_Compiler.class.php, line 590)
USER ERROR [2nd August 2010, at 17:15] > Smarty error: [in index.php line 6]: syntax error: unrecognized tag '/while' (Smarty_Compiler.class.php, line 590)
USER WARNING [2nd August 2010, at 17:27] > Smarty error: unable to read resource: "index.php"
USER WARNING [2nd August 2010, at 17:27] > Smarty error: unable to read resource: "index.php"
WARNING [2nd August 2010, at 17:29] > unlink(temp/smarty/compile/\%%D4^D4F^D4F7EC07%%index.php.php) [<a href='function.unlink'>function.unlink</a>]: No such file or directory
WARNING [2nd August 2010, at 17:31] > unlink(temp/smarty/compile/\%%D4^D4F^D4F7EC07%%index.php.php) [<a href='function.unlink'>function.unlink</a>]: No such file or directory
USER WARNING [2nd August 2010, at 17:42] > Smarty error: unable to read resource: "<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
		<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />

		<title>Spine PHP</title>
    </head>

    <body>

		Welcome to Spine PHP.<br /><br />
You have successfully installed Spine, this is just an example page.<br /><br />
0<br />
1<br />
2<br />
3<br />
4<br />		{if 5 gt 8}
			Smarty is working.
		{/if}

    </body>
</html>"
USER WARNING [2nd August 2010, at 17:42] > Smarty error: unable to read resource: "<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
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
			SpinePHP encountered 1 errors during processing the page. However only the first encountered error
			will be shown for reasons being that any following errors may be the result of the first error. Attempt to fix the error shown first.

			<div class="error-box">
				<h1>User Warning</h1><p>Smarty error: unable to read resource: "<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
		<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />

		<title>Spine PHP</title>
    </head>

    <body>

		Welcome to Spine PHP.<br /><br />
You have successfully installed Spine, this is just an example page.<br /><br />
0<br />
1<br />
2<br />
3<br />
4<br />		{if 5 gt 8}
			Smarty is working.
		{/if}

    </body>
</html>"</p>				<span class="details"">Found in C:\wamp\www\spine\plugins\smarty\Smarty.class.php on line 1093</span>
			</div>
			
			<div id="more">
				If you're having troubles, please visit <a href="http://www.spinephp.org">SpinePHP</a> or check out the <a href="http://www.spinephp.org/wiki">Wiki</a> and <a href="http://www.spinephp.org/forums">Forums</a>.
			</div>
		</div>
    </body>
</html>"
USER WARNING [2nd August 2010, at 17:42] > Smarty error: unable to read resource: "<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
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
			SpinePHP encountered 1 errors during processing the page. However only the first encountered error
			will be shown for reasons being that any following errors may be the result of the first error. Attempt to fix the error shown first.

			<div class="error-box">
				<h1>User Warning</h1><p>Smarty error: unable to read resource: "<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
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
			SpinePHP encountered 1 errors during processing the page. However only the first encountered error
			will be shown for reasons being that any following errors may be the result of the first error. Attempt to fix the error shown first.

			<div class="error-box">
				<h1>User Warning</h1><p>Smarty error: unable to read resource: "<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
		<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />

		<title>Spine PHP</title>
    </head>

    <body>

		Welcome to Spine PHP.<br /><br />
You have successfully installed Spine, this is just an example page.<br /><br />
0<br />
1<br />
2<br />
3<br />
4<br />		{if 5 gt 8}
			Smarty is working.
		{/if}

    </body>
</html>"</p>				<span class="details"">Found in C:\wamp\www\spine\plugins\smarty\Smarty.class.php on line 1093</span>
			</div>
			
			<div id="more">
				If you're having troubles, please visit <a href="http://www.spinephp.org">SpinePHP</a> or check out the <a href="http://www.spinephp.org/wiki">Wiki</a> and <a href="http://www.spinephp.org/forums">Forums</a>.
			</div>
		</div>
    </body>
</html>"</p>				<span class="details"">Found in C:\wamp\www\spine\plugins\smarty\Smarty.class.php on line 1093</span>
			</div>
			
			<div id="more">
				If you're having troubles, please visit <a href="http://www.spinephp.org">SpinePHP</a> or check out the <a href="http://www.spinephp.org/wiki">Wiki</a> and <a href="http://www.spinephp.org/forums">Forums</a>.
			</div>
		</div>
    </body>
</html>"
USER WARNING [2nd August 2010, at 17:42] > Smarty error: unable to read resource: "<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
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
			SpinePHP encountered 1 errors during processing the page. However only the first encountered error
			will be shown for reasons being that any following errors may be the result of the first error. Attempt to fix the error shown first.

			<div class="error-box">
				<h1>User Warning</h1><p>Smarty error: unable to read resource: "<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
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
			SpinePHP encountered 1 errors during processing the page. However only the first encountered error
			will be shown for reasons being that any following errors may be the result of the first error. Attempt to fix the error shown first.

			<div class="error-box">
				<h1>User Warning</h1><p>Smarty error: unable to read resource: "<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
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
			SpinePHP encountered 1 errors during processing the page. However only the first encountered error
			will be shown for reasons being that any following errors may be the result of the first error. Attempt to fix the error shown first.

			<div class="error-box">
				<h1>User Warning</h1><p>Smarty error: unable to read resource: "<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
		<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />

		<title>Spine PHP</title>
    </head>

    <body>

		Welcome to Spine PHP.<br /><br />
You have successfully installed Spine, this is just an example page.<br /><br />
0<br />
1<br />
2<br />
3<br />
4<br />		{if 5 gt 8}
			Smarty is working.
		{/if}

    </body>
</html>"</p>				<span class="details"">Found in C:\wamp\www\spine\plugins\smarty\Smarty.class.php on line 1093</span>
			</div>
			
			<div id="more">
				If you're having troubles, please visit <a href="http://www.spinephp.org">SpinePHP</a> or check out the <a href="http://www.spinephp.org/wiki">Wiki</a> and <a href="http://www.spinephp.org/forums">Forums</a>.
			</div>
		</div>
    </body>
</html>"</p>				<span class="details"">Found in C:\wamp\www\spine\plugins\smarty\Smarty.class.php on line 1093</span>
			</div>
			
			<div id="more">
				If you're having troubles, please visit <a href="http://www.spinephp.org">SpinePHP</a> or check out the <a href="http://www.spinephp.org/wiki">Wiki</a> and <a href="http://www.spinephp.org/forums">Forums</a>.
			</div>
		</div>
    </body>
</html>"</p>				<span class="details"">Found in C:\wamp\www\spine\plugins\smarty\Smarty.class.php on line 1093</span>
			</div>
			
			<div id="more">
				If you're having troubles, please visit <a href="http://www.spinephp.org">SpinePHP</a> or check out the <a href="http://www.spinephp.org/wiki">Wiki</a> and <a href="http://www.spinephp.org/forums">Forums</a>.
			</div>
		</div>
    </body>
</html>"
USER WARNING [2nd August 2010, at 17:42] > Smarty error: unable to read resource: "<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
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
			SpinePHP encountered 1 errors during processing the page. However only the first encountered error
			will be shown for reasons being that any following errors may be the result of the first error. Attempt to fix the error shown first.

			<div class="error-box">
				<h1>User Warning</h1><p>Smarty error: unable to read resource: "<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
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
			SpinePHP encountered 1 errors during processing the page. However only the first encountered error
			will be shown for reasons being that any following errors may be the result of the first error. Attempt to fix the error shown first.

			<div class="error-box">
				<h1>User Warning</h1><p>Smarty error: unable to read resource: "<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
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
			SpinePHP encountered 1 errors during processing the page. However only the first encountered error
			will be shown for reasons being that any following errors may be the result of the first error. Attempt to fix the error shown first.

			<div class="error-box">
				<h1>User Warning</h1><p>Smarty error: unable to read resource: "<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
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
			SpinePHP encountered 1 errors during processing the page. However only the first encountered error
			will be shown for reasons being that any following errors may be the result of the first error. Attempt to fix the error shown first.

			<div class="error-box">
				<h1>User Warning</h1><p>Smarty error: unable to read resource: "<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
		<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />

		<title>Spine PHP</title>
    </head>

    <body>

		Welcome to Spine PHP.<br /><br />
You have successfully installed Spine, this is just an example page.<br /><br />
0<br />
1<br />
2<br />
3<br />
4<br />		{if 5 gt 8}
			Smarty is working.
		{/if}

    </body>
</html>"</p>				<span class="details"">Found in C:\wamp\www\spine\plugins\smarty\Smarty.class.php on line 1093</span>
			</div>
			
			<div id="more">
				If you're having troubles, please visit <a href="http://www.spinephp.org">SpinePHP</a> or check out the <a href="http://www.spinephp.org/wiki">Wiki</a> and <a href="http://www.spinephp.org/forums">Forums</a>.
			</div>
		</div>
    </body>
</html>"</p>				<span class="details"">Found in C:\wamp\www\spine\plugins\smarty\Smarty.class.php on line 1093</span>
			</div>
			
			<div id="more">
				If you're having troubles, please visit <a href="http://www.spinephp.org">SpinePHP</a> or check out the <a href="http://www.spinephp.org/wiki">Wiki</a> and <a href="http://www.spinephp.org/forums">Forums</a>.
			</div>
		</div>
    </body>
</html>"</p>				<span class="details"">Found in C:\wamp\www\spine\plugins\smarty\Smarty.class.php on line 1093</span>
			</div>
			
			<div id="more">
				If you're having troubles, please visit <a href="http://www.spinephp.org">SpinePHP</a> or check out the <a href="http://www.spinephp.org/wiki">Wiki</a> and <a href="http://www.spinephp.org/forums">Forums</a>.
			</div>
		</div>
    </body>
</html>"</p>				<span class="details"">Found in C:\wamp\www\spine\plugins\smarty\Smarty.class.php on line 1093</span>
			</div>
			
			<div id="more">
				If you're having troubles, please visit <a href="http://www.spinephp.org">SpinePHP</a> or check out the <a href="http://www.spinephp.org/wiki">Wiki</a> and <a href="http://www.spinephp.org/forums">Forums</a>.
			</div>
		</div>
    </body>
</html>"
USER WARNING [2nd August 2010, at 17:45] > Smarty error: unable to read resource: "application/templates/default/html.php"
USER WARNING [2nd August 2010, at 17:45] > Smarty error: unable to read resource: "application/templates/default/error.php"
USER WARNING [2nd August 2010, at 17:45] > Smarty error: unable to read resource: "application/templates/default/error.php"
USER WARNING [2nd August 2010, at 17:45] > Smarty error: unable to read resource: "application/templates/default/error.php"
USER WARNING [2nd August 2010, at 17:45] > Smarty error: unable to read resource: "application/templates/default/error.php"
WARNING [2nd August 2010, at 17:46] > unlink(temp/smarty/compile/\%%7C^7CC^7CC1E0C3%%html.php.php) [<a href='function.unlink'>function.unlink</a>]: No such file or directory
USER ERROR [2nd August 2010, at 17:46] > Smarty error: [in C:/wamp/www/spine/application/templates/default/error.php line 9]: syntax error: unrecognized tag: font-family: Tahoma;
				font-size: 12pt;
				padding: 0;
				margin: 0;
				background-color: #f3f3f3; (Smarty_Compiler.class.php, line 446)
NOTICE [2nd August 2010, at 17:46] > Undefined offset: 1
USER ERROR [2nd August 2010, at 17:46] > Smarty error: [in C:/wamp/www/spine/application/templates/default/error.php line 9]: syntax error: unrecognized tag '' (Smarty_Compiler.class.php, line 590)
USER ERROR [2nd August 2010, at 17:46] > Smarty error: [in C:/wamp/www/spine/application/templates/default/error.php line 15]: syntax error: unrecognized tag: color: #3781cd;
				border-bottom: 2px solid #e5e5e5;
				text-decoration: none; (Smarty_Compiler.class.php, line 446)
NOTICE [2nd August 2010, at 17:46] > Undefined offset: 1
USER ERROR [2nd August 2010, at 17:46] > Smarty error: [in C:/wamp/www/spine/application/templates/default/error.php line 15]: syntax error: unrecognized tag '' (Smarty_Compiler.class.php, line 590)
USER ERROR [2nd August 2010, at 17:46] > Smarty error: [in C:/wamp/www/spine/application/templates/default/error.php line 19]: syntax error: unrecognized tag: position: relative;
				width: 100%;
				height: 80px;
				background-color: #231c18;
				border-bottom: 5px solid #19120f; (Smarty_Compiler.class.php, line 446)
NOTICE [2nd August 2010, at 17:46] > Undefined offset: 1
USER ERROR [2nd August 2010, at 17:46] > Smarty error: [in C:/wamp/www/spine/application/templates/default/error.php line 19]: syntax error: unrecognized tag '' (Smarty_Compiler.class.php, line 590)
USER ERROR [2nd August 2010, at 17:46] > Smarty error: [in C:/wamp/www/spine/application/templates/default/error.php line 25]: syntax error: unrecognized tag: position: absolute;
				bottom: 5px;
				left: 5px;
				color: #fff;
				font-size: 0.9em;
				font-weight: bold; (Smarty_Compiler.class.php, line 446)
NOTICE [2nd August 2010, at 17:46] > Undefined offset: 1
USER ERROR [2nd August 2010, at 17:46] > Smarty error: [in C:/wamp/www/spine/application/templates/default/error.php line 25]: syntax error: unrecognized tag '' (Smarty_Compiler.class.php, line 590)
USER ERROR [2nd August 2010, at 17:46] > Smarty error: [in C:/wamp/www/spine/application/templates/default/error.php line 32]: syntax error: unrecognized tag: width: 80%;
				margin: 5px auto;
				font-size: 0.8em; (Smarty_Compiler.class.php, line 446)
NOTICE [2nd August 2010, at 17:46] > Undefined offset: 1
USER ERROR [2nd August 2010, at 17:46] > Smarty error: [in C:/wamp/www/spine/application/templates/default/error.php line 32]: syntax error: unrecognized tag '' (Smarty_Compiler.class.php, line 590)
USER ERROR [2nd August 2010, at 17:46] > Smarty error: [in C:/wamp/www/spine/application/templates/default/error.php line 36]: syntax error: unrecognized tag: border: 1px solid #CCCC66;
				background-color: #FFFFBB;
				padding: 4px;
				width: 100%;
				margin: 5px 0; (Smarty_Compiler.class.php, line 446)
NOTICE [2nd August 2010, at 17:46] > Undefined offset: 1
USER ERROR [2nd August 2010, at 17:46] > Smarty error: [in C:/wamp/www/spine/application/templates/default/error.php line 36]: syntax error: unrecognized tag '' (Smarty_Compiler.class.php, line 590)
USER ERROR [2nd August 2010, at 17:46] > Smarty error: [in C:/wamp/www/spine/application/templates/default/error.php line 42]: syntax error: unrecognized tag: font-size: 0.8em;
				font-weight: bold; (Smarty_Compiler.class.php, line 446)
NOTICE [2nd August 2010, at 17:46] > Undefined offset: 1
USER ERROR [2nd August 2010, at 17:46] > Smarty error: [in C:/wamp/www/spine/application/templates/default/error.php line 42]: syntax error: unrecognized tag '' (Smarty_Compiler.class.php, line 590)
USER ERROR [2nd August 2010, at 17:46] > Smarty error: [in C:/wamp/www/spine/application/templates/default/error.php line 45]: syntax error: unrecognized tag: width: 150px;
				text-align: center;
				padding: 6px;
				background-color: #e3e3e3;
				border-radius: 6px;
				-moz-border-radius: 6px;
				-webkit-border-radius: 6px;
				color: #535353;
				font-size: 0.9em;
				cursor: default; (Smarty_Compiler.class.php, line 446)
NOTICE [2nd August 2010, at 17:46] > Undefined offset: 1
USER ERROR [2nd August 2010, at 17:46] > Smarty error: [in C:/wamp/www/spine/application/templates/default/error.php line 45]: syntax error: unrecognized tag '' (Smarty_Compiler.class.php, line 590)
USER ERROR [2nd August 2010, at 17:46] > Smarty error: [in C:/wamp/www/spine/application/templates/default/error.php line 56]: syntax error: unrecognized tag: margin: 25px 0 0 0; (Smarty_Compiler.class.php, line 446)
NOTICE [2nd August 2010, at 17:46] > Undefined offset: 1
USER ERROR [2nd August 2010, at 17:46] > Smarty error: [in C:/wamp/www/spine/application/templates/default/error.php line 56]: syntax error: unrecognized tag '' (Smarty_Compiler.class.php, line 590)
USER ERROR [2nd August 2010, at 17:46] > Smarty error: [in C:/wamp/www/spine/application/templates/default/error.php line 58]: syntax error: unrecognized tag: margin: 0;
				padding: 0;
				font-size: 1.1em;
				font-weight: bold; (Smarty_Compiler.class.php, line 446)
NOTICE [2nd August 2010, at 17:46] > Undefined offset: 1
USER ERROR [2nd August 2010, at 17:46] > Smarty error: [in C:/wamp/www/spine/application/templates/default/error.php line 58]: syntax error: unrecognized tag '' (Smarty_Compiler.class.php, line 590)
USER ERROR [2nd August 2010, at 17:46] > Smarty error: [in C:/wamp/www/spine/application/templates/default/error.php line 63]: syntax error: unrecognized tag: margin: 5px 0 0 0; (Smarty_Compiler.class.php, line 446)
NOTICE [2nd August 2010, at 17:46] > Undefined offset: 1
USER ERROR [2nd August 2010, at 17:46] > Smarty error: [in C:/wamp/www/spine/application/templates/default/error.php line 63]: syntax error: unrecognized tag '' (Smarty_Compiler.class.php, line 590)
USER ERROR [2nd August 2010, at 17:46] > Smarty error: [in C:/wamp/www/spine/application/templates/default/error.php line 67]: syntax error: unrecognized tag 'var' (Smarty_Compiler.class.php, line 590)
USER ERROR [2nd August 2010, at 17:46] > Smarty error: [in C:/wamp/www/spine/application/templates/default/error.php line 72]: syntax error: unrecognized tag: remainder.style.display = 'none';
					parent.innerHTML = 'Show Remaining Errors'; (Smarty_Compiler.class.php, line 446)
NOTICE [2nd August 2010, at 17:46] > Undefined offset: 1
USER ERROR [2nd August 2010, at 17:46] > Smarty error: [in C:/wamp/www/spine/application/templates/default/error.php line 72]: syntax error: unrecognized tag '' (Smarty_Compiler.class.php, line 590)
WARNING [2nd August 2010, at 17:46] > unlink(temp/smarty/compile/\%%E6^E62^E62B8CC8%%error.php.php) [<a href='function.unlink'>function.unlink</a>]: No such file or directory
NOTICE [2nd August 2010, at 17:46] > Undefined index: remainder
NOTICE [2nd August 2010, at 17:46] > Undefined index: remainder
NOTICE [2nd August 2010, at 17:46] > Undefined index: remainder
NOTICE [2nd August 2010, at 17:46] > Undefined index: remainder
WARNING [2nd August 2010, at 17:48] > unlink(temp/smarty/compile/\%%7F^7F9^7F9E3659%%html.php.php) [<a href='function.unlink'>function.unlink</a>]: No such file or directory
USER ERROR [2nd August 2010, at 18:00] > Smarty error: [in C:/wamp/www/spine/application/templates/default/html.php line 11]: syntax error: unrecognized tag: $tpl['content'] (Smarty_Compiler.class.php, line 446)
NOTICE [2nd August 2010, at 18:00] > Undefined offset: 1
USER ERROR [2nd August 2010, at 18:00] > Smarty error: [in C:/wamp/www/spine/application/templates/default/html.php line 11]: syntax error: unrecognized tag '' (Smarty_Compiler.class.php, line 590)
NOTICE [2nd August 2010, at 18:00] > Undefined index: remainder
NOTICE [2nd August 2010, at 18:00] > Undefined index: remainder
NOTICE [2nd August 2010, at 18:00] > Undefined index: remainder
NOTICE [2nd August 2010, at 18:00] > Undefined index: remainder
NOTICE [2nd August 2010, at 18:23] > Undefined property: IndexController::$Smarty
NOTICE [2nd August 2010, at 18:25] > Undefined index: smarty
NOTICE [2nd August 2010, at 18:25] > Undefined index: 
WARNING [2nd August 2010, at 18:25] > Invalid argument supplied for foreach()
NOTICE [2nd August 2010, at 18:25] > Undefined variable: index
NOTICE [2nd August 2010, at 18:25] > Undefined variable: index
NOTICE [2nd August 2010, at 18:25] > Undefined property: IndexController::$Smarty
NOTICE [2nd August 2010, at 18:25] > Undefined property: IndexController::$Smarty

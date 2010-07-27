<?php
	if(ini_get('register_globals') === 1){
		die("globals are on");
	}else{
		die("globals are off");
	}
?>
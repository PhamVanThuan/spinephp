<?php
	if($vars = $this->request('home/example,limit:10/order:asc/sort:id')){
		foreach($vars as $news){
			echo 'The article is ID ' . $news['id'] . ' and the name is ' . $news['article'] . '<br />';
		}
	}else{
		echo 'Argh, damn!';
	}
?>
<?php

	/**
	* Home Model
	*
	* Just a quick model, grabs a few things from the database.
	* More of an example model really.
	*/
	class HomeModel extends Model {

		function get_news(){
			$this->DB->query('SELECT * FROM test ORDER BY ' . $this->params['sort'] . ' ' . $this->params['order'] . ' LIMIT ' . $this->params['limit']);
			return $this->DB->fetch_all('id', 'article');
		}
		
	}
?>
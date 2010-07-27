<?php

	/**
	 * html.helper.php
	 *
	 * HTML Helper Class
	 */

	class HtmlHelper extends Helpers {

		public $html = array(
			'link' => '<a href="%s">%s</a>',
			'mailto' => '<a href="mailto:%s">%s</a>'
		);

		/**
		 * link
		 *
		 * Return a correctly formatted HTML link tag.
		 *
		 * @param mixed $url
		 * @param string $title
		 * @return string
		 */
		public function link($url, $title = null){
			$url = $this->build_url($url);
			if(empty($title)){
				$title = $url;
			}

			return sprintf($this->html['link'], $url, $title);
		}

		/**
		 * mail
		 *
		 * Return a correctly formatted HTML link tag to an e-mail.
		 *
		 * @param string $email
		 * @param string $title
		 * @return string
		 */
		public function mail($email, $title = null){
			if(empty($title)){
				$title = $email;
			}

			return sprintf($this->html['mailto'], $email, $title);
		}

	}

?>
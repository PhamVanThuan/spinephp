<?php

	/**
	 * html.helper.php
	 *
	 * HTML Helper Class
	 */

	class HtmlHelper extends Helpers {

		public $html = array(
			'link' => '<a href="%s"%s>%s</a>',
			'mailto' => '<a href="mailto:%s"%s>%s</a>',
			'img' => '<img src="%s"%s />'
		);

		/**
		 * link
		 *
		 * Return a correctly formatted HTML link tag.
		 *
		 * @param mixed $url
		 * @param string $text
		 * @param array $attr
		 * @return string
		 */
		public function link($url, $text = null, $attr = array()){
			$url = $this->build_url($url);
			$attr = $this->parse_attributes($attr);

			if(empty($text)){
				$text = $url;
			}

			return sprintf($this->html['link'], $url, $attr, $text);
		}

		/**
		 * mail
		 *
		 * Return a correctly formatted HTML link tag to an e-mail.
		 *
		 * @param string $email
		 * @param string $text
		 * @param array $attr
		 * @return string
		 */
		public function mail($email, $text = null, $attr = array()){
			if(empty($text)){
				$text = $email;
			}

			$attr = $this->parse_attributes($attr);

			return sprintf($this->html['mailto'], $email, $text, $attr);
		}

		/**
		 * img
		 *
		 * Link to an image inside the current templates /public/img directory.
		 *
		 * @param string $img
		 * @param array $attr
		 * @return string
		 */
		public function img($img, $attr = array()){
			if(empty($this->registry->Template->user_set_template)){
				list($folder, $template) = Config::read('Template.default_template');
			}else{
				list($folder, $template) = $this->registry->Template->user_set_template;
			}

			// Parse the attributes into a string.
			$attr = $this->parse_attributes($attr);

			return sprintf($this->html['img'], SYS_URL . 'application/templates/' . $folder . '/public/img/' . $img, $attr);
			
		}

	}

?>
<?php

	/**
	 * geshi.plugin.php
	 *
	 * Geshi Plugin. Registers Geshi hooks and allows geshi to be
	 * used as a syntax highlighter.
	 *
	 * Copyright (c) 2010, Jason Lewis (http://www.spinephp.org)
	 *
	 * Licensed under the MIT License.
	 * Redistribution of files must retain the above copyright notice.
	 *
	 * @copyright	Copyright 2010, Jason Lewis
	 * @link		(http://www.spinephp.org)
	 * @license		MIT License (http://www.opensource.org/licenses/mit-license.html)
	 */

	class GeshiPlugin {

		// Attached hook.
		public $hook = 'Display.before';

		// Location of the geshi file.
		public $geshi_file = 'geshi.php';

		// Language File
		public $language = 'php';

		// GeSHi Line Numbering
		public $line_numbers = true;

		// GeSHi Header
		public $header = 'div';

		// Tab Width
		public $tab_width = 4;

		// Keyword Links
		public $keyword_links = true;

		/**
		 * set_hooks
		 *
		 * Registers the hooks for the geshi plugin.
		 */
		public function set_hooks(){
			Hooks::register('Geshi', 'Display.before', null, $this, 'highlight');
		}

		/**
		 * highlight
		 *
		 * The hook that is called before the output is displayed.
		 */
		public function highlight(){
			$output = & Template::$output;

			if(!file_exists(dirname(__FILE__) . (substr($this->geshi_file, 0, 1) != '/' ? '/' : '') . $this->geshi_file)){
				return false;
			}

			// Load in the Geshi file.
			require_once($this->geshi_file);

			/**
			 * Find all traces of <highlightcode>
			 * Can also have the paramater of lang
			 */
			preg_match_all('/<highlight\s?(.*?)>(.*?)<\/highlight>/smi', $output, $matches);
			array_splice($matches, 0, 1);

			$params = array(
				'lines',
				'lang',
				'tabs'
			);

			$headers = array(
				'none' => GESHI_HEADER_NONE,
				'div' => GESHI_HEADER_DIV
			);

			$highlights = array();
			for($i = 0; $i < count($matches[0]); ++$i){
				preg_match_all('/(\w+)\s?\=\s?[\'"]?(\w+)[\'"]?/is', $matches[0][$i], $tmp);
				$attr = array();
				for($k = 0; $k < count($tmp[1]); ++$k){
					// Make sure that the attribute is valid.
					if(in_array($tmp[1][$k], $params)){
						$attr[$tmp[1][$k]] = $tmp[2][$k];
					}
				}

				// Default langauge.
				if(!isset($attr['lang'])){
					$attr['lang'] = $this->language;
				}

				// Default lines.
				if(!isset($attr['lines'])){
					$attr['lines'] = $this->line_numbers;
				}else{
					$attr['lines'] = $attr['lines'] == 'false' ? false : true;
				}

				// Default header.
				if(!isset($attr['header'])){
					$attr['header'] = $headers[$this->header];
				}else{
					if(!in_array($attr['header'], $headers)){
						$attr['header'] = $this->header;
					}
					$attr['header'] = $headers[$attr['header']];
				}

				// Default tabs.
				if(!isset($attr['tabs'])){
					$attr['tabs'] = $this->tab_width;
				}

				$highlights[] = array(
					'code' => $matches[1][$i],
					'attr' => $attr
				);
			}

			// Create the new GeSHi object.
			$geshi = new GeSHi;
			$geshi->set_encoding('UTF-8');
			$geshi->enable_classes();
			$geshi->enable_keyword_links($this->keyword_links);

			foreach($highlights as $key => $val){
				// Set the code and language.
				$geshi->set_source(trim(html_entity_decode($val['code'])));
				$geshi->set_language($val['attr']['lang']);

				// Tab width
				$geshi->set_tab_width($val['attr']['tabs']);

				// Overall class
				$geshi->set_overall_class('source-' . $val['attr']['lang']);

				// Set the CSS
				$registry->Template->append_head_code($this->generate_css($geshi, $key === 0 ? true : false, ($key == count($highlights) - 1 ? true : false)));

				if($geshi->error() == GESHI_ERROR_NO_SUCH_LANG){
					die('Highlighting failed, attempted to load a language that was not recognised.');
				}

				// Line numbers.
				if(isset($val['attr']['lines']) && $val['attr']['lines'] == true){
					$geshi->enable_line_numbers(GESHI_FANCY_LINE_NUMBERS, 2);
				}else{
					$geshi->enable_line_numbers(GESHI_NO_LINE_NUMBERS);
				}

				// Header.
				if(isset($val['attr']['header'])){
					$geshi->set_header_type($val['attr']['header']);
				}

				// Parse the code.
				$replace = $geshi->parse_code();
				if($val['attr']['header'] == GESHI_HEADER_DIV){
					$replace = '<div class="geshi-plugin"><div class="body">' . $replace . '</div></div>';
				}

				// Replace, from left to right, how the array was built.
				$output = preg_replace('/<highlight\s?(.*?)>(.*?)<\/highlight>/smi', $replace, $output, 1);
			}
		}

		/**
		 * generate_css
		 *
		 * Generate the geshi related css.
		 *
		 * @param object $geshi
		 * @param boolean $first
		 * @param boolean $final
		 * @return string
		 */
		public function generate_css($geshi, $first = true, $final = false){
			$css = array();
			if($first){
				$css[] = "\n<style type=\"text/css\">/*<![CDATA[*/";
			}
			
			$css[] = $geshi->get_stylesheet(false);

			if($final){
				$css[] = "/*]]>*/";
				$css[] = "</style>";
			}

			return implode("\n", $css);
		}

	}

?>

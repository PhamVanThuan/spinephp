<?php

	/**
	 * thumbnail.plugin.php
	 *
	 * Creates a thumbnail of an image.
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

	class ThumbnailPlugin {

		// Width of thumbnails in pixels
		protected $max_width;

		// Height of thumbnails in pixels.
		protected $max_height;

		// Calculated thumbnail width.
		protected $thumb_width;

		// Calculated thumbnail height.
		protected $thumb_height;

		// Scaling larger images.
		protected $scale;

		// Inflate smaller images.
		protected $inflate;

		// Quality of JPEG images.
		protected $quality;

		// The created thumbnail.
		protected $thumb;

		// Array of MIME type loaders.
		protected $loaders = array(
			'image/jpeg' => 'imagecreatefromjpeg',
			'image/png' => 'imagecreatefrompng',
			'image/gif' => 'imagecreatefromgif'
		);

		// Array of MIME type creators.
		protected $creators = array(
			'image/jpeg' => 'imagejpeg',
			'image/png' => 'imagepng',
			'image/gif' => 'imagegif'
		);

		// Source image properties.
		protected $source;
		protected $source_width;
		protected $source_height;
		protected $source_mime;

		public function __construct(){
			if(!extension_loaded('gd')){
				$this->__destruct();
			}
		}

		/**
		 * set_dimensions
		 *
		 * Set the dimensions to use for thumbnails.
		 *
		 * @param int $max_width
		 * @param int $max_height
		 * @param boolean $scale
		 * @param boolean $inflate
		 * @param int $quality
		 */
		public function set_dimensions($max_width = null, $max_height = null, $scale = true, $inflate = true, $quality = 75){
			$this->max_width = $max_width;
			$this->max_height = $max_height;
			$this->scale = $scale;
			$this->inflate = $inflate;
			$this->quality = 75;
		}

		/**
		 * load_image
		 *
		 * Loads an image and applys thumbnailing with set dimensions.
		 *
		 * @param string $url
		 * @return boolean
		 */
		public function load_image($url){
			if(is_readable($url)){
				$data = @getimagesize($url);

				if(!$data){
					return;
				}

				if(array_key_exists($data['mime'], $this->loaders)){
					// Valid image type.

					$loader = $this->loaders[$data['mime']];
					if(!function_exists($loader)){
						return;
					}

					$this->source = $loader($url);
					$this->source_width = $data[0];
					$this->source_height = $data[1];
					$this->source_mime = $data['mime'];

					// Now to determine the width and height. Start by getting both ratios.
					$ratio_width = $ratio_height = 1;
					if($this->max_width > 0){
						$ratio_width = $this->max_width / $this->source_width;
					}
					if($this->max_height > 0){
						$ratio_height = $this->max_height / $this->source_height;
					}

					if($this->scale){
						// We are scaling the image.
						if($this->source_width > $this->source_height){
							$ratio = $ratio_width;
						}elseif($this->source_height > $this->source_width){
							$ratio = $ratio_height;
						}else{
							$ratio = 1;
						}

						// If not inflating and ratio is enlarge, set ratio to 0
						if(!$this->inflate && $ratio > 1){
							$ratio = 1;
						}

						$this->thumb_width = round($ratio * $this->source_width);
						$this->thumb_height = round($ratio * $this->source_height);
					}else{
						if(!isset($ratio_width) || (!$this->inflate && $ratio_width > 1)){
							$ratio_width = 1;
						}
						if(!isset($ratio_height) || (!$this->inflate && $ratio_height > 1)){
							$ratio_height = 1;
						}

						$this->thumb_width = round($ratio_width * $this->source_width);
						$this->thumb_height = round($ratio_height * $this->source_height);
					}

					// Create the thumbnail
					if($this->source_mime == 'image/png'){
						// Preserve PNG transparancy.
						$this->thumb = imagecreate($this->thumb_width, $this->thumb_height);
						$color = imagecolorallocate($this->thumb, 0, 0, 0);
						imagecolortransparent($this->thumb, $color);
					}else{
						$this->thumb = imagecreatetruecolor($this->thumb_width, $this->thumb_height);
					}

					if($data[0] == $this->max_width && $data[1] == $this->max_height){
						// No resizing was needed.
						$this->thumb = $this->source;
					}else{
						// Create the new image.
						imagecopyresampled($this->thumb, $this->source, 0, 0, 0, 0, $this->thumb_width, $this->thumb_height, $data[0], $data[1]);
					}

					return true;
				}
			}
		}

		/**
		 * save
		 *
		 * Save a thumbnail to a destination.
		 *
		 * @param string $destination
		 * @param string $mime
		 */
		public function save($destination, $mime = null){
			if(!empty($mime)){
				$function = $this->creators[$mime];
			}else{
				$function = $this->creators[$this->source_mime];
			}

			if($function === 'imagejpeg'){
				imagejpeg($this->thumb, $destination, $this->quality);
			}else{
				$function($this->thumb, $destination);
			}
			
		}

	}

?>

<?php
Configure::load('tinypng');
App::uses('HtmlHelper', 'View/Helper');

class TinyPngHelper extends HtmlHelper {
	
	const tinypng_url = 'https://api.tinypng.com/shrink';

    public function image($path, $options = Array()){
    	if(pathinfo( WWW_ROOT . 'img/' . $path, PATHINFO_EXTENSION) == 'png'){
			$input = WWW_ROOT . 'img/' . $path;
			$output = WWW_ROOT . 'img/resized/' . $path;
    		if(!file_exists($output)){
				$request = curl_init();
				curl_setopt_array($request, array(
				  CURLOPT_URL => self::tinypng_url,
				  CURLOPT_USERPWD => "api:" . Configure::read('TinyPng.tinypng_key'),
				  CURLOPT_POSTFIELDS => file_get_contents($input),
				  CURLOPT_BINARYTRANSFER => true,
				  CURLOPT_RETURNTRANSFER => true,
				  CURLOPT_HEADER => true,
				  // Uncomment below if you have trouble validating our SSL certificate.
				  // Download cacert.pem from: http://curl.haxx.se/ca/cacert.pem 
				  // CURLOPT_CAINFO => __DIR__ . "/cacert.pem",
				  CURLOPT_SSL_VERIFYPEER => true
				));

				$response = curl_exec($request);
				if (curl_getinfo($request, CURLINFO_HTTP_CODE) === 201) {
					// Compression was successful, retrieve output from Location header.
					$headers = substr($response, 0, curl_getinfo($request, CURLINFO_HEADER_SIZE));
					foreach (explode("\r\n", $headers) as $header) {
						if (substr($header, 0, 10) === "Location: ") {
							$request = curl_init();
							curl_setopt_array($request, array(
							CURLOPT_URL => substr($header, 10),
							CURLOPT_RETURNTRANSFER => true,
							// Uncomment below if you have trouble validating our SSL certificate. 
							// CURLOPT_CAINFO => __DIR__ . "/cacert.pem",
							CURLOPT_SSL_VERIFYPEER => true
							));
						    if (!is_dir(WWW_ROOT . 'img/resized/')){
						    	if(!is_writable(WWW_ROOT . 'img/resized/')){
						    		mkdir(WWW_ROOT . 'img/resized/', 0775, true);
						    	}
						    }
							file_put_contents($output, curl_exec($request));
						}
					}
				} else {
			    	print(curl_error($request));
			  		print("Compression failed");
				}
    		}
    		$path = 'resized/'.$path;
    	}

		$path = $this->assetUrl($path, $options + array('pathPrefix' => Configure::read('App.imageBaseUrl')));
		$options = array_diff_key($options, array('fullBase' => null, 'pathPrefix' => null));

		if (!isset($options['alt'])) {
			$options['alt'] = '';
		}

		$url = false;
		if (!empty($options['url'])) {
			$url = $options['url'];
			unset($options['url']);
		}

		$image = sprintf($this->_tags['image'], $path, $this->_parseAttributes($options, null, '', ' '));

		if ($url) {
			return sprintf($this->_tags['link'], $this->url($url), null, $image);
		}
		return $image;
    }    
}

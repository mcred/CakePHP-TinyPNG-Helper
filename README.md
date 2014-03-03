CakePHP-TinyPNG-Helper
======================

A CakePhp Plugin that extends the HTML Image helper to resize and cache PNG files using the TinyPNG.com API.

#Requirements#
*PHP 4,5<br />
*CakePhp 2+<br />
*TinyPNG API key

#Installation#
```
$ cd /your_app_path/Plugin
$ git submodule add git@github.com:mcred/CakePHP-TMDB-API-Plugin.git TinyPng
```

#Configuration#
1. Create an account at https://tinypng.com/developers
2. Obtain your API key
3. Create a copy of tinypng.php to /app/Config/tinypng.php
4. Insert your API Key
5. Edit your /app/Controller/AppController.php

```
class AppController extends Controller {
	public $helpers = array(
		'Html' => array('className' => 'TinyPng.TinyPng')
	);
}
```

#Usage#
This Plugin extends the Image helper, so usage is the same. There are no special calls required.

#Example#
Load the CakePHP Favicon
```
<?php echo $this->Html->image('cake.icon.png'); ?>
```

#Change History#
CakePHP TMDB v.1 - 2014-03-03<br />
*Initial Committ

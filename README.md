# JG WordPress Kit

## Overview

JG WordPress Kit is an innovative WordPress plugin development framework designed to streamline and organize your plugin development process. By modularizing features into organized chunks, it enables developers to build robust and scalable WordPress plugins with ease.

More information can be found on my [Medium article](https://medium.com/@jtgraham38/my-innovative-wordpress-plugin-development-framework-ede03f540f6)

## Features

- **Modular Structure:** Break down your plugin into manageable and reusable features.
- **Easy Integration:** Simplifies the process of adding filters and actions.
- **Flexible Configuration:** Easily set plugin prefixes, base directories, and URLs.
- **Extensible:** Allows for easy registration and initialization of new features.

## Usage

### Plugin Initialization Example

```php:your-plugin.php
use jtgraham38\jgwordpresskit\Plugin;
use YourPlugin\YourFirstFeature;
use YourPlugin\YourSecondFeature;

$plugin = new Plugin('your_prefix', plugin_dir_path(__FILE__), plugin_dir_url(__FILE__));

// Add features to the plugin
$plugin->register_feature(new YourFirstFeature()); // Add your first feature
$plugin->register_feature(new YourSecondFeature()); // Add your second feature

// Initialize the plugin
$plugin->init();
```

### Plugin Uninstall Example

```php:uninstall.php
<?php
defined('WP_UNINSTALL_PLUGIN') || exit;

use jtgraham38\jgwordpresskit\Plugin;

require_once __DIR__ . '/vendor/autoload.php';

$plugin = new Plugin('your_prefix', plugin_dir_path(__FILE__), plugin_dir_url(__FILE__));
Plugin::uninstall('your_prefix_');
```


```json: composer.json
...

   "autoload": {
       "psr-4": {
           "YourPlugin\\": "features/"
      }
   },
    "require": {
        "jtgraham38/jgwordpresskit": "dev-main"
    },
    "repositories": [
        {
            "type": "composer",
            "url": "https://php.jacob-t-graham.com"
        }
    ]

...

```
### Plugin Feature Implementation Example

```php:features/YourFirstFeature/YourFirstFeature.php
<?php

//exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

use jtgraham38\jgwordpresskit\PluginFeature;

class YourFirstFeature extends PluginFeature{
    public function add_filters(){
        //todo: add filters here
    }

    public function add_actions(){
        \add_action('admin_menu', array($this, 'add_menu'));
    }

    //  \\  //  \\  //  \\  //  \\  //  \\  //  \\  //  \\  //  \\
    public function add_menu(){
        add_menu_page(
            'YourFirstFeature', // page title
            'YourFirstFeature', // menu title
            'manage_options', // capability
            'yourfirstfeature', // menu slug
            array($this, 'render_page') // callback function
        );
    }

    public function render_page(){
        echo esc_html("<h1>YourFirstFeature</h1> <strong>Coming soon...</strong>");
    }


}
```

## Contributing

Contributions are welcome! Please fork the repository and submit a pull request for any improvements or bug fixes.

## License

This project is licensed under the MIT License.

## Author

[John Graham](https://jacob-t-graham.com/contact/)

[GitHub](https://github.com/jtgraham38) [Medium](https://medium.com/@jtgraham38)
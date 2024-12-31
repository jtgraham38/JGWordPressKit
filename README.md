# JG WordPress Kit

## Overview

JG WordPress Kit is an innovative WordPress plugin development framework designed to streamline and organize your plugin development process. By modularizing features into organized chunks, it enables developers to build robust and scalable WordPress plugins with ease.

> **"When working with plugin code, prioritize *what features* are implemented and *where* they reside rather than *which frameworks* are used and *how to navigate their extensive directories*."**

More information can be found in this [article](https://jacob-t-graham.com/2024/11/07/my-innovative-wordpress-plugin-development-framework/).

## Features

- **Modular Structure:** Break down your plugin into manageable and reusable features.
- **Easy Integration:** Simplifies the process of adding filters and actions.
- **Flexible Configuration:** Easily set plugin prefixes, base directories, and URLs.
- **Extensible:** Allows for easy registration and initialization of new features.

## Directory Structure

```
your-plugin
|->features
|  |->Api
|  |->Etc
|  |->FirstBlock
|  |->SecondBlock
|  |->Settings
|  |->Shortcode
|  |->Statistics
|  |->Widget
|->vendor
|  |->dependencies here...
|->composer.json
|->composer.lock
|->plugin.php
|->README.md
|->uninstall.php
```

## Usage

### Plugin Initialization Example

#### your-plugin.php
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

#### uninstall.php
```php:uninstall.php
<?php
defined('WP_UNINSTALL_PLUGIN') || exit;

use jtgraham38\jgwordpresskit\Plugin;

$plugin = new Plugin('your_prefix', plugin_dir_path(__FILE__), plugin_dir_url(__FILE__));
Plugin::uninstall('your_prefix_');
```

#### composer.json
```json: composer.json
...

   "autoload": {
       "psr-4": {
           "YourPlugin\\": "features/"
      }
   },
    "require": {
        "jtgraham38/jgwordpresskit": "^1.1.0"
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

#### features/YourFirstFeature/YourFirstFeature.php
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

## More Info

Check this [article](https://jacob-t-graham.com/2024/11/07/my-innovative-wordpress-plugin-development-framework/) for more info about this project

## Author

[Jacob Graham](https://jacob-t-graham.com/contact/)

[GitHub](https://github.com/jtgraham38) [X](https://x.com/jtgraham38) [Medium](https://medium.com/@jtgraham38)

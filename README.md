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
- **Key-Based Feature Sharing:** Features can access other features using string keys for seamless communication.
- **Streamlined Uninstall:** Automatic cleanup through feature-specific uninstall methods.
- **Prefix Utilities:** Built-in methods for consistent prefixing of strings, options, and settings.
- **Configuration Management:** Single source of truth for plugin configuration with easy access across all features.

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

// Define your plugin configuration
$config = [
    'version' => '1.0.0',
    'api_endpoint' => 'https://api.example.com',
    'debug_mode' => false,
    'cache_duration' => 3600,
    'max_items' => 100
];

$plugin = new Plugin('your_prefix', plugin_dir_path(__FILE__), plugin_dir_url(__FILE__), $config);

// Add features to the plugin with descriptive keys
$plugin->register_feature('first_feature', new YourFirstFeature());
$plugin->register_feature('second_feature', new YourSecondFeature());

// Initialize the plugin
$plugin->init();

// Register the uninstall hook
register_uninstall_hook(__FILE__, array($plugin, 'uninstall'));
```

### Plugin Uninstall Example

#### uninstall.php
```php:uninstall.php
<?php
defined('WP_UNINSTALL_PLUGIN') || exit;

// The uninstall method will be called automatically via the hook
// No additional code needed here
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
            $this->prefixed('yourfirstfeature'), // menu slug with prefix
            array($this, 'render_page') // callback function
        );
    }

    public function render_page(){
        echo esc_html("<h1>YourFirstFeature</h1> <strong>Coming soon...</strong>");
        
        // Access configuration values
        $version = $this->config('version');
        $debug_mode = $this->config('debug_mode');
        
        if ($debug_mode) {
            echo '<p>Debug mode is enabled. Version: ' . esc_html($version) . '</p>';
        }
    }

    // Example of accessing another feature
    public function access_other_feature() {
        $second_feature = $this->get_feature('second_feature');
        // Now you can use methods from the second feature
    }

    // Example of using configuration in feature logic
    public function process_data() {
        $max_items = $this->config('max_items');
        $cache_duration = $this->config('cache_duration');
        
        // Use configuration values in your feature logic
        $items = get_posts(['numberposts' => $max_items]);
        
        // Set cache with configured duration
        set_transient($this->prefixed('cached_data'), $items, $cache_duration);
    }

    // Cleanup method called during uninstall
    public function uninstall() {
        // Clean up any data, options, or settings created by this feature
        delete_option($this->prefixed('some_option'));
        // Remove any database tables, etc.
    }
}
```

## Key Features

### Prefix Utilities

The framework provides convenient methods for consistent prefixing:

- `$this->prefixed($string)` - Returns a string prefixed with your plugin prefix
- `$this->pre($string)` - Echoes a prefixed string
- `$this->get_prefix()` - Returns the plugin prefix
- `$this->get_base_dir()` - Returns the plugin base directory
- `$this->get_base_url()` - Returns the plugin base URL

### Feature Communication

Features can access other features using string keys:

```php
// In any feature class
$other_feature = $this->get_feature('feature_key');
$other_feature->some_method();
```

### Streamlined Uninstall

The framework automatically handles plugin uninstallation by calling the `uninstall()` method on each feature that implements it. Simply call `$plugin->uninstall()` in your uninstall.php file, and each feature will clean up its own data.

### Configuration Management

The framework provides a centralized configuration system that serves as a single source of truth for your plugin settings:

```php
// Access configuration values in any feature
$version = $this->config('version');
$api_endpoint = $this->config('api_endpoint');
$debug_mode = $this->config('debug_mode');
```

**Benefits:**
- **Single Source of Truth:** All configuration is defined in one place
- **Easy Access:** Any feature can access any configuration value
- **Type Safety:** Configuration values are read-only and consistent
- **Maintainability:** Change a value once and it's updated everywhere

## Contributing

Contributions are welcome! Please fork the repository and submit a pull request for any improvements or bug fixes.

## License

This project is licensed under the MIT License.

## More Info

Check this [article](https://jacob-t-graham.com/2024/11/07/my-innovative-wordpress-plugin-development-framework/) for more info about this project

## Author

[Jacob Graham](https://jacob-t-graham.com/contact/)

[GitHub](https://github.com/jtgraham38) [X](https://x.com/jtgraham38) [Medium](https://medium.com/@jtgraham38)

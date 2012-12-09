<?php
/*
Plugin Name: Less Developer
Description: Auto change *.css to *.less for theme development.
Author: Takayuki Miyauchi
Plugin URI: http://wpist.me/
Version: 0.1.0
Author URI: http://wpist.me/
Domain Path: /languages
Text Domain: less-developer
*/

new LessDeveloper();

class LessDeveloper {

function __construct()
{
    add_filter('style_loader_tag', array(&$this, 'style_loader_tag'), 5, 2);
    add_action('wp_enqueue_scripts', array(&$this, 'wp_enqueue_scripts'));
    add_action('admin_enqueue_scripts', array(&$this, 'wp_enqueue_scripts'));
}

public function style_loader_tag($tag, $handler)
{
    global $wp_styles;
    $style = $wp_styles->registered[$handler];
    $src = $style->src;
    $less = preg_replace('/\.css$/i', '.less', $src);

    $res = wp_remote_head($less);

    if (!is_wp_error($res) && $res['response']['code'] === 200) {
        $tag = str_replace($src, $less, $tag);
        $tag = preg_replace("/stylesheet/i", 'stylesheet/less', $tag);
    }

    return $tag;
}

public function wp_enqueue_scripts()
{
    wp_enqueue_script(
        'lesscss',
        'http://cloud.github.com/downloads/cloudhead/less.js/less-1.3.1.min.js',
        array(),
        null,
        false
    );
}

}


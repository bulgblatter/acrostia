<?php

/**
 * Implements hook_preprocess_html().
 */
function acrostia_preprocess_html(&$vars) {
  // You can use process hooks to modify the variables before they are passed to
  // the theme function or template file.
  drupal_add_css('http://netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/css/bootstrap-combined.no-icons.min.css', array('type' => 'external'));
  drupal_add_css('http://netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css', array('type' => 'external'));
  
  /**
   * @todo Add this as config in theme or something like that
   */
  $styles = array(
      'acrostia-2',
      'acrostia-3',
      'acrostia-4',
  );
  $vars['classes_array'][] = $styles[array_rand($styles)];
}

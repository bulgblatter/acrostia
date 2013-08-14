<?php

/**
 * @file
 * Template overrides as well as (pre-)process and alter hooks for the
 * Acrostia theme.
 */

/**
 * Implements hook_html_head_alter()
 * add html 5 encoding tag
 */
function acrostia_html_head_alter(&$head_elements) {
  $head_elements['system_meta_content_type']['#attributes'] = array(
      'charset' => 'utf-8'
  );
}

/**
 * Add awesome icons to views rows if views have class "awesome-views".
 *
 * @param <array> view
 *    View object to alter
 */
function acrostia_views_pre_render(&$view) {
  
}

/**
 * Implements hook_form_alter()
 */
function acrostia_form_alter(&$form, &$form_state, $form_id) {
  if ($form_id == 'views_exposed_form' && $form['#id'] == 'views-exposed-form-projects-page-1') {
    $links = array();
    $form['#info']['filter-acrostia_link_filter']['value'] = 'acrostia_link_filter';
    foreach ($form['field_portfilio_tid']['#options'] as $key => $value) {
      $links[] = l($value, $form['#action'], array(
          'query' => array(
              $form['#info']['filter-field_portfilio_tid']['value'] => $key
          ),
          'attributes' => array(
              'rel' => $key,
              'class' => array(
                  isset($_GET[$form['#info']['filter-field_portfilio_tid']['value']]) == $key && $_GET[$form['#info']['filter-field_portfilio_tid']['value']] == $key ? 'active' : 'not-active'
              )
          )
      ));
    }
    $form['field_portfilio_tid']['#attributes']['class'][] = "element-invisible";
    $form['submit']['#attributes']['class'][] = "element-invisible";
    $form['acrostia_link_filter'] = array(
        '#type' => 'markup',
        '#markup' => theme('item_list', array('items' => $links)),
        '#weight' => -100
    );
  }
}

/**
 * Helper function to load slides and generate html
 * @return string
 */
function _acrostia_slides() {
  $slider = array();
  if (drupal_is_front_page()) {
    $slides = _acrostia_load_slides(FALSE);
    if (count($slides)) {
      $path = drupal_get_path('theme', 'acrostia');
      drupal_add_css($path . '/css/superslides.css');
      drupal_add_js($path . '/js/jquery.easing.1.3.js', array('type' => 'file', 'scope' => 'footer'));
      drupal_add_js($path . '/js/jquery.animate-enhanced.min.js', array('type' => 'file', 'scope' => 'footer'));
      drupal_add_js($path . '/js/jquery.superslides.min.js', array('type' => 'file', 'scope' => 'footer'));
      drupal_add_js("(function($) {
  Drupal.behaviors.acrostiaSliderBehavior = {
    attach: function(context, settings) {
      $('#slides').superslides({
      animation: 'fade'
    });
    }
  };
})(jQuery);", array('type' => 'inline', 'scope' => 'footer'));
      $output = '<ul class="slides-container">';
      foreach ($slides as $slide) {
        $output .= '<li>';
        $output .= theme('image', array('path' => $slide['image_path'], 'title' => check_plain($slide['image_title'])));
        $output .= '<div class="container">' . $slide['image_description'] . '</div>';
        $output .= '</li>';
      }
      $output .= '</ul>
  <nav class="slides-navigation">
    <a href="#" class="next"><i class="icon-chevron-right"></i></a>
    <a href="#" class="prev"><i class="icon-chevron-left"></i></a>
  </nav>';
      $slider = array(
          '#prefix' => '<div class="slides-wrapper"><div id="slides">',
          '#suffix' => '</div></div>',
          '#markup' => $output,
      );
    }
  }

  return $slider;
}

/**
 * Get slides settings.
 *
 * @param <bool> $all
 *    Return all slides or only active.
 *
 * @return <array>
 *    Settings information
 */
function _acrostia_load_slides($all = TRUE) {
  // Get all slides
  $slides = variable_get('theme_acrostia_slider_settings', array());
  // Create list of slides to return
  $slides_value = array();
  foreach ($slides as $i => $slide) {
    if ($all || $slide['image_published']) {
      // Add weight param to use `drupal_sort_weight`
      $slide['weight'] = $slide['image_weight'];
      $slides_value[] = $slide;
    }
  }
  // Sort image by weight
  usort($slides_value, 'drupal_sort_weight');

  return $slides_value;
}

/**
 * Set slider settings.
 *
 * @param <array> $value
 *    Settings to save
 */
function acrostia_set_slider($value) {
  variable_set('theme_acrostia_slider_settings', $value);
}

/**
 * Inject fontawesome code in place
 * @param type string $id - id of the awesome element in the array
 * @param type string $daicon - the awesome font class name
 * @param type boolean $hicon - hi-icon class name where to be set
 * @return string to be injected 
 */
function acrostia_awesome_icon($id = '', $daicon = '', $hicon = TRUE) {
  $icon = $hi_icon = FALSE;
  $icons = acrostia_load_awesome_icons();
  if ($hicon) {
    $hi_icon = ' hi-icon';
  }
  if ($id && $id != '' && isset($icons[$id])) {
    $icon = '<i class="' . $icons[$id] . $hi_icon . '"></i>';
  } else {
    // insert random 
    $icon = '<i class="' . $icons[array_rand($icons, 1)] . $hi_icon . '"></i>';
  }
  if ($daicon && $daicon != '') {
    $icon = '<i class="' . $daicon . $hi_icon . '"></i>';
  }
  return $icon;
}

/**
 * Load awesome icons
 * @todo Make it configurable
 */
function acrostia_load_awesome_icons() {
  return array(
      'icon-lightbulb',
      'icon-magic',
      'icon-cogs',
      'icon-rocket',
  );
}
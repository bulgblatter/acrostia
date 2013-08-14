<?php

/**
 * @file
 * Theme settings file for the Acrostia theme.
 */
require_once dirname(__FILE__) . '/template.php';

/**
 * Implements hook_form_FORM_alter().
 */
function acrostia_form_system_theme_settings_alter(&$form, $form_state) {
  // You can use this hook to append your own theme settings to the theme
  // settings form for your subtheme. However, you should also take a look at
  // the 'extensions' concept in the Omega base theme. We highly suggest that you
  // put your custom features and theme settings into extensions.
  $form['slider'] = array(
      '#type' => 'fieldset',
      '#title' => t('Front page background Slider'),
      '#collapsible' => TRUE,
      '#collapsed' => TRUE,
  );
  $slides = _acrostia_load_slides();
  $form['slider']['slides'] = array(
      '#type' => 'vertical_tabs',
      '#title' => t('Slides'),
      '#weight' => 0,
      '#collapsible' => TRUE,
      '#collapsed' => FALSE,
      '#tree' => TRUE
  );
  $i = 0;
  foreach ($slides as $slide_data) {
    $form['slider']['slides'][$i] = array(
        '#type' => 'fieldset',
        '#title' => t('Image !number: !title', array(
            '!number' => $i + 1,
            '!title' => $slide_data['image_title']
        )),
        '#weight' => $i,
        '#collapsible' => TRUE,
        '#collapsed' => FALSE,
        '#tree' => TRUE,
        // Add image config form to $form
        'image' => _acrostia_slide_form($slide_data)
    );
    $i++;
  }
  $form['slider']['image_upload'] = array(
      '#type' => 'file',
      '#title' => t('Upload a new slide'),
      '#weight' => $i
  );
  $form['#submit'][] = 'acrostia_settings_submit';

  $form['acrostia_contact'] = array(
      '#type' => 'fieldset',
      '#title' => t('Contact page info'),
      '#collapsible' => TRUE,
      '#collapsed' => TRUE,
  );
  $form['acrostia_contact']['acrostia_address'] = array(
      '#title' => t('Address'),
      '#type' => 'textarea',
      '#description' => t('Content will be displayed as address info next to the map'),
      '#default_value' => theme_get_setting('acrostia_address', 'acrostia'),
  );
  $form['acrostia_contact']['acrostia_map_lat'] = array(
      '#title' => t('Latitude'),
      '#type' => 'textfield',
      '#description' => t('Type in latitude of your location.'),
      '#default_value' => theme_get_setting('acrostia_map_lat', 'acrostia'),
  );
  $form['acrostia_contact']['acrostia_map_long'] = array(
      '#title' => t('Longitude'),
      '#type' => 'textfield',
      '#description' => t('Type in longitude of your location. simple map link with marker https://maps.google.com/maps?q=loc:18.486691,-69.873219'),
      '#default_value' => theme_get_setting('acrostia_map_long', 'acrostia'),
  );
  $form['acrostia_social'] = array(
      '#type' => 'fieldset',
      '#title' => t('Social links'),
      '#collapsible' => TRUE,
      '#collapsed' => TRUE,
  );
  $form['acrostia_social']['acrostia_facebook'] = array(
      '#title' => t('Facebook URL'),
      '#type' => 'textfield',
      '#description' => t('The link to your facebook profile'),
      '#default_value' => theme_get_setting('acrostia_facebook', 'acrostia'),
  );
  $form['acrostia_social']['acrostia_twitter'] = array(
      '#title' => t('Twitter URL'),
      '#type' => 'textfield',
      '#description' => t('The link to your Twitter profile'),
      '#default_value' => theme_get_setting('acrostia_twitter', 'acrostia'),
  );
  $form['acrostia_social']['acrostia_googleplus'] = array(
      '#title' => t('Google plus URL'),
      '#type' => 'textfield',
      '#description' => t('The link to your Google+ profile'),
      '#default_value' => theme_get_setting('acrostia_googleplus', 'acrostia'),
  );
  $form['acrostia_social']['acrostia_linkedin'] = array(
      '#title' => t('Linked in URL'),
      '#type' => 'textfield',
      '#description' => t('The link to your LindekIn profile'),
      '#default_value' => theme_get_setting('acrostia_linkedin', 'acrostia'),
  );
}

/**
 * Generate form to mange slide informations
 *
 * @param <array> $slide_data
 *    Array with image data
 *
 * @return <array>
 *    Form to manage image informations
 */
function _acrostia_slide_form($slide_data) {
  $form = array();
  // Image preview
  $form['image_preview'] = array(
      '#markup' => theme('image', array('path' => $slide_data['image_thumb']))
  );
  // Image path
  $form['image_path'] = array(
      '#type' => 'hidden',
      '#value' => $slide_data['image_path']
  );
  // Thumbnail path
  $form['image_thumb'] = array(
      '#type' => 'hidden',
      '#value' => $slide_data['image_thumb']
  );
  // Image title
  $form['image_title'] = array(
      '#type' => 'textfield',
      '#title' => t('Title'),
      '#default_value' => $slide_data['image_title']
  );
  // Image description
  $form['image_description'] = array(
      '#type' => 'textarea',
      '#title' => t('Description'),
      '#default_value' => $slide_data['image_description']
  );
  // Link url
  $form['image_url'] = array(
      '#type' => 'textfield',
      '#title' => t('Url'),
      '#default_value' => $slide_data['image_url']
  );
  // Image weight
  $form['image_weight'] = array(
      '#type' => 'weight',
      '#title' => t('Weight'),
      '#default_value' => $slide_data['image_weight']
  );
  // Image is published
  $form['image_published'] = array(
      '#type' => 'checkbox',
      '#title' => t('Published'),
      '#default_value' => $slide_data['image_published']
  );
  // Delete image
  $form['image_delete'] = array(
      '#type' => 'checkbox',
      '#title' => t('Delete image.'),
      '#default_value' => FALSE
  );

  return $form;
}

/**
 * Save settings data.
 */
function acrostia_settings_submit($form, &$form_state) {
  $settings = array();
  // Update image field
  foreach ($form_state['input']['slides'] as $image) {
    if (is_array($image)) {
      $image = $image['image'];
      if ($image['image_delete']) {
        // Delete slide file
        file_unmanaged_delete($image['image_path']);
        // Delete slide thumbnail file
        file_unmanaged_delete($image['image_thumb']);
      } else {
        // Update image
        $settings[] = $image;
      }
    }
  }
  // Check for a new uploaded file, and use that if available.
  if ($file = file_save_upload('image_upload')) {
    $file->status = FILE_STATUS_PERMANENT;
    if ($image = _acrostia_save_image($file)) {
      // Put new image into settings
      $settings[] = $image;
    }
  }
  // Save settings
  acrostia_set_slider($settings);
}

/**
 * Provvide default installation settings for marinelli.
 */
function _acrostia_install() {
  // Deafault data
  $file = new stdClass;
  $slides = array();
  // Source base for images
  $src_base_path = drupal_get_path('theme', 'acrostia');
  $default_slides = theme_get_setting('default_slides');

  // Put all image as slides
  foreach ($default_slides as $i => $data) {
    $file->uri = $src_base_path . '/' . $data['image_path'];
    $file->filename = $file->uri;
    $slide = _acrostia_save_image($file);
    unset($data['image_path']);
    $slide = array_merge($slide, $data);
    $slides[$i] = $slide;
  }
  // Save slide data
  acrostia_set_slider($slides);
  // Flag theme is installed
  variable_set('theme_acrostia_first_install', FALSE);
}

/**
 * Save file uploaded by user and generate setting to save.
 *
 * @param <file> $file
 *    File uploaded from user
 *
 * @param <string> $slide_folder
 *    Folder where save image
 *
 * @param <string> $slide_thumb_folder
 *    Folder where save image thumbnail
 *
 * @return <array>
 *    Array with file data.
 *    FALSE on error.
 */
function _acrostia_save_image($file, $slide_folder = 'public://acrostia/slide/', $slide_thumb_folder = 'public://acrostia/slide/thumb/') {
  // Check directory and create it (if not exist)
  _acrostia_check_dir($slide_folder);
  _acrostia_check_dir($slide_thumb_folder);
  $parts = pathinfo($file->filename);
  $destination = $slide_folder . $parts['basename'];
  $setting = array();
  $file->status = FILE_STATUS_PERMANENT;
  // Copy temporary image into slide folder
  if ($img = file_copy($file, $destination, FILE_EXISTS_REPLACE)) {
    // Generate image thumb
    $image = image_load($destination);
    $small_img = image_scale($image, 300, 100);
    $image->source = $slide_thumb_folder . $parts['basename'];
    image_save($image);
    // Set image info
    $setting['image_path'] = $destination;
    $setting['image_thumb'] = $image->source;
    $setting['image_title'] = '';
    $setting['image_description'] = '';
    $setting['image_url'] = '';
    $setting['image_weight'] = 0;
    $setting['image_published'] = FALSE;
    return $setting;
  }
  return FALSE;
}

/**
 * Check if folder is available or create it.
 *
 * @param <string> $dir
 *    Folder to check
 */
function _acrostia_check_dir($dir) {
  // Normalize directory name
  $dir = file_stream_wrapper_uri_normalize($dir);
  // Create directory (if not exist)
  file_prepare_directory($dir, FILE_CREATE_DIRECTORY);
}
<?php

/**
 * Implements hook_preprocess_page().
 */
function acrostia_preprocess_page(&$variables) {
  // You can use preprocess hooks to modify the variables before they are passed
  // to the theme function or template file.
  // Load slides html 
  $variables['page']['content']['slider'] = _acrostia_slides();
}

<?php
/**
 * @file
 * Default simple view template to display a list of rows.
 *
 * @ingroup views_templates
 */
?>
<?php if (!empty($title)): ?>
  <h3><?php print $title; ?></h3>
<?php endif; ?>
<?php foreach ($rows as $id => $row): ?>
  <div<?php
  if ($classes_array[$id]) {
    print ' class="' . $classes_array[$id] . '"';
  }
  ?>>
      <?php
      /**
       * Insert awesome icons in view
       */
      if (isset($view->display['default']->display_options['css_class'])
      &&
      strpos($view->display['default']->display_options['css_class'], 'awesome-views') !== FALSE) {
        print acrostia_awesome_icon($id);
      }
      ?>
      <?php print $row; ?>
  </div>
<?php endforeach; ?>
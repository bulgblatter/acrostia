<?php print render($page['content']['slider']); ?>
<div class="page">
  <header class="header" role="logo">
    <div class="constrained clearfix">
      <div class="branding site-branding">
        <?php if ($logo): ?>
          <a href="<?php print $front_page; ?>" title="<?php print t('Home'); ?>" rel="home" class="site-branding__logo"><img src="<?php print $logo; ?>" alt="<?php print t('Home'); ?>" /></a>
        <?php endif; ?>
        <?php if ($site_name): ?>
          <a href="<?php print $front_page; ?>" class="site-branding__name" title="<?php print t('Home'); ?>" rel="home"><span><?php print $site_name; ?></span></a>
        <?php endif; ?>
        <?php if ($site_slogan): ?>
          <h2 class="site-branding__slogan"><?php print $site_slogan; ?></h2>
        <?php endif; ?>
      </div>
      <?php print render($page['navigation']); ?>
    </div>
  </header>

  <div class="main constrained">
    <a id="main-content"></a>
    <?php print render($title_prefix); ?>
    <?php if ($title): ?>
      <h1><?php print $title; ?></h1>
    <?php endif; ?>
    <?php print render($title_suffix); ?>
    <?php print render($tabs); ?>
    <?php print $messages; ?>
    <?php print render($page['content_top']); ?>
    <?php print render($page['help']); ?>
    <div class="content" role="main">
      <?php if ($action_links): ?>
        <ul class="action-links"><?php print render($action_links); ?></ul>
      <?php endif; ?>
      <?php print render($page['content']); ?>
      <?php print $feed_icons; ?>
    </div>
    <?php print render($page['content_bottom']); ?>
  </div>
  <footer class="footer-wrapper" role="contentinfo">
    <?php print render($page['footer']); ?>
  </footer>
</div>


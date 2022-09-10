<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1">
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
<?php get_template_part( 'templates/title' ) ?>
<link rel="shortcut icon" href="<?php echo io_get_option('favicon')['url'] ?>">
<link rel="apple-touch-icon" href="<?php echo io_get_option('apple_icon')['url'] ?>">
<?php wp_head(); ?>
<?php custom_color() ?>
</head> 
<body class="<?php echo theme_mode() ?>">
<?php if(io_get_option('loading_fx')) { ?><div id="loading"><div id="preloader_3"></div></div><?php } ?>
   <div class="page-container">
      
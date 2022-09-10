<?php if ( ! defined( 'ABSPATH' )  ) { die; } 
 
$prefix = 'csf_io_shortcodes';
 
CSF::createShortcoder( $prefix, array(
  'button_title'   => '添加短代码',
  'select_title'   => '选择一个短代码',
  'insert_title'   => '插入短代码',
  'show_in_editor' => true,
  'gutenberg'      => array(
    'title'        => 'io 主题短代码',
    'description'  => 'io 主题短代码块',
    'icon'         => 'screenoptions',
    'category'     => 'widgets',
    'keywords'     => array( 'shortcode', 'io', 'insert' ),
    'placeholder'  => '在这里写短代码...',
  )
) );
 
CSF::createSection( $prefix, array(
  'title'     => '插入文章卡片',
  'view'      => 'normal',
  'shortcode' => 'post_card',
  'fields'    => array(

    array(
      'id'    => 'ids',
      'type'  => 'text',
      'title' => '文章id',
      'after' => '多个请用英语逗号分隔，如：1,12,32'
    ),
  )
) );
 
CSF::createSection( $prefix, array(
  'title'     => '插入网址卡片',
  'view'      => 'normal',
  'shortcode' => 'site_card',
  'fields'    => array(

    array(
      'id'    => 'ids',
      'type'  => 'text',
      'title' => '网址id',
      'after' => '多个请用英语逗号分隔，如：1,12,32'
    ),
  )
) );

CSF::createSection( $prefix, array(
  'title'     => '插入 App 卡片',
  'view'      => 'normal',
  'shortcode' => 'app_card',
  'fields'    => array(

    array(
      'id'    => 'ids',
      'type'  => 'text',
      'title' => 'App id',
      'after' => '多个请用英语逗号分隔，如：1,12,32'
    ),
  )
) );


CSF::createSection( $prefix, array(
  'title'     => '插入 广告',
  'view'      => 'normal',
  'shortcode' => 'ad',
  'fields'    => array(
    array(
      'type'    => 'content',
      'content' => '广告代码请到主题设置-->广告添加，点下方“插入短代码”插入文章中',
    ),
  )
) );


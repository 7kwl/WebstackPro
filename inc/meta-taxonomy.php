<?php
// 文章分类SEO设置
if( class_exists( 'CSF' ) ) {
    $prefix = 'category_meta'; 
  
    CSF::createTaxonomyOptions( $prefix, array(
        'taxonomy'  => 'category',
        'data_type' => 'serialize', 
    ) );

  
    CSF::createSection( $prefix, array(
        'fields' => array(
            array(
                'type'    => 'subheading',
                'content' => __('文章分类SEO设置（可留空）','io_setting'),
            ),
            array(
                'id'    => 'seo_title',
                'type'  => 'text',
                'title' => __('自定义标题','io_setting'),
            ),
            array(
                'id'    => 'seo_metakey',
                'type'  => 'text',
                'title' => __('设置关键词','io_setting'),
            ),
            array(
                'id'    => 'seo_desc',
                'type'  => 'textarea',
                'title' => __('自定义描述','io_setting'),
            ),

        )
    ));
}

// 文章标签SEO设置
if( class_exists( 'CSF' ) ) {
    $prefix = 'post_tag_meta'; 
  
    CSF::createTaxonomyOptions( $prefix, array(
        'taxonomy'  => 'post_tag',
        'data_type' => 'serialize', 
    ) );

  
    CSF::createSection( $prefix, array(
        'fields' => array(
            array(
                'type'    => 'subheading',
                'content' => __('文章标签SEO设置（可留空）','io_setting'),
            ),
            array(
                'id'    => 'seo_title',
                'type'  => 'text',
                'title' => __('自定义标题','io_setting'),
            ),
            array(
                'id'    => 'seo_metakey',
                'type'  => 'text',
                'title' => __('设置关键词','io_setting'),
            ),
            array(
                'id'    => 'seo_desc',
                'type'  => 'textarea',
                'title' => __('自定义描述','io_setting'),
            ),

        )
    ));
}
// 网址分类SEO设置
if( class_exists( 'CSF' ) ) {
    $prefix = 'favorites_options'; 
  
    CSF::createTaxonomyOptions( $prefix, array(
        'taxonomy'  => 'favorites',
        'data_type' => 'unserialize', 
    ) );

  
    CSF::createSection( $prefix, array(
        'fields' => array(
            array(
                'type'    => 'notice',
                'style'   => 'danger',
                'content' => __('注意，最多2级，且父级不应有内容','io_setting'),
            ),
            array(
                'id'      => '_term_order',
                'type'    => 'number',
                'title'   => __('排序','io_setting'),
                'default' => 0,
                'after'   => '此选项仅对父级分类归档页有效',
            ), 
            array(
                'type'    => 'subheading',
                'content' => __('SEO设置（可留空）','io_setting'),
            ),
            array(
                'id'    => 'seo_title',
                'type'  => 'text',
                'title' => __('自定义标题','io_setting'),
            ),
            array(
                'id'    => 'seo_metakey',
                'type'  => 'text',
                'title' => __('设置关键词','io_setting'),
            ),
            array(
                'id'    => 'seo_desc',
                'type'  => 'textarea',
                'title' => __('自定义描述','io_setting'),
            ),

        )
    ));
}

// 网址标签SEO设置
if( class_exists( 'CSF' ) ) {
    $prefix = 'sitetag_meta'; 
  
    CSF::createTaxonomyOptions( $prefix, array(
        'taxonomy'  => 'sitetag',
        'data_type' => 'serialize', 
    ) );

  
    CSF::createSection( $prefix, array(
        'fields' => array(
            array(
                'type'    => 'subheading',
                'content' => __('SEO设置（可留空）','io_setting'),
            ),
            array(
                'id'    => 'seo_title',
                'type'  => 'text',
                'title' => __('自定义标题','io_setting'),
            ),
            array(
                'id'    => 'seo_metakey',
                'type'  => 'text',
                'title' => __('设置关键词','io_setting'),
            ),
            array(
                'id'    => 'seo_desc',
                'type'  => 'textarea',
                'title' => __('自定义描述','io_setting'),
            ),

        )
    ));
}
// app分类SEO设置
if( class_exists( 'CSF' ) ) {
    $prefix = 'apps_meta'; 
  
    CSF::createTaxonomyOptions( $prefix, array(
        'taxonomy'  => 'apps',
        'data_type' => 'unserialize', 
    ) );

  
    CSF::createSection( $prefix, array(
        'fields' => array(
            array(
                'type'    => 'notice',
                'style'   => 'danger',
                'content' => __('注意，最多2级，且父级不应有内容','io_setting'),
            ),
            //array(
            //    'id'      => '_term_order',
            //    'type'    => 'number',
            //    'title'   => __('排序','io_setting'),
            //    'default' => 0,
            //    'after'   => '此选项将被弃用，请到主题设置-->基础设置排序',
            //), 
            array(
                'type'    => 'subheading',
                'content' => __('SEO设置（可留空）','io_setting'),
            ),
            array(
                'id'    => 'seo_title',
                'type'  => 'text',
                'title' => __('自定义标题','io_setting'),
            ),
            array(
                'id'    => 'seo_metakey',
                'type'  => 'text',
                'title' => __('设置关键词','io_setting'),
            ),
            array(
                'id'    => 'seo_desc',
                'type'  => 'textarea',
                'title' => __('自定义描述','io_setting'),
            ),

        )
    ));
}

// app标签SEO设置
if( class_exists( 'CSF' ) ) {
    $prefix = 'apptag_meta'; 
  
    CSF::createTaxonomyOptions( $prefix, array(
        'taxonomy'  => 'apptag',
        'data_type' => 'serialize', 
    ) );

  
    CSF::createSection( $prefix, array(
        'fields' => array(
            array(
                'type'    => 'subheading',
                'content' => __('SEO设置（可留空）','io_setting'),
            ),
            array(
                'id'    => 'seo_title',
                'type'  => 'text',
                'title' => __('自定义标题','io_setting'),
            ),
            array(
                'id'    => 'seo_metakey',
                'type'  => 'text',
                'title' => __('设置关键词','io_setting'),
            ),
            array(
                'id'    => 'seo_desc',
                'type'  => 'textarea',
                'title' => __('自定义描述','io_setting'),
            ),

        )
    ));
}

// book分类SEO设置
if( class_exists( 'CSF' ) ) {
    $prefix = 'books_meta'; 
  
    CSF::createTaxonomyOptions( $prefix, array(
        'taxonomy'  => 'books',
        'data_type' => 'unserialize', 
    ) );

  
    CSF::createSection( $prefix, array(
        'fields' => array(
            array(
                'type'    => 'notice',
                'style'   => 'danger',
                'content' => __('注意，最多2级，且父级不应有内容','io_setting'),
            ),
            //array(
            //    'id'      => '_term_order',
            //    'type'    => 'number',
            //    'title'   => __('排序','io_setting'),
            //    'default' => 0,
            //    'after'   => '此选项将被弃用，请到主题设置-->基础设置排序',
            //), 
            array(
                'type'    => 'subheading',
                'content' => __('SEO设置（可留空）','io_setting'),
            ),
            array(
                'id'    => 'seo_title',
                'type'  => 'text',
                'title' => __('自定义标题','io_setting'),
            ),
            array(
                'id'    => 'seo_metakey',
                'type'  => 'text',
                'title' => __('设置关键词','io_setting'),
            ),
            array(
                'id'    => 'seo_desc',
                'type'  => 'textarea',
                'title' => __('自定义描述','io_setting'),
            ),

        )
    ));
}

// book标签SEO设置
if( class_exists( 'CSF' ) ) {
    $prefix = 'booktag_meta'; 
  
    CSF::createTaxonomyOptions( $prefix, array(
        'taxonomy'  => 'booktag',
        'data_type' => 'serialize', 
    ) );

  
    CSF::createSection( $prefix, array(
        'fields' => array(
            array(
                'type'    => 'subheading',
                'content' => __('SEO设置（可留空）','io_setting'),
            ),
            array(
                'id'    => 'seo_title',
                'type'  => 'text',
                'title' => __('自定义标题','io_setting'),
            ),
            array(
                'id'    => 'seo_metakey',
                'type'  => 'text',
                'title' => __('设置关键词','io_setting'),
            ),
            array(
                'id'    => 'seo_desc',
                'type'  => 'textarea',
                'title' => __('自定义描述','io_setting'),
            ),

        )
    ));
}

// book标签SEO设置
if( class_exists( 'CSF' ) ) {
    $prefix = 'series_meta'; 
  
    CSF::createTaxonomyOptions( $prefix, array(
        'taxonomy'  => 'series',
        'data_type' => 'serialize', 
    ) );

  
    CSF::createSection( $prefix, array(
        'fields' => array(
            array(
                'type'    => 'subheading',
                'content' => __('SEO设置（可留空）','io_setting'),
            ),
            array(
                'id'    => 'seo_title',
                'type'  => 'text',
                'title' => __('自定义标题','io_setting'),
            ),
            array(
                'id'    => 'seo_metakey',
                'type'  => 'text',
                'title' => __('设置关键词','io_setting'),
            ),
            array(
                'id'    => 'seo_desc',
                'type'  => 'textarea',
                'title' => __('自定义描述','io_setting'),
            ),

        )
    ));
}
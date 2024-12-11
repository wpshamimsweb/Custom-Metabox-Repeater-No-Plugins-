<?php

function customize_theme_settings($wp_customize) {
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'site_logo', array(
        'label'    => __('Site Logo', 'yourtheme'),
        'section'  => 'title_tagline',
        'settings' => 'site_logo',
    )));

    $wp_customize->add_setting('instagram_url', array(
        'default'   => '',
        'transport' => 'refresh',
    ));

    $wp_customize->add_control('instagram_url', array(
        'label'    => __('Instagram URL', 'yourtheme'),
        'section'  => 'social_links_section',
        'type'     => 'url',
    ));

    $wp_customize->add_setting('facebook_url', array(
        'default'   => '',
        'transport' => 'refresh',
    ));

    $wp_customize->add_control('facebook_url', array(
        'label'    => __('Facebook URL', 'yourtheme'),
        'section'  => 'social_links_section',
        'type'     => 'url',
    ));

    $wp_customize->add_setting('youtube_url', array(
        'default'   => '',
        'transport' => 'refresh',
    ));

    $wp_customize->add_control('youtube_url', array(
        'label'    => __('YouTube URL', 'yourtheme'),
        'section'  => 'social_links_section',
        'type'     => 'url',
    ));

    $wp_customize->add_section('social_links_section', array(
        'title'    => __('Social Links', 'yourtheme'),
        'priority' => 30,
    ));
}

add_action('customize_register', 'customize_theme_settings');

<?php
if (!defined('ABSPATH')) {
    exit;
}
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="site-header">
    <div class="header-inner">
        <a class="brand" href="<?php echo esc_url(home_url('/')); ?>">
            <span class="brand-icon">🍃</span>
            <span>
                <strong>TRÀ THÁI NGUYÊN</strong>
                <small>Hương vị từ vùng đất trà</small>
            </span>
        </a>

        <nav class="main-nav" aria-label="Menu chính">
            <?php
            if (has_nav_menu('primary')) {
                wp_nav_menu(array(
                    'theme_location' => 'primary',
                    'container' => false,
                    'fallback_cb' => false
                ));
            } else {
                ?>
                <a href="<?php echo esc_url(home_url('/')); ?>">Trang chủ</a>
                <a href="#gioi-thieu">Giới thiệu</a>
                <a href="#san-pham">Sản phẩm</a>
                <a href="#lien-he">Liên hệ</a>
                <?php
            }
            ?>
        </nav>
    </div>
</header>
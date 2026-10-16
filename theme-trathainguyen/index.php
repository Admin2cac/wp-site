<?php
if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>

<main class="section-wrap">
    <?php if (have_posts()) : ?>
        <?php while (have_posts()) : the_post(); ?>
            <article <?php post_class(); ?>>
                <h1>
                    <a href="<?php the_permalink(); ?>">
                        <?php the_title(); ?>
                    </a>
                </h1>

                <?php if (has_post_thumbnail()) : ?>
                    <?php the_post_thumbnail('large'); ?>
                <?php endif; ?>

                <div class="entry-content">
                    <?php the_content(); ?>
                </div>
            </article>
        <?php endwhile; ?>

        <?php the_posts_pagination(); ?>

    <?php else : ?>
        <h1>Chào mừng đến với Trà Thái Nguyên</h1>
        <p>Khám phá hương vị trà và văn hóa vùng chè Việt Nam.</p>
    <?php endif; ?>
</main>

<?php get_footer(); ?>
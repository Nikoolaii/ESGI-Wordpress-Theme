<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php bloginfo('name'); ?></title>
    <link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>">
    <?php wp_head(); ?>
</head>
<?php
?>
<body <?php body_class(); ?>>
<?php get_header(); ?>

<?php
if (is_category()) {
    $category = get_queried_object();
    $image_url = get_term_meta($category->term_id, 'category-image', true);

    if ($image_url) {
        echo '<div class="mx-auto px-4 py-8 headerImage" style="background-image: url(' . esc_url($image_url) . '">';
        echo '<h1 class="text-4xl font-bold mb-4">' . single_cat_title('', false) . '</h1>';
        echo '</div>';
    }
} else {
    echo '<div class="container mx-auto px-4 py-8">';
    echo '<h1 class="text-4xl font-bold mb-4">Cinema Blog</h1>';
    echo '</div>';
}
?>


<div class="container mx-auto px-4 py-8 flex">
    <main class="w-3/4 grid grid-cols-1 md:grid-cols-2">
        <?php
        if (have_posts()) :
            while (have_posts()) : the_post();
                ?>
                <article class="w-full px-2 mb-4">
                    <div class="bg-white p-4 shadow-md rounded flex items-center flex-col">
                        <h2 class="text-2xl font-semibold mb-2">
                            <?php
                            ?>
                            <a href="<?php the_permalink(); ?>"
                               class="text-blue-500 hover:underline"><?php the_title(); ?></a>
                        </h2>
                        <?php
                        if (has_post_thumbnail()) {
                            the_post_thumbnail('medium'); // Display medium-sized featured image
                        }
                        $rating = get_post_meta(get_the_ID(), '_rating', true);
                        if (!empty($rating)) {
                            echo '<div class="post-rating">';
                            for ($i = 0; $i < $rating; $i++) {
                                echo '⭐️';
                            }
                            echo '</div>';
                        }
                        ?>
                        <div class="text-gray-700">
                            <?php the_excerpt(); ?>
                        </div>
                    </div>
                </article>
            <?php
            endwhile;
        else :
            echo '<p>No content found</p>';
        endif;
        ?>
    </main>


    <aside class="w-1/4">
        <?php get_sidebar(); ?>
    </aside>
</div>

<?php if (have_posts()) {
    echo '<div class="pagination flex justify-center">';
    echo paginate_links();
    echo '</div>';
} ?>

<?php get_footer(); ?>
<?php wp_footer(); ?>
</body>
</html>

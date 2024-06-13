<?php wp_head(); ?>

<?php get_header(); ?>
<main>
    <div class="container mx-auto px-4 py-8">
        <a href="<?php echo esc_url(home_url()); ?>"
           class="text-white hover:underline bg-blue-500 hover:bg-blue-700  font-bold py-2 px-4 rounded mb-3 hover:no-underline">Retour
            à la liste des
            films</a>
        <?php
        if (have_posts()) :
            while (have_posts()) : the_post();
                ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class('bg-white p-6 rounded-lg shadow-md'); ?>>
                    <div class="content mb-6 flex items-center flex-col">
                        <header class="mb-4">
                            <h1 class="text-4xl font-bold mb-2"><?php the_title(); ?></h1>
                            <div class="text-gray-600 mb-4">
                                <?php echo get_the_date(); ?> by <?php the_author(); ?>
                            </div>
                        </header>
                        <div class="mb-5">
                            <?php
                            if (has_post_thumbnail()) {
                                the_post_thumbnail('large');
                            }
                            ?>
                        </div>
                        <?php the_content(); ?>
                    </div>
                    <?php
                    $link = get_post_meta(get_the_ID(), '_link', true);
                    if (!empty($link)) {
                        echo '<div class="post-link text-center">';
                        echo '<h3>Trailer : </h3>';
                        echo '<div class="link-preview flex align-center justify-center">';
                        echo '<iframe src="' . esc_url($link) . '" width="600" height="400"></iframe>';
                        echo '</div>';

                        echo '</div>';
                    }
                    ?>

                    <div class="text-center mb-3">
                        <?php
                        $rating = get_post_meta(get_the_ID(), '_rating', true);
                        if (!empty($rating)) {
                            echo '<div class="text-lg font-semibold mb-2">Avis</div>';
                            echo '<div class="post-rating">';
                            for ($i = 0; $i < $rating; $i++) {
                                echo '⭐️';
                            }
                            echo '</div>';
                        }
                        ?>
                    </div>
                    <footer class="border-t pt-4">
                        <div class="tags mb-2">
                            <?php the_tags('<span class="inline-block bg-blue-200 text-blue-800 text-xs font-semibold mr-2 px-2.5 py-0.5 rounded">', '</span><span class="inline-block bg-blue-200 text-blue-800 text-xs font-semibold mr-2 px-2.5 py-0.5 rounded">', '</span>'); ?>
                        </div>
                        <div class="categories text-gray-600">
                            <?php _e('Categories: ');
                            the_category(', '); ?>
                        </div>
                    </footer>
                </article>
            <?php
            endwhile;
        else :
            echo '<p>No content found</p>';
        endif;
        ?>
    </div>
</main>
<?php get_footer(); ?>

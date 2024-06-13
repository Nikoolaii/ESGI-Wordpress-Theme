<header class="bg-gray-800 text-white p-4">
    <nav class="container mx-auto flex flex-wrap justify-center items-center">
    <a href="<?php echo home_url(); ?>">
        <h1 class="font-bold text-xl"><?php bloginfo('name'); ?></h1>
        <h4 class="text-sm"><?php bloginfo('description'); ?></h4>
    </a>
<!--    --><?php
//    // Fetch all categories
//    $categories = get_categories();
//
//    if ( ! empty( $categories ) ) {
//        foreach ( $categories as $category ) {
//            $category_link = get_category_link( $category->term_id );
//            echo '<a href="' . esc_url( $category_link ) . '" class="m-2 p-2 bg-blue-500 rounded hover:bg-blue-700">';
//            echo esc_html( $category->name );
//            echo '</a>';
//        }
//    }
//    ?>
    </nav>
</header>

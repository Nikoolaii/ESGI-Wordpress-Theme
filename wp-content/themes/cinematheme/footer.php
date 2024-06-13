<?php
// Fetch all categories
$categories = get_categories();
?>

    <footer class="bg-gray-800 text-white py-8">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="footer-section">
                    <h2 class="text-2xl font-bold mb-4">A propos</h2>
                    <p><?php bloginfo('description'); ?></p>
                </div>

                <div class="footer-section">
                    <h2 class="text-2xl font-bold mb-4">Catégories</h2>
                    <ul class="list-none">
                        <?php
                        if ( ! empty( $categories ) ) {
                            foreach ( $categories as $category ) {
                                $category_link = get_category_link( $category->term_id );
                                echo '<li class="mb-2">';
                                echo '<a href="' . esc_url( $category_link ) . '" class="text-blue-400 hover:underline">';
                                echo esc_html( $category->name );
                                echo '</a>';
                                echo '</li>';
                            }
                        }
                        ?>
                    </ul>
                </div>

                <div class="footer-section">
                    <h2 class="text-2xl font-bold mb-4">Contact</h2>
                    <p>
                        <strong>Email:</strong> <a href="mailto:info@example.com" class="text-blue-400 hover:underline">info@example.com</a><br>
                        <strong>Phone:</strong> <a href="tel:+1234567890" class="text-blue-400 hover:underline">+1 234 567 890</a><br>
                        <strong>Address:</strong> 123 Main Street, City, Country
                    </p>
                </div>
            </div>
            <div class="text-center mt-8">
                <p>&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. Made with ❤️ By Nikoolaii.</p>
            </div>
        </div>
    </footer>

<?php wp_footer(); ?>
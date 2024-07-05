<?php
/*
Plugin Name: Todo Plugin
Description: A simple todo plugin
Version: 1.0
Author: Nikolaï LEMERRE
*/

// Créer une table quand le plugin est activé en base 
function todopl_install()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'todos';
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        todo text NOT NULL,
        done tinyint(1) DEFAULT 0 NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}
register_activation_hook(__FILE__, 'todopl_install');

// Supprimer la table quand le plugin est désactivé
function todopl_uninstall()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'todos';
    $sql = "DROP TABLE IF EXISTS $table_name";
    $wpdb->query($sql);
}
register_deactivation_hook(__FILE__, 'todopl_uninstall');

// Ajouter un menu dans l'administration
function todopl_menu()
{
    add_menu_page(
        'Todos',
        'Todos',
        'manage_options',
        'todopl',
        'todopl_page'
    );
}
add_action('admin_menu', 'todopl_menu');

// Afficher la page d'administration
function todopl_page()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'todos';
    if (isset($_POST['todo'])) {
        $todo = sanitize_text_field($_POST['todo']);
        $wpdb->insert($table_name, ['todo' => $todo]);
    }
    if (isset($_POST['done'])) {
        $id = intval($_POST['done']);
        $wpdb->update($table_name, ['done' => 1], ['id' => $id]);
    }
    if (isset($_POST['undone'])) {
        $id = intval($_POST['undone']);
        $wpdb->update($table_name, ['done' => 0], ['id' => $id]);
    }
    if (isset($_POST['delete'])) {
        $id = intval($_POST['delete']);
        $wpdb->delete($table_name, ['id' => $id]);
    }
    $todos = $wpdb->get_results("SELECT * FROM $table_name");
?>
    <div class="wrap">
        <h1>Todos</h1>
        <form method="post">
            <input type="text" name="todo" placeholder="Nouvelle tâche">
            <button type="submit">Ajouter</button>
        </form>
        <ul>
            <?php foreach ($todos as $todo) : ?>
                <?php if ($todo->done) : ?>
                    <li style="text-decoration: line-through;">
                    <?php else : ?>
                    <li>
                    <?php endif; ?>
                    <div style="display: flex;">
                        <p style="flex: 1;"><?php echo $todo->todo; ?></p>

                        <?php if ($todo->done) : ?>
                            <form method="post">
                                <input type="hidden" name="undone" value="<?php echo $todo->id; ?>">
                                <button type="submit">✗</button>
                            </form>
                        <?php else : ?>
                            <form method="post">
                                <input type="hidden" name="done" value="<?php echo $todo->id; ?>">
                                <button type="submit">✓</button>
                            </form>
                        <?php endif; ?>
                        <form method="post">
                            <input type="hidden" name="delete" value="<?php echo $todo->id; ?>">
                            <button type="submit">✖</button>
                        </form>
                    </div>
                    </li>
                <?php endforeach; ?>
        </ul>
    </div>
<?php
}

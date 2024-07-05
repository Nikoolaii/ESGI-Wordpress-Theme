<?php
/*
Plugin Name: Horaires Cinema Plugin
Description: Un plugin pour gérer les horaires des films
Version: 1.0
Author: Nikolaï LEMERRE
*/

// Créer une table quand le plugin est activé en base
function horaires_cinema_install()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'horaires_cinema';
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        film text NOT NULL,
        id_film int NOT NULL,
        horaire text NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

register_activation_hook(__FILE__, 'horaires_cinema_install');


// Supprimer la table quand le plugin est désactivé
function horaires_cinema_uninstall()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'horaires_cinema';
    $sql = "DROP TABLE IF EXISTS $table_name";
    $wpdb->query($sql);
}

register_deactivation_hook(__FILE__, 'horaires_cinema_uninstall');


// Ajouter un menu dans l'administration
function horaires_cinema_menu()
{
    add_menu_page(
        'Horaires Cinema',
        'Horaires Cinema',
        'manage_options',
        'horaires_cinema',
        'horaires_cinema_page'
    );
}

add_action('admin_menu', 'horaires_cinema_menu');


// Afficher la page d'administration
function horaires_cinema_page()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'horaires_cinema';
    if (isset($_POST['action']) && $_POST['action'] === 'add') {

        // Vérifie si un film avec le même nom existe déjà
        $film = sanitize_text_field($_POST['film']);
        $result = $wpdb->get_results("SELECT * FROM $table_name WHERE film = '$film'");
        if (count($result) > 0) {
            $id_film = $result[0]->id_film;
        } else {
            // Récupérer l'id max et ajouter 1
            $max_id = $wpdb->get_results("SELECT MAX(id_film) as max_id FROM $table_name")[0]->max_id;
            $id_film = $max_id + 1;
        }

        $film = sanitize_text_field($_POST['film']);
        $horaire = sanitize_text_field($_POST['horaire']);
        $wpdb->insert($table_name, ['film' => $film, 'id_film' => $id_film, 'horaire' => $horaire]);
    }
    if (isset($_POST['action']) && $_POST['action'] === 'delete') {
        $horaire_id = sanitize_text_field($_POST['horaire_id']);
        $result = $wpdb->delete($table_name, ['id' => $horaire_id]);
    }

    $films = $wpdb->get_results("SELECT DISTINCT film, id_film FROM $table_name");
?>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        form {
            margin-bottom: 10px;
        }

        input[type="text"] {
            margin-right: 10px;
        }

        button {
            padding: 5px 10px;
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        li {
            margin-bottom: 5px;
        }
    </style>
    <h1>Horaires Cinema</h1>
    <form method="post">
        <input type="hidden" name="action" value="add">
        <label for="film">Film</label>
        <input type="text" name="film" id="film">
        <label for="horaire">Horaire</label>
        <input type="text" name="horaire" id="horaire">
        <button type="submit">Ajouter</button>
    </form>
    <table>
        <thead>
            <tr>
                <th>ID Film</th>
                <th>Film</th>
                <th>Horaire</th>
                <th>Ajouter horaire</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($films as $film) {
            ?>
                <tr>
                    <td><?php echo $film->id_film; ?></td>
                    <td><?php echo $film->film; ?></td>
                    <td>
                        <ul>
                            <?php
                            $horaires = $wpdb->get_results("SELECT horaire, id FROM $table_name WHERE film = '$film->film'");
                            foreach ($horaires as $horaire) {
                            ?>
                                <li style="display: flex; flex-direction:row">
                                    <form method="post">
                                        <input type="hidden" name="action" value="edit">
                                        <input type=text value="<?php echo $horaire->horaire; ?>">
                                        <input type="hidden" name="horaire_id" value="<?php echo $horaire->id; ?>">
                                    </form>
                                    <form method="post">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="horaire_id" value="<?php echo $horaire->id; ?>">
                                        <button type="submit">Supprimer</button>
                                    </form>
                                </li>
                            <?php
                            }
                            ?>
                        </ul>
                    </td>
                    <td>
                        <form method="post">
                            <input type="hidden" name="action" value="add">
                            <input type="hidden" name="film" value="<?php echo $film->film; ?>">
                            <label for="horaire">Horaire</label>
                            <input type="text" name="horaire" id="horaire">
                            <button type="submit">Ajouter</button>
                        </form>
                    </td>
                </tr>
            <?php
            }
            ?>
        </tbody>
    </table>
<?php
}


function horaires_shortcode($atts)
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'horaires_cinema';
    $id_film = $atts['id_film'];
    $horaires = $wpdb->get_results("SELECT horaire FROM $table_name WHERE id_film = '$id_film'");
    $html = "<ul>";
    foreach ($horaires as $horaire) {
        $html .= "<li>$horaire->horaire</li>";
    }
    $html .= "</ul>";
    return $html;
}

add_shortcode('horaires', 'horaires_shortcode');
?>
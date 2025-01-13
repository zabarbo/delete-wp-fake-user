<?php
/**
 * Plugin Name: Delete WP spam user
 * Description: Plugin para gestionar usuarios falsos en wordpress.
 * Version: 1.0.0
 * Author: Victor Barboza
 */

// Evitar accesos directos al archivo.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Función para contar usuarios que no tienen nombre y no han hecho pedidos en WooCommerce.
function umh_count_users_without_name_and_orders() {
    $args = [
        'role__not_in' => ['Administrator'],
        'fields'       => 'ID'
    ];
    $users = get_users($args);

    $count = 0;

    foreach ( $users as $user_id ) {
        $user_data = get_userdata( $user_id );
        $user_orders = wc_get_customer_order_count( $user_id );

        if ( empty( $user_data->first_name ) && empty( $user_data->last_name ) && $user_orders === 0 ) {
            $count++;
        }
    }

    return $count;
}

// Shortcode para mostrar el conteo de usuarios en el frontend.
add_shortcode( 'umh_user_count', function() {
    $count = umh_count_users_without_name_and_orders();
    return "Hay $count usuarios sin nombre y sin pedidos.";
});

// Función para listar usuarios sin nombre y sin pedidos.
function umh_list_users_without_name_and_orders() {
    $args = [
        'role__not_in' => ['Administrator'],
        'fields'       => 'all'
    ];
    $users = get_users($args);

    $output = '<table border="1">';
    $output .= '<tr><th>Nombre</th><th>Correo</th><th>Pedidos</th></tr>';

    foreach ( $users as $user ) {
        $orders = wc_get_customer_order_count( $user->ID );

        if ( $orders === 0 && empty( $user->first_name ) && empty( $user->last_name ) ) {
            $name = '(Sin nombre)';
            $email = $user->user_email;
            $output .= "<tr><td>$name</td><td>$email</td><td>$orders</td></tr>";
        }
    }

    $output .= '</table>';

    return $output;
}

// Shortcode para mostrar la lista de usuarios sin nombre y sin pedidos en el frontend.
add_shortcode( 'umh_user_list', 'umh_list_users_without_name_and_orders' );

// Función para exportar usuarios a un archivo CSV.
function umh_export_users_to_csv() {
    $filename = 'wordpress_users_' . date( 'Y-m-d' ) . '.csv';

    header( 'Content-Type: text/csv; charset=utf-8' );
    header( 'Content-Disposition: attachment; filename=' . $filename );

    $output = fopen( 'php://output', 'w' );

    // Encabezados del archivo CSV.
    fputcsv( $output, [ 'ID', 'Nombre', 'Correo Electr&oacute;nico', 'Rol' ] );

    $users = get_users();
    foreach ( $users as $user ) {
        fputcsv( $output, [
            $user->ID,
            $user->display_name,
            $user->user_email,
            implode( ', ', $user->roles )
        ] );
    }

    fclose( $output );
    exit;
}

// Función para eliminar usuarios sin nombre y sin pedidos.
function umh_delete_users_without_name_and_orders() {
    $args = [
        'role__not_in' => ['Administrator'],
        'fields'       => 'ID'
    ];
    $users = get_users($args);

    $deleted_count = 0;

    foreach ( $users as $user_id ) {
        $user_data = get_userdata( $user_id );
        $user_orders = wc_get_customer_order_count( $user_id );

        if ( $user_orders === 0 && empty( $user_data->first_name ) && empty( $user_data->last_name ) ) {
            wp_delete_user( $user_id );
            $deleted_count++;
        }
    }

    return $deleted_count;
}

// Hook para crear una p&aacute;gina en el backend con las funcionalidades.
add_action( 'admin_menu', function() {
    add_menu_page(
        'Gesti&oacute;n de Usuarios',
        'Usuarios WooCommerce',
        'manage_options',
        'umh_user_management',
        'umh_user_management_page'
    );
});

// Contenido de la p&aacute;gina en el backend.
function umh_user_management_page() {
    if ( isset( $_POST['umh_delete_users'] ) ) {
        echo "<script>
            if (confirm('Esta acci&oacute;n es irreversible. Por favor haga una copia de seguridad de su base de datos antes de continuar. &iquest;Desea continuar?')) {
                window.location.href = '" . admin_url( 'admin-post.php?action=umh_export_users_to_csv' ) . "';
            }
        </script>";

        $deleted_count = umh_delete_users_without_name_and_orders();
        echo "<div class='updated'><p>Se eliminaron $deleted_count usuarios sin nombre y sin pedidos.</p></div>";
    }

    echo '<h1>Gesti&oacute;n de Usuarios</h1>';

    echo '<h2>Conteo de Usuarios</h2>';
    echo '<p>' . umh_count_users_without_name_and_orders() . ' usuarios no tienen nombre y no han hecho pedidos.</p>';

    echo '<h2>Lista de Usuarios sin Nombre y sin Pedidos</h2>';
    echo umh_list_users_without_name_and_orders();

    echo '<h2>Eliminar Usuarios</h2>';
    echo '<form method="post">';
    echo '<input type="button" onclick="if(confirm(\'Esta acci&oacute;n es irreversible. Por favor haga una copia de seguridad de su base de datos antes de continuar. &iquest;Desea continuar?\')) { this.form.submit(); }" class="button button-primary" value="Eliminar usuarios sin nombre y sin pedidos">';
    echo '</form>';

    echo '<h2>Exportar Usuarios</h2>';
    echo '<form method="post" action="' . admin_url( 'admin-post.php?action=umh_export_users_to_csv' ) . '">';
    echo '<input type="submit" class="button button-secondary" value="Exportar usuarios">';
    echo '</form>';
}

// Registrar acci&oacute;n para exportar usuarios.
add_action( 'admin_post_umh_export_users_to_csv', 'umh_export_users_to_csv' );

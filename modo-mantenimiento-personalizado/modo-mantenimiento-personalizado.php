<?php
/**
 * Plugin Name: Modo Mantenimiento Personalizado
 * Plugin URI: 
 * Description: Plugin personalizado de mantenimiento que permite seleccionar qué roles de usuario tienen acceso al frontend, aplicar a toda la web o URLs específicas y personalizar el mensaje.
 * Version: 1.0.1
 * Author: n3uron4
 * Author URI: 
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: modo-mantenimiento-personalizado
 */

// Evitar acceso directo al archivo
if (!defined('ABSPATH')) {
    exit;
}

class Modo_Mantenimiento_Personalizado {
    
    // Constructor
    public function __construct() {
        // Inicializar las opciones predeterminadas al activar el plugin
        register_activation_hook(__FILE__, array($this, 'activar_plugin'));
        
        // Agregar menú de administración
        add_action('admin_menu', array($this, 'agregar_menu_admin'));
        
        // Registrar configuraciones
        add_action('admin_init', array($this, 'registrar_configuraciones'));
        
        // Verificar si el modo mantenimiento está activo
        add_action('template_redirect', array($this, 'verificar_mantenimiento'), 1);
        
        // Agregar estilos y scripts
        add_action('wp_enqueue_scripts', array($this, 'agregar_estilos'));
        add_action('admin_enqueue_scripts', array($this, 'agregar_estilos_admin'));
    }
    
    // Función que se ejecuta al activar el plugin
    public function activar_plugin() {
        $opciones_predeterminadas = array(
            'modo_activo' => false,
            'roles_permitidos' => array('administrator'),
            'tipo_alcance' => 'toda_web',
            'urls_especificas' => '',
            'mensaje_personalizado' => 'Sitio en mantenimiento. Volveremos pronto.',
            'titulo_pagina' => 'Modo Mantenimiento',
            'color_fondo' => '#f1f1f1',
            'color_texto' => '#333333'
        );
        
        // Añadir opciones solo si no existen
        if (!get_option('mmp_opciones')) {
            add_option('mmp_opciones', $opciones_predeterminadas);
        }
    }
    
    // Agregar menú al panel de administración
    public function agregar_menu_admin() {
        add_menu_page(
            __('Modo Mantenimiento', 'modo-mantenimiento-personalizado'),
            __('Modo Mantenimiento', 'modo-mantenimiento-personalizado'),
            'manage_options',
            'mmp-configuracion',
            array($this, 'mostrar_pagina_configuracion'),
            'dashicons-admin-tools',
            99
        );
    }
    
    // Registrar campos de configuración
    public function registrar_configuraciones() {
        register_setting('mmp_grupo_opciones', 'mmp_opciones', array($this, 'validar_opciones'));
    }
    
    // Validar las opciones antes de guardar
    public function validar_opciones($input) {
        $output = get_option('mmp_opciones');
        
        // Activar/desactivar modo mantenimiento
        $output['modo_activo'] = isset($input['modo_activo']) ? true : false;
        
        // Roles permitidos
        $output['roles_permitidos'] = isset($input['roles_permitidos']) ? $input['roles_permitidos'] : array('administrator');
        
        // Tipo de alcance (toda la web o URLs específicas)
        $output['tipo_alcance'] = isset($input['tipo_alcance']) ? sanitize_text_field($input['tipo_alcance']) : 'toda_web';
        
        // URLs específicas (limpieza básica)
        $output['urls_especificas'] = isset($input['urls_especificas']) ? sanitize_textarea_field($input['urls_especificas']) : '';
        
        // Mensaje personalizado
        $output['mensaje_personalizado'] = isset($input['mensaje_personalizado']) ? wp_kses_post($input['mensaje_personalizado']) : '';
        
        // Título de la página
        $output['titulo_pagina'] = isset($input['titulo_pagina']) ? sanitize_text_field($input['titulo_pagina']) : '';
        
        // Colores
        $output['color_fondo'] = isset($input['color_fondo']) ? sanitize_hex_color($input['color_fondo']) : '#f1f1f1';
        $output['color_texto'] = isset($input['color_texto']) ? sanitize_hex_color($input['color_texto']) : '#333333';
        
        return $output;
    }
    
    // Mostrar la página de configuración
    public function mostrar_pagina_configuracion() {
        // Verificar permisos
        if (!current_user_can('manage_options')) {
            wp_die(__('No tienes permisos suficientes para acceder a esta página.', 'modo-mantenimiento-personalizado'));
        }
        
        // Obtener opciones guardadas
        $opciones = get_option('mmp_opciones');
        
        // Obtener todos los roles disponibles en WordPress
        $roles_wp = get_editable_roles();
        ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            
            <form method="post" action="options.php">
                <?php
                settings_fields('mmp_grupo_opciones');
                ?>
                
                <div class="mmp-admin-container">
                    <!-- Sección de Activación -->
                    <div class="mmp-card">
                        <h2><?php _e('Activación', 'modo-mantenimiento-personalizado'); ?></h2>
                        <table class="form-table">
                            <tr>
                                <th scope="row"><?php _e('Activar modo mantenimiento', 'modo-mantenimiento-personalizado'); ?></th>
                                <td>
                                    <label>
                                        <input type="checkbox" name="mmp_opciones[modo_activo]" value="1" <?php checked(true, $opciones['modo_activo']); ?>>
                                        <?php _e('Activar', 'modo-mantenimiento-personalizado'); ?>
                                    </label>
                                </td>
                            </tr>
                        </table>
                    </div>
                    
                    <!-- Sección de Permisos de Roles -->
                    <div class="mmp-card">
                        <h2><?php _e('Roles con Acceso', 'modo-mantenimiento-personalizado'); ?></h2>
                        <p><?php _e('Selecciona qué roles de usuario tendrán acceso al frontend cuando el modo mantenimiento esté activo:', 'modo-mantenimiento-personalizado'); ?></p>
                        
                        <table class="form-table">
                            <?php foreach ($roles_wp as $role_key => $role_info) : ?>
                            <tr>
                                <th scope="row"><?php echo esc_html($role_info['name']); ?></th>
                                <td>
                                    <label>
                                        <input type="checkbox" 
                                               name="mmp_opciones[roles_permitidos][]" 
                                               value="<?php echo esc_attr($role_key); ?>" 
                                               <?php checked(in_array($role_key, $opciones['roles_permitidos'])); ?>
                                               <?php if ($role_key === 'administrator') echo 'disabled checked'; ?>>
                                        <?php _e('Permitir acceso', 'modo-mantenimiento-personalizado'); ?>
                                    </label>
                                    <?php if ($role_key === 'administrator') : ?>
                                        <input type="hidden" name="mmp_opciones[roles_permitidos][]" value="administrator">
                                        <p class="description"><?php _e('Los administradores siempre tienen acceso', 'modo-mantenimiento-personalizado'); ?></p>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </table>
                    </div>
                    
                    <!-- Sección de Alcance -->
                    <div class="mmp-card">
                        <h2><?php _e('Alcance', 'modo-mantenimiento-personalizado'); ?></h2>
                        <table class="form-table">
                            <tr>
                                <th scope="row"><?php _e('Aplicar a', 'modo-mantenimiento-personalizado'); ?></th>
                                <td>
                                    <label>
                                        <input type="radio" name="mmp_opciones[tipo_alcance]" value="toda_web" <?php checked('toda_web', $opciones['tipo_alcance']); ?>>
                                        <?php _e('Toda la web', 'modo-mantenimiento-personalizado'); ?>
                                    </label>
                                    <br>
                                    <label>
                                        <input type="radio" name="mmp_opciones[tipo_alcance]" value="urls_especificas" <?php checked('urls_especificas', $opciones['tipo_alcance']); ?>>
                                        <?php _e('Solo URLs específicas', 'modo-mantenimiento-personalizado'); ?>
                                    </label>
                                    
                                    <div id="mmp-urls-especificas" style="margin-top: 10px; <?php echo ($opciones['tipo_alcance'] === 'urls_especificas') ? '' : 'display: none;'; ?>">
                                        <p class="description"><?php _e('Introduce las URLs (una por línea) a las que quieres aplicar el modo mantenimiento. Usa patrones con asteriscos como comodines (ejemplo: /productos/*, /categoria/*):', 'modo-mantenimiento-personalizado'); ?></p>
                                        <textarea name="mmp_opciones[urls_especificas]" rows="5" class="large-text code"><?php echo esc_textarea($opciones['urls_especificas']); ?></textarea>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                    
                    <!-- Sección de Personalización -->
                    <div class="mmp-card">
                        <h2><?php _e('Personalización', 'modo-mantenimiento-personalizado'); ?></h2>
                        <table class="form-table">
                            <tr>
                                <th scope="row"><?php _e('Título de la página', 'modo-mantenimiento-personalizado'); ?></th>
                                <td>
                                    <input type="text" name="mmp_opciones[titulo_pagina]" class="regular-text" value="<?php echo esc_attr($opciones['titulo_pagina']); ?>">
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><?php _e('Mensaje personalizado', 'modo-mantenimiento-personalizado'); ?></th>
                                <td>
                                    <?php
                                    wp_editor(
                                        $opciones['mensaje_personalizado'],
                                        'mmp_mensaje_personalizado',
                                        array(
                                            'textarea_name' => 'mmp_opciones[mensaje_personalizado]',
                                            'media_buttons' => false,
                                            'textarea_rows' => 10,
                                            'teeny' => true
                                        )
                                    );
                                    ?>
                                    <p class="description"><?php _e('Este es el mensaje que se mostrará a los visitantes.', 'modo-mantenimiento-personalizado'); ?></p>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><?php _e('Color de fondo', 'modo-mantenimiento-personalizado'); ?></th>
                                <td>
                                    <input type="color" name="mmp_opciones[color_fondo]" value="<?php echo esc_attr($opciones['color_fondo']); ?>">
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><?php _e('Color del texto', 'modo-mantenimiento-personalizado'); ?></th>
                                <td>
                                    <input type="color" name="mmp_opciones[color_texto]" value="<?php echo esc_attr($opciones['color_texto']); ?>">
                                </td>
                            </tr>
                        </table>
                    </div>
                
                </div><!-- .mmp-admin-container -->
                
                <?php submit_button(__('Guardar Cambios', 'modo-mantenimiento-personalizado')); ?>
            </form>
        </div>
        
        <script>
            jQuery(document).ready(function($) {
                // Mostrar/ocultar sección de URLs específicas
                $('input[name="mmp_opciones[tipo_alcance]"]').change(function() {
                    if ($(this).val() === 'urls_especificas') {
                        $('#mmp-urls-especificas').show();
                    } else {
                        $('#mmp-urls-especificas').hide();
                    }
                });
            });
        </script>
        <?php
    }
    
    // Agregar estilos para el panel de administración
    public function agregar_estilos_admin($hook) {
        if ($hook != 'toplevel_page_mmp-configuracion') {
            return;
        }
        
        // Estilos CSS específicos para la página de administración
        wp_enqueue_style('wp-color-picker');
        wp_enqueue_script('wp-color-picker');
        
        // Inyectar estilos inline
        $custom_css = "
            .mmp-admin-container {
                max-width: 100%;
            }
            .mmp-card {
                background: #fff;
                border: 1px solid #e5e5e5;
                box-shadow: 0 1px 1px rgba(0,0,0,.04);
                margin-bottom: 20px;
                padding: 20px;
            }
            .mmp-card h2 {
                margin-top: 0;
                border-bottom: 1px solid #eee;
                padding-bottom: 10px;
            }
        ";
        wp_add_inline_style('wp-admin', $custom_css);
    }
    
    // Agregar estilos para la página de mantenimiento
    public function agregar_estilos() {
        // Solo se agregarán si el modo mantenimiento está activo
    }
    
    // Función principal que verifica y muestra la página de mantenimiento
    public function verificar_mantenimiento() {
        $opciones = get_option('mmp_opciones');
        
        // Si el modo no está activo, salir
        if (!isset($opciones['modo_activo']) || !$opciones['modo_activo']) {
            return;
        }
        
        // Comprobar si el usuario tiene un rol permitido
        if (is_user_logged_in()) {
            $usuario_actual = wp_get_current_user();
            foreach ($usuario_actual->roles as $rol) {
                if (in_array($rol, $opciones['roles_permitidos'])) {
                    return; // El usuario tiene un rol permitido, permitir acceso
                }
            }
        }
        
        // Comprobar si se debe aplicar a toda la web o a URLs específicas
        if ($opciones['tipo_alcance'] === 'urls_especificas') {
            $path_actual = $_SERVER['REQUEST_URI'];
            $urls_aplicar = explode("\n", $opciones['urls_especificas']);
            $urls_aplicar = array_map('trim', $urls_aplicar);
            
            $aplicar_mantenimiento = false;
            foreach ($urls_aplicar as $url_patron) {
                // Convertir patrón de URL con comodines en expresión regular
                $patron_regexp = str_replace('*', '.*', preg_quote($url_patron, '/'));
                if (preg_match('/^' . $patron_regexp . '$/', $path_actual)) {
                    $aplicar_mantenimiento = true;
                    break;
                }
            }
            
            if (!$aplicar_mantenimiento) {
                return; // No aplicar mantenimiento a esta URL
            }
        }
        
        // Permitir acceso a wp-login.php y wp-admin
        if (preg_match('/wp-login\.php|wp-admin/i', $_SERVER['REQUEST_URI'])) {
            return;
        }
        
        // Mostrar la página de mantenimiento
        $this->mostrar_pagina_mantenimiento($opciones);
        exit;
    }
    
    // Mostrar la página de mantenimiento
    private function mostrar_pagina_mantenimiento($opciones) {
        // Establecer el código de estado HTTP para indicar mantenimiento
        status_header(503);
        header('Retry-After: 3600'); // Sugerir volver a intentar en 1 hora
        
        // Asegurar que tenemos valores para todos los parámetros
        $titulo = !empty($opciones['titulo_pagina']) ? $opciones['titulo_pagina'] : __('Modo Mantenimiento', 'modo-mantenimiento-personalizado');
        $mensaje = !empty($opciones['mensaje_personalizado']) ? $opciones['mensaje_personalizado'] : __('Sitio en mantenimiento. Volveremos pronto.', 'modo-mantenimiento-personalizado');
        $color_fondo = !empty($opciones['color_fondo']) ? $opciones['color_fondo'] : '#f1f1f1';
        $color_texto = !empty($opciones['color_texto']) ? $opciones['color_texto'] : '#333333';
        
        // Imprimir la página de mantenimiento
        ?>
        <!DOCTYPE html>
        <html <?php language_attributes(); ?>>
        <head>
            <meta charset="<?php bloginfo('charset'); ?>">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <title><?php echo esc_html($titulo); ?></title>
            <style>
                body {
                    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
                    background-color: <?php echo esc_attr($color_fondo); ?>;
                    color: <?php echo esc_attr($color_texto); ?>;
                    margin: 0;
                    padding: 0;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    min-height: 100vh;
                }
                .maintenance-container {
                    max-width: 800px;
                    margin: 0 auto;
                    padding: 40px;
                    text-align: center;
                    background-color: rgba(255, 255, 255, 0.9);
                    border-radius: 8px;
                    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
                }
                h1 {
                    font-size: 36px;
                    margin-bottom: 20px;
                }
                .content {
                    font-size: 18px;
                    line-height: 1.6;
                }
                @media (max-width: 767px) {
                    .maintenance-container {
                        padding: 20px;
                        margin: 0 15px;
                    }
                }
            </style>
            <?php wp_head(); ?>
        </head>
        <body>
            <div class="maintenance-container">
                <h1><?php echo esc_html($titulo); ?></h1>
                <div class="content">
                    <?php echo wp_kses_post($mensaje); ?>
                </div>
            </div>
            <?php wp_footer(); ?>
        </body>
        </html>
        <?php
    }
}

// Inicializar el plugin
$modo_mantenimiento_personalizado = new Modo_Mantenimiento_Personalizado();
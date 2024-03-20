<?php
// Exit if accessed directly
if (!defined('ABSPATH'))
    exit;

// BEGIN ENQUEUE PARENT ACTION
// AUTO GENERATED - Do not modify or remove comment markers above or below:

if (!function_exists('chld_thm_cfg_locale_css')):
    function chld_thm_cfg_locale_css($uri)
    {
        if (empty($uri) && is_rtl() && file_exists(get_template_directory() . '/rtl.css'))
            $uri = get_template_directory_uri() . '/rtl.css';
        return $uri;
    }
endif;
add_filter('locale_stylesheet_uri', 'chld_thm_cfg_locale_css');

if (!function_exists('child_theme_configurator_css')):
    function child_theme_configurator_css()
    {
        wp_enqueue_style('chld_thm_cfg_child', trailingslashit(get_stylesheet_directory_uri()) . 'style.css', array('hello-elementor', 'hello-elementor', 'hello-elementor-theme-style'));
    }
endif;
add_action('wp_enqueue_scripts', 'child_theme_configurator_css', 10);

load_theme_textdomain('hello-elementor-child', get_stylesheet_directory() . '/languages');

function aggiungi_stile_tiny_slider()
{

    if (in_array('prodotti-template-default', get_body_class())) {
        wp_enqueue_style('tiny-slider', 'https://cdnjs.cloudflare.com/ajax/libs/tiny-slider/2.9.4/tiny-slider.css');
    }

}
add_action('wp_enqueue_scripts', 'aggiungi_stile_tiny_slider');

function theme_add_js()
{
    wp_enqueue_script('custom-js', get_stylesheet_directory_uri() . '/assets/js/custom.js', array('jquery'), '', true);
    if (in_array('prodotti-template-default', get_body_class())) {
        wp_enqueue_script('tiny-slider-js', 'https://cdnjs.cloudflare.com/ajax/libs/tiny-slider/2.9.2/min/tiny-slider.js');
    }
}
add_action('wp_enqueue_scripts', 'theme_add_js', 999);



function disabilita_editor_a_blocchi()
{
    add_filter('use_block_editor_for_post', '__return_false', 10);
}

add_action('admin_init', 'disabilita_editor_a_blocchi');

function custom_shortcode_func($atts)
{
    ob_start();
    include 'shortcodes/applicazioni.php';
    $output = ob_get_clean();
    return $output;
}
add_shortcode('applicazioni_shortcode', 'custom_shortcode_func');

function custom_shortcode_PDF($atts)
{
    ob_start();
    include 'shortcodes/schedepdf.php';
    $output = ob_get_clean();
    return $output;
}
add_shortcode('PDF_shortcode', 'custom_shortcode_PDF');

function custom_shortcode_PDFsing($atts)
{
    ob_start();
    include 'shortcodes/schedepdfsing.php';
    $output = ob_get_clean();
    return $output;
}
add_shortcode('PDFsing_shortcode', 'custom_shortcode_PDFsing');

function custom_shortcode_rigen($atts)
{
    ob_start();
    include 'shortcodes/rigenerazione.php';
    $output = ob_get_clean();
    return $output;
}
add_shortcode('rigen_shortcode', 'custom_shortcode_rigen');

function custom_shortcode_linkApp($atts)
{
    ob_start();
    include 'shortcodes/linkApplicazioni.php';
    $output = ob_get_clean();
    return $output;
}
add_shortcode('linkApp_shortcode', 'custom_shortcode_linkApp');


function custom_shortcode_linkAppLista($atts)
{
    ob_start();
    include 'shortcodes/linkApplicazioniLista.php';
    $output = ob_get_clean();
    return $output;
}
add_shortcode('linkApp_shortcodeLista', 'custom_shortcode_linkAppLista');

function custom_shortcode_listaApplicazioni($atts)
{
    ob_start();
    include 'shortcodes/listaApplicazioni.php';
    $output = ob_get_clean();
    return $output;
}
add_shortcode('listaApplicazioni', 'custom_shortcode_listaApplicazioni');

function custom_shortcode_carousel($atts)
{
    ob_start();
    include 'shortcodes/carousel.php';
    $output = ob_get_clean();
    return $output;
}
add_shortcode('carousel', 'custom_shortcode_carousel');

add_action('init', 'register_taxonomy_for_attachments');
function register_taxonomy_for_attachments()
{
    register_taxonomy_for_object_type('post_tag', 'attachment');
}

function child_theme_load_textdomain()
{
    load_child_theme_textdomain('hello-elementor-child', get_stylesheet_directory() . '/languages');
}
add_action('after_setup_theme', 'child_theme_load_textdomain');



function add_custom_admin_menu()
{
    add_menu_page(
        'IMPORT',
        'IMPORT',
        'manage_options',
        // Capabilità richiesta per accedere
        'scheda-tecnica-import',
        'custom_import_page',
        'dashicons-database-import'
    );
}

add_action('admin_menu', 'add_custom_admin_menu');




function auto_update_applications($post_id)
{
    global $wpdb;

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        return;

    if (get_post_type($post_id) === 'prodotti') {
        $query = $wpdb->prepare(
            "SELECT trid FROM {$wpdb->prefix}icl_translations WHERE element_id = %d",
            $post_id
        );

        $trid = $wpdb->get_var($query);

        if ($trid) {
            $element_ids = $wpdb->get_col(
                $wpdb->prepare(
                    "SELECT element_id FROM {$wpdb->prefix}icl_translations WHERE trid = %d",
                    $trid
                )
            );

            $app_translation_value = get_post_meta($post_id, 'applicazioni', true);

            foreach ($element_ids as $element_id) {
                update_post_meta($element_id, 'applicazioni', $app_translation_value);
            }
        }
    }
}

add_action('save_post', 'auto_update_applications');


function manipola_elementi_contenuto($content)
{
    if (strpos($_SERVER['REQUEST_URI'], '/materiali-da-attrito/') !== false) {
        $document = new DOMDocument();
        @$document->loadHTML(mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8'), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

        $xpath = new DOMXPath($document);

        // Rimuovi la classe "border" dai container
        $containers = $xpath->query('//div[contains(@class, "container") and contains(@class, "border") and contains(@class, "rounded") and contains(@class, "py-1")]');
        foreach ($containers as $container) {
            $classes = explode(' ', $container->getAttribute('class'));
            $newClasses = array_diff($classes, ['border']);
            $container->setAttribute('class', implode(' ', $newClasses));
        }

        // // Aggiungi "display: none;" all'elemento specifico
        // $elementToHide = $xpath->query('//div[contains(@class, "elementor-element-403ffad")]');
        // foreach ($elementToHide as $element) {
        //     $element->setAttribute('style', 'display: none;');
        // }

        $newContent = $document->saveHTML();
        return $newContent;
    }
    return $content;
}
add_filter('the_content', 'manipola_elementi_contenuto');

// INOL3 cambio tasto applicazioni
// function rimpiazza_codice_html_applicazioni($content) {
//     if (strpos($_SERVER['REQUEST_URI'], '/applicazioni/') !== false) {
//         $document = new DOMDocument();
//         @$document->loadHTML(mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8'), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
//         $xpath = new DOMXPath($document);

//         // Modifica gli elementi con classe "circle"
//         $elementsToModify = $xpath->query('//div[contains(@class, "circle")]');
//         foreach ($elementsToModify as $element) {
//             // Crea un nuovo div con il testo "Scopri di più"
//             $newDiv = $document->createElement('div');
//             $newDiv->setAttribute('class', 'circle alternative-text'); // Aggiungi la classe "alternative-text"
//             $newDiv->textContent = 'Scopri di più';

//             // Imposta lo stile del nuovo div (colore del testo bianco)
//             $newDiv->setAttribute('style', 'color: white;');

//             // Aggiungi il nuovo div all'interno dell'elemento esistente
//             $element->appendChild($newDiv);

//             // Modifica lo stile dell'elemento esistente
//             $element->setAttribute('style', 'border-radius: 0px;');
//         }

//         // Aggiungi stile "display: none" al div con classe "arrow-right"
//         $arrowRightElements = $xpath->query('//div[contains(@class, "arrow-right")]');
//         foreach ($arrowRightElements as $arrowRightElement) {
//             $arrowRightElement->setAttribute('style', 'display: none;');
//         }

//         $newContent = $document->saveHTML();
//         return $newContent;
//     }
//     return $content;
// }

// add_filter('the_content', 'rimpiazza_codice_html_applicazioni');






function custom_acf_content_shortcode()
{
    // Verifica se la funzione ACF è disponibile
    if (function_exists('get_field')) {
        $post_id = get_the_ID();

        global $wpdb; // Ottieni l'accesso al database di WordPress

        // Query SQL per ottenere il trid corrispondente all'element_id
        $query = $wpdb->prepare(
            "SELECT trid FROM {$wpdb->prefix}icl_translations WHERE element_id = %s", $post_id
        );

        $trid = $wpdb->get_var($query);

        $querytwo = $wpdb->prepare(
            "SELECT element_id FROM {$wpdb->prefix}icl_translations WHERE trid = $trid AND language_code = '%s'", "it"
        );

        $italian_id = $wpdb->get_var($querytwo);
        // Recupera il valore del campo personalizzato "immagine_anteprima" per il post con ID 25956
        $acf_value = get_field('immagine_anteprima', $italian_id);

        // Verifica se il valore del campo personalizzato è stato recuperato con successo
        if ($acf_value) {

            $img_url = $acf_value["url"];
            $output = '<img src="' . esc_url($img_url) . '" alt="Immagine personalizzata" />';

            return $output;
        }
    }
}

add_shortcode('custom_acf_content', 'custom_acf_content_shortcode');

global $the_target_tax;
$the_target_tax = 'category';

add_filter("manage_edit-{$the_target_tax}_columns", function ($columns) {
    $columns['_description'] = '';
    return $columns;
});

add_filter("manage_{$the_target_tax}_custom_column", function ($e, $column, $term_id) {
    if ($column === '_description')
        return '';
}, 10, 3);

add_filter("get_user_option_manageedit-{$the_target_tax}columnshidden", function ($r) {
    $r[] = '_description';
    return $r;
});

add_action('quick_edit_custom_box', function ($column, $screen, $tax) {
    if ($screen !== 'edit-tags')
        return;
    $taxonomy = get_taxonomy($tax);
    if (!current_user_can($taxonomy->cap->edit_terms))
        return;
    global $the_target_tax;
    if ($tax !== $the_target_tax || $column !== '_description')
        return;
    ?>
        <fieldset>
            <div class="inline-edit-col">
                <label>
                    <span class="title"><?php _e('Description'); ?></span>
                    <span class="input-text-wrap">
                        <textarea id="inline-desc" name="description" rows="3" class="ptitle"></textarea>
                    </span>
                </label>
            </div>
        </fieldset>
        <script>
            jQuery('#the-list').on('click', 'a.editinline', function () {
    var now = jQuery(this).closest('tr').find('td.column-description').text();
    jQuery('#inline-desc').text(now);
    });
        </script>
    <?php
}, 10, 3);
function save_inline_description($term_id)
{
    global $the_target_tax;
    $tax = get_taxonomy($the_target_tax);
    if (
        current_filter() === "edited_{$the_target_tax}"
        && current_user_can($tax->cap->edit_terms)
    ) {
        $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
        // removing action to avoid recursion
        remove_action(current_filter(), __FUNCTION__);
        wp_update_term($term_id, $the_target_tax, array('description' => $description));
    }
}
add_action("edited_{$the_target_tax}", 'save_inline_description');

add_filter( 'big_image_size_threshold', '__return_false' );

function my_analytics()
{
	?>
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-TKX5TLQ');</script>
    <!-- End Google Tag Manager -->
	<?php
}
add_action('wp_head', 'my_analytics', 20);

function googlenoscript() {
    ?>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-TKX5TLQ"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    <?php
}
add_action('wp_body_open', 'googlenoscript');


function cookieyes()
{
	?>
        <script>

        window.dataLayer = window.dataLayer || [];

        function gtag() {

            dataLayer.push(arguments);

        }

        gtag("consent", "default", {
            ad_storage: "denied",
            ad_user_data: "denied", 
            ad_personalization: "denied",
            analytics_storage: "denied",
            functionality_storage: "denied",
            personalization_storage: "denied",
            security_storage: "granted",
            wait_for_update: 2000,
        });

        gtag("set", "ads_data_redaction", true);
        gtag("set", "url_passthrough", true);

        </script>

	<?php
}
add_action('wp_head', 'cookieyes', 19);

function my_analytics2()
{
	?>
        <!-- Google tag (gtag.js) -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=AW-975317281"></script>
        <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'AW-975317281');
        </script>
	<?php
}
add_action('wp_head', 'my_analytics2', 21);


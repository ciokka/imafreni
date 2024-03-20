<?php
// Otteniamo l'ID del post corrente
$post_id = get_the_ID();
$categories = get_the_category($post_id);

if ($categories) {
?>
    <div class="elementor-element elementor-element-d9490ac elementor-widget elementor-widget-heading" data-id="d9490ac" data-element_type="widget" data-widget_type="heading.default">
        <div class="elementor-widget-container my-2">
            <h6 class="elementor-heading-title elementor-size-default" style="text-transform: uppercase;"><?php esc_html_e('applicazioni', 'hello-elementor-child'); ?></h6>
        </div>
    </div>
    <!-- Il tuo codice HTML per visualizzare le applicazioni -->
    <div class="container border rounded py-1">
        <div class="row row-cols-1">
            <?php foreach ($categories as $category): 
                $category_id = $category->term_id;
                $icona_applicazione = get_term_meta($category_id, 'icona_applicazione', true);
                $posta_slug = $category->slug;
                $categoria = __('categoria', 'hello-elementor-child');
                $languageCode = substr(get_locale(), 0, 2); // Ottieni il codice della lingua corrente
                $url = get_site_url() . "/"; // URL base
                if ($languageCode !== 'it') {
                $url .= $languageCode . "/";
                }
                $url .= $categoria . "/" . $posta_slug;
                ?>
                <div class="col">
                    <!-- Il tuo codice HTML per visualizzare ciascuna applicazione -->
                    <div class="d-flex align-items-center py-2">
                        <div class="bs-icon-sm bs-icon-rounded bs-icon-semi-white text-primary d-flex flex-shrink-0 justify-content-center align-items-center d-inline-block bs-icon">
                            <?php

                            if (!empty($icona_applicazione)) {
                                echo '<img class="img-fluid" style="width: 40px; height: 40px;" src="' . esc_url(wp_get_attachment_image_url($icona_applicazione, 'thumbnail')) . '" alt="' . esc_attr($category->name) . '">';
                            }
                            ?>
                        </div>
                        <div class="px-3">
                            <h4 class="text-dark my-0" style="font-size: 14px; line-height: 1;">
                                <a href="<?php echo $url ?>" class="text-dark" style="text-decoration: none;"><?php echo esc_html($category->name); ?></a>
                            </h4>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php } // Chiudi la condizione if ?>

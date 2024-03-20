<?php
// Otteniamo l'ID del post corrente
$post_id = get_the_ID();
// Otteniamo l'array di ID dei post del campo personalizzato 'applicazioni' per l'ID del post corrente
$applicazioni_ids = get_field('applicazioni', $post_id);

// Verifichiamo se ci sono elementi con $posta->post_title valorizzato
$any_applicazioni_with_title = false;
foreach ($applicazioni_ids as $applicazione_id) {
    $posta = get_post($applicazione_id);

    if ($posta->post_title !== '') {
        $any_applicazioni_with_title = true;
        break;
    }
}

if ($any_applicazioni_with_title): ?>
    <div class="elementor-element elementor-element-d9490ac elementor-widget elementor-widget-heading" data-id="d9490ac" data-element_type="widget" data-widget_type="heading.default">
		<div class="elementor-widget-container my-2">
			<h6 class="elementor-heading-title elementor-size-default" style="text-transform: uppercase;"><?php esc_html_e('applicazioni', 'hello-elementor-child'); ?></h6>
        </div>
	</div>
    <!-- Il tuo codice HTML per visualizzare le applicazioni -->
    <div class="container border rounded py-1">
        <div class="row row-cols-1">

            <?php foreach ($applicazioni_ids as $applicazione_id): 
                $posta = get_post($applicazione_id);
                if ($posta->post_title !== ''):
                setup_postdata($posta);
                $posta_slug = $posta->post_name;
                ?>
    
                    <div class="col">
                        <!-- Il tuo codice HTML per visualizzare ciascuna applicazione -->
                        <div class="d-flex align-items-center py-2">
                            <div class="bs-icon-sm bs-icon-rounded bs-icon-semi-white text-primary d-flex flex-shrink-0 justify-content-center align-items-center d-inline-block bs-icon">
                                <img src="<?php echo get_the_post_thumbnail_url($posta, 'full'); ?>" alt="<?php echo esc_html(get_the_title($posta)); ?>" class="img-fluid" style="width: 40px; height: 40px;">
                            </div>
                            <div class="px-3">
                                <h4 class="text-dark mb-0" style="font-size: 14px; line-height: 1;">
                                    <a href="<?php echo do_shortcode('[linkApp_shortcodeLista]') ?>" class="text-dark" style="text-decoration: none;"><?php echo esc_html(get_the_title($posta)); ?></a>
                                </h4>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
            <?php wp_reset_postdata(); ?>
        </div>
    </div>
<?php endif; ?>



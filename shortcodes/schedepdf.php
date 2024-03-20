<?php
// Otteniamo l'ID del post corrente
$post_id = get_the_ID();
$scheda_ids = get_field('schede_tecniche', $post_id);

// Verifichiamo se ci sono elementi con $posta->post_title valorizzato
$any_applicazioni_with_title = false;
if ($scheda_ids) {
    foreach ($scheda_ids as $scheda_id) {
        $posta = get_post($scheda_id);
    
        if ($posta->post_title !== '') {
            $any_applicazioni_with_title = true;
            break;
        }
    }
}

?>


<?php if ($scheda_ids): ?>
    <div class="elementor-element elementor-element-403ffad elementor-widget elementor-widget-heading headScheda" data-id="403ffad" data-element_type="widget" data-widget_type="heading.default">
        <div class="elementor-widget-container my-2">
            <h6 class="elementor-heading-title elementor-size-default" style="text-transform: uppercase;"><?php esc_html_e('Dati tecnici', 'hello-elementor-child'); ?></h6>
        </div>
    </div>
    <div class="container py-1 mb-0 mb-sm-5 px-0" id="boxSchede">
    <?php foreach ($scheda_ids as $posta): ?>
                <?php setup_postdata($posta); ?>
            <div class="row align-items-center mx-0">
            <?php if (has_post_thumbnail($posta)) { ?>
                                    <div class="col-4 col-sm-2">
                        <div class="d-flex align-items-center py-2">
                            <img src="<?php echo get_the_post_thumbnail_url($posta, 'full'); ?>" alt="<?php echo esc_html(get_the_title($posta)); ?>" class="img-fluid">
                        </div>
                    </div>
                    <div class="col-8 col-sm-4">
            <?php } else { ?>

                <div class="col-12 col-sm-6">
                <?php } ?> 
                
                    <div class="d-flex align-items-center py-2 text-center text-sm-start">

                        <h4 class="text-dark m-0 text-center text-sm-start w-100">
                            <?php echo esc_html(get_the_title($posta)); ?>
                        </h4>

                    </div>
                </div>
                <div class="col-12 col-sm-6 iconPDF">
                        <?php $didascalia = esc_html(get_field('didascalia', $posta)); ?>
                        <?php if ($didascalia): ?>
                        <div class="px-1 ps-sm-3 pe-sm-0 didascalia"><?php echo esc_html($didascalia); ?></div>
                        <?php endif; ?>
                        <?php $pdf = get_field('pdf', $posta); ?>
                        <?php if ($pdf): ?>
                            <?php foreach ($pdf as $singlePDF): ?>
                                <div class="d-flex flex-column align-items-center justify-content-end px-1 ps-sm-3 pe-sm-0 pt-2">
                                    <?php $attachment_title = get_the_title($singlePDF->ID); ?>
                                    <a href="<?php echo get_the_guid(($singlePDF)); ?>">

                                        <?php
                                        $image = get_field('immaginepdf', $singlePDF);
                                            if ($image) {
                                                $url = $image['url'];
                                                $alt = $image['alt'];
                                                
                                                echo '<img src="' . $url . '" alt="' . $alt . '" class="img-fluid imgPDF"><br>';
                                                
                                            } else { ?>
                                                <img src="<?php echo get_stylesheet_directory_uri() . '/assets/img/pdf.svg'; ?>" class="img-fluid" style="width: 30px;"><br>
                                            <?php
                                            }
                                        ?>
                                    <?php echo esc_html($attachment_title); ?>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    
                </div>
            </div>
            <div class="row border-bottom mb-3 mx-0">
                <div class="col">
                    <div class="py-2">
                        <p><?php echo wpautop($posta->post_content); ?></p>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
            <?php wp_reset_postdata(); ?>

    </div>
<?php endif; ?>

<?php $elenco_pdf = get_field( 'elenco_pdf' ); ?>

<?php if ($elenco_pdf): ?>
    <div class="elementor-element elementor-element-403ffad elementor-widget elementor-widget-heading" data-id="403ffad" data-element_type="widget" data-widget_type="heading.default">
		<div class="elementor-widget-container my-2">
			<h6 class="elementor-heading-title elementor-size-default" style="text-transform: uppercase;"><?php esc_html_e('Schede tecniche', 'hello-elementor-child'); ?></h6>
        </div>
	</div>
    <div class="container border rounded p-2">
        <div class="d-flex flex-wrap justify-content-start ">

            <?php foreach ($elenco_pdf as $postPDF): ?>
                <?php setup_postdata($postPDF); ?>

                <div class="w-auto p-1">
                    <div class="d-flex align-items-center py-2">
                        <div class="bs-icon-sm bs-icon-rounded bs-icon-semi-white text-primary d-flex flex-shrink-0 justify-content-center align-items-center d-inline-block bs-icon">
                            <img src="<?php echo get_stylesheet_directory_uri() . '/assets/img/pdf.svg';?>" class="img-fluid" style="width: 30px;">
                        </div>
                        <div class="px-3">
                            <h4 class="text-dark mb-0" style="font-size: 14px; line-height: 1;">
                                <a href="<?php echo get_the_guid(($postPDF)); ?>" class="text-dark" style="text-decoration: none;" target="_blank"><?php echo esc_html(get_the_title($postPDF)); ?></a>
                            </h4>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
            <?php wp_reset_postdata(); ?>
        </div>
    </div>
<?php endif; ?>



<?php if ( get_field( 'rigenerazione' ) == 1 ) : ?>
    <div class="container py-1 g-0">
        <div class="row g-0">
                <div class="col">
                    <div class="d-flex align-items-center py-2">
                        <div class="bs-icon-sm bs-icon-rounded bs-icon-semi-white text-primary d-flex flex-shrink-0 justify-content-center align-items-center d-inline-block bs-icon">
                            <img src="<?php echo get_stylesheet_directory_uri() . '/assets/img/rigenera.svg';?>" class="img-fluid" style="width: 30px;">
                        </div>
                        <div class="px-3">
                            <h4 class="text-green mb-0 text-uppercase" style="font-size: 14px; line-height: 1;"><?php esc_html_e('Prodotto rigenerabile', 'hello-elementor-child'); ?></a>
                            </h4>
                        </div>
                    </div>
                </div>
        </div>
    </div>
<?php else : ?>
	<?php // echo 'false'; ?>
<?php endif; ?>

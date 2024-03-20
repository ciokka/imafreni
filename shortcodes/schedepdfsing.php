<?php $schede_tecniche = get_field('schede_tecniche'); ?>

<?php if ($schede_tecniche): ?>
    <div class="elementor-element elementor-element-403ffad elementor-widget elementor-widget-heading" data-id="403ffad"
        data-element_type="widget" data-widget_type="heading.default">
    </div>
    <div class="container border rounded py-1 pdfMateriali">
        <div class="row row-cols-1">

            <?php foreach ($schede_tecniche as $posta): ?>
                <?php setup_postdata($posta); ?>

                <div class="d-flex justify-content-between align-items-center  ">
                    <div class="d-flex">
                        <h6 class="text-dark m-0">
                            <a href="<?php echo get_the_guid(($posta)); ?>" class="text-dark" style="text-decoration: none;"
                                target="_blank">
                                <?php echo esc_html(get_the_title($posta)); ?>
                            </a>
                        </h6>

                    </div>
                    <div class="d-flex align-items-center py-2">
                        <div class="px-2 txtScarica">
                        <a href="<?php echo get_the_guid(($posta)); ?>" target="_blank"><?php esc_html_e('Scarica il PDF', 'hello-elementor-child'); ?></a>
                        </div>
                        <div
                            class="bs-icon-sm bs-icon-rounded bs-icon-semi-white text-primary d-flex flex-shrink-0 justify-content-center align-items-center d-inline-block bs-icon">
                            <a href="<?php echo get_the_guid(($posta)); ?>" target="_blank"><img
                                    src="<?php echo get_stylesheet_directory_uri() . '/assets/img/pdf.svg'; ?>" class="img-fluid"
                                    style="width: 30px;"></a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
            <?php wp_reset_postdata(); ?>
        </div>
    </div>
<?php endif; ?>
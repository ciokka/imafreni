<?php
// http://ganlanyuan.github.io/tiny-slider/ 
// Ottenere l'ID del post corrente
$post_id = get_the_ID();
// Ottenere le immagini caricate nel post corrente
$images = get_attached_media('image', $post_id);

?>
<!-- carosello immagini custom -->

<div class="row row-cols-1 carouselIma">
<?php 
        // Verificare se ci sono immagini caricate
        if ($images) {
?> 
    <script type="module">
        var slider = tns({
        container: '#customize',
        items: 1,
        controlsContainer: '#customize-controls',
        navContainer: '#customize-thumbnails',
        controls: true,
        navAsThumbnails: true,
        autoplay: false,
        autoplayButton: false,
        swipeAngle: false,
        autoplayHoverPause: true,
        autoplayButtonOutput: false,
        mouseDrag: true,
        speed: 800
        });
    </script>
    <div class="controlContainer">
        <div id="customize-controls">
            <div>
                <svg fill="none" height="512" viewbox="0 0 128 128" width="512" xmlns="http://www.w3.org/2000/svg"><path clip-rule="evenodd" d="m80.1642 20.5858c-.781-.781-2.0474-.781-2.8284 0l-39.8787 39.8787c-1.9526 1.9526-1.9526 5.1185 0 7.0711l39.8786 39.8784c.7811.781 2.0474.781 2.8285 0 .781-.781.781-2.047 0-2.828l-39.8786-39.8789c-.3906-.3905-.3906-1.0236 0-1.4142l39.8786-39.8786c.7811-.7811.7811-2.0474 0-2.8285z" fill="rgb(239, 128, 13)" fill-rule="evenodd"/></svg>
            </div>
            <div>
                <svg fill="none" height="512" viewbox="0 0 128 128" width="512" xmlns="http://www.w3.org/2000/svg"><path clip-rule="evenodd" d="m47.8358 107.414c.781.781 2.0474.781 2.8284 0l39.8787-39.8785c1.9526-1.9526 1.9526-5.1185 0-7.0711l-39.8786-39.8786c-.7811-.781-2.0474-.781-2.8285 0-.781.7811-.781 2.0474 0 2.8285l39.8786 39.8785c.3906.3906.3906 1.0237 0 1.4143l-39.8786 39.8789c-.7811.781-.7811 2.047 0 2.828z" fill="rgb(239, 128, 13)" fill-rule="evenodd"/></svg>
            </div>
        </div>
    </div>
    <div id="customize" class="my-slider">

        <?php

            foreach ($images as $image) {
                // Ottenere l'URL dell'immagine
                $image_url = wp_get_attachment_image_src($image->ID, 'full')[0];
                ?>

                <div>
                    <a data-elementor-open-lightbox="yes" data-elementor-lightbox-title="Lastre rigide" href="<?php echo $image_url ?>">
                        <img src="<?php echo $image_url ?>" alt="Immagine"/>
                    </a>
                </div>
            <?php
            }
        ?>
    </div>
    <div class="customize-tools">
        <div id="customize-thumbnails">
            <?php
            // Verificare se ci sono immagini caricate
            if ($images) {
                $test = []; 
                foreach ($images as $image) {
                    $image_url = wp_get_attachment_image_src($image->ID, 'full')[0];
                    if(!in_array($image_url, $test)) {
                    // Ottenere l'URL dell'immagine
                    
                    ?>

                    <div><img src="<?php echo $image_url ?>" alt="Immagine"/></div>
                <?php
                        $test[] = $image_url;
                    }
                }
            }
            ?>
        </div>
    </div>
    <?php } else { ?>
    <span class="text-muted fs-6 mt-3 text-center"><?php esc_html_e('Nessuna immagine per questo prodotto', 'hello-elementor-child'); ?></span>
    <?php } ?>
</div>


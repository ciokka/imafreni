<?php
// Otteniamo tutte le categorie del post type "prodotto"
$categories = get_terms(array(
    'taxonomy' => 'category',
    'hide_empty' => false,
));

if ($categories) {
?>
<div class="container" id="listaApplicazioni">
    <div class="row">
    
    <?php foreach ($categories as $category): 
        $category_id = $category->term_id;
        $category_name = $category->name;
        $category_desc = $category->description;
        $icona_applicazione = get_term_meta($category_id, 'icona_applicazione', true);
        $posta_slug = $category->slug;
        $category_link = get_term_link($category_id, 'category');
    ?>
    
    <div class="col-md-3 py-3">
        <div class="card align-items-center p-3">
        
        <?php if ($icona_applicazione): ?>
            <img class="img-fluid" style="width: 70px; height: 70px;" src="<?php echo esc_url(wp_get_attachment_image_url($icona_applicazione, 'thumbnail')); ?>" alt="<?php echo esc_attr($category->name); ?>">
        <?php endif; ?>
        
        <div class="card-body text-center p-0">
            <h5 class="card-title p-1"><?php echo esc_html($category_name); ?></h5>
            <p class="card-text"><?php echo esc_html(mb_strimwidth($category_desc, 0, 100, '...')); ?></p>
            <a href="<?php echo esc_url($category_link); ?>" class="btn btn-primary my-2"><?php esc_html_e('Scopri di più', 'hello-elementor-child'); ?></a>
        </div>
        
        </div>
    </div>
    
    <?php endforeach; ?>
    
    </div>
</div>
<?php
}   
?>

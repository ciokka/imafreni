<?php

require_once("wp-load.php");
 // Sostituisci con il percorso corretto
 if (function_exists('wpml_add_translatable_content')) {
// Dati del prodotto principale
$product_data = array(
    'post_title' => 'Nome del prodotto',
    'post_content' => 'Descrizione del prodotto',
    'post_status' => 'publish',
    'post_type' => 'prodotti',
);

// Crea il prodotto principale
$product_id = wp_insert_post($product_data);


// Array di traduzioni
$translations = array(
    'en' => array(
        'post_title' => 'Product Name (English)',
        'post_content' => 'Product Description (English)',
    ),
    'fr' => array(
        'post_title' => 'Nom du produit (Français)',
        'post_content' => 'Description du produit (Français)',
    ),
    // Aggiungi altre lingue e traduzioni qui
);

// Aggiungi traduzioni utilizzando WPML
foreach ($translations as $lang => $translation_data) {
    // Crea una copia del prodotto principale
    $translated_product_id = wpml_add_translatable_content('post_prodotti', $product_id, $lang, $translation_data);
    
    // Aggiungi metadati tradotti se necessario
    // update_post_meta($translated_product_id, '_price', 'Translated Product Price');
    // update_post_meta($translated_product_id, '_sku', 'Translated Product SKU');
}
 }else{
    echo "plugin non attivo";
 }
?>
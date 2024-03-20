<?php
global $wpdb;

// Sostituisci 'wp_posts' con il nome corretto della tabella dei post nel tuo database
$posts_table = $wpdb->prefix . 'posts';

// Sostituisci 'en' con il codice della lingua di default
$default_language = 'it';

// Sostituisci 'it' con il codice della lingua delle traduzioni che stai cercando
$translation_language = 'en';

$query = $wpdb->prepare(
    "
    SELECT *
    FROM {$wpdb->prefix}icl_translations
    WHERE element_type = 'post_prodotti'
    AND language_code = %s
    ",
    $default_language
);

$results = $wpdb->get_results($query);
var_dump($results);
// Per ogni risultato, puoi utilizzare il trid per trovare le traduzioni correlate
foreach ($results as $result) {
    $trid = $result->trid;
    $element_id = $result->element_id;

    // Puoi eseguire un'altra query per ottenere le traduzioni
    $translation_query = $wpdb->prepare("
        SELECT language_code, element_id
        FROM {$wpdb->prefix}icl_translations
        WHERE trid = %d
    ", $trid);

    $translations = $wpdb->get_results($translation_query);

    // Elenca le traduzioni correlate
    foreach ($translations as $translation) {
        $language_code = $translation->language_code;
        $translated_element_id = $translation->element_id;

        echo "Post ID {$element_id} in lingua {$default_language} ha una traduzione in lingua {$language_code} con ID {$translated_element_id}<br>";
    }
}

<?php
require_once("wp-load.php");
global $wpdb;


$csv_file = get_stylesheet_directory() . '/import/import-scheda-tecnica.csv';
$file = fopen($csv_file, 'r');
fgetcsv($file);

while (($data = fgetcsv($file)) !== false) {
    $title = $data[0];
    $pdf = explode("|", $data[1]);



    // Effettua una query per ottenere l'ID del post dal titolo
     $myPost = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->posts WHERE post_title = %s AND post_type = 'prodotti'", $title));
     if(!isset($myPost[0])){
        continue;
     }
     $myPost = $myPost[0];
     $post_id = $myPost->ID;
     $post_title = $myPost->post_title;
    //aggiungere get pos by title


    $pages = get_posts(array(
        'post_type' => 'attachment',
        'numberposts' => -1,
        'tax_query' => array(
          array(
            'taxonomy' => 'post_tag',
            'field' => 'id',
            'terms' => 13
          )
        )
      ));
    
      $post_ids = array();
    echo "********" . $post_id . " " . $post_title . "\n";
      foreach ($pages as $page) {
        foreach($pdf as $singlePdf){
            if($page->post_title == $singlePdf){
                echo $page->post_title . "############" . $singlePdf . "\n";
                $post_ids[] = $page->ID;
            }
        }
    }

    var_dump($post_ids);
    $test = update_post_meta( $post_id, 'schede_tecniche', $post_ids );
    var_dump($test);
    unset($post_ids);


}

//RECUPERO TUTTI GLI ID DEI TERMS

// $pages = get_posts(array(
//     'post_type' => 'attachment',
//     'numberposts' => -1,
//     'tax_query' => array(
//       array(
//         'taxonomy' => 'post_tag',
//         'field' => 'id',
//         'terms' => 13
//       )
//     )
//   ));

//   $post_ids = array();

//   foreach ($pages as $page) {
//     foreach($term_names as $term_name){
//         if($page->post_title == $term_name){
//             $post_ids[] = $page->ID;
//         }
//     }
// }

// var_dump($post_ids); 


//RECUPERO POST META ID DEI TERMS GIA SUL POST


// $postMeta = get_post_meta( 1185, $key = 'schede_tecniche', $single = false );

// var_dump($postMeta);



//UPDATE DEI TERMS NEL PRODOTTO

// update_post_meta( 1185, 'schede_tecniche', $post_ids );
//s:50:"a:4:{i:0;i:6330;i:1;i:6325;i:2;i:6320;i:3;i:6315;}";
//a:2:{i:0;s:4:"6130";i:1;s:4:"6150";}
?>

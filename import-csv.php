<?php
require_once("wp-load.php");


global $wpdb;




function get_or_create_applicazione_post($applicazione_title) {
    $applicazione_title = trim($applicazione_title);
    $applicazione_title = ucfirst(strtolower($applicazione_title));
    if($applicazione_title == ''){
        return false;
    }
    $existing_post = get_page_by_title($applicazione_title, OBJECT, 'applicazioni');
    if ($existing_post) {
        return $existing_post->ID;
    } else {
       // if(!in_array($applicazione_title, $applicazione_titles_array)){
            $post_id = wp_insert_post(array(
                'post_type' => 'applicazioni',
                'post_title' => $applicazione_title,
                'post_status' => 'publish'
            ));
       // }
        
        // if (!in_array($applicazione_title, $applicazione_titles_array)) {
        //     $applicazione_titles_array[] = $applicazione_title;
        // }
        return $post_id;
    }
}






$csv_file = get_stylesheet_directory() . '/import/import.csv';

$csvData = [];
if (($handle = fopen($csv_file, "r")) !== FALSE) {

    while (($data = fgetcsv($handle, 10000, ",", "\"")) !== FALSE) {
        $csvData[] = $data;
    }
    fclose($handle);
}


$productArray = array();
$existing_product = array();
$i = 0;
foreach ($csvData as $row) {
    if($i == 0){
        $i++;
        continue;
    }

        $rif = $row[1];
        $language = $row[0];
        unset($row[1]);
        unset($row[0]);
        $productArray[$rif][$language] = $row;
        $i++;
}

$products_query = get_posts(array('post_type' => 'prodotti', 'numberposts' => -1));

foreach($products_query as $product_query){
    $existing_product[] = $product_query->post_title;
}


foreach ($productArray as $productReference => $productLanguages) {


    if(!in_array(ucfirst(strtolower($productLanguages['it'][2])), $existing_product)){
        var_dump('#########################################');
        var_dump("creato");
        var_dump('#########################################');
        $custom_field_slug = explode(" ", $productLanguages['it'][2]);
        $custom_field_slug = implode("-", $custom_field_slug);

        $args_italiano = array(
            'post_title' => ucfirst(strtolower($productLanguages['it'][2])),
            'post_name' => sanitize_title($custom_field_slug),
            'post_type' => 'prodotti',
            'post_status' => 'publish',
        );
        $prodotto_id_italiano = wp_insert_post($args_italiano);



        $custom_fields = array(
            'sottotitolo' => $productLanguages['it'][3],
            'descrizione' => "<p>" . $productLanguages['it'][4] . "</p>",
            'applicazioni' => explode("|", $productLanguages['it'][5]),
            'video' => $productLanguages['it'][6],
            'rigenerazione' => $productLanguages['it'][8],
        );

        $custom_applications_field = explode('|', $productLanguages['it'][5]);
        $applicazioni_ids = array();

        foreach ($custom_applications_field as $applicazione_title) {
            $applicazione_title = htmlentities($applicazione_title);
            $applicazione_id = get_or_create_applicazione_post($applicazione_title);
            if ($applicazione_id) {
                $applicazioni_ids[] = $applicazione_id;
            }
        }

        
        $custom_fields['applicazioni'] = $applicazioni_ids;
        
        $categories_array = explode('|', $productLanguages['it'][7]);
        $categories_array = array_map(function($category) {
            $category = trim($category);
            return ucfirst(strtolower($category));
        }, $categories_array);
        wp_set_object_terms($prodotto_id_italiano, $categories_array, 'category');
        
        $query = "SELECT trid FROM {$wpdb->prefix}icl_translations WHERE element_id = %d AND element_type = %s";
        $results = $wpdb->get_results($wpdb->prepare($query, $prodotto_id_italiano, 'post_prodotti'));
        
        foreach ($custom_fields as $meta_key => $meta_value) {
            update_post_meta($prodotto_id_italiano, $meta_key, $meta_value);
        }

        $trid = $results[0]->trid;

        foreach ($productLanguages as $lang => $singleLanguageProduct) {
            if ($lang != 'it') {
                $custom_field_slug = explode(" ", $productLanguages[$lang][2]);
                $custom_field_slug = implode("-", $custom_field_slug);

            $args_multilingua = array(
                'post_title' => ucfirst(strtolower($productLanguages[$lang][2])),
                'post_name' => sanitize_title($custom_field_slug),
                'post_type' => 'prodotti',
                'post_status' => 'publish',
            );
            $prodotto_id_multilingua = wp_insert_post($args_multilingua);


            $custom_fields_multi = array(
                'sottotitolo' => $productLanguages[$lang][3],
                'descrizione' => "<p>" . $productLanguages[$lang][4] . "</p>",
                'video' => $productLanguages[$lang][6],
                'rigenerazione' => $productLanguages[$lang][8],
            );
            $custom_fields_multi['applicazioni'] = $applicazioni_ids;

            // $custom_applications_field_multi = explode('|', $productLanguages[$lang][5]);
            // $applicazioni_ids_multi = array();

            // foreach ($custom_applications_field_multi as $applicazione_title_multi) {
            //     $applicazione_title_multi = htmlentities($applicazione_title_multi);
            //     $applicazione_id_multi = get_or_create_applicazione_post($applicazione_title_multi);
            //     if ($applicazione_id_multi) {
            //         $applicazioni_ids_multi[] = $applicazione_id_multi;
            //     }
            // }


            $categories_array_multi = explode('|', $productLanguages[$lang][7]);
            $categories_array_multi = array_map(function($category_multi) {
                $category_multi = trim($category_multi);
                return ucfirst(strtolower($category_multi));
            }, $categories_array_multi);
            wp_set_object_terms($prodotto_id_multilingua, $categories_array_multi, 'category');


            foreach ($custom_fields_multi as $meta_key_multi => $meta_value_multi) {
                update_post_meta($prodotto_id_multilingua, $meta_key_multi, $meta_value_multi);
                update_field('applicazioni', $applicazioni_ids, $prodotto_id_multilingua);
            }



        
            $table_name = $wpdb->prefix . 'icl_translations';
         
            $data_to_update = array(
                'trid' => $trid,
                'language_code' => $lang,
                'source_language_code' => 'it'
            );
            
            $where_condition = array(
                'element_id' => $prodotto_id_multilingua
            );

            
            $wpdb->update($table_name, $data_to_update, $where_condition);


                
            }
        }
    }
}


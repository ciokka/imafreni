<?php
require_once("wp-load.php");

global $wpdb;

$csv_file = get_stylesheet_directory() . '/import/import-ita.csv';
$file = fopen($csv_file, 'r');

// Ignore the first row if it contains column headers
fgetcsv($file);

// Set verbose mode
$verbose = true;
$existing_posts = get_posts(array(
    'post_type' => 'prodotti', // Replace 'prodotti' with the name of your custom post type
    'numberposts' => -1,
    ));
//$applicazione_titles_array = array();
// Function to get or create an 'applicazioni' custom post
function get_or_create_applicazione_post($applicazione_title) {
    $applicazione_title = trim($applicazione_title);
    $applicazione_title = ucfirst(strtolower($applicazione_title));
    if($applicazione_title == ''){
        return false;
    }
   // global $applicazione_titles_array;
    $existing_post = get_page_by_title($applicazione_title, OBJECT, 'applicazioni');
    var_dump("##############################APPLICAZIONE TITLE########################" . $applicazione_title);
    if ($existing_post) {
        return $existing_post->ID;
    } else {
       // if(!in_array($applicazione_title, $applicazione_titles_array)){
        var_dump("##############################TITOLO APP########################" . $applicazione_title);
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

$existing_posts_by_title = array();
foreach ($existing_posts as $post) {
    $existing_posts_by_title[sanitize_title($post->post_title)] = $post;
}



// Loop through the remaining rows of the CSV file
while (($data = fgetcsv($file)) !== false) {
    // CSV row data
    $title = $data[0]; // Post title
    $title = ucfirst(strtolower($title));
    $custom_field1 = $data[1]; // Value of the first custom field
    $custom_field2 = $data[2]; // Value of the second custom field
    $custom_field3 = explode('|', $data[3]); // Array of values for the third custom field
    $custom_field4 = $data[4]; // Value of the fourth custom field
    $categories = $data[5]; // Post categories
    $custom_field6 = $data[6]; // Value of the fourth custom field
    $custom_field_slug = $title . ' - ' . $custom_field1;

    // Check if the post with the same title already exists
    $existing_post = isset($existing_posts_by_title[sanitize_title($title)]) ? $existing_posts_by_title[sanitize_title($title)] : null;

    var_dump("##############################ID POST########################" . $post_id);
    
    
    if ($existing_post) {
        // Update the existing post
        $post_id = $existing_post->ID;
        wp_update_post(array(
            'ID' => $post_id,
            'post_title' => $title,
            'post_name' => sanitize_title($custom_field_slug),
            'post_status' => 'publish'
        ));
        if ($verbose) {
            echo "# Post updated: ID $post_id, Title $title\n";
            echo " - sottotitolo updated: $custom_field1\n";
            echo " - descrizione updated: $custom_field2\n";
            echo " - applicazioni updated: " . implode(", ", $custom_field3) . "\n";
            echo " - Video updated: $custom_field4\n";
            echo " - Categories updated: $categories\n";
            echo " - Rigen updated: $custom_field6\n";
        }
    } else {
        // Create a new post
        $post_id = wp_insert_post(array(
            'post_type' => 'prodotti',
            'post_title' => $title,
            'post_name' => sanitize_title($custom_field_slug),
            'post_status' => 'publish'
        ));
        if ($verbose) {
            echo "# New post created: ID $post_id, Title $title\n";
            echo " - sottotitolo created: $custom_field1\n";
            echo " - descrizione created: $custom_field2\n";
            echo " - applicazioni created: " . implode(", ", $custom_field3) . "\n";
            echo " - Video created: $custom_field4\n";
            echo " - Categories created: $categories\n";
            echo " - Rigen created: $custom_field6\n";
        }
    }

    // Combine custom fields and update in a single call
    $custom_fields = array(
        'sottotitolo' => $custom_field1,
        'descrizione' => "<p>" . $custom_field2 . "</p>",
        'applicazioni' => $custom_field3,

        'video' => $custom_field4,
        'rigenerazione' => $custom_field6,
    );

    // Process applicazioni custom field
    $applicazioni_ids = array();
    foreach ($custom_field3 as $applicazione_title) {
        $applicazione_title = htmlentities($applicazione_title);
        $applicazione_id = get_or_create_applicazione_post($applicazione_title);
        if ($applicazione_id) {
            $applicazioni_ids[] = $applicazione_id;
        }
    }
    $custom_fields['applicazioni'] = $applicazioni_ids;

    // Update custom fields
    foreach ($custom_fields as $meta_key => $meta_value) {
        update_post_meta($post_id, $meta_key, $meta_value);
    }

    // Add categories to the post
    $categories_array = explode('|', $categories);
    $categories_array = array_map(function($category) {
        $category = trim($category);
        return ucfirst(strtolower($category));
    }, $categories_array);
    wp_set_object_terms($post_id, $categories_array, 'category');
}
echo "##############################APPLICAZIONE ARRAY########################" . implode("\n -" , $applicazione_titles_array);
fclose($file);
// $post_id = 3932;

// $meta_key = 'applicazioni';

// $applicazioni_value = get_post_meta($post_id, $meta_key, true);
// var_dump("############################VALORE APPLICAZIONE############################" . $applicazioni_value);


// foreach ($applicazioni_value as $post_id) {
//     $post_title = get_the_title($post_id);
//     echo "Post ID: $post_id, Post Title: $post_title\n";
// }

// Output a message after importing is complete
echo "\n################ Import COMPLETATO ################\n";




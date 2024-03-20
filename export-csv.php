<?php
require_once("wp-load.php");

global $wpdb;

// Set verbose mode
$verbose = true;

// Define the CSV file path
$csv_file = get_stylesheet_directory() . '/export/export.csv';

// Open the CSV file for writing
$file = fopen($csv_file, 'w');

// Write the column headers to the CSV file
$headers = array('Title', 'Sottotitolo', 'Descrizione', 'Applicazioni', 'Video', 'Categories', 'Rigen');
fputcsv($file, $headers);

// Get all posts of the custom post type
$custom_post_type = 'prodotti'; // Replace 'prodotti' with the name of your custom post type
$args = array(
    'post_type' => $custom_post_type,
    'posts_per_page' => -1,
);
$query = new WP_Query($args);

if ($query->have_posts()) {
    while ($query->have_posts()) {
        $query->the_post();

        // Get post data
        $post_id = get_the_ID();
        $title = get_the_title();
        $sottotitolo = get_post_meta($post_id, 'sottotitolo', true);
        $descrizione = get_post_meta($post_id, 'descrizione', true);
        $applicazioni = get_post_meta($post_id, 'applicazioni', true);
        $video = get_post_meta($post_id, 'video', true);
        $categories = get_the_category();
        $categories_names = array();
        foreach ($categories as $category) {
            $categories_names[] = $category->name;
        }
        $categories_string = implode('|', $categories_names);
        $rigenerazione = get_post_meta($post_id, 'rigenerazione', true);

        // Prepare row data for CSV
        $row_data = array(
            $title,
            $sottotitolo,
            $descrizione,
            implode(", ", $applicazioni),
            $video,
            $categories_string,
            $rigenerazione
        );

        // Write row data to the CSV file
        fputcsv($file, $row_data);

        if ($verbose) {
            echo "# Post exported: ID $post_id, Title $title\n";
            echo " - Sottotitolo: $sottotitolo\n";
            echo " - Descrizione: $descrizione\n";
            echo " - Applicazioni: " . implode(", ", $applicazioni) . "\n";
            echo " - Video: $video\n";
            echo " - Categories: $categories_string\n";
            echo " - Rigen: $rigenerazione\n";
        }
    }
}

fclose($file);

// Output a message after exporting is complete
echo "\n################ Esportazione COMPLETATA ################\n";

// Reset the global post object
wp_reset_postdata();

<?php
$nomeDelMedia = [
    "pippo",
    "pluto",
    "clarabella"
];
$nome = $nomeDelMedia[0] . ".jpeg";
echo $nome . " ### " . $nomeDelMedia[0] . "\n";
die;
require_once("wp-load.php");
define('WP_USE_THEMES', false);
            
            $cartellaImport = ABSPATH . 'wp-content/uploads/import/';
            
            $elencoFile = scandir($cartellaImport);
            $elencoFiles = array();
            $imgCount = 0;
            $removedImg = array();
            
            if ($elencoFile !== false) {
                $existing_media_filenames = array();
            
                // Costruisci un array con i nomi dei file dei media esistenti
                $existing_media = get_posts(array('post_type' => 'attachment', 'numberposts' => -1));
                foreach ($existing_media as $media_item) {
                    $existing_media_filenames[$media_item->post_title] = $media_item;
                }
            
                foreach ($elencoFile as $nomeFile) {
                    if ($nomeFile != '.' && $nomeFile != '..') {
                        $percorsoFile = $cartellaImport . $nomeFile;
            
            
                        // Verifica se il file è già presente nella libreria dei media
                        $existing_attachment = array_key_exists($nomeFile, $existing_media_filenames);
            
                        if (!$existing_attachment) {
                            // Il file non esiste ancora nella libreria dei media, procedi con l'aggiunta
                            $percorsoDestinazione = wp_upload_dir()['path'] . '/' . $nomeFile;
            
                            if (copy($percorsoFile, $percorsoDestinazione)) {
                                // Aggiungi l'immagine come allegato
                                $attachment = array(
                                    'post_title' => $nomeFile,
                                    'post_content' => '',
                                    'post_type' => 'attachment',
                                    'post_mime_type' => mime_content_type($percorsoDestinazione),
                                    'post_status' => 'inherit'
                                );
            
                                $attachmentId = wp_insert_attachment($attachment, $percorsoDestinazione);
                                $imgAdded |= true;
                                require_once ABSPATH . 'wp-admin/includes/image.php';
                                $attachData = wp_generate_attachment_metadata($attachmentId, $percorsoDestinazione);
                                wp_update_attachment_metadata($attachmentId, $attachData);
                                echo "media aggiunto \n";
                                $elencoFiles[] = $nomeFile;
                                $imgCount++;
                            }
                        }else{
                            $hashFile = md5_file($percorsoFile);
                            $nomeDelFile = basename($percorsoFile);
                            


                            $media_item = $existing_media_filenames[$nomeFile];

                            $file_path = str_replace("-scaled","", get_attached_file($media_item->ID));
                            if(!file_exists($file_path))continue;
                            $hashMedia = md5_file($file_path);
                            $nomeDelMedia = basename($file_path);
                            // $nomeDelMedia = explode("-",$nomeDelMedia);
                            // $nomeDelMedia = $nomeDelMedia[0] .= ".jpeg";
                            // var_dump($nomeDelMedai);
                            // echo $hashFile . "####################### HASH FILE " . $nomeDelFile . "\n";
                            // echo $hashMedia . "####################### HASH MEDIA " . $nomeDelMedia . "\n";


                                if($hashFile != $hashMedia){
                                 // Elimina l'immagine se è presente nei media

                                 $args = array(
                                    'post_type' => 'any', // Specifica il tipo di post (puoi usare 'post', 'page', 'attachment', ecc.)
                                    'post_status' => 'any', // Specifica lo stato del post (puoi usare 'publish', 'draft', 'pending', ecc.)
                                    'posts_per_page' => -1, // Recupera tutti i post corrispondenti
                                    'title' => $nomeFile, // Filtra i post per titolo
                                );
                                
                                $posts = get_posts($args);
                                
                                foreach ($posts as $post) {
                                    wp_delete_post($post->ID, true);
                                    echo "variante post cancellata \n";
                                }

                                    echo "cancellato \n";

                                                            // Il file non esiste ancora nella libreria dei media, procedi con l'aggiunta
                                    $percorsoDestinazione = wp_upload_dir()['path'] . '/' . $nomeFile;
                    
                                    if (copy($percorsoFile, $percorsoDestinazione)) {
                                        // Aggiungi l'immagine come allegato
                                        $attachment = array(
                                            'post_title' => $nomeFile,
                                            'post_content' => '',
                                            'post_type' => 'attachment',
                                            'post_mime_type' => mime_content_type($percorsoDestinazione),
                                            'post_status' => 'inherit'
                                        );
                    
                                        $attachmentId = wp_insert_attachment($attachment, $percorsoDestinazione);
                    
                                        require_once ABSPATH . 'wp-admin/includes/image.php';
                                        $attachData = wp_generate_attachment_metadata($attachmentId, $percorsoDestinazione);
                                        wp_update_attachment_metadata($attachmentId, $attachData);
                                        echo "aggiunto il remake \n";
                                        $elencoFiles[] = $nomeFile;
                                        $imgCount++;
                                    }

                                    echo "sono diversi hash \n";
                                }
                                else{
                                    echo "non sono diversi! \n";
                                }
                            }
                        }
                    }
                }

                    // Rimuovi immagini dai media se sono state rimosse dalla cartella di import
                    $media = get_posts(array('post_type' => 'attachment', 'numberposts' => -1));
                    foreach ($media as $media_item) {
                        $file_path = get_attached_file($media_item->ID);

                        if (!in_array($media_item->post_title, $elencoFile)) {
                            if (preg_match('/^(IMG_2|IMG_3|IMG_4|IMG_5)/', $media_item->post_title)) {
                                wp_delete_attachment($media_item->ID, true);

                                $removedImg[$media_item->post_title] = true;
                                echo "cancellato perché non presente nella cartella import \n";
                            }
                        }
                    }
            
            
            $csv_file = get_stylesheet_directory() . '/import/import-immagini.csv';
            $file = fopen($csv_file, 'r');
            fgetcsv($file);
            
            while (($data = fgetcsv($file)) !== false) {
                $title = $data[0];
                $imgs = explode("|", $data[1]);
                $img_ids = array();
                foreach($imgs as $img){
                    $img .= ".jpeg";
                    $img_obj = get_page_by_title($img, OBJECT, 'attachment');
                    
                    if ($img_obj) {
                        $img_id = $img_obj->ID;
                        $img_ids[] = $img_id;
            
                        $product_id = get_page_by_title($title, OBJECT, 'prodotti')->ID;
            
                        if ($product_id && !empty($img_ids)) {
                            foreach ($img_ids as $img_id) {
                                wp_update_post(array(
                                    'ID' => $img_id,
                                    'post_parent' => $product_id,
                                ));
            
                                wp_update_post(array(
                                    'ID' => $img_id,
                                    'post_status' => 'inherit',
                                ));
                            }
                        }
                    }
                }
            }

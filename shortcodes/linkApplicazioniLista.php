<?php 
$post = get_post();
$categoria = __('categoria', 'hello-elementor-child');
$languageCode = substr(get_locale(), 0, 2); // Ottieni il codice della lingua corrente
$url = get_site_url() . "/"; // URL base
if ($languageCode !== 'it') {
  $url .= $languageCode . "/";
}

$url .= $categoria . "/" . $post->post_name;

echo $url;
?>

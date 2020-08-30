<?php   
get_header();
global $mode;
if ($mode=='json') {
 echo "Place for JSON";
}  elseif ($mode=='OpenAPI') {
  echo "Place for OpenAPI";
} else { 
  $menu_name = get_query_var( 'vw_nav_menu' ); 
  wp_nav_menu( array(
    'menu'           => $menu_name, 
    'theme_location' => 'top',
    'fallback_cb'    => false // Do not fall back to wp_page_menu()
  ) );
?>
<h1>Plugins</h1>
<pre>
<?php
var_dump( get_option('active_plugins') );
?>
</pre>
<h1>CMSAPI Settings</h1>
<?php
    $options=get_option('cmsapi_plg_options', array('cmsapi_mode','html') );
    var_dump($options);
    echo 'cmsapi_mode='.$options['cmsapi_mode'].
         '<br />  cmsapi_notes='. $options['cmsapi_notes'];
?>
<h1>Page</h1>
<?php
  echo 'ID='.get_the_ID();
  echo '<br />title='.$page['title'];
  echo '<br />content='.$page['content'];
?>
<h1>Single page?</h1>
<?php
  if ($single) {
    if ( $single['featured_media']) {
      set_query_var('vw_responsive_image', array(
      'id'    => $single['featured_media'],
      'sizes' => '(max-width: 1200px) 100vw, 1200px'
      ));
      echo get_query_var('vw_responsive_image');
    } 
    echo $single['title']['rendered'];  
    echo $single['content']['rendered'];
  }
  
  // get_sidebar();
  get_footer();
} 
?>
<?php
 $options=get_option('cmsapi_plg_options');
 global $mode;
 if ( $options ) $mode=$options['cmsapi_mode'];
 else $mode='html';
 if ($mode=='html') {
?>
<html>
<head>
<?php
 set_query_var( 'vw_nav_menu', 'Primary Menu' );
 echo wp_head();
?>
</head>
<body>
<?php
 }
?>
<?php
function UPCP_Rewrite_Rules() { 
		global $wp_rewrite;
		
    add_rewrite_tag('%single_product%','([^&]+)');
		add_rewrite_rule("(.?.+?)/([^&]+)/?$", "index.php?pagename=\$matches[1]&single_product=\$matches[2]", 'top');
		flush_rewrite_rules();
}

function add_query_vars_filter( $vars ){
  $vars[] = "single_product";
  return $vars;
}


?>
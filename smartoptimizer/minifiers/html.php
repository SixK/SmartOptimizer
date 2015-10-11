<?php
function minify_html($buffer) {
	global $settings;
	
    if ($settings['concatenate'] ) {
        /* here we take automatically all js files found in HTML and we concatenate them.
            scripts with http:// in path are skipped  */
        
        preg_match_all('|<script.*src="(?!http://)(.*)".*></script>|', $buffer, $out);   
        $jsScripts=$out[1];
	
        $fp=fopen($settings['alljs'],"w");
        foreach ($jsScripts as $value)	fwrite($fp, $value."\n");
        fclose($fp);
	
        $html_modified=preg_replace('|<script.*src="(?!http://)(.*)".*></script>|', '',$buffer );   
        $buffer=preg_replace('|</body>|', '<script async src="group.alljs.js"></script></body>',$html_modified );
        
        /* here we take automatically all css files found in HTML and we concatenate them. */
         preg_match_all('|<link href="(.*)".*rel="styleSheet".*type="text/css".*/>|', $buffer, $out);  
        $cssScripts=$out[1];
        	
        $fp=fopen($settings['allcss'],"w");
        foreach ($cssScripts as $value)	fwrite($fp, $value."\n");
        fclose($fp);
	
        $html_modified=preg_replace('|<link href="(.*)" rel="styleSheet" type="text/css".*/>|', '',$buffer );   
        $buffer=preg_replace('|<head>|', '<head><link href="group.allcss.css" rel="styleSheet" type="text/css" />',$html_modified );
        
    }

    $search = array(
        '/\>[^\S ]+/s',  // strip whitespaces after tags, except space
        '/[^\S ]+\</s',  // strip whitespaces before tags, except space
        '/(\s)+/s'       // shorten multiple whitespace sequences
    );

    $replace = array(
        '>',
        '<',
        '\\1'
    );

    $buffer = preg_replace($search, $replace, $buffer);

    return $buffer;
}

?>

<?
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
        $buffer=preg_replace('|</body>|', '<script defer src="group.alljs.js"></script></body>',$html_modified );
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

<html>
<head>
    <meta charset="utf-8" />
</head>

<?php

require 'library/simplehtmldom/simple_html_dom.php';

// get DOM from URL or file
$html = file_get_html('https://ru.wikipedia.org/wiki/%D0%90%D0%BB%D1%84%D0%B0%D0%B2%D0%B8%D1%82%D0%BD%D1%8B%D0%B9_%D1%81%D0%BF%D0%B8%D1%81%D0%BE%D0%BA_%D1%81%D1%82%D1%80%D0%B0%D0%BD_%D0%B8_%D1%82%D0%B5%D1%80%D1%80%D0%B8%D1%82%D0%BE%D1%80%D0%B8%D0%B9');

$set = $html->find('ol li');

foreach($set as $item){
    $a = $item->find('a', 1);
    echo '<br />'.$a->innertext .' '.$a->href.  PHP_EOL;
}

?>
</html>
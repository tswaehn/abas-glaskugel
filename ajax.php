<?php

require_once('./lib/main.php');

$action = getUrlParam('action');


switch ($action) {
	case 'verwendung':
		connectToDb();
		$article = getArticle(getUrlParam('article_id'));
		$article = $article->fetch();
		renderVerwendungTree($article);
		break;
	
	default:
		echo '<!-- empty ajax result -->';
}

?>
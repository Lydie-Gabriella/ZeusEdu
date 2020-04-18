<?php

require_once '../../../config.inc.php';

require_once INSTALL_DIR.'/inc/classes/classApplication.inc.php';
$Application = new Application();

// définition de la class USER utilisée en variable de SESSION
require_once INSTALL_DIR.'/inc/classes/classUser.inc.php';
session_start();

if (!(isset($_SESSION[APPLICATION]))) {
    echo "<script type='text/javascript'>document.location.replace('".BASEDIR."');</script>";
    exit;
}

$User = $_SESSION[APPLICATION];
$acronyme = $User->getAcronyme();

$module = $Application->getModule(3);

$formulaire = isset($_POST['formulaire']) ? $_POST['formulaire'] : null;
$form = array();
parse_str($formulaire, $form);

$ds = DIRECTORY_SEPARATOR;
require_once INSTALL_DIR.$ds.$module.$ds.'inc/classes/class.thotForum.php';
$Forum = new thotForum();

$post = isset($form['reponse']) ? $form['reponse'] : Null;
$idSujet = isset($form['idSujet']) ? $form['idSujet'] : Null;
$idCategorie = isset($form['idCategorie']) ? $form['idCategorie'] : Null;
$parentId = isset($form['parentId']) ? $form['parentId'] : Null;
$modifie = isset($form['modifie']) ? $form['modifie'] : Null;
$postId = isset($form['postId']) ? $form['postId'] : Null;

$postId = $Forum->saveNewPost($post, $idSujet, $idCategorie, $parentId, $acronyme);

// rafraîchissement de la liste des contributions
$listePosts = $Forum->getPosts4subject($idCategorie, $idSujet);

require_once INSTALL_DIR.'/smarty/Smarty.class.php';
$smarty = new Smarty();
$smarty->template_dir = '../../templates';
$smarty->compile_dir = '../../templates_c';

$smarty->assign('listePosts', $listePosts);
// informations pour la racine
$smarty->assign('idCategorie', $idCategorie);
$smarty->assign('idSujet', $idSujet);

$html = $smarty->fetch('forums/treeviewPosts.tpl');

echo json_encode(array('postId' => $postId, 'html' => $html));

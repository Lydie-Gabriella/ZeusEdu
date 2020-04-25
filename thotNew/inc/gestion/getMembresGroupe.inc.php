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

$ds = DIRECTORY_SEPARATOR;
require_once INSTALL_DIR.$ds.'inc/classes/classThot.inc.php';
$Thot = new Thot();

require_once INSTALL_DIR.$ds.'inc/classes/classEcole.inc.php';
$Ecole = new Ecole();
$listeNiveaux = Ecole::listeNiveaux();

$nomGroupe = isset($_POST['nomGroupe']) ? $_POST['nomGroupe'] : Null;

$dataGroupe = $Thot->getData4groupe($nomGroupe);
$listeMembres = $Thot->getListeMembresGroupe($nomGroupe);

// liste de tous les profs de l'école (utilisée éventuellement pour la sélection)
$listeProfs = $Ecole->listeProfs();

require_once INSTALL_DIR.'/smarty/Smarty.class.php';
$smarty = new Smarty();
$smarty->template_dir = '../../templates';
$smarty->compile_dir = '../../templates_c';

$nbProfs = isset($listeMembres['profs']) ? count($listeMembres['profs']) : 0;
$nbEleves = isset($listeMembres['eleves']) ? count($listeMembres['eleves']) : 0;
$nbMembres = $nbProfs + $nbEleves;

$smarty->assign('nomGroupe', $nomGroupe);
$smarty->assign('dataGroupe', $dataGroupe);
$smarty->assign('listeNiveaux', $listeNiveaux);
$smarty->assign('listeMembres', $listeMembres);
$smarty->assign('nbMembres', $nbMembres);

$smarty->assign('listeProfs', $listeProfs);

$smarty->display('gestion/gestMembres.tpl');

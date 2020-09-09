<?php

require_once '../../config.inc.php';

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

$module = $Application->getModule(2);

$idListe = isset($_POST['idListe']) ? $_POST['idListe'] : Null;

$ds = DIRECTORY_SEPARATOR;
require_once INSTALL_DIR.$ds.$module.$ds.'inc/classes/classHermes.inc.php';
$Hermes = New hermes();

$membres = $Hermes->membresListe($idListe);
$detailsListe = $Hermes->getDetailsListe($idListe);
$abonnes = $Hermes->getAbonnes4liste($idListe);

require_once INSTALL_DIR.'/smarty/Smarty.class.php';
$smarty = new Smarty();
$smarty->template_dir = '../templates';
$smarty->compile_dir = '../templates_c';

$smarty->assign('membres', $membres);
$smarty->assign('detailslListe', $detailsListe);
$smarty->assign('abonnes', $abonnes);

$smarty->display('modal/modalDelListe.tpl');

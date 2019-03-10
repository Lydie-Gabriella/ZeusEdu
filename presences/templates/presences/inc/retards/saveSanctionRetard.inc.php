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

$module = $Application::getmodule(3);

$formulaire = isset($_POST['formulaire']) ? $_POST['formulaire'] : null;
$form = array();
parse_str($formulaire, $form);

if (isset($form['dateF'])) {
    $ds = DIRECTORY_SEPARATOR;
    require_once INSTALL_DIR.$ds.$module.$ds.'inc/classes/classPresences.inc.php';
    $Presences = new Presences();
    // définir un identifiant $ref pour le traitement en cours
    $ref = $Presences->initTraitementRetard();
    // liaison entre le $ref et les dates choisies pour la sanction
    $nb = $Presences->saveDatesSanction($ref, $form['dateF']);

    $nb = $Presences->saveRefIdLogs($ref, $form['matricule'], $form['id']);
    echo $ref;
}

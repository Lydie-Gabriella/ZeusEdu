<?php

require_once '../../../config.inc.php';

require_once INSTALL_DIR.'/inc/classes/classApplication.inc.php';
$Application = new Application();

// définition de la class USER utilisée en variable de SESSION
require_once INSTALL_DIR.'/inc/classes/classUser.inc.php';
session_start();

if (!(isset($_SESSION[APPLICATION]))) {
    die("<div class='alert alert-danger'>".RECONNECT.'</div>');
}

$User = $_SESSION[APPLICATION];
$acronyme = $User->getAcronyme();

$idTravail = isset($_POST['idTravail']) ? $_POST['idTravail'] : null;

require_once INSTALL_DIR.'/inc/classes/class.Files.php';
$Files = new Files();

// liste des travaux remis par les élèves
$listeTravaux = $Files->listeTravauxRemis($idTravail, $acronyme);

// informations générales sur le travail (dates, consigne,...)
$infoTravail = $Files->getDataTravail($idTravail, $acronyme);
$coursGrp = $infoTravail['coursGrp'];

require_once INSTALL_DIR.'/inc/classes/classEcole.inc.php';
$Ecole = new Ecole();

$listeEleves = $Ecole->listeElevesCours($coursGrp);
foreach ($listeTravaux as $matricule => $dataTravail) {
    $listeTravaux[$matricule]['photo'] = $listeEleves[$matricule]['photo'];
}

require_once INSTALL_DIR.'/smarty/Smarty.class.php';
$smarty = new Smarty();
$smarty->template_dir = '../../templates';
$smarty->compile_dir = '../../templates_c';

$smarty->assign('listeTravaux', $listeTravaux);
$smarty->assign('infoTravail', $infoTravail);

echo $smarty->fetch('casier/showTravauxRemis.tpl');

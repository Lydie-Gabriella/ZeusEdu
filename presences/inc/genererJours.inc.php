<?php
session_start();
require_once("../../config.inc.php");

// définition de la class Application
require_once (INSTALL_DIR."/inc/classes/classApplication.inc.php");
$Application = new Application();

// définition de la class Presences
require_once("../inc/classes/classPresences.inc.php");
$Presences = new Presences();

require_once(INSTALL_DIR."/smarty/Smarty.class.php");
$smarty = new Smarty();
$smarty->template_dir = "../templates";
$smarty->compile_dir = "../templates_c";

$matricule = isset($_POST['matricule'])?$_POST['matricule']:Null;
$smarty->assign('matricule',$matricule);
$date = isset($_POST['date'])?$_POST['date']:Null;

$dateSuivante = $Application->datesSuivantes($date,1,false);
$dateSuivante = current($dateSuivante);

$listePeriodes = $Presences->lirePeriodesCours();
$smarty->assign('listePeriodes',$listePeriodes);

$html = '';
$smarty->assign('listePresences',$Presences->listePresencesElevesDate($dateSuivante,$matricule));
$smarty->assign('date',$dateSuivante);
$smarty->assign('ajout',true);
$html = $smarty->display('presencesJourDate.tpl');

echo $html;
?>

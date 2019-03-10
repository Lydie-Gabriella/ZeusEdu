<?php

// prise de présence par cours par le titulaire du cours
$listeCoursGrp = $Ecole->listeCoursProf($acronyme, true);
$smarty->assign('listeCoursGrp', $listeCoursGrp);
$smarty->assign('acronyme', $acronyme);

// la date postée dans le formulaire ou la date du jour
$date = isset($_POST['date']) ? $_POST['date'] : strftime('%d/%m/%Y');

require_once INSTALL_DIR.'/inc/classes/classFlashInfo.inc.php';
$FlashInfo = new flashInfo();

$listeFlashInfos = $FlashInfo->listeFlashInfos ($module);
$smarty->assign('listeFlashInfos', $listeFlashInfos);

$listePeriodes = $Presences->lirePeriodesCours();

if (!empty($listePeriodes)) {
    $periode = isset($_POST['periode']) ? $_POST['periode'] : $Presences->periodeActuelle($listePeriodes);
    $smarty->assign('periode', $periode);

    // l'utilisateur peut-il changer la date de prise de présence?
    $freeDate = isset($_POST['freeDate']) ? $_POST['freeDate'] : null;
    // retrouver la date de travail à partir de la date du jour ou accepter la date postés si date libre souhaitée
    if ($freeDate == null) {
        $date = strftime('%d/%m/%Y');
    }
    $smarty->assign('freeDate', $freeDate);

    $jourSemaine = strftime('%A', $Application->dateFR2Time($date));
    $smarty->assign('jourSemaine', $jourSemaine);

    $smarty->assign('date', $date);

    if ($mode == 'classe') {
        $listeClasses = $Ecole->listeClasses();
        $smarty->assign('listeClasses', $listeClasses);
        $smarty->assign('corpsPage', 'choixClasse');
        }
        else {
            $listeCoursGrp = $Ecole->listeCoursProf($acronyme, true);
            $smarty->assign('listeCoursGrp', $listeCoursGrp);
            $listeProfs = $Ecole->listeProfs(true);
            $smarty->assign('listeProfs', $listeProfs);
            $smarty->assign('corpsPage', 'choixCoursProf');
        }

    }
    else {
        $smarty->assign('message', array(
            'title' => 'AVERTISSEMENT',
            'texte' => "Les périodes de cours ne sont pas encore définies. Contactez l'administrateur",
            'urgence' => 'danger', ));
    }

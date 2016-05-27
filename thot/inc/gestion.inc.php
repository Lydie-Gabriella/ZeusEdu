<?php

$date = isset($_POST['date']) ? $_POST['date'] : null;
$smarty->assign('date', $date);

$showEdition = false;

switch ($mode) {

    case 'bulletin':
        require_once 'inc/gestBulletins.inc.php';
        break;

    case 'rv':
        require_once 'inc/rdv/listeRv.inc.php';
        break;

    case 'edition':
        $showEdition = true;
        break;

    case 'gestAccuses':
        require_once 'inc/gestAccuses.inc.php';
        break;

    case 'reunionParents':
        require_once 'inc/gestRP.inc.php';
        break;

    case 'parents':
        $titulariats = $user->listeTitulariats("'G','TT','S','C','D'");
        $smarty->assign('listeClasses', implode(',', array_keys($titulariats)));
        $listesParents = $Thot->listeParents($titulariats);
        $smarty->assign('listesParents', $listesParents);
        $smarty->assign('corpsPage', 'listesParents');
        break;

    default:
        // wtf
        break;
    }

if ($showEdition) {
    // lecture de toutes les notifications en vrac pour l'utilisateur courant
    $listeNotifications = $Thot->listeUserNotification($acronyme);
    $smarty->assign('listeNotifications', $listeNotifications);
    // traitement particuler pour les élèves dont on ne connaît, sinon, que le matricule
    $listeEleves = array();
    $notificationsEleves = isset($listeNotifications['eleves']) ? $listeNotifications['eleves'] : null;
    foreach ($notificationsEleves as $id => $item) {
        $matricule = $item['destinataire'];
        $listeEleves[$matricule] = $matricule;
    }
    $smarty->assign('detailsEleves', $Ecole->detailsDeListeEleves($listeEleves));
    $smarty->assign('corpsPage', 'listeEdition');
}
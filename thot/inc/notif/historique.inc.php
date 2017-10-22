<?php

// lecture de toutes les notifications pour l'utilisateur courant
// ecole => ... niveau => ... classes => ... cours => ... eleves => ...
$listeNotifications = $Thot->listeUserNotification($acronyme);
// liste des pièces jointes liées à ces notifications
$listePJ = $Thot->getPj4Notifs($listeNotifications, $acronyme);
// liste des accusés de lecture demandés par l'utilisateur courant
$listeAccuses = $Thot->getAccuses4user($acronyme);

$smarty->assign('listeAccuses', $listeAccuses);
$smarty->assign('listePJ', $listePJ);

// pour chaque notification, déterminer le nombre d'accusés attendus
$listeAttendus = array('ecole' => Null, 'niveau' => Null, 'cours' => Null, 'classes' => Null, 'eleves' => Null);

foreach ($listeNotifications as $type => $lesNotifications) {
    foreach ($lesNotifications as $id => $uneNotification) {
        if ($uneNotification['accuse'] == 1)
            {
            switch ($type) {
                case 'ecole':
                    // wtf : il n'y a pas d'accusé de lecture possible pour l'école
                    break;
                case 'niveau':
                    $destinataire = $uneNotification['destinataire'];
                    $listeAttendus[$type][$id] = $Ecole->nbEleves($destinataire);
                    break;
                case 'cours':
                    $destinataire = $uneNotification['destinataire'];
                    $nb = count($Ecole->listeElevesCours($destinataire));
                    $listeAttendus[$type][$id] = $nb;
                    break;
                case 'classes':
                    $destinataire = $uneNotification['destinataire'];
                    $nb = count($Ecole->listeElevesClasse($destinataire));
                    $listeAttendus[$type][$id] = $nb;
                    break;
                case 'eleves':
                    $destinataire = $uneNotification['destinataire'];
                    $listeAttendus[$type][$id] = 1;
                }
            }
            if ($type == 'eleves') {
                $destinataire = $uneNotification['destinataire'];
                $listeNotifications[$type][$id]['detailsEleve'] = $Ecole->detailsDeListeEleves($destinataire)[$destinataire];
                // Application::afficher($listeNotifications);
                }
        }
    }

// $listeAccuses = $Thot->getAccuses4user($id, $acronyme);

// Application::afficher($listeAccuses, true);
$smarty->assign('listeNotifications', $listeNotifications);
$smarty->assign('listeAttendus', $listeAttendus);
$smarty->assign('corpsPage', 'notification/historique');

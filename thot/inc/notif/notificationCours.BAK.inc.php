<?php

$coursGrp = isset($_POST['coursGrp']) ? $_POST['coursGrp'] : null;

switch ($etape) {
    case 'showCours':
        // le cours a été choisi dans le sélecteur; on prépare un formulaire pour le cours "$coursGrp"
        if ($coursGrp != null) {
            $listeEleves = $Ecole->listeElevesCours($coursGrp);
            $smarty->assign('listeEleves', $listeEleves);
            $notification = $Thot->newNotification('cours', $user->acronyme(), $coursGrp);
            $smarty->assign('notification', $notification);
            $smarty->assign('corpsPage', 'notification/formNotification');
        }
        break;

    case 'enregistrer':
        // enregistrement de la notification avec retour de la liste des identifiants correspondants
        // il peut y avoir plusieurs identifiants quand plusieurs destinataires "élèves" dans le cours
        $listeId = $Thot->enregistrerNotification($_POST);
        // enregistrement éventuel des PJ
        if (isset($_POST['files']) && count($_POST['files']) > 0) {
            require_once INSTALL_DIR.'/inc/classes/class.Files.php';
            $Files = new Files();
            $nb = $Files->linkFilesNotifications($listeId, $_POST);
            }

        // si des notifications ont été enregistrées
        if (count($listeId) > 0) {
            // liste de tous les élèves du cours indexée sur le matricule (nom, prénom, classe,...)
            $listeEleves = $Ecole->listeElevesCours($coursGrp);
            $smarty->assign('listeEleves', $listeEleves);
            // seulement la liste des matricules de tous les élèves du cours
            $matriculesTous = array_keys($listeEleves);
            // $matricules = liste de tous les élèves sélectionnés dans la classe
            $matriculesSelect = isset($_POST['membres']) ? array_flip($_POST['membres']) : null;
            $smarty->assign('listeMatricules', $matriculesSelect);
            // si pas d'élèves sélectionnés séparément, l'enregistrement concerne l'ensemble du cours
            $type = (count($matriculesSelect) != 0) ? 'eleves' : 'cours';

            $nbEleves = ($type == 'eleves') ? count($matriculesSelect) : count($matriculesTous);
            $texte = sprintf('Notification aux %d élèves du cours %s enregistrée', $nbEleves, $coursGrp);

            // ok pour la notification en BD, passons éventuellement à l'envoi de mail
            if (isset($_POST['mail']) && $_POST['mail'] == 1) {
                if (!(isset($_POST['TOUS']))) {
                    // quelques élèves
                    // retrouver les détails pour les élèves sélectionnés
                    $listeElevesSelect = $Ecole->detailsDeListeEleves($matriculesSelect);
                    $listeMailing = $Ecole->detailsDeListeEleves($listeElevesSelect);
                } else {
                    // tous les élèves du coursGrp
                    // $listeEleves contient les données principales élèves indexées sur le matricule
                    $listeMailing = $Ecole->detailsDeListeEleves($listeEleves);
                }

                $smarty->assign('THOTELEVE', THOTELEVE);
                $smarty->assign('ECOLE', ECOLE);
                $smarty->assign('VILLE', VILLE);
                $smarty->assign('ADRESSE', ADRESSE);
                $objetMail = $smarty->fetch('templates/notification/objetMail.tpl');
                $texteMail = $smarty->fetch('templates/notification/texteMail.tpl');
                $signatureMail = $smarty->fetch('templates/notification/signatureMail.tpl');
                // la fonction $Thot->mailer() revient avec la liste des matricules des élèves auxquels un mail a été envoyé
                $listeEnvois = $Thot->mailer($listeMailing, $objetMail, $texteMail, $signatureMail);
                $nbMails = count($listeEnvois);
                $texte .= sprintf('<br> %d mails envoyés', $nbMails);
            }

            // voyons si un accusé de lecture est souhaité
            if (isset($_POST['accuse']) && $_POST['accuse'] == 1) {
                if ($_POST['type'] == 'eleves') {
                    $nbAccuses = $Thot->setAccuse($listeId, $matriculesSelect, 'eleves');
                } else {
                    $nbAccuses = $Thot->setAccuse($listeId, $matriculesTous,'groupe');
                }
                $texte .= sprintf("<br>%d demande(s) d'accusé de lecture envoyée(s)", $nbAccuses);
            }

            $smarty->assign('message', array(
                    'title' => SAVE,
                    'texte' => $texte,
                    'urgence' => SUCCES, )
                    );

            $notification = $_POST;
            // remise en état de "$notification" si des élèves particuliers avaient été sélectionnés
            $notification['type'] = 'cours';
            $notification['destinataire'] = $coursGrp;
            $smarty->assign('type', 'cours');

            $smarty->assign('notification', $notification);
            $smarty->assign('corpsPage', 'notification/syntheseNotification');
        }
            break;
        default:
            $notification = null;
            break;
        }

$smarty->assign('action', $action);
$smarty->assign('mode', $mode);

// informations pour le sélecteur 'selectCours'
$smarty->assign('listeCours', $user->listeCoursProf());
$smarty->assign('coursGrp', $coursGrp);
$smarty->assign('selecteur', 'selecteurs/selectCoursPOST');

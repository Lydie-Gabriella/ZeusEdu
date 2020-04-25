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

$shareId = isset($_POST['shareId']) ? $_POST['shareId'] : Null;

require_once INSTALL_DIR.'/inc/classes/class.Files.php';
$Files = new Files();

$fileInfos = $Files->getFileInfoByShareId($shareId, $acronyme);

$ds = DIRECTORY_SEPARATOR;
$path = $fileInfos['path'];
$fileName = $fileInfos['fileName'];
$fileId = $fileInfos['fileId'];
$root = INSTALL_DIR.$ds.'upload'.$ds.$acronyme;

$arborescence = $root.$path.$ds.$fileName;
$arborescence = preg_replace('~/+~', '/', $arborescence);

$isDir = is_dir($arborescence) ? 1 : 0;

$nb = $Files->setSpyForShareId($shareId, $fileId, $isDir);

echo $nb;

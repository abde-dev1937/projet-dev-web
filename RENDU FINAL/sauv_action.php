<?php
// sauvegarde_plateforme.php

$backupDir = __DIR__ . '/sauv';
if (!file_exists($backupDir)) mkdir($backupDir);

$date = date('Y-m-d_H-i-s');
$subDir = "$backupDir/$date";
mkdir($subDir);

$dbName = 'devweb';
$user = 'root';
$pass = '1234';
$host = 'localhost';
$dumpFile = "$subDir/sauvegarde_bdd.sql";
$command = "mysqldump -h $host -u $user " . ($pass ? "-p$pass " : '') . "$dbName > \"$dumpFile\"";
system($command, $retval);

if ($retval !== 0) {
  echo "<p style='color:red;'>Erreur lors de la sauvegarde SQL.</p>";
  exit;
}

$filesToCopy = ['config_devices.json', 'plateform_rules.json'];
foreach ($filesToCopy as $file) {
  $src = __DIR__ . "/$file";
  $dst = "$subDir/$file";
  if (!copy($src, $dst)) {
    echo "<p style='color:red;'>Erreur de copie du fichier $file.</p>";
  }
}

header("Location: sauv.php");
exit;
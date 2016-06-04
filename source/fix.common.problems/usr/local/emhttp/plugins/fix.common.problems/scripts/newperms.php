#!/usr/bin/php
<?PHP

require_once("/usr/local/emhttp/plugins/fix.common.problems/include/helpers.php");
require_once("/usr/local/emhttp/plugins/dynamix.docker.manager/include/DockerClient.php");

$excludedShares = getAppData();

$disks = parse_ini_file("/var/local/emhttp/disks.ini", true);

foreach ($disks as $disk) {
  if ( ! is_dir("/mnt/".$disk['name']) ) {
    continue;
  }
  $folderlist = array_diff(scandir("/mnt/".$disk['name']),array(".",".."));
  
  foreach ($folderlist as $folder) {
    if ( $excludedShares[$folder] ) {
      continue;
    }
    $fullpath = escapeshellarg("/mnt/".$disk['name']."/$folder");
    if ( is_file($fullpath) ) {
      continue; 
    }
    echo "Processing $fullpath\n";
    
    unset($output);
    exec("/usr/local/emhttp/webGui/scripts/newperms $fullpath");
  }
}
echo "\nFinished!\n"
?>

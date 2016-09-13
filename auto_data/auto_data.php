<?php

ini_set('memory_limit','2048M');

try
{
  rrmdir('./prod/');
  if (file_exists('./prod/'))
  {
    throw new Exception('La suppression du dossier Prod/ à rencontré une erreur. Les droits ont peut être été modifiés.');
  }
}
catch (Exception $e)
{
  echo $e;
}

$content = file_get_contents($argv[1]);

$csv_to_utf8 = array();
$split_csv = array();

$csv_to_utf8 = split_csv($content, $csv_to_utf8, $argv);

utf8_encode_deep($csv_to_utf8);

$final_csv = ft_sort($csv_to_utf8, $argv);

$json = json_encode($final_csv);
$json = '{"slides":'.$json."}";

$path_parts = pathinfo($argv[2]);
unset($final_csv);

try
{
  $result = smartCopy($path_parts['dirname'], "prod");
  if ($result == false)
  {
    throw new Exception('Erreur dans la création du dossier Prod/.');
  }
}
catch (Exception $e)
{
  echo $e;
}

$main_code = file_get_contents("prod/".$path_parts['basename']);
$main_code = str_replace("%DATA%", $json, $main_code);
file_put_contents("prod/".$path_parts['basename'], $main_code);

echo('Voici un lien générique, si le chemin vers votre fichier index.html n\'est pas le bon, changez manuellement après /prod/ :'.PHP_EOL.PHP_EOL);
echo ('file:///'.getcwd().'/prod/index.html'.PHP_EOL);
unset($main_code);
unset($path_parts);
unset($json);

//Cette fonction sert à trier le tableau contenant les données du tableau csv afin de les ranger dans les cases correspondantes.

function ft_sort($csv_to_utf8 ,$argv)
{
  $i = 0;
  $i2 = 0;

  $final_csv = array();
  $rand = rand($argv[3], $argv[4]);

  while ($i2 < $rand)
  {
   $i = rand(0, count($csv_to_utf8) - 1);
   $final_csv[] = array(
     "OfferId" => $csv_to_utf8[$i][0],
     "Category" => $csv_to_utf8[$i][1],
     "ProductCode" => $csv_to_utf8[$i][2],
     "Title" => $csv_to_utf8[$i][3],
     "Description" => $csv_to_utf8[$i][4],
     "ReducePrice" => $csv_to_utf8[$i][5],
     "ProductURL" => $csv_to_utf8[$i][6],
     "ImageURL" => $csv_to_utf8[$i][7],
     "ShippingFee" => $csv_to_utf8[$i][8],
     "Availability" => $csv_to_utf8[$i][9],
     "Delivery" => $csv_to_utf8[$i][10],
     "Warranty" => $csv_to_utf8[$i][11],
     "Brand" => $csv_to_utf8[$i][12],
     "EAN" => $csv_to_utf8[$i][13],
     "Price" => $csv_to_utf8[$i][14],
     "PromoType" => $csv_to_utf8[$i][15],
     "Devise" => $csv_to_utf8[$i][16],
     "OccasionURLMobile" => $csv_to_utf8[$i][17]
   );
   $i2++;
  }
  unset($csv_to_utf8);
  return ($final_csv);
}

function split_csv($content, $csv_to_utf8, $argv)
{
  $i = 0;
  $split_csv = explode(PHP_EOL, $content);
  while ($i < count($split_csv) - 1)
  {
   $csv_to_utf8[] = explode($argv[5], $split_csv[$i]);
   $i++;
  }
  unset($content);
  unset($split_csv);
  return ($csv_to_utf8);
}

function utf8_encode_deep(&$input) {
    if (is_string($input)) {
        $input = utf8_encode($input);
    } else if (is_array($input)) {
        foreach ($input as &$value) {
            utf8_encode_deep($value);
        }

        unset($value);
    } else if (is_object($input)) {
        $vars = array_keys(get_object_vars($input));

        foreach ($vars as $var) {
            utf8_encode_deep($input->$var);
        }
      }
    }

//copie le dossier contenant le fichier .js ($source) envoyé à la fonction pour creer le dossier Prod/ ($dest),
//la variable options contient les droits du dossier et des fichiers)

function smartCopy($source, $dest, $options=array('folderPermission'=>0755,'filePermission'=>0755))
{
    $result=false;

    if (is_file($source)) {
        if ($dest[strlen($dest)-1]=='/') {
            if (!file_exists($dest)) {
                cmfcDirectory::makeAll($dest,$options['folderPermission'],true);
            }
            $__dest=$dest."/".basename($source);
        } else {
            $__dest=$dest;
        }
        $result=copy($source, $__dest);
        chmod($__dest,$options['filePermission']);

    } elseif(is_dir($source)) {
        if ($dest[strlen($dest)-1]=='/') {
            if ($source[strlen($source)-1]=='/') {
                //Copy only contents
            } else {
                //Change parent itself and its contents
                $dest=$dest.basename($source);
                @mkdir($dest);
                chmod($dest,$options['filePermission']);
            }
        } else {
            if ($source[strlen($source)-1]=='/') {
                //Copy parent directory with new name and all its content
                @mkdir($dest,$options['folderPermission']);
                chmod($dest,$options['filePermission']);
            } else {
                //Copy parent directory with new name and all its content
                @mkdir($dest,$options['folderPermission']);
                chmod($dest,$options['filePermission']);
            }
        }

        $dirHandle=opendir($source);
        while($file=readdir($dirHandle))
        {
            if($file!="." && $file!="..")
            {
                 if(!is_dir($source."/".$file)) {
                    $__dest=$dest."/".$file;
                } else {
                    $__dest=$dest."/".$file;
                }
                //echo "$source/$file ||| $__dest<br />";
                $result=smartCopy($source."/".$file, $__dest, $options);
            }
        }
        closedir($dirHandle);

    } else {
        $result=false;
    }
    return $result;
}

function rrmdir($dir) {
  if (is_dir($dir)) {
    $objects = scandir($dir);
    foreach ($objects as $object) {
      if ($object != "." && $object != "..") {
        if (filetype($dir."/".$object) == "dir") rmdir($dir."/".$object); else unlink($dir."/".$object);
      }
    }
    reset($objects);
    rmdir($dir);
  }
}
?>

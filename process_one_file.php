<?php
$path = "lexicon/";

// Define output arrays
$out = [];

// Open the directory
if ($handle = opendir($path)) {
    // for ech file in the directory
    while (false !== ($file = readdir($handle))) {

        // SKip system items
        if ('.' === $file) continue;
        if ('..' === $file) continue;

        // Get the file content and convert JSON to PHP Object
        $string = file_get_contents($path . $file);
        $json = json_decode($string);

        $array[] = $json;
    }
    // Close the directory, end for each
    closedir($handle);
}

// Sort both arrays by entry property using 'cmp' function (below)

usort($array, "cmp");

foreach ($array as $word) {
  if(isset($word->header->letter)){

    // catch aramaic
    if($word->header->entry >= 10678){
      // Fix letter assignment of Tsades (written as Het)
      if ($word->header->entry >= 11321 && $word->header->entry <= 11334){
         $word->header->letter = "צ";
       }
      // Fix letter assignment of Sin/Šin
      if ($word->header->entry >= 11410 && $word->header->entry <= 11420){
         $word->header->letter = "שׂ";
       }
      if ($word->header->entry >= 11421 && $word->header->entry <= 11497){
        $word->header->letter = "שׁ";
      }

      $word->header->language = 'aramaic';
    } else {
      // Fix letter assignment of Tsades (written as Het)
      if ($word->header->entry >= 7975 && $word->header->entry <= 8385){
         $word->header->letter = "צ";
       }

      // Fix letter assignment of Sin/Šin
      if ($word->header->entry >= 9336 && $word->header->entry <= 9574){
         $word->header->letter = "שׂ";
       }
      if ($word->header->entry >= 9575 && $word->header->entry <= 10405){
        $word->header->letter = "שׁ";
      }

       $word->header->language = 'hebrew';
    }

    // Build array based on letter key
    $out[] = $word;
  } else {
    $out['undef'][] = $word;
  }


}
// Write-out header file

$out_file = fopen("output/content.json", 'w');
fwrite($out_file, json_encode($out, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
// Close file
fclose($out_file);


// Sorting function for above
function cmp($a, $b)
{
    if (!isset($a->entry)){
      return -1;
    }
    if ($a->entry == $b->entry) {
        return 0;
    }
    return ($a->entry < $b->entry) ? -1 : 1;
}

?>

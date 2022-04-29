<?php

$data = json_decode(file_get_contents('philprov.json'),TRUE);
$filtered['mindanao'] = [];
$path = '/Mindanao/';

$pathListing = [];


function normalizeName($name)
{
  $word = '';
  $chars = str_split($name);
  $lowerNext = false;
  foreach ($chars as $char) {
    if ($lowerNext===true) {
      if ($char==="Ñ") {
        echo $char;
        $word = $word."ñ";
      } else {
        $word = $word.strtolower($char);
      }
    } else {
      $word = $word.$char;
    }

    if ($char==='-'||$char==='('||$char===' ') {
      $lowerNext = false;
    } else {
      $lowerNext = true;
    }
  }
  return $word;
}

function toValidPath($name){
  $word = '';
  $chars = str_split($name);
  foreach ($chars as $char) {
    if ($char==='(') {
      $word=substr($word,0,strrpos($word,"_"));
      break;
    }elseif($char===' ') {
      $word = $word.'_';
    } else {
      $word = $word.$char;
    }
  }
  return trim($word);
}

foreach ($data as $key => $region) {
   $regionArr = [];
   if ($key>8&&$key<14||$key==='BARMM') {
     $regionArr['designation'] = $region['region_name'];
     if ($key=='09') {
       $regionArr['name'] = 'Zamboanga Peninsula';
     }
     if ($key=='10') {
       $regionArr['name'] = 'Northern Mindanao';
     }
     if ($key=='11') {
       $regionArr['name'] = 'Davao Region';
     }
     if ($key=='12') {
       $regionArr['name'] = 'SOCCSKSARGEN';
     }
     if ($key=='13') {
       $regionArr['name'] = 'Caraga';
     }
     if ($key=='BARMM') {
       $regionArr['name'] = 'BARMM';
     }
     $regionArr['namespace'] = toValidPath($regionArr['name']);
     $path = '/Mindanao/'.$regionArr['namespace'].'/';
     $regionArr['path'] = $path;
     $regionArr['traceback'] = [
      'province' => null,
      'region' => null,
     ];
     $pathListing[$regionArr['name']] = $regionArr['path'];

     // $regionArr['no'] = $key;
     // $regionArr['name'] = '';
     // $regionArr['capital'] = [
     //   'administrative' => '',
     //   'economic' => ''
     // ];
     // $regionArr['metrics'] = [];
     // $regionArr['logo'] = [
     //   'fan' => '',
     //   'seal' => ''
     // ];

     $regionArr['provinces'] = [];

     foreach ($region['province_list'] as $provinceName => $province) {
       $provinceArr = [];
       $provinceArr['name'] = ucwords(strtolower($provinceName));
       $provinceArr['namespace'] = toValidPath($provinceArr['name']);
       $provinceArr['traceback'] = [
        'province' => null,
        'region' => $regionArr['path'],
       ];
       $provinceArr['path'] = $path.$provinceArr['namespace'].'/';
       $pathListing[$provinceArr['name']] = $provinceArr['path'];
       //$provinceArr['path'] =
       // $provinceArr['capital'] = [
       //   'administrative' => '',
       //   'economic' => ''
       // ];
       // $provinceArr['logo'] = [
       //   'fan' => '',
       //   'seal' => ''
       // ];
       $provinceArr['towns'] = [];
       $provinceArr['cities'] = [];
       foreach ($province['municipality_list'] as $municipalityName => $municipality) {
         if (str_contains($municipalityName,'CITY')) {
           $cityArr = [];
           $cityArr['name'] = normalizeName($municipalityName);
           $cityArr['namespace'] = toValidPath($cityArr['name']);
           $cityArr['traceback'] = [
            'province' => $provinceArr['path'],
            'region' => $regionArr['path'],
           ];
           $cityArr['path'] = $provinceArr['path'].$cityArr['namespace'].'/';
           $pathListing[$cityArr['name']] = $cityArr['path'];
           array_push($provinceArr['cities'],$cityArr);
         } else {
           //normalizeName($municipalityName);
           //array_push($provinceArr['towns'],ucwords(strtolower($municipalityName)));
           $townArr = [];
           $townArr['name'] = normalizeName($municipalityName);
           $townArr['traceback'] = [
            'province' => $provinceArr['path'],
            'region' => $regionArr['path'],
           ];
           $townArr['namespace'] = toValidPath($townArr['name']);
           $townArr['path'] = $provinceArr['path'].$townArr['namespace'].'/';
           $pathListing[$townArr['name']] = $townArr['path'];
           array_push($provinceArr['towns'],$townArr);
         }
       }

       array_push($regionArr['provinces'],$provinceArr);
     }

     array_push($filtered['mindanao'],$regionArr);
   }
}


file_put_contents(__dir__.'/public/data/index.json',json_encode($filtered,JSON_UNESCAPED_UNICODE));
file_put_contents(__dir__.'/public/data/path.json',json_encode($pathListing,JSON_UNESCAPED_UNICODE));

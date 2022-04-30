<?php

class TimeStamp
{

    # Returns the current timestamp in the time zone
    public static function now (
        string $format = 'Y-m-d H:i:s'
        )
    {
        $date = new \DateTime(
            "now",
            new \DateTimeZone("Asia/Manila")
        );
        return $date->format($format);
    }

    public static function add (
        string $date,
        string $addParam
        )
    {
        return (new \DateTime($date))
                ->modify("+{$addParam}")
                ->format("Y-m-d H:i:s");
    }
}

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
  return preg_replace("/\.$/","",trim($word));
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


     $forProvinceListing = [];

     $regionalPathsListing = [];


     # Create A New Card.json for region
     $regionDirPath = __dir__.'/public/data'.$regionArr['path'];
     mkdir($regionDirPath);
     $regionCard = [
       'no' => strval($key),
       'designation' => $regionArr['designation'],
       'name' => $regionArr['name'],
       'namespace' => $regionArr['namespace'],
       'path' => $regionArr['path'],
       'traceback' => $regionArr['traceback'],
       'capital' => [
         'administrative' => [
           'name' => null,
           'path' => null
         ],
         'economic' => [
           'name' => null,
           'path' => null
         ]
       ],
       'metrics' => [],
       'logo' => [
         'fan' => [
           'src' => null,
           'credits' => null
         ],
         'official' => [
           'src' => null,
           'credits' => null
         ]
       ],
       'images' => [
         'cover' => [
           'src' => null,
           'credits' => null
         ]
       ],
       '_card' => [
         'type' => 'region',
         'createdAt' => TimeStamp::now(),
         'updatedAt' => TimeStamp::now(),
         'updatedBy' => '@themindanaoproject'
       ]
     ];
     file_put_contents($regionDirPath.'/card.json',json_encode($regionCard),JSON_UNESCAPED_UNICODE);

     # Projects
     file_put_contents($regionDirPath.'/projects.json',json_encode([]),JSON_UNESCAPED_UNICODE);
     mkdir($regionDirPath.'/Projects/');
     file_put_contents($regionDirPath.'/Projects/index.json',json_encode([]),JSON_UNESCAPED_UNICODE);

     $regionArr['provinces'] = [];


     /**
      * PROVINCES PROVINCES PROVINCES
      */
     foreach ($region['province_list'] as $provinceName => $province) {

       $provinceArr = [];
       $provinceArr['name'] = normalizeName($provinceName);
       $provinceArr['namespace'] = toValidPath($provinceArr['name']);
       $provinceArr['traceback'] = [
        'province' => null,
        'region' => $regionArr['namespace'],
       ];
       $provinceArr['path'] = $path.$provinceArr['namespace'].'/';

       $provincialPathsListing = [];
       $provincialcityListing = [];
       $provincialtownListing = [];

       $pathListing[$provinceArr['name'].', '.$regionArr['name']] = $provinceArr['path'];
       $regionalPathsListing[$provinceArr['name']] = $provinceArr['path'];

       $forProvinceListing[$provinceArr['name']] = $provinceArr['path'];

       $provinceArr['towns'] = [];
       $provinceArr['cities'] = [];

       # Create a card json for each province
       $provinceDirPath = __dir__.'/public/data'.$regionArr['path'].$provinceArr['namespace'];
       mkdir($provinceDirPath);
       $provinceCard = [
         'name' => $provinceArr['name'],
         'namespace' => $provinceArr['namespace'],
         'path' => $provinceArr['path'],
         'traceback' => $provinceArr['traceback'],
         'capital' => [
           'administrative' => [
             'name' => null,
             'path' => null
           ],
           'economic' => [
             'name' => null,
             'path' => null
           ]
         ],
         'metrics' => [],
         'logo' => [
           'fan' => [
             'src' => null,
             'credits' => null
           ],
           'official' => [
             'src' => null,
             'credits' => null
           ]
         ],
         'images' => [
           'cover' => [
             'src' => null,
             'credits' => null
           ]
         ],
         '_card' => [
           'type' => 'province',
           'createdAt' => TimeStamp::now(),
           'updatedAt' => TimeStamp::now(),
           'updatedBy' => '@themindanaoproject'
         ]
       ];
       file_put_contents($provinceDirPath.'/card.json',json_encode($provinceCard),JSON_UNESCAPED_UNICODE);

       # Projects
       file_put_contents($provinceDirPath.'/projects.json',json_encode([]),JSON_UNESCAPED_UNICODE);
       mkdir($provinceDirPath.'/Projects/');
       file_put_contents($provinceDirPath.'/Projects/index.json',json_encode([]),JSON_UNESCAPED_UNICODE);


       foreach ($province['municipality_list'] as $municipalityName => $municipality) {
         if (str_contains($municipalityName,'CITY')) {
           $cityArr = [];
           $cityArr['name'] = normalizeName($municipalityName);
           $cityArr['namespace'] = toValidPath($cityArr['name']);
           $cityArr['traceback'] = [
            'province' => $provinceArr['namespace'],
            'region' => $regionArr['namespace'],
           ];
           $cityArr['path'] = $provinceArr['path'].$cityArr['namespace'].'/';

           $pathListing[$cityArr['name'].', '.$provinceArr['name']] = $cityArr['path'];
           $regionalPathsListing[$cityArr['name'].', '.$provinceArr['name']] = $cityArr['path'];
           $provincialPathsListing[$cityArr['name']] = $cityArr['path'];
           $provincialcityListing[$cityArr['name']] = $cityArr['path'];

           array_push($provinceArr['cities'],$cityArr);


           # Create a card json for each city
           $cityDirPath = __dir__.'/public/data'.$regionArr['path'].$provinceArr['namespace'].'/'.$cityArr['namespace'];
           mkdir($cityDirPath);

           $cityCard = [
             'name' => $cityArr['name'],
             'namespace' => $cityArr['namespace'],
             'path' => $cityArr['path'],
             'traceback' => $cityArr['traceback'],
             'metrics' => [],
             'logo' => [
               'fan' => [
                 'src' => null,
                 'credits' => null
               ],
               'official' => [
                 'src' => null,
                 'credits' => null
               ]
             ],
             'images' => [
               'cover' => [
                 'src' => null,
                 'credits' => null
               ]
             ],
             '_card' => [
               'type' => 'city',
               'createdAt' => TimeStamp::now(),
               'updatedAt' => TimeStamp::now(),
               'updatedBy' => '@themindanaoproject'
             ]
           ];
           file_put_contents($cityDirPath.'/card.json',json_encode($cityCard),JSON_UNESCAPED_UNICODE);

           # Projects
           file_put_contents($cityDirPath.'/projects.json',json_encode([]),JSON_UNESCAPED_UNICODE);
           mkdir($cityDirPath.'/Projects/');
           file_put_contents($cityDirPath.'/Projects/index.json',json_encode([]),JSON_UNESCAPED_UNICODE);

         } else {
           //normalizeName($municipalityName);
           //array_push($provinceArr['towns'],ucwords(strtolower($municipalityName)));
           $townArr = [];
           $townArr['name'] = normalizeName($municipalityName);
           $townArr['traceback'] = [
            'province' => $provinceArr['namespace'],
            'region' => $regionArr['namespace'],
           ];
           $townArr['namespace'] = toValidPath($townArr['name']);
           $townArr['path'] = $provinceArr['path'].$townArr['namespace'].'/';

           $pathListing[$townArr['name'].', '.$provinceArr['name']] = $townArr['path'];
           $regionalPathsListing[$townArr['name'].', '.$provinceArr['name']] = $townArr['path'];
           $provincialPathsListing[$townArr['name']] = $townArr['path'];
           $provincialtownListing[$townArr['name']] = $townArr['path'];

           array_push($provinceArr['towns'],$townArr);


           # Create a card json for each city
           $townDirPath = __dir__.'/public/data'.$regionArr['path'].$provinceArr['namespace'].'/'.$townArr['namespace'];
           mkdir($townDirPath);

           $townCard = [
             'name' => $townArr['name'],
             'namespace' => $townArr['namespace'],
             'path' => $townArr['path'],
             'traceback' => $townArr['traceback'],
             'metrics' => [],
             'logo' => [
               'fan' => [
                 'src' => null,
                 'credits' => null
               ],
               'official' => [
                 'src' => null,
                 'credits' => null
               ]
             ],
             'images' => [
               'cover' => [
                 'src' => null,
                 'credits' => null
               ]
             ],
             '_card' => [
               'type' => 'city',
               'createdAt' => TimeStamp::now(),
               'updatedAt' => TimeStamp::now(),
               'updatedBy' => '@themindanaoproject'
             ]
           ];
           file_put_contents($townDirPath.'/card.json',json_encode($townCard),JSON_UNESCAPED_UNICODE);

           # Projects
           file_put_contents($townDirPath.'/projects.json',json_encode([]),JSON_UNESCAPED_UNICODE);
           mkdir($townDirPath.'/Projects/');
           file_put_contents($townDirPath.'/Projects/index.json',json_encode([]),JSON_UNESCAPED_UNICODE);

         }
       }

       array_push($regionArr['provinces'],$provinceArr);
       file_put_contents($provinceDirPath.'/paths.json',json_encode($provincialPathsListing),JSON_UNESCAPED_UNICODE);
       file_put_contents($provinceDirPath.'/cities.json',json_encode($provincialcityListing),JSON_UNESCAPED_UNICODE);
       file_put_contents($provinceDirPath.'/towns.json',json_encode($provincialtownListing),JSON_UNESCAPED_UNICODE);

     }

     array_push($filtered['mindanao'],$regionArr);
     file_put_contents($regionDirPath.'/provinces.json',json_encode($forProvinceListing),JSON_UNESCAPED_UNICODE);
     file_put_contents($regionDirPath.'/paths.json',json_encode($regionalPathsListing),JSON_UNESCAPED_UNICODE);


   }
}


file_put_contents(__dir__.'/public/data/index.json',json_encode($filtered,JSON_UNESCAPED_UNICODE));
file_put_contents(__dir__.'/public/data/paths.json',json_encode($pathListing,JSON_UNESCAPED_UNICODE));

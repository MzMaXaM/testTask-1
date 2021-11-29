<?php
#check the args to run the function
if (count($argv)>1){
  switch ($argv[1]) {
    case '-help':
      echo "To run the small pString(1,000 lines) add argument: -sTest", PHP_EOL;
      echo "To run the medium pString(10,000 lines) add argument: -mTest", PHP_EOL;
      echo "To run the big pString(153,040 lines) add argument: -bTest", PHP_EOL;
      echo "To run the error pString(6 lines, missing field) add argument: -eTest", PHP_EOL;
      echo "To run it on your own file add argument: -myTest and  the full name of the file", PHP_EOL;
      echo "For example: \"php parser.php -myTest example.csv\"";
      break;
    case '-sTest':
      mainFunc("smallTest.csv");
      break;
    case '-mTest':
      mainFunc("mediumTest.csv");
      break;
    case '-bTest':
      mainFunc("bigTest.csv");
      break;
    case '-eTest':
      mainFunc("errorTest.csv");
      break;
    case '-myTest':
      mainFunc($argv[2]);
      break;
    default:
      echo "For help use argument -help";
      break;
  }
}else
  echo "For help use argument -help";


function mainFunc($fileName){
  echo "Reading file $fileName",PHP_EOL;
  $dataArray = readData($fileName);
  echo "Parsing data",PHP_EOL;
  $parsedString = parseData($dataArray);
  echo "Writing parsed data to file",PHP_EOL;
  writeData($parsedString);
  echo "Done.",PHP_EOL;
}

#read the CSV file
function readData($fileName){
  if (($hData = fopen($fileName, "r")) !== FALSE) 
  {
    while (($data = fgetcsv($hData, 500, ",")) !== FALSE) 
    {
      #add the data to the array so we can parse it
      $dataArray[] = $data;
    }
    fclose($hData);
    parseData($dataArray);
  }
  return $dataArray;
}
#parsing data & saving it into a string
function parseData($dataArray){
  $count = 1;
  $lastRow = [];
  $fieldsArray = $dataArray[0];
  $pString="";
  #loop through array to parse it
  #the fact that the DB is nicely ordered is very important
  #count can be reseted after every row if it's different from the last 
  for ($j = 1; $j < sizeof($dataArray);$j++){
    if ($dataArray[$j] == $lastRow){
      $count++;
      continue;
    }else{
      if($j != 1)
      $pString = $pString."quantity:  $count\n";
      $pString = $pString."=/=/=/=/=/=/=/=/=/=/=/=/=/=/=/=/=/=/=/=/=/=/=/=/=/=/=/=/=/=/=/=/=\n";
      PHP_EOL;
      for($i = 0; $i < sizeof($fieldsArray); $i++){
        if ($dataArray[$j][$i] == null)
          throw new Exception("Required field not found!");
        else 
          $pString = $pString."$fieldsArray[$i] : {$dataArray[$j][$i]}\n";
      }
      $count = 1;
      $lastRow = $dataArray[$j];
    }
  }
  $pString = $pString."quantity: $count\n";
  $pString = $pString."=/=/=/=/=/=/=/=/=/=/=/=/=/=/=/=/=/=/=/=/=/=/=/=/=/=/=/=/=/=/=/=/=";

  return $pString;
}

#write parsed data to the file
function writeData($str){
  $parsedFile = fopen("Parsed_File.txt", "w");
  fwrite($parsedFile, $str);
  fclose($parsedFile);
}

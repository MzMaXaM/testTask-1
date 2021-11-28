<?php
#check the args to run the function
if (count($argv)>1){
  switch ($argv[1]) {
    case '-help':
      echo "To run the small test(1,000 lines) add argument: -sTest", PHP_EOL;
      echo "To run the medium test(10,000 lines) add argument: -mTest", PHP_EOL;
      echo "To run the big test(153,040 lines) add argument: -bTest", PHP_EOL;
      echo "To run the error test(6 lines, missing field) add argument: -eTest", PHP_EOL;
      echo "To run it on your own file add argument: -myTest and  the full name of the file", PHP_EOL;
      echo "For example: \"php parser.php -myTest example.csv\"";
      break;
    case '-sTest':
      parseData("smallTest.csv");
      break;
    case '-mTest':
      parseData("mediumTest.csv");
      break;
    case '-bTest':
      parseData("bigTest.csv");
      break;
    case '-eTest':
      parseData("errorTest.csv");
      break;
    case '-myTest':
      parseData($argv[2]);
      break;
    default:
      echo "For help use argument -help";
      break;
  }
}else
  echo "For help use argument -help";

#read the CSV file
function parseData($fileName){
  if (($hData = fopen($fileName, "r")) !== FALSE) 
  {
    while (($data = fgetcsv($hData, 500, ",")) !== FALSE) 
    {
      #add the data to the array so we can parse it
      $mainArray[] = $data;
    }
    fclose($hData);
    //add some variables
    $count = 1;
    $lastRow = [];
    $fieldsArray = $mainArray[0];
    #loop through array to show the info
    #the fact that the DB is nicely ordered is very important
    #count can be reseted after every row if it's different from the last 
    for ($j = 1; $j < sizeof($mainArray);$j++){
      if ($mainArray[$j] == $lastRow){
        $count++;
        continue;
      }else{
        if($j != 1)
          echo "quantity: ", $count, PHP_EOL;
        echo "=/=/=/=/=/=/=/=/=/=/=/=/=/=/=/=/=/=/=/=/=/=/=/=/=/=/=/=/=/=/=/=/=",
        PHP_EOL;
        for($i = 0; $i < sizeof($fieldsArray); $i++){
          if ($mainArray[$j][$i] == null)
            throw new Exception("Required field not found!");
          else 
            echo $fieldsArray[$i], ":", $mainArray[$j][$i], PHP_EOL;
        }
        $count = 1;
        $lastRow = $mainArray[$j];
      }
    }
    echo "quantity: ", $count, PHP_EOL;
    echo "=/=/=/=/=/=/=/=/=/=/=/=/=/=/=/=/=/=/=/=/=/=/=/=/=/=/=/=/=/=/=/=/=",
        PHP_EOL;
  }
}

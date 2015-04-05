<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title>Upload Excel File</title>
</head>
<body>
<h1 align="center">Upload Excel File</h1>

<form method="post" enctype="multipart/form-data">
    <fieldset>
        <legend>Upload Excel File</legend>
        <input type="file" name="file"/>&nbsp;&nbsp;&nbsp;
        <input type="submit" name="submit" value="Load"/>
    </fieldset>
</form>
</body>
</html>

<?php
    if(isset($_FILES['file'])){
        require_once 'SpreadsheetExcelReader.php';
        //require_once 'Reader.php';

        $fileName = basename($_FILES['file']['name']);
        $fileNameN=rtrim($fileName,'.xls');
        $excelReader = new Spreadsheet_Excel_Reader();

        echo "<h1>$fileName Result</h1>";

        $fileNameTmp=$_FILES['file']['tmp_name'];
        $excelReader->read($fileNameTmp);
       /* $data=$excelReader->sheets[0]['cells'];
        print_r($data);*/

        //display full excel data
        /*$allData=$excelReader->sheets;
        print_r($allData);*/

        $totalSheet=sizeof($excelReader->sheets);

        echo "Number of sheets: " . $totalSheet . "<br/>";
        for ($i=0; $i<$totalSheet; $i++) {
            echo "Number of rows in sheet " . ($i+1) . ": " . $excelReader->sheets[$i]["numRows"] . "<br/>";
            echo "Number of columns in sheet " . ($i+1) . ": " . $excelReader->sheets[$i]["numCols"] . "<br/>";
        }
        /*echo '<pre>';
        var_export($excelReader->sheets);
        echo '</pre>';*/
        $tableColsName=array();

        $tableName=array();
        $tableNameInc=1;

        $colsValueAllSheet=array();
        $colsValueAllSheetInc=1;

        $cellValue=array();

        $cellSheetValue=array();
        $cellSheetValueInc=1;
        echo "<form action='save.php' method='post' enctype='multipart/form-data'>";
        echo "<input type='hidden' name='fileNameTmp' value='$fileNameTmp'/>";

        for($i=0; $i<$totalSheet;$i++) // counting how many excel sheet until false
        {
            $data=$excelReader->sheets[$i]['cells'];
            //print_r($data[1]);
            $tableColsNameInc=1;

            foreach($data[1] as $k=>$v)
            {
                $vLower=strtolower($v);
                $tableColsName[$tableColsNameInc++]=str_replace(' ','',$vLower);
                //echo $k.' => '.$cName."<br/>";
            }
            $colsValueAllSheet[$colsValueAllSheetInc++]=$tableColsName;

            $tableName[$tableNameInc++]=$fileNameN.($i+1); //concate tableColSheetValue filename+sheet number

            //print_r($tableName);

            //echo $tableName."<br/>";
            echo "Sheet No: ".($i+1)."<br/>";   // display the excel sheet title
            $sheetRow=$excelReader->sheets[$i]['numRows'];  // count the excel sheet rows
            $sheetCols=$excelReader->sheets[$i]['numCols']; // count the excel sheet columns
            echo '<table cellpadding="2" style="border-collapse: collapse;">';   // creating tableColSheetValue
            $cellValueInc=1;
            for($r=1;$r<=$sheetRow;$r++){   // excel sheet rows

                echo "<tr>\n";

                for($c=1;$c<=$sheetCols;$c++){  // excel sheet columns
                    $cell = isset($excelReader->sheets[$i]['cells'][$r][$c]) ? $excelReader->sheets[$i]['cells'][$r][$c] : ' '; //store tableColSheetValue cells Value
                    //echo "<td><input type='text' name='cellName' value='$cell'/></td>";    //display tableColSheetValue cells data
                    //print_r($cell);
                    if($r==1)
                    {
                        echo "<td>$cell</td>";    //display tableColSheetValue cells data
                    }
                    else
                    {
                        echo "<td><input type='text' name='cellValue' value='$cell'/></td>";    //display tableColSheetValue cells data
                        $cellValue[$cellValueInc++]=$cell;
                    }
                }
                echo "</tr>\n";
            }
            $cellSheetValue[$cellSheetValueInc++]=$cellValue;

            echo "</table><br/><br/>";
        }
        //print_r($cellSheetValue);


        //Passing Values

        $tNamePass=serialize($tableName);
        echo "<input type='hidden' name='tableName' value='$tNamePass'/>";

        $tableColSheetValue=serialize($colsValueAllSheet);
        //print_r($tableColSheetValue);
        echo "<input type='hidden' name='colsValueAllSheet' value='$tableColSheetValue' />";

        echo "<input type='hidden' name='totalSheet' value='$totalSheet'/>";

        $totalCellSheetValue=serialize($cellSheetValue);
        echo "<input type='hidden' name='totalCellSheetValue' value='$totalCellSheetValue'/>";

        echo "<br/><input type='submit' name='submit' value='Save' />";
        echo "</form>";
    }
?>
<?php
    include_once 'dbConnect.php';
    //print $_POST['cellValue'];
    if(isset($_POST['submit'])){

        $tNamePass=unserialize($_POST['tableName']);
        //print_r($tNamePass);

        $tableColSheetValue=unserialize($_POST['colsValueAllSheet']);
        //print_r($tableColSheetValue);

        $totalSheet=$_POST['totalSheet'];
        //print_r($totalSheet);

        $totalCellSheetValue=unserialize($_POST['totalCellSheetValue']);
        print_r($totalCellSheetValue);

        $colName=array();

        for($mainL=0; $mainL<$totalSheet;$mainL++){     //This loop continue until excel sheets are not end

            $cInc=1+$mainL;

            for($tableNamePass=1;$tableNamePass<=count($tNamePass);$tableNamePass++){

                $splitTableName=$tNamePass[$tableNamePass];
                $colNameInc=1;

                foreach($tableColSheetValue[$tableNamePass] as $value){     //Table Column Name set

                    $colName[$colNameInc++]=$value;
                }

                if($cInc==$tableNamePass)
                {
                    //Drop & Create the tableColSheetValue
                    $tDelete = "DROP TABLE IF EXISTS `$splitTableName`";
                    mysqli_query($con, $tDelete);

                    $countCol=1;
                    $create_query = "CREATE TABLE IF NOT EXISTS `$splitTableName`(\n";
                    $create_query .= " `id` INT(10) NOT NULL AUTO_INCREMENT ,\n";

                    foreach ($colName as $key => $name)     //insert to the table column name set
                    {
                        $create_query .= " `$name` VARCHAR(50) ,\n";
                        $countCol++;      //count the total table column into each table
                    }
                    $create_query .= " PRIMARY KEY (id)\n);";
                    //var_dump($create_query);
                    print_r($create_query);
                    mysqli_query($con, $create_query);
                    //echo "CountCol: ".$countCol;

                    echo "\n";
                    $countMainArray=1;
                    $subCellValue=array();
                    foreach($totalCellSheetValue[$tableNamePass] as $cellValue){

                        $subCellValue[$countMainArray++]=$cellValue;    //assign the Main Row value to the Sub array value
                        if($countMainArray==$countCol){     //check the condition Table row field value == Total column
                            //Insert Data into Table
                            $insert_query = "INSERT INTO `$splitTableName` VALUES('',";
                            $insert_array = array();

                            //print_r($subCellValue);
                            foreach ($subCellValue as $line)
                            {
                                $insert_array [] = "'$line'";
                            }
                            $insert_query .= implode(",", $insert_array);
                            $insert_query .= ");";
                            print_r($insert_query);
                            echo "\n";
                            mysqli_query($con,$insert_query);
                            $countMainArray=1;
                        }
                    }
                }
            }
        }
    }



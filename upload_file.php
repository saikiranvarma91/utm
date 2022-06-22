<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en"><head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8">
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="shortcut icon" href="img/favicon1.ico">
        <title>Universal Turing Machine</title>
        <!-- Bootstrap core CSS -->
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="css/bootstrap.css" rel="stylesheet">
        <link href="css/bootstrap-responsive.css" rel="stylesheet">
        <link rel="stylesheet" href="font-awesome/css/font-awesome.min.css">
        <link href="css/custom.css" rel="stylesheet">
        <style type="text/css" id="holderjs-style">  
            body {
                background: url("img/bg.jpg") repeat-x scroll left top #2E7EAA;
                color: #FFF;
            }
        </style>
    </head>
    <body>
    <center>
        <a href="/UTM" class="btn btn-success btn-xs" style="margin-top:1px;color:#FFF">
            <i class="fa fa-home fa-1x"></i>
        </a>
        <a href="video.php" class="btn btn-success btn-xs" style="margin-top:1px;color:#FFF">
            <i class="fa fa-film fa-1x"></i>
        </a>
    </center>

    <br/><br/>
    <div class="container">
<?php
/*
------------------------------------------------------------------------------
| Six Steps
|
|      1) Getting Details Of Uploaded File And Uploading 
|         it to Server uploads Folder.
|
|      2) Reading Data Line By Line From Uploaded File 
|         And Form Array Of InputTape, States And Instructions
|         $instructions[key] = PresentState,Input,NewState,output,Move
|
|      3) Forming An Array From Previous Data For 
|         Searching Data Based On PresentState & Input
|             FinalArray[PresentState][Input]
|                 Which Gives The Result
|             FinalArray[NewState][Output][Move]
|
|      4) Performing Turing Machine Operations
|
|      5) Display Instructions
|
|      6) Display Output
------------------------------------------------------------------------------
 */
?>
        <div class="row">
<?php
/*
------------------------------------------------------------------------------
| START Of Step-I.
------------------------------------------------------------------------------
*/
/*
------------------------------------------------------------------------------
| Getting Details Of Uploaded File And Uploading  it to Server uploads Folder.
------------------------------------------------------------------------------
*/
?>
            <div class="span2" style="text-align:center;">
                <h2>
                    <i class="fa fa-cloud-upload fa-2x"></i>
                </h2>
                <p>
                    <strong>
    <?php
    if (isset($_FILES['files'])) 
    {
        if ($_FILES["files"]["error"] > 0) 
        {
            echo die("Error: " . $_FILES["files"]["error"] . "<br>");
        } 
        else 
        {
            echo "Uploaded: " . $_FILES["files"]["name"] . "<br>";
            echo "Type: " . $_FILES["files"]["type"] . "<br>";
            echo "Size: " . ($_FILES["files"]["size"] / 1024) . " kB<br>";
            $newFileName = "uploads/" . time() . 
                    str_shuffle('abcdEfghIjkl012345') . $_FILES["files"]["name"];
        ?>
            <a href="<?php echo $newFileName; ?>" target="_blank">
                <span class="label label-default">Download File</span>
            </a>
        <?php
            if (move_uploaded_file($_FILES["files"]["tmp_name"], $newFileName)) 
            {
                $_SESSION['name'] = $_FILES["files"]["name"];
                $_SESSION['size'] = $_FILES["files"]["size"];
                $_SESSION['file_name'] = $newFileName;
            }
        }
    } 
    else if (isset($_SESSION['file_name'])) 
    {
        echo "Uploaded: " . $_SESSION['name'];
        echo "Type: text/plain <br>";
        echo "Size: " . ($_SESSION["size"] / 1024) . " kB<br/><br/>";
    ?>
        <a href="<?php echo $_SESSION['file_name']; ?>"  target="_blank">
            <span class="label label-default">Download File</span>
        </a>
        <?php
    } 
    ?>
                    </strong>
                </p>
            </div>
<?php           
/*
------------------------------------------------------------------------------
| END Of Step-I.
------------------------------------------------------------------------------
*/
?>         
            
            
<?php
/*
------------------------------------------------------------------------------
| START Of Step-II.
------------------------------------------------------------------------------
*/
/*
------------------------------------------------------------------------------
| Reading Data Line By Line From Uploaded File And Form Array Of 
| InputTape, States And Instructions
------------------------------------------------------------------------------
*/
$states = array();
$input[] = '_';
$output = array();
$finalData = array();
if(!isset($_SESSION['file_name']))
{
    header("location: /UTM");
}
if(!file_exists($_SESSION['file_name']))
{
    die('<center>File not found.</center>');
}
$file = fopen($_SESSION['file_name'], "r") or exit("Unable to open file!");
$ln = 0;
while (!feof($file)) 
{
    $inputVal = trim(fgets($file));
    if (is_numeric($inputVal) || !empty($inputVal)) 
     {
        if ($ln == 0) 
        {
            if (strlen($inputVal) > 0) 
            {
                for ($i = 0; $i < strlen($inputVal); $i++) 
                {
                    $input[] = substr($inputVal, $i, 1);
                }
                $output = $input;
            } 
            else 
            {
                die('Syntax Error On line ' . ($ln + 1));
            }
        } 
        elseif ($ln == 1) 
        {
            if (strlen($inputVal) == 1) 
            {
                $states[$inputVal] = $inputVal;
                $stateIndex = $inputVal;
            } 
            else 
            {
                die('Syntax Error On line ' . ($ln + 1));
            }
        } 
        else 
        {
            $data = str_replace(';', '', str_replace('}', '', 
                    str_replace('{', '', str_replace('=', ',', 
                    str_replace(']', '', str_replace('[', '', 
                    str_replace('d', '', $inputVal)))))));
            $arrayData[] = explode(',', $data);
        }
        $ln++;
    }
    if($ln==5000)
    {
        break;
    }
}
fclose($file);
/*
------------------------------------------------------------------------------
| END Of Step-II.
------------------------------------------------------------------------------
*/


/*
------------------------------------------------------------------------------
| Start Of Step-III.
------------------------------------------------------------------------------
*/
/*
------------------------------------------------------------------------------
| Forming An Array From Previous Data For Searching Data Based On 
| PresentState & Input FinalArray[PresentState][Input] Which Gives 
| The Result FinalArray[NewState][Output][Move]
------------------------------------------------------------------------------
*/
if (!empty($arrayData)) {
    foreach ($arrayData as $key => $val) {
        $finalData[$val[0]][$val[1]] = array($val[2], $val[3], $val[4], '');
    }
}
/*
------------------------------------------------------------------------------
| END Of Step-III.
------------------------------------------------------------------------------
*/


/*
------------------------------------------------------------------------------
| Start Of Step-IV.
------------------------------------------------------------------------------
*/
/*
------------------------------------------------------------------------------
| Performing Turing Machine Operations
------------------------------------------------------------------------------
*/
$statePos = $inputPos = $outputPos = 0;
$loopCount = $inputPos = 1;
while (true) {
    if (isset($states[$statePos]) && isset($output[$inputPos])) {
        $presentState = $states[$statePos];
        $inputVal = $output[$inputPos];
    } else {
        break;
    }
    $instructionData = $finalData[$presentState][$inputVal];
    $newState = $instructionData[0];
    $outputVal = $instructionData[1];
    $move = $instructionData[2];
    $states[$statePos + 1] = $newState;
    if ($move == 'R') {
        $finalData[$presentState][$inputVal][3] .= "," . $loopCount;
        $output[$inputPos] = $outputVal;
        $inputPos++;
    } elseif ($move == 'L') {
        $finalData[$presentState][$inputVal][3] .= "," . $loopCount;
        $output[$inputPos] = $outputVal;
        $inputPos--;
    } else {
        break;
    }
    if ($newState == 'h') {
        break;
    }
    $presentState = $newState;
    $loopCount++;
    $statePos++;
}
/*
------------------------------------------------------------------------------
| END Of Step-IV.
------------------------------------------------------------------------------
*/

/*
------------------------------------------------------------------------------
| Start Of Step-V.
------------------------------------------------------------------------------
*/
/*
------------------------------------------------------------------------------
| Display Instructions
------------------------------------------------------------------------------
*/
?>
<div class="span6" style="text-align:center;">
    <h2>
        <i class="fa fa-play-circle fa-2x"></i>
    </h2>
<?php
if (!empty($finalData)) {
?>
    <table class="table black">
        <thead>
            <tr>
                <th><center>Present State</center></th>
                <th><center>Input</center></th>
                <th><center>Next State</center></th>
                <th><center>Output</center></th>
                <th><center>Move</center></th>
                <th><center>Execution Order</center></th>
            </tr>
        </thead>
        <tbody>
    <?php
    foreach ($finalData as $topKey => $topVal) {
        foreach ($topVal as $midKey => $midVal) {
            ?>
             <tr class="<?php echo!empty($midVal[3]) ? 'steps' : ''; ?>">
                <td><center><?php echo $topKey; ?></center></td>
                <td><center><?php echo $midKey; ?></center></td>
                <td><center><?php echo $midVal[0]; ?></center></td>
                <td><center><?php echo $midVal[1]; ?></center></td>
                <td><center><?php echo $midVal[2]; ?></center></td>
                <td>
                <center> 
                    <?php
                    $execOrdData = explode(',', (substr($midVal[3], 1)));
                    $exCount = 1;
                    foreach ($execOrdData as $exKey => $exVal) {
                        if (is_numeric($exVal) && !empty($exVal)) {
                            echo $exVal . ", ";
                            if (($exCount % 3) == 0) {
                                echo "<br/>";
                            }
                            $exCount++;
                        }
                    }
                    ?>
                </center>
                </td>
            </tr>
<?php }
} ?>
        </tbody>
    </table>
<?php } ?> 
</div>
<?php
/*
------------------------------------------------------------------------------
| END Of Step-V.
------------------------------------------------------------------------------
*/

/*
------------------------------------------------------------------------------
| Start Of Step-VI.
------------------------------------------------------------------------------
*/
/*
------------------------------------------------------------------------------
| Display Instructions
------------------------------------------------------------------------------
*/
?>
            <div class="span4" style="text-align:center;">
                <h2>
                    <i class="fa fa-eye fa-2x"></i>
                </h2>
<?php              
/*
------------------------------------------------------------------------------
| Diplaying Given Input
------------------------------------------------------------------------------
*/
?>
        <table class="table black  table-bordered">
            <thead>
                <tr>
                    <th colspan="<?php echo count($input); ?>">
                        <center>Input</center>
                    </th>
            </tr>
            </thead>
            <tbody>
                <tr>
                <?php
                $count = 0;
                foreach ($input as $iKey => $iVal) {
                    if (($count % 5) == 0) {
                        echo "<tr>";
                    }
                    if ($iVal != '_') {
                        ?>
                            <td><center><?php echo $iVal; ?></center></td>
                <?php
                $count++;
            }
            if (($count % 5) == 0) {
                echo "</tr>";
            }
        }
        if ((($count % 5) != 0) && ($count > 5)) {
            for ($k = 0; $k < (5 - ($count % 5)); $k++) {
                echo "<td></td>";
            }
            echo "</tr>";
        } else {
            for ($k = 0; $k < (5 - $count); $k++) {
                echo "<td></td>";
            }
            echo "</tr>";
        }
        ?>
            </tbody>
        </table>

<?php              
/*
------------------------------------------------------------------------------
| Diplaying States
------------------------------------------------------------------------------
*/
?>
        <table class="table black  table-bordered">
            <thead>
                <tr>
                    <th colspan="<?php echo count($states); ?>">
                        <center>States</center>
                    </th>
            </tr>
            </thead>
            <tbody>
                <tr>
            <?php
            $count = 0;
            foreach ($states as $sKey => $sVal) {
                if (($count % 5) == 0) {
                    echo "<tr>";
                }
                ?>
                        <td><center><?php echo $sVal; ?></center></td>
                <?php
                $count++;
                if (($count % 5) == 0) {
                    echo "</tr>";
                }
            }

            if ((($count % 5) != 0) && ($count > 5)) {
                for ($k = 0; $k < (5 - ($count % 5)); $k++) {
                    echo "<td></td>";
                }
                echo "</tr>";
            } else {
                for ($k = 0; $k < (5 - $count); $k++) {
                    echo "<td></td>";
                }
                echo "</tr>";
            }
            ?>  
            </tbody>
        </table>

<?php              
/*
------------------------------------------------------------------------------
| Diplaying Output
------------------------------------------------------------------------------
*/
?>
        <table class="table black  table-bordered">
            <thead>
                <tr>
                    <th colspan="<?php echo count($output); ?>">
                        <center>Output</center>
                    </th>
            </tr>
            </thead>
            <tbody>
                <tr>
                    <?php
                    $count = 0;
                    foreach ($output as $oKey => $oVal) {
                        if (($count % 5) == 0) {
                            echo "<tr>";
                        }
                        if ($oVal != '_') {
                            ?>
                            <td><center><?php echo $oVal; ?></center></td>
                    <?php
                    $count++;
                }
                if (($count % 5) == 0) {
                    echo "</tr>";
                }
            }

            if ((($count % 5) != 0) && ($count > 5)) {
                for ($k = 0; $k < (5 - ($count % 5)); $k++) {
                    echo "<td></td>";
                }
                echo "</tr>";
            } else {
                for ($k = 0; $k < (5 - $count); $k++) {
                    echo "<td></td>";
                }
                echo "</tr>";
            }
            ?>
            </tr>   
            </tbody>
        </table>
    </div>
<?php
/*
------------------------------------------------------------------------------
| END Of Step-VI.
------------------------------------------------------------------------------
*/
?>
        </div>

    </div>
    <br/><br/><br/><br/><br/><br/>
    <span id="iconMainLeft">
        <h2>
            <i class="fa fa-cog fa-spin fa-2x"></i>
        </h2>
    </span>
    <span id="iconMainRight">
        <h2>
            <i class="fa fa-cog fa-spin fa-2x"></i>
        </h2>
    </span>
    <div class="clear-fix"></div>
    <div id="footFixed">
        <footer>
            <p style="margin-top: 17px;text-align: center;">
                &copy; Universal Turing Machine 2014
            </p>
        </footer>
    </div>
    <!-- Bootstrap core JavaScript -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="js/bootstrap.js"></script>
    <script src="js/jquery-1.9.0.js"></script>
    <script>
            
    </script>
</body>
</html>

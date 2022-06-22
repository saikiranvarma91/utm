<!DOCTYPE html>
<html lang="en">
    <head>
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
            <!-- Main hero unit for a primary marketing message or call to action -->
            <div class="hero-unit" id="hero">
                <center>
                    <h2>Universal Turing Machine</h2>
                    <hr/>
                    <br/>
                    <br/>
<?php
/*
------------------------------------------------------------------------------
| Form For File Uploading.
------------------------------------------------------------------------------
*/
?>                    
<form id="target" action="upload_file.php" method="post" enctype="multipart/form-data">
    <input type="file" name="files" id="files" class="btn btn-default btn-medium">
    <output id="list"></output>
</form>
<span id="errorMsg"></span>
<?php
/*
------------------------------------------------------------------------------
| END Of Form.
------------------------------------------------------------------------------
*/
?>                   
                </center>
            </div>
        </div>
        
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
 
 <?php
/*
------------------------------------------------------------------------------
|Jquery For validating And Auto Submit File On Selecting File Use Mozila Firefox.
------------------------------------------------------------------------------
*/
?>
<script>
$('#files').change(function() {
    var size = 0;
    var fileInput = document.getElementById ("files");
    var errorMsg  = document.getElementById ("errorMsg");  
    if ('files' in fileInput) 
    {  
        if (fileInput.files.length == 0) 
        {
            errorMsg.innerHTML = "<span class='errorMsg'>Please Upload a File</span>";
            fileInput.value = "";
        }
        else 
        {   
            for (var i = 0; i < fileInput.files.length; i++) 
            {
                var file = fileInput.files[i];
                if ('size' in file) 
                {
                     size = file.size;
                }
            }
            if(size == 0)
            {
                 errorMsg.innerHTML = "<span class='errorMsg'>Empty File Not Allowed Here.</span>";   
                 fileInput.value = "";
            }
            else
            {    
            var extension = fileInput.value.substr( (fileInput.value.lastIndexOf('.') +1) );
            if(extension == 'txt')
             {
                 $('#target').submit();
             }
             else
             {
                  errorMsg.innerHTML = "<span class='errorMsg'>Only .txt extension is supported.</span>";   
                  fileInput.value = "";
             }
            }
         }
     }
});
</script>
 <?php
/*
------------------------------------------------------------------------------
| End Of Jquery.
------------------------------------------------------------------------------
*/
?>
    </body>
</html>

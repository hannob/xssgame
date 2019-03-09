<!DOCTYPE html><html lang="en">
<head>
<title>Can you XSS me?</title>
</head>
<body style="margin:0px;padding:0px;background-color:#ffffff;font-family:Sans;">
<div style="position:absolute;background-color:#999999;left:0px;right:0px;top:0px;margin:0px;padding:20px;height:80px;"><h2 style="float:right;">Can you XSS me?</h2></div>

<div style="position:absolute;top:100px;padding:20px;">
<?php
if (array_key_exists("upload", $_FILES)) {
    if (substr($_FILES['upload']['name'], -3) != 'jpg') {
        die("Only JPG allowed");
    }
    if ($_FILES['upload']['name'] > 5000) {
        die("Only files up to 5000 bytes allowed");
    }
    if ((strpos($_FILES['upload']['name'], '..') !== false) ||
         (strpos($_FILES['upload']['name'], '/') !== false)) {
        die("Invalid filename");
    }
    move_uploaded_file($_FILES['upload']['tmp_name'], $_FILES['upload']['name']);
    echo "Upload succeeded!<br>";
    echo '<a href="'.htmlspecialchars($_FILES['upload']['name'], ENT_QUOTES).'">';
    echo 'View your file '.htmlspecialchars($_FILES['upload']['name'], ENT_QUOTES).'</a>';
}

?>
<p>
You can upload small JPG images (up to 5000 bytes) here:
</p>
<form action="." enctype="multipart/form-data" method="POST">
<input type="hidden" name="MAX_FILE_SIZE" value="5000">
<input type="file" name="upload" accept=".jpg"><br>
<input type="submit" value="Upload JPG">
</form>
<h4>Recent uploads</h4>
<p>
<?php

$handle=opendir(".");

while (false !== ($fn = readdir($handle))) {
    //	echo(htmlspecialchars($fn, ENT_QUOTES).'<br>');
    if (substr($fn, -3) == 'jpg') {
        $l[] = $fn;
    }
}

$max = count($l);

for ($i=0;$i<3;$i++) {
    $max--;
    if ($max < 0) {
        break;
    }
    echo '<a href="'.htmlspecialchars($l[$max], ENT_QUOTES).'">';
    print(htmlspecialchars($l[$max], ENT_QUOTES)."</a><br>");
}

?>
</p>
<h4>Background</h4>
<p>Can you find a Cross Site Scripting vulnerability here?</p>
<p>The form has no CSRF protection and existing files can be overwritten.
This is not what we're looking for here.</p>
<p>If you found the XSS: Do you think there are real world applications where this
attack vector applies? (If you find some please contact me.)</p>
<em>Created by <a href="https://hboeck.de/">Hanno Böck</a>.</em>
</div></body></html>

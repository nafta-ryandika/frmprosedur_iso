<script type="text/javascript" src="js/jquery-latest.js"></script>
<?php
include("../../configuration.php");
include("../../connection.php");
include("../../endec.php");

function count_pages($pdfname) {
    //sudah tidak bisa (????) trus bisa lagi tgl 11/05/2018
//    $pdftext = file_get_contents($pdfname);
//    $num = preg_match_all("/\/Page\W/", $pdftext, $dummy);

    //tidak bisa tgl 11/05/18
//    $exec = exec("identify -density 12 -format \"%p\" " . $pdfname);
//    $num = substr($exec, -1);
    
    //new 18/05/2018 using imagick
    $exec = exec("identify -format %n $pdfname");
    $num = $exec;
    
    return $num;
}

//file upload.php
//echo "jajal";
$fileName = strtolower($_FILES['picture']['name']);
$fileSize = $_FILES['picture']['size'];
$fileError = $_FILES['picture']['error'];
$fileType = $_FILES['picture']['type'];

$thn = date('dmy');

$nama_gambar = 'tmp_' . $fileName . "_" . $_SESSION[$domainApp . "_myname"] . $thn;
$success = false;

$output_dir = "uploads/";
$convert_dir = "convert/";

//$message = "File Name : $fileName | File Size : $fileSize | File Error : $fileError ";
$message = "Warning : Filesize > 2MB";

if ($fileSize <= 2000000) {
//experimen
    if ($fileSize > 0 || $fileError == 0) {
        $message = "masuk file size";
        $RandomNum = time();

        $fileName = str_replace(" ", "_", $fileName);
        $FileExt = substr($fileName, strrpos($fileName, '.'));
        $FileExt = str_replace('.', '', $FileExt);

        if ($FileExt != "pdf") {
            $message = "Invalid file format only <b>\"PDF\"</b> allowed.";
            $success = false;
        } else {
            $fileName = preg_replace("/\.[^.\s]{3,4}$/", "", $fileName);
            $NewFileName = $fileName . '-' . $RandomNum . '.' . $FileExt;

            $move = move_uploaded_file($_FILES["picture"]["tmp_name"], $output_dir . $NewFileName); //atau ke directory yang dinginkan
            if ($move) {
                //proses convert pdf to jpg 
                $location = "";
                $name = $output_dir . $NewFileName;
                $num = count_pages($location . $name);
                $nameJPG = $convert_dir . $fileName . '-' . $RandomNum . ".jpg";
                $convert = "convert -density 80x80 " . $location . $name . " " . $location . $nameJPG;
                //convert from pdf to jpg
                exec($convert);
                //delete file pdf in folder uploads
                unlink($location . $name);

                $success = true;
                $message = "";
            } else {
                $success = false;
                $message = "Upload Gagal !!! $fileError";
            }
        }
    }
}

//end//
//if ($fileSize > 0 || $fileError == 0) {
//    $move = move_uploaded_file($_FILES['picture']['tmp_name'], $output_dir . $nama_gambar); //atau ke directory yang dinginkan
//    if ($move) {
//        $success = true;
//    }
//}
?>
<script type="text/javascript">
    $(document).ready(function(){
        //        alert("<?= $message ?>");
    })
<?php
if ($success) {
    echo "parent.displayPicture('$fileName-$RandomNum', '$num');";
} else {
    echo "alert('$message');";
}
?>
</script>

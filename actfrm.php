<?php

include("../../configuration.php");
include("../../connection.php");
include("../../endec.php");

function update_parent($id, $chk) {
    if ($chk == "1") {
        $sqlupdate = "update prosedur_iso set view_toko = 1 where id = " . $id;
        if (!mysql_query($sqlupdate)) {
            die("Error1 : " . mysql_error());
        }

        $sql = "select id_parent from prosedur_iso where id = $id";
        $rs = mysql_query($sql);
        $data = mysql_fetch_array($rs);
        $id_parent = $data['id_parent'];
        $sqlupdate = "update prosedur_iso set view_toko = 1 where id = " . $id_parent;
        if (!mysql_query($sqlupdate)) {
            die("Error2 : " . mysql_error());
        }

        if ($id_parent > 0) {
            update_parent($id_parent, 1);
        }
    } else {
        $sqlupdate = "update prosedur_iso set view_toko = 0 where id = " . $id;
        if (!mysql_query($sqlupdate)) {
            die("Error4 : " . mysql_error());
        }

        $sql = "select id from prosedur_iso where id_parent = $id";
        $rs = mysql_query($sql);
        while ($data = mysql_fetch_array($rs)) {
            $sqlupdate = "update prosedur_iso set view_toko = 0 where id = " . $data['id'];
            if (!mysql_query($sqlupdate)) {
                die("Error5 : " . mysql_error());
            }
            update_parent($data['id'], 0);
        }
    }
}

// Action input php

$intxtmode = $_POST['intxtmode'];

$inid = $_POST['inid'];
$innamafile = $_POST['txtnamafile'];
$injmlfile = $_POST['txtjmlfile'];
$inprosedur = $_POST['inprosedur'];
$inprocparent = $_POST['inprocparent'];
$indepartemen = $_POST['indepartemen'];
$incheck = $_POST['incheck'];


//variabel image
$gambar = explode(".", $_POST['namagambar']);
$namagambar = $gambar[0];
$jmlpage = $_POST['jmlpage'];




if ($intxtmode == 'add') {

    $sqlINSERT = "
            insert into prosedur_iso (
               departemen
               ,id_parent
               ,prosedur
               ,nama_gambar
               ,total_halaman
               ,updated
               ,updateby
               ,jam
            ) VALUES (
               '" . $indepartemen . "'  -- departemen - IN varchar(50)
              ,'" . $inprocparent . "'  -- id_parent - IN INT
              ,'" . $inprosedur . "'  -- prosedur - IN varchar(160)
              ,'" . $innamafile . "'  -- nama_gambar - IN varchar(160)
              ,'" . $injmlfile . "'  -- total_halaman - IN int(5)
              ,(select curdate())  -- updated - IN date()
              ,'" . $_SESSION[$domainApp . "_myname1"] . "'  -- updateby - IN date()
              ,(select curtime())  -- jam - IN date()
            )
        ";
//    echo $sqlINSERT;
    //execute query
    if (!mysql_query($sqlINSERT, $conn)) {
        die('Error: ' . mysql_error());
    }
    echo "1 record added";
} elseif ($intxtmode == 'edit') {
    $sqlUPDATE = "
          update prosedur_iso SET
             departemen = '" . $indepartemen . "' -- varchar(50)
             ,id_parent = '" . $inprocparent . "' -- varchar(160)
             ,prosedur = '" . $inprosedur . "' -- varchar(160)
             ,nama_gambar = '" . $innamafile . "' -- varchar(160)
             ,total_halaman = " . $injmlfile . " -- int(5)
             ,updated = (select curdate()) -- date()
             ,updateby = '" . $_SESSION[$domainApp . "_myname1"] . "' -- varchar(50)
             ,jam = (select curtime()) -- varchar(50)
          WHERE id = '" . $inid . "' -- int(11)
        ";

    //execute query
    if (!mysql_query($sqlUPDATE, $conn)) {
        die('Error: ' . mysql_error());
    }
    echo "1 record updated";
} elseif ($intxtmode == 'delete') {
    //hapus file
    $sql = "SELECT 
                a.id
                , a.nama_gambar
                , a.total_halaman
                FROM prosedur_iso a 
                WHERE a.id = '$inid'";
    $rs = mysql_query($sql);
    $data = mysql_fetch_array($rs);
    for ($idx = 0; $idx < $data['total_halaman']; $idx++) {
        unlink("convert/" . $data['nama_gambar'] . "-" . $idx . ".jpg");
    }

    //echo "delete";
    $sqlDELETE = "delete from prosedur_iso WHERE id = '" . $inid . "'";
//    echo $sqlDELETE;
    //execute query
    if (!mysql_query($sqlDELETE, $conn)) {
        die('Error: ' . mysql_error());
    }
    echo "record has been deleted";
} elseif ($intxtmode == 'getedit') {
    $sql = "SELECT 
                a.id
                , a.departemen
                , a.id_parent
                , a.prosedur
                , a.nama_gambar
                , a.total_halaman
                FROM prosedur_iso a 
                WHERE a.id = '$inid'";
    $result = mysql_query($sql, $conn);
    while ($data = mysql_fetch_array($result, MYSQL_BOTH)) {
        echo "<span id='getID'>" . $data['id'] . "</span>";
        echo "<span id='getDEPARTEMEN'>" . $data['departemen'] . "</span>";
        echo "<span id='getIDPARENT'>" . $data['id_parent'] . "</span>";
        echo "<span id='getPROSEDUR'>" . $data['prosedur'] . "</span>";
        echo "<span id='getNAMAGAMBAR'>" . $data['nama_gambar'] . "</span>";
        echo "<span id='getJMLFILE'>" . $data['total_halaman'] . "</span>";
    }
    mysql_free_result($result);
} else if ($intxtmode == "viewgambar") {
    if ($jmlpage == 1) {
        echo '<a href="#" style="border: black"><img src="convert/' . $namagambar . '.jpg" width="200px" height="200px"/></a>';
    } else {
        for ($idx = 0; $idx < $jmlpage; $idx++) {
            echo '<a href="#" style="border: black"><img src="convert/' . $namagambar . '-' . $idx . '.jpg" width="200px" height="200px"/></a>';
        }
    }
} else if ($intxtmode == "viewgambar2") {
    if ($jmlpage == 1) {
        echo '<div class="background" style="background-image: url(convert/' . $namagambar . '.jpg); background-repeat: no-repeat">
                    <div class="watermark"></div>
                </div>';
//        echo '<img src="convert/' . $namagambar . '.jpg"  style="border: black" border="1"/>';
    } else {
        for ($idx = 0; $idx < $jmlpage; $idx++) {
            echo '<div class="background" style="background-image: url(convert/' . $namagambar . '-' . $idx . '.jpg); background-repeat: no-repeat">
                    <div class="watermark"></div>
                </div>';
//            echo '<img src="convert/' . $namagambar . '-' . $idx . '.jpg"  style="border: black" border="1"/>';
        }
    }
} else if ($intxtmode == "deletejpg") {

    for ($idx = 0; $idx < $jmlpage; $idx++) {
        unlink("convert/" . $namagambar . "-" . $idx . ".jpg");
    }
} else if ($intxtmode == "findparent") {
    $sql = "SELECT a.id, a.id_parent, a.prosedur, a.departemen FROM prosedur_iso a WHERE 1 AND a.departemen = '$indepartemen'";
    $rs = mysql_query($sql);
    echo "<option value=''> - </option>";
    while ($data = mysql_fetch_array($rs)) {
        echo "<option value='" . $data['id'] . "'>" . $data['id'] . " - " . $data['prosedur'] . "</option>";
    }
} else if ($intxtmode == "updateshowtoko") {
    if ($incheck == "1") {
        update_parent($inid, 1);
    } else {
        update_parent($inid, 0);
    }
}


// close connection !!!!
mysql_close($conn)
?>
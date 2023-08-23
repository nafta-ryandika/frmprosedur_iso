<?php
    include("../../connection.php");
    $xrdm = date("YmdHis");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>Form Share Document ISO</title>
    </head>

    <link rel="stylesheet" type="text/css" href="../../theme/south-street/jquery-ui-1.8.13.custom.css">
    <link rel="stylesheet" type="text/css" href="css/demos.css">
    <link rel="stylesheet" type="text/css" href="css/table.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" type="text/css" href="css/frmstyle.css">
    
    <script type="text/javascript" src="../../js/ui/jquery.ui.core.js"></script>
    <script type="text/javascript" src="../../js/ui/jquery.ui.widget.js"></script>
    <script type="text/javascript" src="../../js/ui/jquery.ui.datepicker.js"></script>

    <!-- CALENDAR -->
    <link rel="stylesheet" type="text/css" href="js/calendar/calendar.css">
    <script type="text/javascript" src="js/calendar/calendar.js"></script>

    <!-- MODAL DIALOG -->
    <link rel="stylesheet" type="text/css" href="../../css/jquery-ui.css"/>
    <script type="text/javascript" src="../../js/jquery.js"></script>
    <script type="text/javascript" src="../../js/jquery-ui.js"></script>

    <!-- DATA TABLE -->
    <link rel="stylesheet" type="text/css" href="js/datatables/datatables.css"/>
    <link rel="stylesheet" type="text/css" href="js/datatables/css/fixedColumns.dataTables.min.css"/>
    <script type="text/javascript" src="js/datatables/js/jquery.dataTables.min.js"></script>

    <!-- MASK INPUT -->
    <!-- <script type="text/javascript" src="js/jquery.maskedinput.min.js"></script> -->

    <!-- CHART -->
    <!-- <link rel="stylesheet" type="text/css" href="js/chart/Chart.min.css"/> -->
    <!-- <script type="text/javascript" src="js/chart/Chart.min.js"></script> -->
    <!-- <script type="text/javascript" src="js/chart/Chart.bundle.min.js"></script> -->

    <!-- SEARCHABLE-DROPDOWN -->
    <link rel="stylesheet" type="text/css" href="js/searchable-dropdown/src/selectstyle.css"/>
    <script type="text/javascript" src="js/searchable-dropdown/src/selectstyle.js"></script>

    <script type="text/javascript" src="js/frm1.js?version=<?=$xrdm?>"></script>
    <style>

        #displaySCAN{
            width:600px;
            height:210px;
            border: 13px solid #bed5cd;
            overflow-x: scroll;
            overflow-y: hidden;
            white-space: nowrap;
        }
        #displaySCAN a {
            display: inline-block;
            vertical-align: middle;
        }

        #displaySCAN img {border: 0;}

        #dialog-image{
            /*                background: brown;*/
        }

        .background{
            width: 650px;
            height: 900px;
            margin: 0 auto;
        }

        .watermark{
            background: url("img/watermark.png");
            opacity: 0.25;
            font-size: 3em;
            text-align: center;
            z-index: 0;

            height: 100%
        }
    </style>

    <body>
        <table id="tabelview" width="100%"  border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td>
                    <div id="headerx">
                        <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td colspan="2">
                                    <div align="left">
                                        <span style="font-size: 14px;font:Arial, Helvetica, sans-serif;font-weight: bold;">
                                            Form Share Document ISO
                                        </span>
                                        <hr />
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <INPUT id="cmdadd" class="buttonadd" type="button" name="nmcmdadd" value="Add New" onclick="addnewclick()"/>&nbsp;
                                    <INPUT id="cmdedit" class="buttonedit" type="button" name="nmcmdedit" value="Edit" onclick="editclick()"/>&nbsp;
                                    <INPUT id="cmddelete" class="buttondelete" type="button" name="nmcmddelete" value="Delete" onclick="deleteclick()"/>&nbsp;
                                    <!--<INPUT id="cmdsearch" class="buttonfind" type="button" name="nmcmdsearch" value="Search" onclick="searchclick()">&nbsp;-->
                                </td>
                                <td>
                                    <div align="right" id="xpdf" style="display: none;">
                                        <INPUT id="cmdexport" class="buttonexport" type="button" name="nmcmdexport" value="Export" onclick="exportclick()"/>&nbsp;
                                        <INPUT id="cmdexport" class="buttonexport" type="button" name="nmcmdexport" value="Export Toko" onclick="exportclick_toko()"/>&nbsp;
                                        <select id="exporttype">
                                            <!--<option value="grd">HTML</option>-->
                                            <!--<option value="xls">Excel</option>-->
                                            <!--<option value="csv">CSV</option>-->
                                            <!--<option value="txt">Text</option>-->
                                            <option value="pdf" selected>Pdf</option>
                                        </select>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>
            <tr>
                <td><hr></td>
            </tr>
            <tr>
                <td>
                    <div id="areasearch">
                        <fieldset class="info_fieldset"><legend>Search</legend>
                            <form id="ajax-contact-form" action="#"/>
                            <input type="hidden" id="vxid" value=""/>
                            <table width="100%" align="center"  border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td>Departemen : 
                                        <select id="txtdepartemen" onchange="pilihdepartemen()" data-search="true">
                                            <option value=""> All </option>
                                            <?php
                                            $sql = "select depkode, depnama from rldept order by depnama";
                                            $rs = mysql_query($sql);
                                            while ($data = mysql_fetch_array($rs)) {
                                                echo "<option value='" . $data['depkode'] . "'>" . $data['depnama'] . "</option>";
                                            }
                                            ?>
                                        </select>
                                        &nbsp;&nbsp;&nbsp;
                                        <!--                                            <INPUT id="cmdfind" class="buttongofind" type="button" name="nmcmdfind" value="Find" onclick="findclick()"/>-->
                                    </td>
                                    <td>Parent
                                        <select id="txtprocparent">
                                            <option value="">All</option>
                                            <?php
                                            $sql = "SELECT a.id, a.id_parent, a.prosedur FROM prosedur_iso a WHERE 1 ";
                                            $rs = mysql_query($sql);
                                            while ($data = mysql_fetch_array($rs)) {
                                                echo "<option value='" . $data['id'] . "'>". $data['id'] ." - " . $data['prosedur'] . "</option>";
                                            }
                                            ?>
                                        </select>
                                        &nbsp;&nbsp;&nbsp;
                                    </td>
                                    <td>Judul : 
                                        <input id="txtprosedur" type="text" value="" size="50"/>
                                        &nbsp;&nbsp;&nbsp;
                                    </td>
                                </tr>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                </tr>
                                <tr>
                                    <td>
                                        <div align="left">view : <INPUT id="txtperpage" class="textbox" type="text" name="txtperpage" value="100" onkeydown="enterfind(event)"/></div>
                                    </td>
                                    <td>
                                        <div align="center">
                                            <INPUT id="cmdback" class="buttonback" type="button" name="nmcmdback" value="Prev" onclick="prevpage()"/>
                                            <INPUT id="txtpage" class="textbox" type="text" name="nmtxtpage" value="1"/>
                                            <INPUT id="cmdnext" class="buttonnext" type="button" name="nmcmdnext" value="Next" onclick="nextpage()"/>
                                        </div>
                                    </td>
                                    <td>
                                        <div align="right">
                                            <table>
                                                <tr>
                                                    <td>
                                                        <INPUT id="cmdfind" class="buttongofind" type="button" name="nmcmdfind" value="Find" onclick="findclick()">
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                            </form>
                        </fieldset>
                        <table id="tabelviewer" width="100%" align="center"  border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td>
                                    <div id="frmloading" align="center">
                                        <img src="img/ajax-loader.gif" />
                                    </div>
                                    <div id="frmbody">
                                        <div id="frmcontent">
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>
        </table>
        <div id="view-dialog">
            <div id="dialog-image"></div>
        </div>
        <table id="tabelinput" width="100%"  border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td>
                    <input id="intxtmode" name="innmmode" type="hidden" value=""/>Mode: <span id="mode"></span>
                </td>
            </tr>
            <tr>
                <td><hr></td>
            </tr>
            <tr>
                <td>
                    <div id="areaedit" style="display:none"></div>
                    <div id="areainput">
                        <fieldset class="info_fieldset"><legend>Form Input Prosedur</legend>
                            <!--	<form id="ajax-contact-form" action="#">-->
                            <table width="100%"  border="1" cellspacing="0" cellpadding="5">
                                <tr>
                                    <td>Departemen :
                                        <select id="indepartemen" onchange="findparent()">
                                            <option value="">Pilih departemen</option>
                                            <?php
                                            $sql = "select depkode, depnama from rldept order by depnama";
                                            $rs = mysql_query($sql);
                                            while ($data = mysql_fetch_array($rs)) {
                                                echo "<option value='" . $data['depkode'] . "'>" . $data['depnama'] . "</option>";
                                            }
                                            ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Parent :
                                        <select id="inprocparent">
                                            <option value="0"> - </option>
                                            <?php
                                            $sql = "SELECT a.id, a.id_parent, a.prosedur FROM prosedur_iso a WHERE 1 ";
                                            $rs = mysql_query($sql);
                                            while ($data = mysql_fetch_array($rs)) {
                                                echo "<option value='" . $data['id'] . "'>" . $data['id']." - ".$data['prosedur'] . "</option>";
                                            }
                                            ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <input id="inid" type="hidden" value=""/>
                                        <iframe name="upload-frame" id="upload-frame" style="display:none;"></iframe>
                                        <form id="formupload" name="formupload" method="POST" enctype="multipart/form-data" action="upload.php" target="upload-frame" onsubmit="startUpload();">
                                            Pdf File : <input id="picture" name="picture" type="file" onchange="submitclick()" /> ( Only File Pdf )
                <!--                        <input type="submit" id="tbupload" name="upload" value="Upload" style="cursor: pointer;" />-->
                <!--                        <input type="button" value="Cancel" onclick="cancel_gambar()" style="cursor: pointer;" />-->
                                        </form>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center">
                                        <input id="namafilebefore" type="hidden" value=""/>
                                        <input id="jmlfilebefore" type="hidden" value=""/>
                                        <input id="txtnamafile" type="hidden" value=""/>
                                        <input id="txtjmlfile" type="hidden" value=""/>
                                        <div id="displaySCAN">

                                        </div>
                                        <!--                                            <div id="uploaded-picture" style="border:5px black inset;width: 175px;height: 200px;">
                                                                                         div tempat photo yang telah diupload ditampilkan 
                                                                                    </div>-->
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Judul :
                                        <input id="inprosedur" type="text" value="" size="70" maxlength="160"/>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        <div align="center">
                                            <INPUT id="cmdsave" class="buttonadd" type="button" name="nmcmdsave" value="Save" onclick="saveclick()"/>
                                            <INPUT id="cmdcancel" class="buttondelete" type="button" name="nmcmdcancel" value="Cancel" onclick="cancelclick()"/>
                                        </div>
                                    </td>

                                </tr>
                            </table>
                            <!--        </form>-->
                        </fieldset>      
                    </div>
                </td>
            </tr>
        </table>

    </body>
</html>

<?php
    mysql_close($conn);
?>
                                                                                                                                
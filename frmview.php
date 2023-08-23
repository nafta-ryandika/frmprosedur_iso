<?php
include "../../connection.php";

if (isset($_POST['txtpage'])) {
    $txtpage = $_POST['txtpage'];
} else {
    $txtpage = 1;
}
if (isset($_POST['txtperpage'])) {
    $txtperpage = $_POST['txtperpage'];
} else {
    $txtperpage = 5;
}

$offset   = ($txtpage - 1) * $txtperpage;
$sqlLIMIT = " LIMIT $offset, $txtperpage";
$sqlWHERE = " ";

if (isset($_POST['txtid'])) {
    if ($_POST['txtid'] != '') {
        $txtid    = $_POST['txtid'];
        $sqlWHERE = $sqlWHERE . " and id = '%$txtid%' ";
    }
}

if (isset($_POST['txtdepartemen'])) {
    if ($_POST['txtdepartemen'] != '') {
        $txtdepartemen = $_POST['txtdepartemen'];
        $sqlWHERE      = $sqlWHERE . " and a.departemen = '$txtdepartemen' ";
    }
}
if (isset($_POST['txtprocparent'])) {
    if ($_POST['txtprocparent'] != '') {
        $txtprocparent = $_POST['txtprocparent'];
        $sqlWHERE      = $sqlWHERE . " and a.id_parent = '$txtprocparent' ";
    }
}

if (isset($_POST['txtprosedur'])) {
    if ($_POST['txtprosedur'] != '') {
        $txtprosedur = $_POST['txtprosedur'];
        $sqlWHERE    = $sqlWHERE . " and a.prosedur like '%$txtprosedur%' ";
    }
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <title>Form View</title>
  </head>
  <body>
    <table width="100%"  border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td>
          <div id="frmisi">
            <table id="myTable" class="table">
              <thead>
                <tr>
                  <th>No</th>
                  <th>...</th>
                  <th>Departemen</th>
                  <th>Parent ID</th>
                  <th>Judul</th>
                  <th>View</th>
                </tr>
              </thead>
              <tbody>
              <?php
                $sqlCOUNT = "SELECT
                            id, departemen, prosedur, total_halaman, depnama
                            FROM prosedur_iso a
                            LEFT JOIN rldept b ON a.departemen = b.depkode
                            WHERE 1 ".$sqlWHERE;
                $result = mysql_query($sqlCOUNT);
                $count  = mysql_num_rows($result);
                mysql_free_result($result);

                $sqlORDER = "ORDER BY a.id DESC";
                $sql = "SELECT
                        id, departemen, id_parent, prosedur, nama_gambar, total_halaman, depnama
                        FROM prosedur_iso a
                        LEFT JOIN rldept b ON a.departemen = b.depkode
                        WHERE 1 ".$sqlWHERE . $sqlORDER . $sqlLIMIT;
                $result = mysql_query($sql);
                
                $jumPage = ceil($count / $txtperpage);

                if ($count > 0) {
                  $row = $offset;
                  while ($data = mysql_fetch_array($result, MYSQL_BOTH)) {
                    $row += 1;
              ?>
                <tr onMouseOver="this.className='highlight'" onMouseOut="this.className='normal'">
                  <td><?php echo $row; ?></td>
                  <td><?php echo "<input id='chk'" . $data['id'] . " type='checkbox' name='chk'" . $data['id'] . " value='" . $data["id"] . "' >"; ?></td>
                  <td><?php echo $data["depnama"]; ?></td>
                  <td><?php echo $data["id_parent"]; ?></td>
                  <td><?php echo $data["prosedur"]; ?></td>
                  <td align="center">
                    <input type="button" class="btnView" value="View" onclick="openImage('<?php echo $data['nama_gambar'] ?>','<?php echo $data['total_halaman'] ?>')"/>
                  </td>
                </tr>
              <?php
                  }
                mysql_free_result($result);
                }
              ?>
              
              </tbody>
            </table>
          </div>
        </td>
      </tr>
      <tr>
        <td>
          <table width="100%"  border="0" cellspacing="0" cellpadding="0" class="info_fieldset">
            <tr>
              <td>
                <div align="left">
                  <input id="jumpage" name="nmjmlrow" type="hidden" value="<?php echo $jumPage; ?>">Records: <?php echo ($offset + 1); ?> / <?php echo $row; ?> of <?php echo $count; ?> 
                </div>
              </td>
              <td>
                <div align="right">
                  <?php
                    echo "Page [ ";

                      for ($page = 1; $page <= $jumPage; $page++) {
                          if ((($page >= $noPage - 3) && ($page <= $noPage + 3)) || ($page == 1) || ($page == $jumPage)) {
                              if (($showPage == 1) && ($page != 2)) {
                                  echo "...";
                              }

                              if (($showPage != ($jumPage - 1)) && ($page == $jumPage)) {
                                  echo "...";
                              }

                              if ($page == $noPage) {
                                  echo " <b>" . $page . "</b> ";
                              } else {
                                  echo " <a href='#' onClick='showpage(" . $page . ")'>" . $page . "</a> ";
                              }

                              $showPage = $page;
                          }
                      }

                    echo " ] ";
                  ?>
                </div>
              </td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
    <form id="formexport" name="nmformexport" action="export.php" method="post" onsubmit="window.open ('', 'NewFormInfo', 'scrollbars,width=730,height=500')" target="NewFormInfo">
      <input id="txtSQL" name="nmSQL" type="hidden" value="<?php echo $sql; ?>"/>
    </form>
  </body>
</html>

<?php
mysql_close($conn);
?>

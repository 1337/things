<?php
    require_once(".things.php"); // "require login" implementation
    CheckAuth ("administrative privilege");

    
    $dbn = SERVER_DB; // $dbn="blland_canadensis";
    $q2 = "SHOW TABLES FROM " . $dbn;
    $qx2= mysql_query($q2) or die(mysql_error());    
    
    while ($qr2 = mysql_fetch_row($qx2)) {
?>
        <table style="width:100%;">
            <tr>
                <td>
                    <h2>
                        <a href='<!--root-->things/tools/gexec.php?q=SELECT * FROM <?php echo $qr2[0]; ?>'>
                            <?php echo $dbn; ?>/<?php echo $qr2[0]; ?></a>
                    </h2>
                </td>
            </tr>
            <tr>
                <td>
<?php
                $tbn=$qr2[0];
         
                $qx3 = mysql_query("SHOW COLUMNS FROM $tbn") or die (mysql_error ());
             
                if (mysql_num_rows($qx3) > 0) {
?>
                    <table style='width:100%'>
                        <tr>
                            <th>Field</th>
                            <th>Type</th>
                            <th>Null</th>
                            <th>Key</th>
                            <th>Default</th>
                            <th>Extra</th>
                        </tr>
<?php
                    while ($qr3 = mysql_fetch_assoc($qx3)) {
                        echo("<tr>
                                <td><a href='<!--root-->things/tools/gexec.php?q=SELECT " . $qr3['Field'] . " FROM $tbn'>" . 
                                 $qr3['Field'] . "</a><td>" . $qr3['Type'] . "<td>" . $qr3['Null'] . "<td>" .
                                 $qr3['Key'] . "<td>" . $qr3['Default'] . "<td>" . $qr3['Extra'] . "</tr>");
                    }
                    echo("<tr><td>&nbsp;</td></tr><tr><th>Num Fields</th><td>" . mysql_num_rows($qx3) . "</td></tr>");
                    echo("<tr><th>Num Records</th><td>" . mysql_num_rows(mysql_query("SELECT * FROM $tbn")) . "</td></tr>");
                 
                    mysql_free_result($qx3);
                    echo("</table>");
                }
?>
                </td>
            </tr>
        </table>
<?php
    }

    render (array('title' => 'Graphical database viewer'));
?>
<?php
    require_once(".things.php"); // "require login" implementation
    CheckAuth ();
    
    $dbn="blland_canadensis";
    $q2 = "SHOW TABLES FROM " . $dbn;
    $qx2= mysql_query($q2) or die(mysql_error());       
    while ($qr2 = mysql_fetch_row($qx2)) {
        echo "<table><tr><td><h4><a href='.gexec.php?q=SELECT * FROM " . $qr2[0] . "'>$dbn/" . $qr2[0] . "</a></h4>";
        $tbn=$qr2[0];
    
        $qx3 = mysql_query("SHOW COLUMNS FROM $tbn") or die(mysql_error());
        
        if (mysql_num_rows($qx3) > 0) {
            echo("<table style='font-size:11px;width:600px;'>
                 <tr>
                    <th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>");
            while ($qr3 = mysql_fetch_assoc($qx3)) {
                echo("<tr>
                        <td><a href='.gexec.php?q=SELECT " . $qr3['Field'] . " FROM $tbn'>" . 
                         $qr3['Field'] . "</a><td>" . $qr3['Type'] . "<td>" . $qr3['Null'] . "<td>" .
                         $qr3['Key'] . "<td>" . $qr3['Default'] . "<td>" . $qr3['Extra'] . "</tr>");
            }
            echo("<tr><th>&nbsp;</th></tr><tr><th>Num Fields</th><td>" . mysql_num_rows($qx3) . "</td></tr>");
            echo("<tr><th>Num Records</th><td>" . mysql_num_rows(mysql_query("SELECT * FROM $tbn")) . "</td></tr>");
            
            mysql_free_result($qx3);
            echo("</table>");
        }
        echo("</td></tr></table>");
    }

    page_out(array('title' => 'Graphical database viewer',
                  'titleextras' => "<a href='$admin_page'>Menu</a>"));
?>
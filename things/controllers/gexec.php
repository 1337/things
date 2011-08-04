<?php
    require_once('.things.php');
    CheckAuth (3);
 
    if(($_POST && isset ($_POST['submit']) && isset ($_POST['q'])) || isset ($_GET['q'])) {
     
        $q1 = isset ($_POST['q']) ? $_POST['q'] : $_GET['q'];
        $q1 = stripslashes($q1); //causing problems before
     
        if ($qx1=mysql_query($q1)) {
            println("Success: " . $q1,$win);
            if($qx1 && stripos($q1,"SELECT")==0) {
                echo("<table class='stripeme'>");
                $rc1=0;
                while ($qr1=mysql_fetch_array($qx1)) {
                    echo("<tr>");
                    foreach ($qr1 as $qk1 => $qv1) {
                        if(!is_numeric($qk1)) {
                            if($rc1==0) { // index 0
                                echo("<td style='padding:2px;font-size:10px;'><b>$qk1</b><br />" . htmlspecialchars ($qv1) . "</td>");
                            } else { // index 1+
                                echo "<td style='padding:2px;font-size:10px'>" . htmlspecialchars ($qv1) . "</td>"; } } }
                    $rc1++;
                    echo("</tr>"); }
                echo("</table>"); }
        } else {
            println("Error! " . mysql_error(),$fail); }
    } else {
        println("Type a valid query.",$fail); }
?>
    <form method="post" action="">
        <fieldset>
            <input type="text" name="q" style="width:580px;" value="<?php if(isset ($q1)) {echo($q1);} ?>" />
            <input type="submit" name="submit" value="Submit" />
        </fieldset>
    </form>
<?php
    render (array('title'=>"GExec"));
?>
<?php
    require_once ('.things.php');
    require_once (PROOT . 'lib/strings.php');
    CheckAuth (); // require a login. --> $user is available to you.
                                       // $gp   is available to you.
    $headers = '
    <style type="text/css">
        html {
            font-family: sans-serif;
        }
        body *, table, table th, table tr, table td {
            font-size: 12px;
        }
        fieldset {
            border: 0;
        }
        table td {
            border: 1px silver solid;
        }
        table td {
            padding-bottom:18px;
            vertical-align:top;
        }
    </style>';
?>
    <form method="post">
        <fieldset>
            <input type="hidden" name="hierarchy_add" value="1" />
            Make #
            <input type="text" name="parent_oid" value="<?php echo($gp->Get('parent_oid')); ?>"/>
            the parent of #
            <input type="text" name="child_oid" value="<?php echo($gp->Get('child_oid')); ?>"/>
            <input type="checkbox" name="delete" value="1" /> Delete
            <input type="submit" value="Go" />
        </fieldset>
    </form>
    <form method="post">
        <fieldset>
            <input type="hidden" name="new_object" value="1" />
            New object Name:
            <input type="text" name="name" value="<?php echo($gp->Get('name')); ?>" />
            Type:
            <input type="text" name="type" value="<?php echo($gp->Get('type')); ?>" />
            <input type="submit" value="Go" />
        </fieldset>
    </form>
    <form method="post">
        <fieldset>
            <input type="hidden" name="set_property" value="1" />
            Set object property
            <input type="text" name="prop" value="<?php echo($gp->Get('prop')); ?>" />
            for object #
            <input type="text" name="oid" value="<?php echo($gp->Get('oid')); ?>" />
            to be 
            <textarea name="value"><?php echo($gp->Get('value')); ?></textarea>
            <input type="submit" value="Go" />
        </fieldset>
    </form>
    <form method="post">
        <fieldset>
            <input type="hidden" name="delete_property" value="1" />
            Delete property named
            <input type="text" name="prop" value="<?php echo($gp->Get('prop')); ?>" />
            of object #
            <input type="text" name="oid" value="<?php echo($gp->Get('oid')); ?>" />
            <input type="submit" value="Go" />
        </fieldset>
    </form>
    <form method="post">
        <fieldset>
            <input type="hidden" name="delete_object" value="1" />
            Delete object #
            <input type="text" name="oid" value="<?php echo($gp->Get('oid')); ?>" />
            <input type="submit" value="Go" />
        </fieldset>
    </form>
    <table>
        <tr>
            <th>ID</th>
            <th>Type</th>
            <th>Props</th>
            <th>Parents / Children</th>
<?php
    
    if (isset ($_POST['hierarchy_add'])) {
        $a = $_POST['parent_oid'];
        $b = $_POST['child_oid'];
        if ($a > 0 && $b > 0) {
            $aobj = new Thing ($a);
            if (isset ($_POST['delete']) && $_POST['delete'] == 1) {
                var_dump ($aobj->DelChildren (array ($b)));                
            } else {
                var_dump ($aobj->SetChildren (array ($b)));
            }
        }
    }
    
    if (isset ($_POST['new_object'])) {
        $a = $_POST['name'];
        $b = $_POST['type'];
        $b_id = GetTypeId ($b);
        try {
            if (strlen ($a) > 0 && !is_null ($b_id)) {
                $aobj = new $b(-$b_id); // of NEW_type
                $aobj->SetProps (array ('name'=>$a)); // set object properties
                var_dump ($aobj);
            }
        } catch (Exception $e) {
            die ($e->getMessage());
        }
    }
    
    if (isset ($_POST['delete_property'])) {
        $a = $_POST['oid'];
        $b = $_POST['prop'];
        if ($a > 0) {
            $aobj = new Thing ($a);
            $aobj->DelProps (array($b)); // boom...
        }
    }

    if (isset ($_POST['delete_object'])) {
        $a = $_POST['oid'];
        if ($a > 0) {
            $aobj = new Thing ($a);
            $aobj->Destroy (); // boom...
        }
    }
    
    if (isset ($_POST['set_property'])) {
        $a = $_POST['oid'];
        $b = $_POST['prop'];
        $c = $_POST['value'];
        if ($a > 0 && strlen ($b) > 0) {
            $aobj = new Thing ($a);
            $aobj->SetProps (array ($b=>$c)); // set object properties
            var_dump ($aobj);
        }
    }
    
    $a = new Things ();
    $a->SetType (all_objects);
    foreach ($a->SetObjects ('ORDER BY `oid` DESC') as $b) {
        $bobj = new Thing ($b);
        $btype = GetTypeName ($bobj->GetType ());
        echo ("<tr>
                <td>
                    <a name='$bobj->oid'>#$bobj->oid</a>
                    <br />
                    <!--form method='post'>
                        <fieldset>
                            <input type='hidden' name='delete_object' value='1' />
                            <input type='hidden' name='oid' value='$b' />
                            <input type='submit' value='x' />
                        </fieldset>
                    </form-->
                </td>
                <td>$btype</td>");
        $p = $bobj->GetProps ();
        if (sizeof ($p) > 0) {
            echo ("<td>");
            foreach ($p as $name=>$value) {
                
                // $value = first (htmlspecialchars ($value));
                $value = first (htmlspecialchars ($value));
                echo ("<b>$name</b>: $value<br />");
                
                /* $a = new AjaxField ($bobj->oid);
                $a->NewTextareaField ($name);
                echo ("<br />"); */
            }
            echo ("</td>");
        }
        
        echo ("<td><b>Parents: </b>");
        $c = $bobj->GetParents ();
        if (sizeof ($c) > 0) {
            foreach ($c as $cc) {
                $ccobj = new Thing ($cc);
                echo (GetTypeName ($ccobj->GetType ()) . " <a href='../object/$cc'>#$cc</a>, ");
            }
        }
        echo ("<br /><b>Children: </b>");
        $c = $bobj->GetChildren ();
        if (sizeof ($c) > 0) {
            foreach ($c as $cc) {
                $ccobj = new Thing ($cc);
                echo (GetTypeName ($ccobj->GetType ()) . " <a href='../object/$cc'>#$cc</a>, ");
            }
        }
?>
                <br />
                <br />
                <!--form method="post">
                    <fieldset>
                        <input type="hidden" name="hierarchy_add" value="1" />
                        <input type="hidden" name="parent_oid" value="<?php echo($b); ?>"/>
                        Add child:
                        <input type="checkbox" name="delete" value="1" /> Delete
                        <br />
                        <input type="text" name="child_oid" value="<?php echo($gp->Get('child_oid')); ?>" style="width: 50px;" />
                        <input type="submit" value="Go" />
                    </fieldset>
                </form-->
            </td>
        </tr>
<?php
    }
        
    /* $ffg = "User";
    $ffh = new $ffg(5); */
?>
    </table>
<?php
    page_out (array ('headers'=>$headers));
?>

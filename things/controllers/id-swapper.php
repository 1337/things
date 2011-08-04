<?php
    require_once ('.things.php');
 
    if ($gp->Has ('swap_from') && $gp->Has ('swap_to')) {
        if (ObjectExists ($gp->Get ('swap_from'))) {
            $a = new Thing ($gp->Get ('swap_from'));
            if ($a->ChangeID ($gp->Get ('swap_to'))) {
                println ("woot", $win);
            } else {
                println ("meh", $fail);
            }
        }
    }
 
?>
    <h1>Change object ID.</h1>
    <form method="post">
        <fieldset>
            From: <br />
            <input type="text" name="swap_from" id="swap_from"><br />
            To: <br />
            <input type="text" name="swap_to" id="swap_to"><br />
            <input type="submit">
        </fieldset>
    </form>
<?php
    render ();
?>
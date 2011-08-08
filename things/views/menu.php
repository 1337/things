<?php
    require_once ('.things.php');
    CheckAuth (); // require a login. --> $user is available to you.
 
    println ("Your Basic Information", 2);
    $a = new AjaxField ($user->oid);
    $a->NewTextField ('name', 'User Name', true);
?>
    <!-- controls.Text.email, name="Email" -->
    <br />
    <!-- controls.Text.realname, name="Real name" -->
    <!-- controls.Text.website, name="Web site" -->
    <br />
    <!-- controls.Text.age, name="Age" -->
    <!-- controls.Text.picture, name="Profile picture" -->
    <hr />
 
<?php
    println ("More about you", 2);
    $a->NewTextareaField ('aboutme', 'Introduce yourself', false, 'width:100%;max-width:450px;height:100px;');
?>
    <hr />
    <table style="width:100%">
        <tr>
            <td style="width:50%">
<?php
                println ("Your Posts", 2);
                println ('<a href="<!--root-->new/post">Write a new post</a>');
                $posts = new Paginate (array (
                    'objects' => $user->GetChildren (POST, "`child_oid` DESC"),
                    'control_suffix' => '_post'
                ));
                if (sizeof ($posts) > 0) {
                    echo ('<ul>');
                    foreach ($posts->GetObjects () as $post_id) {
                        $post = new Post ($post_id);
                        printf ("<li>
                            <a href='<!--root-->edit/post/%s'>%s</a>
                        </li>",
                        $post->oid,
                        $post->GetTitle ());
                    }
                    echo ('</ul>');
                    echo ($posts->Bar ());
                 
                } else {
                    println ("You have no posts yet. <a href='/new/post/'>Write one?</a>");
                }
?>
            </td>
            <td style="width:50%">
                <h2><a href='<!--root-->tickets'>Your Tasks</a></h2>
                <p><a href="<!--root-->new/ticket">File a new task</a> | 
                   <a href="<!--root-->tickets">View all</a></p>
<?php
                $tickets = new Paginate (array (
                    'objects' => $user->GetChildren (TICKET, "`child_oid` DESC"),
                    // 'page_size' => 5,
                    'control_suffix' => '_ticket'
                ));
                if (sizeof ($tickets) > 0) {
                    echo ('<ul>');
                    foreach ($tickets->GetObjects () as $ticket_id) {
                        $ticket = new Ticket ($ticket_id);
                        printf ("<li>
                                <a href='<!--root-->edit/ticket/%s'>%s</a>
                            </li>",
                            $ticket->oid,
                            htmlspecialchars ($ticket->GetProp ('name'))
                        );
                    }
                    echo ('</ul>');
                    echo ($tickets->Bar ());
                 
                } else {
                    println ("You have no tasks yet. <a href='/new/ticket/'>Make one?</a>");
                }
?> 
            </td>
        </tr>
    </table>
    <h2>Options</h2>
    <!-- controls.Text.template, name="Custom template" -->
<?php 
    render (array (
        'title' => 'Menu'
    ));
?>

<?php
    require_once ('.things.php');
    CheckAuth (); // require a login. --> $user is available to you.
    
    $headers = '';
    
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
    echo ("<hr />");
    println ("Your Posts (blog entries)", 2);
    $posts = $user->GetChildren (POST, "`child_oid` DESC LIMIT 10");
    if (sizeof ($posts) > 0) {
        echo ('<ul>');
        foreach ($posts as $post_id) {
            $post = new Post ($post_id);
            printf ("<li>
                <a href='/edit/%s'>%s</a>
            </li>",
            $post->oid,
            $post->GetTitle ());
        }
        echo ('</ul>');
    } else {
        println ("You have no posts yet. <a href='/new'>Write one?</a>");
    }
?>
    <hr />
    <h2>Options</h2>
    <!-- controls.Text.template, name="Custom template" -->
<?php    
    page_out (array ('headers'=>$headers));
?>
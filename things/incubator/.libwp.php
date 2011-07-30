<?php
    /*  libwp
        simulates outputs of various wordpress theme functions.
        this is not compatible with wordpress, wordpress themes, 
        or sparta's templating engine.
        
        to write:
        the_ID();
        the_permalink();
        get_permalink($post->post_parent);
        get_constructor_sidebar();
        edit_post_link(__('Edit', 'constructor'), '', '');
        _e('Archives', 'constructor');
        __('Permanent Link to %s', 'constructor'); http://faq.wordpress.net/view.php?p=50
        wp_list_pages('title_li=' );
        wp_list_categories('title_li=&depth=1&show_count=1');
        wp_get_archives('type=monthly&show_post_count=1');

        written:
        query_posts('cat='.$cat->cat_ID); 
    */
    
    require_once ('.functions.php');
    require_once ('.quicksql.php');
    
    // you can do that?!
    //public function api(/* polymorphic */) {
    //  $args = func_get_args();*/
    
    function query_posts_by_id ($id, $order='ua.`id` ASC', $count=10) {
        global $stories_table, $story_tags_table, $tags_table;
        $n = "SELECT * 
                FROM $stories_table AS ua,
               WHERE ua.id='$id'
            ORDER BY $order
               LIMIT $count"; 
        return asql ($n);
    }

    function query_posts_by_category ($category, $order='ua.id ASC', $count=10) {
        // I believe this is similar to getposts, but returns as array instead.
        global $stories_table, $story_tags_table, $category_tags_table, $categories_table;
        $n = "SELECT ua.*
                FROM `$stories_table` AS ua,
                     `$story_tags_table` AS ub,
                     `$category_tags_table` AS ud,
                     `$categories_table` AS ue
               WHERE ue.category_name='$category'
                 AND ud.category_id=ue.category_id
                 AND ub.tag=ud.tag_id
                 AND ua.id=ub.post_id
            ORDER BY $order
               LIMIT $count";
        //println ($n);
        return asql ($n);
    }
    //print_r (query_posts_by_category ("Site sections"));

    function query_posts_by_tag ($tag, $order='ua.id ASC', $count=10) {
        // I believe this is similar to getposts, but returns as array instead.
        global $stories_table, $story_tags_table, $tags_table;
        $n = "SELECT ua.* 
                FROM $stories_table AS ua,
                     $story_tags_table AS ub,
                     $tags_table AS uc
               WHERE uc.tag='$tag'
                 AND ub.tag=uc.tag_id
                 AND ua.id=ub.post_id
            ORDER BY $order
               LIMIT $count"; 
        return asql ($n);
    }
    
    function query_categories ($order='ua.category_name ASC', $count=10) {
        //defaults to sort alphabetically
        global $categories_table;
        $n = "SELECT ua.*
                FROM $categories_table AS ua
            ORDER BY $order
               LIMIT $count";
        return asql ($n);
    }

    function query_categories_by_count ($order='ue.category_name ASC', $count=10) {
        //defaults to alphabetically
        global $stories_table, $story_tags_table, $tags_table, 
               $category_tags_table, $categories_table;
        $n = "SELECT ue.category_name
                FROM `$stories_table` AS ua,
                     `$story_tags_table` AS ub,
                     `$tags_table` AS uc,
                     `$category_tags_table` AS ud,
                     `$categories_table` AS ue
            ORDER BY $order
               LIMIT $count";
        //println ($n);
        return asql ($n);
    }
    // print_r (query_categories_by_count ());
?>
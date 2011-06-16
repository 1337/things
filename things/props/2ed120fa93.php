<?php
    require_once('.things.php');
?>
    <form style="text-align:center;">
        <textarea id="code" style="width:600px;height:400px;">+++++ +++++             initialize counter (cell #0) to 10
    [                       use loop to set the next four cells to 70/100/30/10
        > +++++ ++              add  7 to cell #1
        > +++++ +++++           add 10 to cell #2 
        > +++                   add  3 to cell #3
        > +                     add  1 to cell #4
        <<<< -                  decrement counter (cell #0)
    ]                   
    > ++ .                  print 'H'
    > + .                   print 'e'
    +++++ ++ .              print 'l'
    .                       print 'l'
    +++ .                   print 'o'
    > ++ .                  print ' '
    << +++++ +++++ +++++ .  print 'W'
    > .                     print 'o'
    +++ .                   print 'r'
    ----- - .               print 'l'
    ----- --- .             print 'd'
    > + .                   print '!'
    > .                     print '\n'
        </textarea>
        <br />
        <input type="button" id="exe" onclick="run()" value="Run!" />
    </form>
    <h2>Rules of the game, according to Wikipedia:</h2>
    <pre>
    &gt;: increment the data pointer (to point to the next cell to the right).
    &lt;: decrement the data pointer (to point to the next cell to the left).
    +: increment (increase by one) the byte at the data pointer.
    -: decrement (decrease by one) the byte at the data pointer.
    .: output a character, the ASCII value of which being the byte at the data pointer.
    ,: accept one byte of input, storing its value in the byte at the data pointer.
    [: if the byte at the data pointer is zero, then instead of moving the instruction pointer forward to the next command, jump it forward to the command after the matching ] command*.
    ]: if the byte at the data pointer is nonzero, then instead of moving the instruction pointer forward to the next command, jump it back to the command after the matching [ command*.
    </pre>
<?php
    page_out(array('title' => 'The JavaScript Brainfuck Interpreter',
                  'headers' => '<script type="text/javascript" src="scripts/brainfuck.js"></script>
                                <script type="text/javascript">
                                    function run () {
                                        var a = new brainfuck (document.getElementById("code").value);
                                        a.run ();
                                        a.out ();
                                        a.reset ();
                                    }  
                                </script>'));
?>
<?php
    require_once('.things.php');
?>
<p>Copy and run it!</p>
<p>(If your SoupScript supports strict mode, please disable 
it)</p>
<p><a href="#Sheet_Music">Sheet Music</a><br />
<a href="#Activate_window_under_mouse">Activate window under 
mouse</a><br />
<a href="#Share_the_love_command_executer">&quot;Share the love&quot; 
command executer</a><br />
<a href="#Mass_shut_down_template">Mass shut down template</a><br />
<a href="#Strings_Example">Strings example</a><br />
<a href="#Multiple_code_hives">Code hives example</a></p>
<h3><a name="Sheet_Music">Sheet Music</a></h3>
<pre>Rem Do Re Mi Music
    i=RGB(0,0,128)
    j=RGB(255,255,200)
<span>Set</span> console center show:1 forecolor:[i] backcolor:[j]
        Set font:Comic_Sans_MS size:15 bold:1 text:
mark q
    cout doe\, a dear\, a female dear
<span>play</span> 2CCCDEEECEECCEEEE
    cout ray\, a drop of golden sun
    play 2DDDEFFEDFFFFFFFF
    cout me\, a name\, I call myself
    play 2EEEFGGGEGGEEGGGG
    cout far\, a long long way to run
    play 2FFFGAAGFAAAAAAAA
    cout sew\, a needle pulling thread
    play 2GGGCDEFGAAAAAAAA
    cout La\, a note to follow sew
    play 2AAADEFGABBBBBBBB
    cout Tea\, I drink with jam and bread
    play 2BBBEFGAB4CCCCCC
    cout That will bring us back to do...oh oh oh
    play 4C2BAAFFBBGG4CC2GGEEDD
    cout \n;goto q
</pre>
<h3><a name="Share_the_love_command_executer">&quot;Share the 
love&quot; command executer</a></h3>
<p>This script allows the client to execute any code 
available in its own library by exposing the DO command to 
the user.</p>
<pre>mark q
cout Enter a command to run it:
i=cin
<span>do [i]</span>
cout call done
goto q</pre>
<h3>Get the mouse coordinate</h3>
<pre>Set my width:300 height:80 visible:1 opacity:80 center top:0 ontop:1 text:MouseCoord
Mark j
    Set console text:
    x=Get CursorX
    y=Get CursorY
    PrintLn x: [x] y: [y]
    Sleep 250
    Goto j</pre>
<h3><a name="Activate_window_under_mouse">Activate window 
under mouse</a></h3>
<pre>Mark beginning

    x=Get cursorx

    y=Get cursory
z=WindowFromPoint([x],[y])
t=Get hwnd:[z] windowText
<span>AppActivate [t]</span>
Sleep 200
Goto beginning</pre>
<h3><a name="Mass_shut_down_template">Mass shut down 
template</a></h3>
<p>This script attempts to shut down all computers on your 
network, given the parameters in the constants.</p>
<pre>#### Consts #### <span>(Note: this line is invalid, so SoupScript skips it.)</span>
string netgroup, <span>(Enter your own network here. Example: MSHOME)</span>
int total,23
int wildcard,2
Sub Main
    set Notheme forecolor:0 backcolor:16777215 center opacity:85 ontop:1
    call Destroy
End

Sub Destroy
    cout Are you sure you want to reboot all 
     computers in the [netgroup] network? \[Y/N\]
    k=cin
    k=ucase([k])
    k2=logic([k],=,Y)
    gt3=iif([k2],GoOn2,Done)
    Goto [gt3]
mark GoOn2
mark BackAgain
    i=math([i],+,1)
    lg=logic([i],&gt;,[total])
    gt=iif([lg],Done,GoOn)
    Goto [gt]
mark GoOn
    bfr1=buffer([wildcard],0)
    bfr2=len([i])
    bfr3=Math(2,-,[bfr2])
    bfr1=left([bfr1],[bfr3])
    lp=join([bfr1],[i])
    string tmp,shutdownEx [netgroup]-[lp]\,0\,0\,1
<span>do</span> [tmp]
        cout Executed [tmp]
    goto BackAgain
mark Done
    breakEx
End Destroy</pre>
<h3><a name="Uses_Example">Uses Example</a></h3>
<pre><span>Uses</span> ASCII
a=z
lg=Logic([a],=,<span>[Char-122]</span>);REM <span>This is how you use the imported ASCII constants</span>
oc=IIf([lg],OMG,NOT)
oc=Join([oc], IDENTICAL!)
cout [oc]
Break</pre>
<pre><span>Uses</span> Environ
cout <span>[UserName]</span> @ <span>[Host]</span>;REM <span>this prints Brian @ TecraM2</span></pre>
<pre><span>Uses</span> colors, showWindow
Set backcolor:<span>[White]</span> forecolor:<span>[Black]</span> show:<span>[Show]</span></pre>
<pre><span>Uses </span>variables
Var Hello,128,<span>[Integer]</span>
Recast Hello,<span>[Long]</span></pre>
<h3><a name="Strings_Example">Strings Example</a> (Morse 
code beeper)</h3>
<p>This example uses a lot of logic to create conditional 
loops.</p>
<pre>uses variables,colors

silver=RGB(127,127,127)
set my notheme center backcolor:[silver] forecolor:[black] opacity:90
mark restartapp
    cout Enter morse code: \(accepts symbols &quot;.&quot;\,&quot;-&quot; and &quot; &quot;\)
    i=cin
mark aag
    accumulator=math([accumulator],+,1)
    ll1=len([i])
    ll1=math([ll1],+,1)
    ll2=logic([accumulator],&gt;=,[ll1])
    ll3=iif([ll2],allover,ll4)
    goto [ll3]
    mark ll4
    2mid=mid([i],[accumulator],1)
    
    rem getting the beep time done here
    3mid=logic([2mid],=,.)
    4mid=iif([3mid],150,0)
    3mid=logic([2mid],=,-)
    4mid=iif([3mid],450,[4mid])
    3mid=logic([2mid],=, )
    4mid=iif([3mid],0,[4mid])
    5mid=logic([4mid],=,0)
    6mid=iif([5mid],100,50)

    print [2mid]
    beep 1000,[4mid]
    sleep [6mid]
    goto aag
mark allover
    cout \ndone!
    var accumulator,0
    goto restartapp</pre>
<h3><a name="Multiple_code_hives">Multiple m<span>odules</span></a></h3>
<p>SoupScript can have multiple code hives, which are, 
essentially, modules. This is how you use it:</p>
<pre>msgbox hi!

[MODULE moooo]<span>
rem Note that MODULE is case sensitive and does not work if you just put [Soup]</span>
msgbox hello!</pre>
<p>The above program only shows a message box, &quot;hi!&quot;, 
because this is the only hive SoupScript is told to run.<br />
To run other modules:</p>
<pre>msgbox hi!
newmodule=get module:moooo <span>Get the code of this module</span>
do [newmodule] <span>and run it</span>

[MODULE moooo]
msgbox hello!</pre>
<p>&nbsp;</p>
<?php
    page_out(array('title' => 'SoupScript: sample code',
                  'titleextras' => '<a href="soupscript.php">(Back to product main page)</a>'));
?>
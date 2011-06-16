<?php
    require_once('.things.php');
?>
<h3>What are functions?</h3>
<p>Functions are what makes SoupScript a functional script. Every line is a function. 
For example:</p>
<pre>MsgBox(Text as string,Optional StyleVal as integer,Optional Title as string) as integer;</pre>
<p>is a built-in function. We can start using it...</p>
<h3><a name="Start_Coding">Start Coding</a></h3>
<p>A sample &quot;Hello World&quot; can be done using the wizard.</p>
<p>All of the following mean the same thing:</p>
<pre>msgbox hello world!
msgbox hello world!;
msgbox(hello world!);</pre>
<p>Explanation:</p>
<ul>
    <li>Under most circumstances, brackets surrounding parameters aren't necessary. 
    That means,<br />
    msgbox Hello! is essentially the same as msgbox(Hello!) </li>
    <li>Semicolons after each line is not necessary, unless you are running under 
    &quot;Strict Encoding&quot;. </li>
</ul>
<h3><a name="Using_Variables">Using Variables</a></h3>
<p>As a beginner, however, you will only need to know the following:</p>
<table style="width: 100%">
    <tr>
        <th>Type</th>
        <th>What is it?</th>
        <th>Examples</th>
    </tr>
    <tr>
        <td>String</td>
        <td>Stores letters and text</td>
        <td>abc123<br />
        Hello world<br />
        I'm constipating.</td>
    </tr>
    <tr>
        <td>Integer</td>
        <td>Stores small numbers.</td>
        <td>0<br />
        1<br />
        255<br />
        -1<br />
        -254</td>
    </tr>
    <tr>
        <td>Long</td>
        <td>Stores large and small numbers.</td>
        <td>0<br />
        1<br />
        24717597<br />
        -798759</td>
    </tr>
    <tr>
        <td>Boolean</td>
        <td>Stores yes or no.</td>
        <td>1<br />
        0<br />
        -1<br />
        True<br />
        False</td>
    </tr>
    <tr>
        <td>Date</td>
        <td>Stores a date and/or a time</td>
        <td>2008/10/07<br />
        9:38</td>
    </tr>
    <tr>
        <td>Variant</td>
        <td>Stores anything</td>
        <td>&nbsp;</td>
    </tr>
</table>
<p>In SoupScript, all variables as accessed at the same speed.</p>
<p>To actually use them, see this example:</p>
<pre>string output,hello world!</pre>
<p>(the above line stores &quot;hello world!&quot; into the string variable called &quot;output&quot;.)</p>
<p>msgbox [output]</p>
<p>(the above line looks for the variable called &quot;output&quot; then putting it into the 
msgbox parameter.</p>
<h3>Converting variables from one type to another (Casting)</h3>
<p>Certain variables don't work if you leave them at variant / string, so you need 
to change them to something more sensible, like longs where appropriate. </p>
<p>In general, you don't need to convert variable types yourself in SoupScript, 
but there is such functionality anyway. Look at
<a href="http://www.kgv.net/blai/projs/soupscript/commands.htm#Variables">Variables 
at Commands</a> help for more details.</p>
<h3><a name="Special_Characters">Special Characters</a></h3>
<p>Certain things (like a two-line message box) don't work without those special 
characters. </p>
<table>
    <tr>
        <th>Syntax</th>
        <th>Definition</th>
    </tr>
    <tr>
        <td>\\n</td>
        <td>Carriage return<br />
        Example: <span>msgbox hello</span><b>\\</b><span>nworld!</span></td>
    </tr>
    <tr>
        <td>\\t</td>
        <td>Tab</td>
    </tr>
    <tr>
        <td>To use a variable</td>
        <td>myWord=Inputbox(Hello to who?)<br />
        msgbox Hello [myWord]!</td>
    </tr>
    <tr>
        <td>To run a string as script</td>
        <td>Do(msgbox Hello world!)</td>
    </tr>
    <tr>
        <td>Using custom functions</td>
        <td>Call subname</td>
    </tr>
</table>
<h3>Recursive functions</h3>
<p>Recursive functions are those which call itself inside itself. For example...</p>
<pre>Sub Main
    call pro
    break
End

Sub pro
    cout loop [tt]
    tt=Math([tt],+,1)
<span>  call pro</span>
End pro,[tt]</pre>
<p>You can see how pro just called itself inside its own function. This will cause 
the function to loop and repeat. This is useful for situations where you find repeating 
tasks necessary.</p>
<ul>
    <li>The above example stops at 270. Why?<br />
    SoupScript supports a maximum of 270 recursions. After that, the loop exits 
    and goes on to the next stop (break). </li>
</ul>
<?php
    page_out(array('title' => 'SoupScript: getting started',
                  'titleextras' => '<a href="soupscript.php">(Back to product main page)</a> | <a href="#Start_Coding">Start Coding</a> | <a href="#Using_Variables">Using Variables</a> | <a href="#Special_Characters">Special Characters</a>'));
?>
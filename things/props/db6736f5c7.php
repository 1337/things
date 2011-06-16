<?php
    require_once('.things.php');
    println("Warning: Graffiti 4 is discontinued.",$fail);
?>
<p style="text-align: center">
<img alt="Graffiti 4" src="photos/goodies/img9.jpg" /></p>
<h2>Draw without talent.</h2>
<p>Tools like these must have their uses :)</p>
<h3><a name="Download">Download</a></h3>
<p style="text-align: center"><a href="Graffiti402.zip">Download</a> Version 4.02
<a href="#4.01">(bug fix info)</a></p>
<h3><a name="Samples">Samples</a></h3>
<h3 style="text-align: center"><img alt="" src="photos/goodies/img4.jpg" /><br />
Drawn with <a href="Graffiti401.zip">Graffiti 4</a></h3>
<h3 style="text-align: center"><img alt="" src="photos/goodies/img16.jpg" /><br />
Drawn with <a href="Graffiti.zip">Graffiti 3</a></h3>
<h3 style="text-align: center"><img alt="" src="photos/goodies/img15.jpg" /><br />
Drawn with Graffiti 2</h3>
<h3 style="text-align: center"><img alt="" src="photos/goodies/img14A.jpg" /><br />
Drawn with Graffiti 1</h3>
<h3><a name="Tips">Tips</a></h3>
<img alt="web-safe colours" class="dimg" height="154" src="photos/goodies/img2.jpg" style="float: right;" width="150" />
<ul>
    <li><strong>Graffiti only draws in Web-safe colours.<br />
    </strong>If you give it a full-colour picture, Graffiti will reduce its number 
    of colours first.<strong><br />
    </strong>To draw better looking graffitis, safe your file to a .gif file with 
    only 216 colours. To do that in PhotoShop:<ul>
        <li>Go to <em>File </em>&gt; <em>Save As</em>...</li>
        <li>in <em>Format</em> dropdown menu, choose <em>Compuserve GIF</em></li>
        <li>Click <em>Save</em></li>
        <li>in <em>Indexed Color</em> window, choose <em>Web</em> palette</li>
        <li>Click <em>OK</em>.</li>
        <li>in <em>GIF Options</em> window (if it comes up): click <em>OK</em>.</li>
    </ul>
    </li>
    <li>Choose a plain background.</li>
    <li>Save images at fractions of the full drawing canvas. The table below shows 
    you the best sizes Graffiti draws in.</li>
</ul>
<table style="margin: 0 auto; border: 1px silver solid">
    <tr>
        <td>Size </td>
        <td>Dimension </td>
    </tr>
    <tr>
        <td>Full size (1:1) </td>
        <td>576*266 pixels </td>
    </tr>
    <tr>
        <td>Half size (1:2) </td>
        <td>288*133 pixels </td>
    </tr>
    <tr>
        <td>Quarter size (1:4) </td>
        <td>144*67 pixels </td>
    </tr>
</table>
<h3><a name="Changes">Changes</a></h3>
<p>Version <a name="4.02">4.02</a></p>
<ul>
    <li>This is a fix to counter facebook graffiti app's measure to stop this program 
    from functioning.</li>
    <li>Thanks Ryan Jones for reporting the change!</li>
    <li>Please submit future problems at the <a href="../php/">php bulletin board</a>.</li>
</ul>
<p>Version <a name="4.01">4.01</a></p>
<ul>
    <li>This is a fix to Graffiti only drawing in black and white on Windows 7 with 
    user account control on.</li>
    <li>This is a fix to Graffiti behaving strangely on other systems because the 
    facebook graffiti interface has changed slightly.</li>
    <li>Please submit future problems at the <a href="../php/">php bulletin board</a>.</li>
</ul>
<p>Version 4</p>
<ul>
    <li>Improved drawing algorithm speeds up drawing of straight lines. by very, 
    very much.</li>
    <li>Drawing canvas increased to full size. (It doesn't always mean your computer 
    can handle drawing full size, though.)</li>
    <li>Change pausing time at the middle of painting.</li>
    <li>Some more fixes under the hood... nobody gives anyway?</li>
    <li>Graffiti is discontinued.</li>
</ul>
<p>Version 3</p>
<ul>
    <li>Wizards.</li>
    <li>Automatic colour selection, canvas location and brush size changes.</li>
</ul>
<h3><a name="FAQ">FAQ</a></h3>
<ol>
    <li>Youtube comments: <strong>this doesn't work on my mac, is there a reason 
    for this?<br />
    </strong>Two reasons. One of them being &quot;this is for Windows&quot;, and the other 
    being &quot;even if you use Windows, you won't be smart enough to use it anyway.&quot; 
    I hope your question has been well answered. Have a great day.</li>
    <li><strong>It is only drawing in one colour.</strong><br />
    This is fixed in 4.02.</li>
    <li><strong>It isn't working.</strong><br />
    Do it again. I've had too many of you reporting errors caused by <strong>not 
    doing things exactly as said</strong>.</li>
    <li><strong>It still isn't working.</strong><br />
    I have not yet come across this situation; if it happens, email me.</li>
    <li><strong>I'd like a new feature.</strong><br />
    It won't happen any time soon. Feel free to email me about it... and I'll add 
    it whenever.</li>
    <li><strong>Where's the old version?</strong><br />
    The old one is <a href="Graffiti.zip">here</a>.</li>
</ol>
<?php
    page_out(array('title' => 'Graffiti 4',
                  'titleextras' => '<a href="#Download">Download</a> | <a href="#Samples">Samples</a> |
                                    <a href="#Changes">Changes</a> | <a href="#Tips">Tips</a> | <a href="#FAQ">FAQ</a>'));
?>
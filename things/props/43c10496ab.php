<?php
    require_once('.things.php');
?>
<img alt="SoupScript" height="486" src="photos/goodies/img18.jpg" style="display: block; margin: 0 auto" width="609" />
<p>SoupScript is a scripting engine which also happens to be able to compile 
your script, making it a versatile tool for task scheduling, software deployment, 
or just coding for fun.</p>
<h3>Possible uses</h3>
<ul>
<li>Making your own software (Drag controls around, What You See is 
What You Get.)</li>
<li>Automation. SoupScript is fully compatible with Windows Task Scheduler 
on all versions of Windows.</li>
<li>Hacking games.</li>
<li>Making bots.</li>
<li>Repeating the same thing over and over with your mouse. (We have 
a macro recorder!)</li>
<li>Pranking people.</li>
<li>Scientific calculations.</li>
</ul>
<p>We <strong>guarantee</strong> that making your first software will be 
even faster than visual basic.</p>
<p><a href="SoupScript.zip">Download</a></p>
<h3>Version 3.62</h3>
<ul>
<li>&quot;Record a new macro&quot; is now available on the wizard window.</li>
<li>Defailts to &quot;Record mouse movements&quot; when you record macros.</li>
</ul>
<h3>Version 3.6</h3>
<ul>
<li>Turns out you really need Admin privileges to make SoupScript work.</li>
<li>&quot;Stop All&quot; really stops all running SoupBase processes.</li>
<li>Weird tab sequencing on the wizard window is fixed.</li>
<li>Focus stays on code window after an exe is launched</li>
<li>Backspacing at a tab actually deletes a tab.</li>
<li>First signs of visual development. You can create GUIs with the 
Interface creator now.</li>
<li>You can also load that same GUI over and over again.</li>
<li>Automatic updates can be turned off.</li>
<li>DRM does not exist.</li>
<li>Several code changes.<ul>
<li><strong>Get </strong>my/username is now <strong>UserName</strong>.</li>
<li>Instead of <strong>\t</strong>, you'll need to <strong>\\t</strong> 
for a tab. Same applies to symbols requiring a backslash.</li>
<li><strong>GetWindowText</strong>, <strong>GetConsoleText</strong></li>
</ul>
</li>
</ul>
<h3>Version 3.5</h3>
<ul>
<li>Macro recording with options.</li>
<li>Automatic removal of text on the console that may reduce code speed.
<strong>Set </strong>trace now responds to value -1. Unless flag -1 
is supplied, the console will always clear text from the console once 
the length of text reaches 60000 characters.</li>
<li>Control how many updates you get.</li>
<li>Run multiple scripts at the same time when you click <strong>Run</strong>.</li>
<li><strong>Include</strong> is renamed <strong>RunScript</strong>.</li>
<li><strong>GetSetting </strong>and <strong>SaveSetting </strong>are 
rendered obsolete by <strong>Get</strong> Setting and <strong>Set
</strong>Setting</li>
<li><strong>Get </strong>bool, boolean, double, integer, long, string 
and date are now obsolete (<strong>CBool</strong>,<strong>CDate,CDbl,CInt,CLng, 
CStr</strong> have always existed)</li>
<li><strong>Get </strong>backcolor and forecolor now return different 
values depending on hwnd supplied</li>
<li><strong>Get </strong>cursorx and <strong>Get </strong>cursory are 
not obsolete. Use <strong>Get</strong> cursor/x and <strong>Get</strong> 
cursor/y instead.</li>
<li><strong>Get</strong> desktop is replaced by <strong>Get</strong> 
hwnd/desktop</li>
<li><strong>Get </strong>fontsize, forecolor, bold, italic, underline 
and strikethru are replaced by <strong>Get </strong>hwnd/? font/color, 
etc.</li>
<li><strong>Get </strong>foregroundwindow will be replaced by
<strong>Get</strong> hwnd/topmost.</li>
<li><strong>Get </strong>productname,<strong> </strong>version, vermajor, 
verminor and verrevision are replaced by <strong>Get</strong> app/name 
and <strong>Get</strong> app/version, <strong>Get</strong> my/ver/major, 
and so on.</li>
<li><strong>Get </strong>my opacity is removed. <strong>Get</strong> 
my window/opacity returns the percentage opacity instead of a boolean 
value <strong>Get </strong>my opacity did before.</li>
<li><strong>Get </strong>username is now <strong>Get </strong>my/username.
</li>
</ul>
<h3>Version 3.3</h3>
<ul>
<li>Added dedicated immediate window</li>
<li>Changed &quot;insert snipplets&quot; and new &quot;environmental variables&quot; to 
the bottom of the main window</li>
<li>Supports new functions Pause, WaitForMouse and extra Uses constants</li>
<li>Window border style can now be changed with <strong>set my border:normal</strong> 
and <strong>set my border:toolbox</strong></li>
</ul>
<h3>Future changes</h3>
<ul>
<li>Make a new &quot;If&quot; operator.</li>
<li>Cascaded, offline help.</li>
<li>Make functions more cascaded.</li>
</ul>
<h3>References</h3>
<p><a href="soupscript-cmd.php">Commands (Functions)</a></p>
<p><a href="soupscript-use.php">Constants</a></p>
<p><a href="soupscript-smp.php">Sample Code</a></p>
<?php
    page_out(array('title' => 'SoupScript',
                  'titleextras' => '<a href="SoupScript.zip">Download</a> | <a href="soupscript-str.php">Get Started</a> | <a href="soupscript-cmd.php">Commands</a> | <a href="soupscript-use.php">Constants</a> | <a href="soupscript-smp.php">Sample Code</a>'));
?>
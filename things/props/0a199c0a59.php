<?php
    require_once('.things.php');
?>
<p>The <strong>uses</strong> function helps the programmer declare 
various standard constants. That declaration routine is as follows:</p>
<pre>If you declare  &quot;color&quot;, &quot;colour&quot;, &quot;colors&quot;, &quot;colours&quot;, &quot;clrs&quot; 'colors
    &quot;3DDKShadow&quot;, vb3DDKShadow, eLong
    &quot;3DFace&quot;, vb3DFace, eLong
    &quot;3DHighlight&quot;, vb3DHighlight, eLong
    &quot;3DLight&quot;, vb3DLight, eLong
    &quot;3DShadow&quot;, vb3DShadow, eLong
    &quot;ActiveBorder&quot;, vbActiveBorder, eLong
    &quot;ActiveTitleBar&quot;, vbActiveTitleBar, eLong
    &quot;ActiveTitleBarText&quot;, vbActiveTitleBarText, eLong
    &quot;ApplicationWorkspace&quot;, vbApplicationWorkspace, eLong
    &quot;ButtonFace&quot;, vbButtonFace, eLong
    &quot;ButtonShadow&quot;, vbButtonShadow, eLong
    &quot;ButtonText&quot;, vbButtonText, eLong
    &quot;Desktop&quot;, vbDesktop, eLong
    &quot;GrayText&quot;, vbGrayText, eLong
    &quot;Highlight&quot;, vbHighlight, eLong
    &quot;HighlightText&quot;, vbHighlightText, eLong
    &quot;InactiveBorder&quot;, vbInactiveBorder, eLong
    &quot;InactiveCaptionText&quot;, vbInactiveCaptionText, eLong
    &quot;InactiveTitleBar&quot;, vbInactiveTitleBar, eLong
    &quot;InactiveTitleBarText&quot;, vbInactiveTitleBarText, eLong
    &quot;InfoBackground&quot;, vbInfoBackground, eLong
    &quot;InfoText&quot;, vbInfoText, eLong
    &quot;MenuBar&quot;, vbMenuBar, eLong
    &quot;MenuText&quot;, vbMenuText, eLong
    &quot;ScrollBars&quot;, vbScrollBars, eLong
    &quot;TitleBarText&quot;, vbTitleBarText, eLong
    &quot;WindowBackground&quot;, vbWindowBackground, eLong
    &quot;WindowFrame&quot;, vbWindowFrame, eLong
    &quot;WindowText&quot;, vbWindowText, eLong

    &quot;White&quot;, vbWhite, eLong
    &quot;Black&quot;, vbBlack, eLong
    &quot;Red&quot;, vbRed, eLong
    &quot;Blue&quot;, vbBlue, eLong
    &quot;Green&quot;, vbGreen, eLong
    &quot;Cyan&quot;, vbCyan, eLong
    &quot;Yellow&quot;, vbYellow, eLong
    &quot;Magenta&quot;, vbMagenta, eLong
If you declare  &quot;environ&quot;, &quot;environment&quot;
    'this code is copied and modified
    Do
        X = X + 1
        v2 = Split(v, &quot;=&quot;)
        v = Environ(X)
        If v = &quot;&quot; Then Exit Do
        v2(0), v2(1), eString 'e.g. [username]
        &quot;environ-&quot; &amp; v2(0), v2(1), eString 'e.g. [environ-username]
        &quot;environ-&quot; &amp; X, v2(1), eString 'e.g. [environ-5]
    Loop Until v = &quot;&quot;
    'more vars the user might find useful
    &quot;Host&quot;, ComputerName, eString
    &quot;UserName&quot;, UserName, eString
    &quot;Command&quot;, Command$, eString
If you declare  &quot;math&quot;
    &quot;pi&quot;, 3.141592654, eDouble
    &quot;e&quot;, Exp(1), eDouble
If you declare  &quot;mouse&quot;
    &quot;Left&quot;, 1, eInteger
    &quot;Right&quot;, 2, eInteger
    &quot;Middle&quot;, 3, eInteger
If you declare  &quot;msg&quot;, &quot;msgbox&quot;, &quot;message box&quot;, &quot;message box styles&quot;
    &quot;Information&quot;, 64, eInteger
    &quot;Exclamation&quot;, 32, eInteger
    &quot;Critical&quot;, 16, eInteger
    &quot;AbortRetryIgnore&quot;, vbAbortRetryIgnore, eInteger
    &quot;YesNo&quot;, vbYesNo, eInteger
    &quot;YesNoCancel&quot;, vbYesNoCancel, eInteger
If you declare  &quot;time&quot;
    &quot;Time&quot;, Now(), eDate
    &quot;Year&quot;, Year(Now()), eLong
    &quot;Month&quot;, Month(Now()), eLong
    &quot;Weekday&quot;, Weekday(Now()), eLong
    &quot;Day&quot;, Day(Now()), eLong
    &quot;Hour&quot;, Hour(Now()), eLong
    &quot;Minute&quot;, Minute(Now()), eLong
    &quot;Second&quot;, Second(Now()), eLong
If you declare  &quot;variables&quot;, &quot;variable types&quot;, &quot;vartypes&quot;, &quot;vars&quot;
    &quot;Variant&quot;, 0, eInteger
    &quot;String&quot;, 1, eInteger
    &quot;Integer&quot;, 2, eInteger
    &quot;Long&quot;, 3, eInteger
    &quot;Double&quot;, 4, eInteger
    &quot;Date&quot;, 5, eInteger
    &quot;Boolean&quot;, 6, eInteger
If you declare  &quot;vk&quot;, &quot;virtual keys&quot;, &quot;virtualkeys&quot; 'VK_Consts
    &quot;VK_ADD&quot;, &amp;H6B, eLong
    &quot;VK_ATTN&quot;, &amp;HF6, eLong
    &quot;VK_BACK&quot;, &amp;H8, eLong
    &quot;VK_CANCEL&quot;, &amp;H3, eLong
    &quot;VK_CAPITAL&quot;, &amp;H14, eLong
    &quot;VK_CLEAR&quot;, &amp;HC, eLong
    &quot;VK_CONTROL&quot;, &amp;H11, eLong
    &quot;VK_CRSEL&quot;, &amp;HF7, eLong
    &quot;VK_DECIMAL&quot;, &amp;H6E, eLong
    &quot;VK_DIVIDE&quot;, &amp;H6F, eLong
    &quot;VK_DELETE&quot;, &amp;H2E, eLong
    &quot;VK_DOWN&quot;, &amp;H28, eLong
    &quot;VK_END&quot;, &amp;H23, eLong
    &quot;VK_EREOF&quot;, &amp;HF9, eLong
    &quot;VK_ESCAPE&quot;, &amp;H1B, eLong
    &quot;VK_EXECUTE&quot;, &amp;H2B, eLong
    &quot;VK_EXSEL&quot;, &amp;HF8, eLong
    &quot;VK_F1&quot;, &amp;H70, eLong
    &quot;VK_F10&quot;, &amp;H79, eLong
    &quot;VK_F11&quot;, &amp;H7A, eLong
    &quot;VK_F12&quot;, &amp;H7B, eLong
    &quot;VK_F13&quot;, &amp;H7C, eLong
    &quot;VK_F14&quot;, &amp;H7D, eLong
    &quot;VK_F15&quot;, &amp;H7E, eLong
    &quot;VK_F16&quot;, &amp;H7F, eLong
    &quot;VK_F17&quot;, &amp;H80, eLong
    &quot;VK_F18&quot;, &amp;H81, eLong
    &quot;VK_F19&quot;, &amp;H82, eLong
    &quot;VK_F2&quot;, &amp;H71, eLong
    &quot;VK_F20&quot;, &amp;H83, eLong
    &quot;VK_F21&quot;, &amp;H84, eLong
    &quot;VK_F22&quot;, &amp;H85, eLong
    &quot;VK_F23&quot;, &amp;H86, eLong
    &quot;VK_F24&quot;, &amp;H87, eLong
    &quot;VK_F3&quot;, &amp;H72, eLong
    &quot;VK_F4&quot;, &amp;H73, eLong
    &quot;VK_F5&quot;, &amp;H74, eLong
    &quot;VK_F6&quot;, &amp;H75, eLong
    &quot;VK_F7&quot;, &amp;H76, eLong
    &quot;VK_F8&quot;, &amp;H77, eLong
    &quot;VK_F9&quot;, &amp;H78, eLong
    &quot;VK_HELP&quot;, &amp;H2F, eLong
    &quot;VK_HOME&quot;, &amp;H24, eLong
    &quot;VK_INSERT&quot;, &amp;H2D, eLong
    &quot;VK_LBUTTON&quot;, &amp;H1, eLong
    &quot;VK_LCONTROL&quot;, &amp;HA2, eLong
    &quot;VK_LEFT&quot;, &amp;H25, eLong
    &quot;VK_LMENU&quot;, &amp;HA4, eLong
    &quot;VK_LSHIFT&quot;, &amp;HA0, eLong
    &quot;VK_MBUTTON&quot;, &amp;H4, eLong ' NOT contiguous with L RBUTTON
    &quot;VK_MENU&quot;, &amp;H12, eLong
    &quot;VK_MULTIPLY&quot;, &amp;H6A, eLong
    &quot;VK_NEXT&quot;, &amp;H22, eLong
    &quot;VK_NONAME&quot;, &amp;HFC, eLong
    &quot;VK_NUMLOCK&quot;, &amp;H90, eLong
    &quot;VK_NUMPAD0&quot;, &amp;H60, eLong
    &quot;VK_NUMPAD1&quot;, &amp;H61, eLong
    &quot;VK_NUMPAD2&quot;, &amp;H62, eLong
    &quot;VK_NUMPAD3&quot;, &amp;H63, eLong
    &quot;VK_NUMPAD4&quot;, &amp;H64, eLong
    &quot;VK_NUMPAD5&quot;, &amp;H65, eLong
    &quot;VK_NUMPAD6&quot;, &amp;H66, eLong
    &quot;VK_NUMPAD7&quot;, &amp;H67, eLong
    &quot;VK_NUMPAD8&quot;, &amp;H68, eLong
    &quot;VK_NUMPAD9&quot;, &amp;H69, eLong
    &quot;VK_OEM_CLEAR&quot;, &amp;HFE, eLong
    &quot;VK_PA1&quot;, &amp;HFD, eLong
    &quot;VK_PAUSE&quot;, &amp;H13, eLong
    &quot;VK_PLAY&quot;, &amp;HFA, eLong
    &quot;VK_PRINT&quot;, &amp;H2A, eLong
    &quot;VK_PRIOR&quot;, &amp;H21, eLong
    &quot;VK_PROCESSKEY&quot;, &amp;HE5, eLong
    &quot;VK_RBUTTON&quot;, &amp;H2, eLong
    &quot;VK_RCONTROL&quot;, &amp;HA3, eLong
    &quot;VK_RETURN&quot;, &amp;HD, eLong
    &quot;VK_RIGHT&quot;, &amp;H27, eLong
    &quot;VK_RMENU&quot;, &amp;HA5, eLong
    &quot;VK_RSHIFT&quot;, &amp;HA1, eLong
    &quot;VK_SCROLL&quot;, &amp;H91, eLong
    &quot;VK_SELECT&quot;, &amp;H29, eLong
    &quot;VK_SEPARATOR&quot;, &amp;H6C, eLong
    &quot;VK_SHIFT&quot;, &amp;H10, eLong
    &quot;VK_SNAPSHOT&quot;, &amp;H2C, eLong
    &quot;VK_SPACE&quot;, &amp;H20, eLong
    &quot;VK_SUBTRACT&quot;, &amp;H6D, eLong
    &quot;VK_TAB&quot;, &amp;H9, eLong
    &quot;VK_UP&quot;, &amp;H26, eLong
    &quot;VK_ZOOM&quot;, &amp;HFB, eLong
If you declare  &quot;window&quot;, &quot;showwindow&quot;, &quot;sw&quot; 'showWindow consts
    &quot;Hide&quot;, 0, eInteger
    &quot;Show&quot;, 1, eInteger
    &quot;Maximize&quot;, 3, eInteger
    &quot;Minimize&quot;, 2, eInteger</pre>
<?php
    page_out(array('title' => 'SoupScript: constants'));
?>
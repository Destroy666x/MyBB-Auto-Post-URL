**Auto Post URL**
===============

![Auto Post URL](https://raw.github.com/Destroy666x/MyBB-Auto-Post-URL/master/preview1.png "Preview")

**Name**: Auto Post URL  
**Author**: Destroy666  
**Version**: 1.0  

**Info**:
---------

Plugin for MyBB forum software, coded for versions 1.8.x.  
It automatically sets the URL of the currently viewed post and optionally highlights it.  
5 template edits, 1 new settings  
Released under GNU GPL v3, 29 June 2007. Read the LICENSE.md file for more information.  

**Support/bug reports**: 
------------------------

**Support**: official MyBB forum - http://community.mybb.com/mods.php?action=profile&uid=58253 (don't PM me, post on forums)  
**Bug reports**: my github - https://github.com/Destroy666x   

**Changelog**:
--------------

**1.0** - initial release  

**Installation**:
-----------------

1. Upload everything from upload folder to your forum root (where index.php, forumdisplay.php etc. are located).
2. Install and activate plugin in ACP -> Configuration -> Plugins.
3. Configure it.

**Templates troubleshooting**:
------------------------------

* Postbit - add **`class="post_anchor" href="{$post['postlink']}#pid{$post['pid']}" title="{$post['subject_title']}"`** to the starting post URL and **`<a name="bottompid{$post['pid']}" id="bottompid{$post['pid']}"></a>`** at the end of postbit and postbit_classic templates
* Showthread - add:
```html
<script type="text/javascript">
<!--
	var autoposturl_newclass = '{$sanitized_class}';
	var autoposturl_bbname = '{$sanitized_bbname}';
//-->
</script>
<script type="text/javascript" src="{$mybb->asset_url}/jscripts/auto_post_url.js?ver=1"></script>
```
to the `<head>` section of the showthread template

**Note 1**: the class specified in the setting needs to be manually added to **global.css** or an other stylesheet that covers showthread, e.g.:  
```css
.autoposturl
{
	background-color: #CFC;
}
```
**Note 2**: changing the URL may not work in older browsers, you can replace the commented code in jscripts/auto_post_url.js with history.js or anything similar if you need support for them

**Translations**:
-----------------

Feel free to submit translations to github in Pull Requests. Also, if you want them to be included on the MyBB mods site, ask me to provide you the contributor status for my project.

**Donations**:
-------------

Donations will motivate me to work on further MyBB plugins. Feel free to use the button in the ACP Plugins section anytime.  
Thanks in advance for any input.
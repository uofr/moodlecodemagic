<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * DragMath equation editor popup.
 *
 * @package   tinymce_dragmath
 * @copyright 2008 Mauno Korpelainen
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define('NO_MOODLE_COOKIES', true);

require('../../../../../config.php');

$PAGE->set_context(context_system::instance());
$PAGE->set_url('/lib/editor/tinymce/plugins/codemagic/codemagic.php');

if (isset($SESSION->lang)) {
    // Language is set via page url param.
    $lang = $SESSION->lang;
} else {
    $lang = 'en';
}

$editor = get_texteditor('tinymce');
$plugin = $editor->get_plugin('codemagic');

// Prevent https security problems.
$relroot = preg_replace('|^http.?://[^/]+|', '', $CFG->wwwroot);

$htmllang = get_html_lang();
header('Content-Type: text/html; charset=utf-8');
header('X-UA-Compatible: IE=edge');
?>
<!DOCTYPE html>
<html <?php echo $htmllang ?>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title><?php print_string('title', 'tinymce_codemagic')?></title>
<script type="text/javascript" src="<?php echo $editor->get_tinymce_base_url(); ?>tiny_mce_popup.js"></script>
<script type="text/javascript" src="<?php echo $plugin->get_tinymce_file_url('js/codemirror-compressed.js'); ?>"></script>
<script type="text/javascript" src="<?php echo $plugin->get_tinymce_file_url('js/codemagic.js'); ?>"></script>
<script type="text/javascript" src="<?php echo $plugin->get_tinymce_file_url('js/beautify-html.js'); ?>"></script>
<script type="text/javascript" src="<?php echo $plugin->get_tinymce_file_url('js/beautify.js'); ?>"></script>

<link rel="stylesheet" media="all" type="text/css" href="<?php echo $plugin->get_tinymce_file_url('css/style.css'); ?>"  />
<link rel="stylesheet" media="all" type="text/css" href="<?php echo $plugin->get_tinymce_file_url('css/codemirror.css'); ?>"  />
<link rel="stylesheet" media="all" type="text/css" href="<?php echo $plugin->get_tinymce_file_url('css/default.css'); ?>" />
</head>
<body onresize="resizeInputs();" style="display: none; overflow: hidden;">

	<form name="source" onsubmit="saveContent(); return false;" action="#">
		<div style="float: left" class="title"><label for="htmlSource"><?php print_string('codemagic:code_label', 'tinymce_codemagic')?></label></div>

        <div class="editor-buttons">
            <a id="undo" class="disabled" href="javascript: void(0)" title="<?php print_string('codemagic:undo', 'tinymce_codemagic')?>" onclick="undo();"><img src="<?php echo $plugin->get_tinymce_file_url('img/icons/undo.png')?>" alt="<?php print_string('codemagic:undo', 'tinymce_codemagic')?>" /></a>
            <a id="redo" class="disabled" href="javascript: void(0)" title="<?php print_string('codemagic:redo', 'tinymce_codemagic')?>" onclick="redo();"><img src="<?php echo $plugin->get_tinymce_file_url('img/icons/redo.png')?>" alt="<?php print_string('codemagic:redo', 'tinymce_codemagic')?>" /></a>
            <a id="search_replace" href="javascript: void(0)" title="<?php print_string('codemagic:search_replace', 'tinymce_codemagic')?>" onclick="toggleSearch(this, 'searchWindow');"><img src="<?php echo $plugin->get_tinymce_file_url('img/icons/lens.png')?>" alt="<?php print_string('codemagic:search_replace', 'tinymce_codemagic')?>" /></a>
            <a id="reintendt" href="javascript: void(0)" title="<?php print_string('codemagic:reintendt', 'tinymce_codemagic')?>" onclick="reIntendt('htmlSource');"><img src="<?php echo $plugin->get_tinymce_file_url('img/icons/file.png')?>" alt="<?php print_string('codemagic:reintendt', 'tinymce_codemagic')?>" /></a>
            <div style="clear: both;"></div>
        </div>

        <div id="wrapline" style="float: right">
            <input type="checkbox" name="wraptext" id="wraptext" onclick="toggleWrapText(this);" class="wordWrapCode" checked="checked" /><label for="wraptext"><?php print_string('codemagic:toggle_wraptext', 'tinymce_codemagic')?></label> &nbsp;&nbsp;&nbsp;&nbsp;
            <input type="checkbox" name="autocompletion" id="autocompletion" onclick="toggleAutocompletion(this);" class="wordWrapCode" checked="checked" /><label for="autocompletion"><?php print_string('codemagic:toggle_autocompletion', 'tinymce_codemagic')?></label> &nbsp;&nbsp;&nbsp;&nbsp;
            <input type="checkbox" name="highlighting" id="highlighting" onclick="toggleHighlighting(this, 'htmlSource');" class="wordWrapCode" checked="checked" /><label for="highlighting"><?php print_string('codemagic:toggle_highlighting', 'tinymce_codemagic')?></label>
        </div>
        <div style="clear: both;"></div>


        <div id="searchWindow" class="search-window" style="display: none;">
            <h2><?php print_string('codemagic:search_replace', 'tinymce_codemagic')?></h2>
            <table>
                <tr>
                    <td colspan="2">&nbsp;</td>
                </tr>
                <tr>
                    <td><label for="query"><?php print_string('codemagic:search', 'tinymce_codemagic')?></label>:</td>
                    <td><input id="query" type="text" style="width: 150px" /></td>
                </tr>
                <tr>
                    <td><label for="replace"><?php print_string('codemagic:replace', 'tinymce_codemagic')?></label>:</td>
                    <td><input id="replace" type="text" style="width: 150px" /></td>
                </tr>
                <tr>
                    <td colspan="2">&nbsp;</td>
                </tr>
                <tr>
                    <td align="left"><input onclick="searchCode(); return false;" type="submit" value="<?php print_string('codemagic:search', 'tinymce_codemagic')?>" class="button" style="float: none" /></td>
                    <td align="right"><input onclick="replaceCode(); return false;" type="submit" value="<?php print_string('codemagic:replace', 'tinymce_codemagic')?>" class="button"  style="float: none" /></td>
                </tr>
            </table>
        </div>

        <textarea name="htmlSource" id="htmlSource" rows="15" cols="100" dir="ltr" class="htmlSource"></textarea>

		<div class="mceActionPanel">
			<input type="submit" role="button" name="insert" value="{#update}" id="insert" />
			<input type="button" role="button" name="cancel" value="{#cancel}" onclick="tinyMCEPopup.close();" id="cancel" />
		</div>
	</form>
</body>
</html>
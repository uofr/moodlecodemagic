/**
 * editor_plugin_src.js
 *
 * Copyright 2011, Sutulustus - Triad Advertising
 * Released under MIT License.
 *
 * License: http://www.opensource.org/licenses/mit-license.php
 */

(function () {
    tinymce.PluginManager.requireLangPack('codemagic');
    tinymce.create('tinymce.plugins.CodeMagic', {
        init: function (ed, url) {
            // Register commands
            ed.addCommand('mceCodeMagic', function() {
                ed.windowManager.open({
                    file : ed.getParam("moodle_plugin_base") + 'codemagic/codemagic.php',
                    width : 800,
                    height : 580,
                    inline : 1,
                    maximizable: true
                }, {
                    plugin_url : url
                });
            });

            // Register buttons
            ed.addButton('code', {
                title: 'codemagic.editor_button',
                cmd: 'mceCodeMagic'
            });

            ed.onNodeChange.add(function(ed, cm, n, co) {
                cm.setDisabled('link', co && n.nodeName != 'A');
                cm.setActive('link', n.nodeName == 'A' && !n.name);
            });
        },
        getInfo: function () {
             return {
                longname: 'CodeMagic - syntax coloring and intendation',
                author: 'Sutulustus',
                authorurl: 'http://www.triad.sk/#/en',
                version: '0.9.5'
             };
        }
    });

    // Register plugin
    tinymce.PluginManager.add('codemagic', tinymce.plugins.CodeMagic);
})();

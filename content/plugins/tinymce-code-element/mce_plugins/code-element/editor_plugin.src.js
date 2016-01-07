(function() {

	tinymce.create('tinymce.plugins.CodeElementPlugin', {
		init : function(ed, url) {

			// Register commands
			ed.addCommand('mceCodeElement', function() {
				ed.execCommand('mceBeginUndoLevel');

				var e = ed.dom.getParent(ed.selection.getNode(), 'CODE');
				if (e===null){
					//add code element
					if ( ed.selection.isCollapsed() ) {
						ed.execCommand('mceInsertContent', false, " <code>code</code> ");
					} else {
                        var code = jQuery('<code />');
                        code.html(ed.selection.getContent());
						ed.execCommand(
                            'mceReplaceContent',
                            false,
                            jQuery('<div />').append(code).html()
                        );
					}
				} else {
					//remove code element
					ed.execCommand('mceRemoveNode', false, e);
					ed.nodeChanged();
				}

				ed.execCommand('mceEndUndoLevel');
			});

			// Register buttons
			ed.addButton('codeElement', {
				title : 'Insert/edit code',
				cmd : 'mceCodeElement',
				image : url + '/img/icon.png'
			});

			//set button to pressed when cursor at code
			ed.onNodeChange.add(function(ed, cm, n) {
				cm.setActive('codeElement', n.nodeName == 'CODE');
			});
		},

		getInfo : function() {
			return {
				longname : 'Code Element',
				author : 'Takayuki Miyauchi',
				authorurl : 'http://wpist.me/',
				infourl : '',
				version : "0.5"
			};
		}
	});

	// Register plugin
	tinymce.PluginManager.add('codeElement', tinymce.plugins.CodeElementPlugin);
})();


(function() {
    tinymce.PluginManager.add('mce4_youtube_button', function( editor, url ) {
        editor.addButton( 'mce4_youtube_button', {
            title: 'YouTube Embed',
            icon: 'icon dashicons-video-alt3',
            onclick: function() {
                selectText = tinymce.activeEditor.selection.getContent({format: 'text'});
                if ( selectText == '' ) {
                    var yeOut = 'Insert video URL or ID here';
                } else {
                    var yeOut = selectText;
                }
                editor.insertContent('[youtube]' + yeOut + '[/youtube]');
            }
        });
    });
})();
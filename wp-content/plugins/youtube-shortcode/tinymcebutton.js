(function() {
    tinymce.create('tinymce.plugins.YoutubeShortcodeMargenn', {
        
        init : function(ed, url) {
        
        	var popUpURL = url + '/youtube-shortcode-tinymce.html';
        
			ed.addCommand('YoutubeShortcodePopupMargenn', function() {
				ed.windowManager.open({
					url : popUpURL,
					width : 600,
					height : 500, 
					inline : 1
				});
			});

			ed.addButton('YoutubeShortcodeButtonMargenn', {
				title : 'YouTube Shortcode',
				image : url + '/youtube-shortcode-button.png',
				cmd : 'YoutubeShortcodePopupMargenn'
			});
		}
    });
    tinymce.PluginManager.add('YoutubeShortcodeMargenn', tinymce.plugins.YoutubeShortcodeMargenn);
}());
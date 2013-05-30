(function() {
    tinymce.PluginManager.requireLangPack('len-slider');
    tinymce.create('tinymce.plugins.LenSlider', {
        init : function(ed, url) {
            url = url.replace(/wp-admin/, '');
            ed.addButton('len-slider', {
                title : 'len-slider.title',
                image :  url+'wp-content/plugins/len-slider/images/tinymce_button.png',
                cmd   : 'len-slider'
            });
            ed.addCommand('len-slider', function() {
                ed.windowManager.open({
                    file       : url+'wp-content/plugins/len-slider/tinymce.php',
                    width      : 350,
                    height     : 120,
                    inline     : 1,
                    resizable  : "yes",
                    scrollbars : "yes"
                }, {
                    plugin_url : url
                });
            });
        },
        
        createControl : function(n, cm) {
            return null;
        },
        
        getInfo : function() {
            return {
                longname  : 'LenSlider shortcode insert',
                author    : 'Igor Sazonov',
                authorurl : 'http://www.lenslider.com/',
                infourl   : 'http://www.lenslider.com/',
                version   : '1.2'
            };
        }
    });
    tinymce.PluginManager.add('lenslider', tinymce.plugins.LenSlider);
})();
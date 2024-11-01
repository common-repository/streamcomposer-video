(function () {
    tinymce.init({
        selector: "streamcomposer-code",  // change this value according to your HTML
        plugins: "contextmenu",
        contextmenu: "link image inserttable | cell row column deletetable"
    });

    tinymce.PluginManager.add('streamcomposer_mce_button', function (editor, url) {
        var width;
        var height;
        var data;
        var videoUrl="https://app.streamcomposer.com/play/streamcomposer/caminadesllamigos";
        function getAttr(s, n) {
            n = new RegExp(' ' + n + '=\"([^\"]+)\"', 'g').exec(s);
            return n ? window.decodeURIComponent(n[1]) : '';
        };

        function streamcomposer_html(cls, data, con) {
            placeholder = url + '/streamcomposer.png';
            var align = getAttr(data, 'align');
            var width = getAttr(data, 'width');
            var height = getAttr(data, 'height');
            data = window.encodeURIComponent(data);
            content = window.encodeURIComponent(con);

            var htmlplaceholder = '<div class="' + align + ' wp-caption"><img src="' + placeholder + '" class="mceItem ' + cls + '" ' + 'data-sh-attr="' + data +
                '" data-sh-content="' + content + '" style="width:' + width + 'px; height:' + height + 'px;' +
                '" data-mce-resize="false" data-mce-placeholder="1"></div>';

            return htmlplaceholder;
        }

        function replaceSCShortcodes(content) {
            return content.replace(/\[streamcomposer-code([^\]]*)\]([^\]]*)\[\/streamcomposer-code\]/g, function (all, attr, con) {
                return streamcomposer_html('streamcomposer-code_tinymce_img', attr, con);
            });
        }

        function restoreSCShortcodes(content) {
            return content.replace(/<div class(?:[^>\"']|\"[^\"]*\"|'[^']*')*>(.*?)<\/div>/g, function (match, video) {
                data = getAttr(video, 'data-sh-attr');
                var con = getAttr(video, 'data-sh-content');

                if (data) {
                    return '[streamcomposer-code' + data + ']' + con + '[/streamcomposer-code]';
                }
                return match;
            });
        }

        //replace from shortcode to an video placeholder
        editor.on('BeforeSetcontent', function (event) {
            event.content = replaceSCShortcodes(event.content);
        });

        //replace from video placeholder to shortcode
        editor.on('GetContent', function (event) {
            event.content = restoreSCShortcodes(event.content);
        });

        //Find another solution since this will break tinyMCE's normal doubleclick
        /*editor.on('DblClick', function (event) {

            width = event.srcElement.width;
            height= event.srcElement.height;
            videoUrl=getAttr(data, 'src');

            showDialog();
        });*/

        function showDialog() {
            editor.windowManager.open({
                title: 'Insert StreamComposer video',
                body: [
                    {
                        type: 'button',
                        text: 'Select video (opens a new tab)',
                        icon: 'icon dashicons-admin-media',
                        name: 'selectfile',
                        onclick: function (e) {
                            window.open('https://app.streamcomposer.com/home/#/listVideos', '_blank');
                        }
                    },
                    {
                        type: 'textbox',
                        name: 'videoUrl',
                        id: 'videoUrl',
                        label: 'Video url',
                        value: videoUrl
                    },
                    {
                        type: 'textbox',
                        name: 'videoWidth',
                        id: 'videoWidth',
                        label: 'Width',
                        value: width

                    },
                    {
                        type: 'textbox',
                        name: 'videoHeight',
                        id: 'videoHeight',
                        label: 'Height',
                        value: height
                    },
                    {
                        type: 'listbox',
                        name: 'videoAlign',
                        label: 'Align',
                        'values': [
                            {text: 'Left', value: 'alignleft'},
                            {text: 'Center', value: 'aligncenter'},
                            {text: 'Right', value: 'alignright'},
                            {text: 'None', value: 'noalign'}
                        ]
                    }

                ],
                onsubmit: function (e) {
                    editor.insertContent('[streamcomposer-code align="' + e.data.videoAlign + '"  src="' + e.data.videoUrl + '" style="border:0px #ffffff none;" scrolling="no" frameborder="0" marginheight="0px" marginwidth="0px" height="'
                        + e.data.videoHeight + '" width="' + e.data.videoWidth + '" allowfullscreen][/streamcomposer-code]');
                }
            });
        }

        editor.addButton('streamcomposer_mce_button', {
            title: 'Insert a StreamComposer video',
            image: url + '/streamcomposer.png',
            onclick: function () {
                width=600;
                height=338;
                showDialog();
            }
        });
    });
})();
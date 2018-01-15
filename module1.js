M.show_forum_panel = {
    init: function(Y, options) {
        this.Y = Y;
        this.spinnerpic = options.spinner;
        this.mid = options.mid;
        var showForumBtn  = Y.one('#showForum');
        showForumBtn.on('click', this.show_on_click);
    },

    show_on_click: function(e) {
        M.show_forum_panel.showforumpanel();
    },

    showforumpanel: function() {
        var Y = this.Y;
         var url = M.cfg.wwwroot + "/blocks/ned_teacher_tools/forum_view.php?id=" + this.mid;
        var div = Y.Node.create('</div><div/>');
        div.set("id", "forumiframecontainer");

        // Create iframe.
        var ifrm = Y.Node.create('<iframe frameborder="0"><iframe>');
        ifrm.set("src", url);
        ifrm.set("name", "forumiframe");
        ifrm.set("id", "forumiframe");
        ifrm.setStyle('width', '100%');
        ifrm.setStyle('height', '430px');
        ifrm.setStyle('display', 'none');
        div.append(ifrm);

         Y.on('contentready', function(){
                                ifrm.setStyle('display', 'block');
         }, '#forumiframe');

            var panel;
            panel = new Y.Panel({
                bodyContent : div,
                headerContent: 'Forum marking Area',
                width        : 950,
                height       : 500,
                zIndex       : 5,
                xy     : [300, -300],
                centered     : true,
                modal        : true,
                visible      : false,
                render       : true,
                scroll       : true,
                focusOn      : Y.one('#region-main-box'),
                buttons: [{value  : 'Close the panel',
                    section: 'footer',
                    action : function (e) {
                             e.preventDefault();
                             panel.hide();
                             window.location.reload();
                    }
                },
                                        {value  : 'Close the panel',
                                            section: 'header',
                                            action : function (e) {
                                                e.preventDefault();
                                                panel.hide();
                                                window.location.reload();
                                            }
                }]

                        });

            panel.show();

    }
};

M.block_ned_teacher_tools = {};

M.block_ned_teacher_tools.init_popup = function(Y) {
    Y.on('click', function(e) {
        var w = 900;
        var h = 500;
        // Fixes dual-screen position.
        var dualScreenLeft = window.screenLeft != undefined ? window.screenLeft : screen.left;
        var dualScreenTop = window.screenTop != undefined ? window.screenTop : screen.top;

        width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
        height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;

        var left = ((width / 2) - (w / 2)) + dualScreenLeft;
        var top = ((height / 2) - (h / 2)) + dualScreenTop;
        var newWindow = window.open(this.get('href'), M.cfg.wwwroot, 'scrollbars=yes, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);

        // Puts focus on the newWindow.
        if (window.focus) {
            newWindow.focus();
        }
        e.preventDefault();
    }, '.popup');
};

M.block_ned_teacher_tools.init_filter_icon = function(Y) {
    Y.on('click', function(e) {
        var w = 900;
        var h = 500;
        // Fixes dual-screen position.
        var dualScreenLeft = window.screenLeft != undefined ? window.screenLeft : screen.left;
        var dualScreenTop = window.screenTop != undefined ? window.screenTop : screen.top;

        width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
        height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;

        var left = ((width / 2) - (w / 2)) + dualScreenLeft;
        var top = ((height / 2) - (h / 2)) + dualScreenTop;
        var newWindow = window.open(this.get('href'), M.cfg.wwwroot, 'scrollbars=yes, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);

        // Puts focus on the newWindow.
        if (window.focus) {
            newWindow.focus();
        }
        e.preventDefault();
    }, '.popup');
};
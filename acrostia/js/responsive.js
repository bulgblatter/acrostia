//Inspiro B custom navigation script

jQuery(function($) {
    $(document).ready(function() {

        //remove img height and width attributes for better responsiveness
        $('img').each(function() {
            $(this).removeAttr('width')
            $(this).removeAttr('height');
        });

        //responsive drop-down <== main nav
        $("<select />").appendTo(".block--system-main-menu");
        $("<option />", {
            "selected": "selected",
            "value": "",
            "text": "Menu"
        }).appendTo(".block--system-main-menu select");
        $(".block--system-main-menu a").each(function() {
            var el = $(this);
            $("<option />", {
                "value": el.attr("href"),
                "text": el.text()
            }).appendTo(".block--system-main-menu select");
        });

        //remove options with # symbol for value
        $(".block--system-main-menu option").each(function() {
            var navOption = $(this);

            if (navOption.val() == '#') {
                navOption.remove();
            }
        });

        //open link
        $(".block--system-main-menu select").change(function() {
            window.location = $(this).find("option:selected").val();
        });

        //uniform
//        $(function() {
//            $("#navigation select").uniform();
//            $("#top-navigation select").uniform();
//        });

    }); // END doc ready
}); // END function
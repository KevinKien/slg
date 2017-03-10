$(document).ready(function () {
    var gnbLayersInfo = [{ParentSelector: ".tit > a.join, #myPulldownNavWrap01", LayerSelector: "#myPulldownNavWrap01"}, {ParentSelector: ".tit > a.lang_tit, #pulldown_lang01", LayerSelector: "#pulldown_lang01"}, {ParentSelector: ".menu > li > a.game, .game_list_wrp", LayerSelector: "#game_list_open"}, {ParentSelector: ".menu > li > a.community, .comm_list_wrp", LayerSelector: "#comm_list_open"}];
    var openedGnbLayerSelector = "";
    var handleGnbLayerOpen = null;
    var procGnbLayerAction = function () {
        var openGnbLayerInfo = null;
        for (var idx in gnbLayersInfo) {
            if (gnbLayersInfo[idx].LayerSelector != openedGnbLayerSelector) {
                $(gnbLayersInfo[idx].LayerSelector).slideUp(100);
                $(gnbLayersInfo[idx].ParentSelector).removeClass("on")
            } else {
                openGnbLayerInfo = gnbLayersInfo[idx]
            }
        }
        if (openGnbLayerInfo == null) {
            return
        }
        $(openGnbLayerInfo.LayerSelector).slideDown(100);
        $(openGnbLayerInfo.ParentSelector).addClass("on")
    };
    var setGnbLayerAction = function (selector) {
        if (handleGnbLayerOpen != null) {
            window.clearTimeout(handleGnbLayerOpen)
        }
        var timeout = selector ? 10 : 500;
        handleGnbLayerOpen = window.setTimeout(function () {
            openedGnbLayerSelector = selector;
            procGnbLayerAction();
            handleGnbLayerOpen = null
        }, timeout)
    };
    for (var idx in gnbLayersInfo) {
        $(gnbLayersInfo[idx].ParentSelector).attr("idx", idx).mouseenter(function () {
            setGnbLayerAction(gnbLayersInfo[$(this).attr("idx")].LayerSelector)
        }).mouseleave(function () {
            setGnbLayerAction("")
        })
    }    
});


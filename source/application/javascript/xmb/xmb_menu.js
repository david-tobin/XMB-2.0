/*
 * XMB Popup Menus
 * @author David "DavidT" Tobin

 */

function menu(parent, menu) {
	$(document).ready(function() {
		$(".menublank").hide();
		var p = $("#" + parent);
		var offset = p.offset();
		
		$("#" + menu).dequeue().fadeIn("fast");
		$("#" + menu).css( {
			"top" : offset.top + 24,
			"left" : offset.left + 4
		});

		$("#" + menu).bind('mouseleave', function() {
			$("#" + menu).dequeue().fadeOut();
           $("#" + menu).css( {
                "opacity" : 1
                });
		});

		$("#body").bind('click', function() {
			$("#" + menu).dequeue().fadeOut();
            $("#" + menu).css( {
                "opacity" : 1
                });
		});
	});
}

function menu2(parent, menu) {
	$(document).ready(function() {
		$(".menublank").hide();
		var p = $("#" + parent);
		var offset = p.offset();

		$("#" + menu).dequeue().fadeIn("fast");
		$("#" + menu).css( {
			"top" : offset.top + 24,
			"left" : offset.left + 4
		});

		$("#" + parent).bind('mouseleave', function() {
			$("#" + menu).dequeue().fadeOut();
            $("#" + menu).css( {
                "opacity" : 0.8
                });
		});
	});
}

function overlay(id) {
    $(document).ready(function() {
        $(".simple_overlay").overlay();
    });
} 
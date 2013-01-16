if(typeof Photon === "undefined") {
	Photon = {};
}

if(typeof Photon.exec === "undefined") {
	Photon.exec = {};
}

jQuery(document).ready(function() {
	Photon.init = function() {
		var me = this;
		for(var i in me.exec) {
			if(typeof Photon.exec[i].init !== "undefined") {
				Photon.exec[i].init();
			}
		}
		
		$(".controls a").live("click", function(e) {
			e.preventDefault();
			var button = this;
			$.ajax({
				url: button.href
			});
			return false;
		});
	};
	Photon.init();
});
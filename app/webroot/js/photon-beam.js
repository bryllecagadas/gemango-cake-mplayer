if(typeof Photon === "undefined") {
	Photon = {};
}

if(typeof Photon.exec === "undefined") {
	Photon.exec = {};
}

if(typeof Photon.settings === "undefined") {
	Photon.settings = {};
}

jQuery(document).ready(function() {
	Photon.init = function(settings) {
		var me = this;
		for(var i in me.exec) {
			if(typeof Photon.exec[i].init !== "undefined") {
				Photon.exec[i].init(settings);
			}
		}
	};
	Photon.init(Photon.settings);
});
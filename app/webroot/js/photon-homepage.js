Photon.exec.homepage = {
	search_term: "",
	init: function() {
		var me = this;
		if($("#search-form").length && $("#song-name").length) {
			var input = $("#song-name");
			var form = $("#search-form");
			
			input.bind("keyup keydown", function() {
				me.search_term = this.value;
				if(this.value.length >= 3) {
					form.submit();
				} else {
					$("#songs-list").empty();
				}
			});
			
			form.bind("submit", function(e) {
				me.query();
				e.preventDefault();
				return false;
			});
			
			input.focus();
		}
		
		$(".song-item a").live("click", function(e) {
			e.preventDefault();
			var button = this;
			$.ajax({
				url: button.href,
				success: function(response) {
					if(!Photon.exec.player.isPlaying) {
						Photon.exec.player.getTrackInfo();
					}
				}
			});
			return false;
		});
		
	},
	query: function() {
		var me = this;
		var search = me.search_term;
		$.ajax({
			url: Photon.base + "players/files/" + search,
			success: function(response) {
				var values = eval("(" + response + ")");
				if($("#songs-list").length) {
					$("#songs-list").empty();
					for(var i = 0; i < values.data.length; i++) {
						$("#songs-list").append(me.createElem(values.data[i], search));
					}
				}
			}
		});
	},
	createElem : function(value, search_query) {
		var indexes = ["artist", "title", "filename"];
		for(var i in indexes) {
			var regexp = new RegExp(search_query, 'i');
			var matches = regexp.exec(value.Song[indexes[i]]);
			if(matches && typeof matches[0] !== "undefined") {
				value.Song[indexes[i]] = value.Song[indexes[i]].replace(regexp, "<strong>" + matches[0] + "</strong>");
			}
		}
		
		return '<div class="song-item"><a href="' + Photon.base + 'players/enqueue/file:' +
				value.Song.id + 
				'" class="song-action" title="Add to queue">' + 
				value.Song.artist + ' - ' + value.Song.title +
				' [' + value.Song.filename + ']' + '</a></div>';
	}
}
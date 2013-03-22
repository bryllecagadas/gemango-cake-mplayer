Photon.exec.player = {
	init : function() {
		this.getTrackInfo();
		this.attachPlayerButtonHandlers();
	},
	songLength : 0,
	songPos : 0,
	timer : null,
	isPlaying: false,
	request: null,
	getTrackInfo : function() {
		var me = this;
		me.request = $.ajax({
			url: Photon.base + "players/song_info",
			success: function(response) {
				var results = eval("(" + response + ")");
				var isPlaying = false;
				$(".player-time").empty();
				if(
					typeof results.status !== "undefined" &&
					results.status != "error" && 
					typeof results.data.length !== "undefined" && 
					typeof results.data.position !== "undefined" &&
					typeof results.data.artist !== "undefined" &&
					typeof results.data.title !== "undefined" &&
					typeof results.data.diff !== "undefined"
				) {
					isPlaying = true;
					me.songLength = results.data.length;
					me.songPos = parseFloat(results.data.position) + results.data.diff;
					$(".player-time").hide().html(me.formatTime(me.songPos)).fadeIn('fast');;
					me.updateTimer();
					me.updateSongInfoBox(results.data);
				}
				me.isPlaying = isPlaying;
			}
		});
	},
	updateSongInfoBox : function(info) {
		$("#song-info").hide().html("<div class='player-artist'>" + info.artist + "</div><div class='player-title'>" + info.title + "</div>").fadeIn('fast');
	},
	updateTimer : function() {
		var me = this;
		var timeout;
		me.timer = setInterval(function() {
			me.songPos = parseFloat(me.songPos) + 1;
			if(parseFloat(me.songPos) > (parseFloat(me.songLength) + 1)) {
				me.clearSongInfo();
				clearInterval(me.timer);
				timeout = setTimeout(function() {
					me.getTrackInfo();
				}, 2000);
			} else {
				$(".player-time").html(me.formatTime(me.songPos));
			}
		}, 1000);
	},
	formatTime : function(songPos) {
		var seconds = Math.floor(songPos % 60);
		var minutes = Math.floor(songPos / 60);
		seconds += "";
		if(seconds.length == 1) {
			seconds = "0" + seconds;
		}
		return minutes + ":" + seconds;
	},
	attachPlayerButtonHandlers : function() {
		var me = this;
		$(".controls a").live("click", function() {
			e.preventDefault();
			var button = this;
			$.ajax({
				url: button.href
			});
			
			if(me.isPlaying) {
				clearInterval(me.timer);
				me.clearSongInfo();
			}
			return false;
		});
	},
	clearSongInfo : function() {
		$(".player-time").fadeOut('fast').empty();
		$("#song-info").fadeOut('fast');
	}
};

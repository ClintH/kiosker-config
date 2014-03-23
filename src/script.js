$(document).ready(function() {
	$(".try").on("click", function(e) {
		if (e.target.nodeName == "H1") {
			$(e.target.parentElement).toggleClass("collapsed");
		} else 
			$(e.target).toggleClass("collapsed");
	})
})

		<script>
		$(function() {
			$(".btn-search").click(function() {
				$(".header-top").css("display", "none");
				$(".header-search").css("display", "block");
// 				$(".header-search").css("height", "33px").removeClass("hide");
			});
			$(".lps-btn-close").click(function() {
				$(".header-top").css("display", "block");
				$(".header-search").css("display", "none");
// 				$(".header-search").css("height", "0").addClass("hide");
			});
			$(".search-del").click(function() {
				$("#glk").val("");
			});
			$("#form-search-global").submit(function(e) {
				if ( $.trim($("#glk").val()) == "" ) {
					alert("검색어를 입력해 주십시오.");
					$("#glk").focus();
					return false;
				} 
			});
		});
		</script>

	</body>
</html>

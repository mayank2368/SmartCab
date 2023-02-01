<?php
	session_start();
	require("process/BaseUrl.php");
    $url = url();
    session_unset();
    session_destroy();
	echo "<script>window.location.href='$url'</script>";
?>
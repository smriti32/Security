<?php
session_start();
$_SESSION['login']=="";
session_regenerate_id(true);
session_unset();
session_destroy();

?>
<script language="javascript">
document.location="../../index.php";
</script>

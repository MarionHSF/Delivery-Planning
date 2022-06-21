<?php

use Translation\Translation;
Translation::setLocalesDir($_SERVER['DOCUMENT_ROOT'].'/locales');

?>

<footer class="d-flex justify-content-center bg-primary pt-3 mt-5 position-fixed fixed-bottom">
    <p>© Henry Schein France&emsp;-&emsp;</p>
    <a href="/privacy_policy.php" class="text-dark"><?= Translation::of('privacyPolicy') ?></a>
</footer>

<script src="/js/general.js" type="text/javascript"></script>
<script src="/js/user.js" type="text/javascript"></script>

</body>
</html>

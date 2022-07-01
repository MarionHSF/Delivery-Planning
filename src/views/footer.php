<?php

use Translation\Translation;
Translation::setLocalesDir($_SERVER['DOCUMENT_ROOT'].'/locales');

?>

<footer class="d-flex justify-content-center bg-primary pt-3 mt-5 position-fixed fixed-bottom d-print-none">
    <p>© Henry Schein France&emsp;-&emsp;</p>
    <a href="/privacy_policy.php" class="text-dark text-decoration-none"><?= Translation::of('privacyPolicy') ?>&emsp;-&emsp;</a>
    <a href="/contact_us.php" class="text-dark text-decoration-none"><?= Translation::of('contactUs') ?></a>
</footer>

<script src="/js/event.js" type="text/javascript"></script>
<script src="/js/general.js" type="text/javascript"></script>
<script src="/js/user.js" type="text/javascript"></script>

</body>
</html>

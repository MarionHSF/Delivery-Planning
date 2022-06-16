<?php

use Translation\Translation;
Translation::setLocalesDir($_SERVER['DOCUMENT_ROOT'].'/locales');
if (isset($_SESSION['lang'])){
    Translation::forceLanguage($_SESSION['lang']);
}

?>

<footer class="d-flex justify-content-center bg-primary pt-3 mt-5 position-fixed fixed-bottom">
    <p>© Henry Schein France&emsp;-&emsp;</p>
    <a href="/privacy_policy.php" class="text-dark"><?= Translation::of('privacyPolicy') ?></a>
</footer>

</body>
</html>

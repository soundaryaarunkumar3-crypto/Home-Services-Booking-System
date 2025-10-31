<?php
// verify.php (one-time use)
$hash = '$2y$12$CozECpTmtklhlXZTkU6k8udAJ724L//8Orm.TTIInH.vUIURv0LkK';
$try = 'Test@123'; // change this to the password you think is correct
if (password_verify($try, $hash)) {
    echo "MATCH — password is '$try'.";
} else {
    echo "NO MATCH for '$try'.";
}
?>

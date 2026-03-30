<?php
try {
    $pdo = new PDO(
        'mysql:host=sql211.infinityfree.com;dbname=if0_41486233_gloria_db;charset=utf8',
        'if0_41486233',
        'TehWhq74zu6tB8'
    );
    echo '✅ الاتصال ناجح!';
} catch (PDOException $e) {
    echo '❌ فشل: ' . $e->getMessage();
}
?>

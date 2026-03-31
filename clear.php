<?php
// مسح الكاش
$files = [
    '../bootstrap/cache/config.php',
    '../bootstrap/cache/packages.php',
    '../bootstrap/cache/routes-v7.php',
    '../bootstrap/cache/services.php',
];

foreach ($files as $file) {
    if (file_exists($file)) {
        unlink($file);
        echo "تم حذف: " . basename($file) . "<br>";
    }
}

echo "<br>✅ تم مسح الكاش بنجاح!";

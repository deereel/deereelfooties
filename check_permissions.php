<?php
require_once 'auth/db.php';

echo "Current Permissions in Database:\n";
echo "================================\n";

$perms = fetchData('permissions', [], 'name, module', 'module ASC, name ASC');

$currentModule = '';
foreach($perms as $p) {
    if ($p['module'] !== $currentModule) {
        echo "\n{$p['module']}:\n";
        $currentModule = $p['module'];
    }
    echo "  - {$p['name']}\n";
}

echo "\nTotal permissions: " . count($perms) . "\n";
?>

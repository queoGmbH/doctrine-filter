<?php

$pattern = '@\d+\.\d+@';

preg_match($pattern, PHP_VERSION, $match);
$phpVersionString = str_replace('.', '_', $match[0]);

$baselineFile = __DIR__ . '/build/config/phpstan-baseline.neon';
$versionBaselineFile = __DIR__ . '/build/config/phpstan-baseline-php' . $phpVersionString . '.neon';

$copySuccess = copy($versionBaselineFile, $baselineFile);

if (!$copySuccess) {
    exit(1);
}

exit(0);
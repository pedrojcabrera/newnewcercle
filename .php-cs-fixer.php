<?php

return PhpCsFixer\Config::create()
 ->setRules([
  '@PSR2'           => true,
  'echo_tag_syntax' => ['format' => 'short'], // Mantiene <?= en lugar de <?php echo
 ])
 ->setIndent("    ")
 ->setLineEnding("\n");

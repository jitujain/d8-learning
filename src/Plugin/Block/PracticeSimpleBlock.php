<?php

namespace Drupal\inno_practice\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'PracticeSimpleBlock' block.
 *
 * @Block(
 *  id = "practice_simple_block",
 *  admin_label = @Translation("Practice simple block"),
 * )
 */
class PracticeSimpleBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];
    $build['practice_simple_block']['#markup'] = 'Implement PracticeSimpleBlock.';

    return $build;
  }

}

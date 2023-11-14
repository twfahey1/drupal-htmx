<?php

namespace Drupal\htmx\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides an example sse block.
 *
 * @Block(
 *   id = "htmx_example_sse",
 *   admin_label = @Translation("Example SSE"),
 *   category = @Translation("HTMX")
 * )
 */
class ExampleSseBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build['content'] = [
      '#theme' => 'htmx_sse_example',
    ];
    // Attach htmx/htmx library
    $build['content']['#attached']['library'][] = 'htmx/htmx';
    return $build;
  }

}

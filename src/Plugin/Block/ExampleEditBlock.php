<?php

namespace Drupal\htmx\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides an example edit block.
 *
 * @Block(
 *   id = "htmx_example_edit",
 *   admin_label = @Translation("Example Edit"),
 *   category = @Translation("HTMX")
 * )
 */
class ExampleEditBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
     // Load the current user
    $user = \Drupal::currentUser();
    // Getr dcrrnet user email
    $user_email = $user->getEmail();
    $build['content'] = [
      '#theme' => 'htmx_example_edit',
      '#user_email' => $user_email,
    ];
    // Cache to where only update if the user email changed
    $build['#cache']['keys'][] = 'htmx_example_edit:' . $user_email;

    // Cache it by the user's email address
    // $build['#cache']['contexts'][] = 'user';
    // Make it uncacheable
    // $build['#cache']['max-age'] = 0;
    // Attach htmx/htmx library
    $build['content']['#attached']['library'][] = 'htmx/htmx';
    return $build;
  }

}

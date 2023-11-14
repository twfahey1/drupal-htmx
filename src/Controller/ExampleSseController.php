<?php

namespace Drupal\htmx\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Returns responses for HTMX routes.
 */
class ExampleSseController extends ControllerBase
{

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The controller constructor.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager)
  {
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container)
  {
    return new static(
      $container->get('entity_type.manager')
    );
  }

  /**
   * Builds the response.
   */
  public function build()
  {
    $response = new StreamedResponse(function () {
      $counter = rand(1, 10);
      while (true) {
        // Event logic
        $curDate = date(DATE_ISO8601);
        echo "event: ping\n";
        // echo 'data: {"time": "' . $curDate . '"}' . "\n\n";
        echo 'data: <div>date: ' . $curDate . '</div>' . "\n\n";

        $counter--;
        if (!$counter) {
          echo 'data:  <div>date: ' . $curDate . '</div>' . "\n\n";
          $counter = rand(1, 10);
        }

        ob_flush();
        flush();

        // Connection management
        if (connection_aborted()) break;

        sleep(1);
      }
    });

    $response->headers->set('Content-Type', 'text/event-stream');
    $response->headers->set('Cache-Control', 'no-store');
    return $response;
  }
}

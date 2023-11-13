<?php

namespace Drupal\htmx\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Render\HtmlResponse;
use Drupal\Core\Session\AccountInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * Returns responses for HTMX routes.
 */
class ExampleEditController extends ControllerBase {

  /**
   * The current user.
   *
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected $account;

  /**
   * The controller constructor.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The current user.
   */
  public function __construct(AccountInterface $account) {
    $this->account = $account;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('current_user')
    );
  }

  /**
   * Builds the response.
   */
  public function build() {

    // Depending on the httpo method switch
    switch (\Drupal::request()->getMethod()) {
      case 'GET':
        return $this->get();
        break;
      case 'PUT':
        return $this->put();
        break;
    }

  }

  public function put() {
    // We can have form data posted like "firstName=Joe&lastName=Blow&email=joe%40blow.com"

    // Lets get the raw post data
    $raw_post_data = file_get_contents('php://input');

    // Lets parse the raw post data into an array
    parse_str($raw_post_data, $parsed_post_data);

    // Load the current user
    $user = \Drupal\user\Entity\User::load($this->account->id());

    // Set the email address
    $user->setEmail($parsed_post_data['email']);

    // Save the user
    $user->save();

    // We'd want to invalidate keys where we might have set like this:
    // $build['#cache']['keys'][] = 'htmx_example_edit:' . $user_email;
    // Begin:
    // Invalidate the cache for the user
    \Drupal::service('cache_tags.invalidator')->invalidateTags(['htmx_example_edit:' . $user->getEmail()]);

    
    // Return a Response with like <h1> Thanks for submitting </h1>
    // Lets render the theme for 
    // Lets return a raw html response from the render of a theme htmx_example_edit_form via renderer
    $array_to_render = [
      '#theme' => 'htmx_example_edit',
      '#user_email' => $parsed_post_data['email'],
      // '#form' => $this->formBuilder()->getForm('Drupal\htmx\Form\ExampleEditForm'),
    ];
    $rendered = \Drupal::service('renderer')->render($array_to_render);
    $response = new Response($rendered);
    return $response;
  }

  /**
   * Builds the response for GET requests.
   */
  public function get() {
    // Load the current user
    $user = \Drupal\user\Entity\User::load($this->account->id());
    
    $user_email = $user->getEmail();
    
    // Lets return a raw html response from the render of a theme htmx_example_edit_form via renderer
    $array_to_render = [
      '#theme' => 'htmx_example_edit_form',
      '#user_email' => $user_email,
      // '#form' => $this->formBuilder()->getForm('Drupal\htmx\Form\ExampleEditForm'),
    ];
    $rendered = \Drupal::service('renderer')->render($array_to_render);
    $response = new Response($rendered);
    return $response;
  }



}

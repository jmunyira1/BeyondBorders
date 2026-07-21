<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 *
 * Extend this class in any new controllers:
 * ```
 *     class Home extends BaseController
 * ```
 *
 * For security, be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */

    protected $session;

    /**
     * Available in every controller and every view rendered from one.
     * `setting` gives views the site() helper; `site` is our own.
     */
    protected $helpers = ['form', 'url', 'setting', 'site'];

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Caution: Do not edit this line.
        parent::initController($request, $response, $logger);

        $this->session = service('session');
    }

    /**
     * Renders a view, but returns only the named fragment when the request came
     * from htmx. Controllers that support partial swaps call this instead of
     * view() so the same action serves both a full page load and an hx-get.
     */
    protected function renderPage(string $fullView, string $fragmentView, array $data = []): string
    {
        return view(is_htmx() ? $fragmentView : $fullView, $data);
    }
}

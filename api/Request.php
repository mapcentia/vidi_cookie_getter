<?php
/**
 * @author     Martin Høgh <mh@mapcentia.com>
 * @copyright  2013-2021 MapCentia ApS
 * @license    http://www.gnu.org/licenses/#AGPL  GNU AFFERO GENERAL PUBLIC LICENSE 3
 *
 */

namespace app\extensions\vidi_cookie_getter\api;

use app\conf\App;
use app\inc\Controller;
use app\inc\Input;
use Exception;
use GuzzleHttp\Client;


/**
 * Class Request
 * @package app\extensions\vidi_cookie_getter\api
 */
class Request extends Controller
{
    function __construct()
    {
        parent::__construct();
    }

    /**
     * @return array<bool|string>
     */
    public function get_index(): array
    {
        $client = new Client(['cookies' => true]);
        $input = [
            'user' => Input::get("user"),
            'password' => Input::get("password"),
            'database' => Input::get("database"),
            'schema' => "public",
        ];

        try {
            $client->post(App::$param["vidiUrl"], [
                'headers' => array('Content-Type' => 'application/json'),
                'json' => $input]);
            $cookieJar = $client->getConfig('cookies');
        } catch (Exception $error) {
            return [
                "success" => false,
                "code" => "400",
                "message" => $error->getMessage(),
            ];
        }
        return [
            "success" => true,
            "session" => ($cookieJar->toArray()[0]['Value']),
        ];
    }
}
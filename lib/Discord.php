<?php

/*
 * Discord Web API client.
 *
 * @author     Xeloses (https://github.com/Xeloses)
 * @package    DiscordWebClient (https://github.com/Xeloses/discord-web-client)
 * @version    1.0
 * @copyright  Xeloses 2020
 * @license    GNU GPL v3 (https://www.gnu.org/licenses/gpl-3.0.html)
 */

namespace Xeloses\DiscordWebClient;

use WebSocket\Client as WebsocketClient;
use Xeloses\RESTy\REST_Client;
use Xeloses\DiscordWebClient\Classes\DiscordEntity;
use Xeloses\DiscordWebClient\Classes\DiscordModel;
use Xeloses\DiscordWebClient\Exceptions\DiscordException;
use Xeloses\DiscordWebClient\Exceptions\GatewayException;

/**
 * Discord class
 *
 * @package DiscordWebClient
 *
 * @uses WebSocket
 * @uses Xeloses\RESTy
 *
 * @method static void          init(string $token)
 * @method static string        getApiUrl()
 * @method static void          setApiBaseUrl(string $url)
 * @method static void          setApiVersion(int $version)
 * @method static string        getCdnUrl()
 * @method static void          setCdnUrl()
 * @method static REST_Client   getApiClient()
 * @method static DiscordEntity create(string $entity_name, ?object $data)
 * @method static DiscordModel  connect(string $model_name, string $id)
 * @method static object        identify(?string $client_name)
 */

final class Discord
{
    /**
     * Server message notification Level.
     *
     * @const int
     */
    const SERVER_NOTIFICATIONS_ALL_MESSAGES  = 0;
    const SERVER_NOTIFICATIONS_ONLY_MENTIONS = 1;

    /**
     * Server content filter Level.
     *
     * @const int
     */
    const SERVER_CONTENT_FILTER_DISABLED              = 0;
    const SERVER_CONTENT_FILTER_MEMBERS_WITHOUT_ROLES = 1;
    const SERVER_CONTENT_FILTER_ALL_MEMBERS           = 2;

    /**
     * Server verification Level.
     *
     * @const int
     */
    const SERVER_VERIFICATION_NONE      = 0; // unrestricted
    const SERVER_VERIFICATION_LOW       = 1; // user must have verified email on account
    const SERVER_VERIFICATION_MEDIUM    = 2; // user must be registered on Discord for longer than 5 minutes
    const SERVER_VERIFICATION_HIGH      = 3; // user must be a member of the server for longer than 10 minutes
    const SERVER_VERIFICATION_VERY_HIGH = 4; // user must have a verified phone number

    /**
     * Server premium tier.
     *
     * @const int
     */
    const SERVER_PREMIUM_NONE   = 0;
    const SERVER_PREMIUM_TIER_1 = 1;
    const SERVER_PREMIUM_TIER_2 = 2;
    const SERVER_PREMIUM_TIER_3 = 3;

    /**
     * Channel type.
     *
     * @const int
     */
    const CHANNEL_GUILD_TEXT     = 0;  // text channel within a server
    const CHANNEL_DM             = 1;  // direct message between users
    const CHANNEL_GUILD_VOICE    = 2;  // voice channel within a server
    const CHANNEL_GROUP_DM       = 3;  // direct message between multiple users
    const CHANNEL_GUILD_CATEGORY = 4;  // category that contains up to 50 channels
    const CHANNEL_GUILD_NEWS     = 5;  // channel that users can follow and crosspost into their own server
    const CHANNEL_GUILD_STORE    = 6;  // channel in which game developers can sell their game on Discord

    /**
     * User flags.
     *
     * @const int
     */
    const USER_FLAG_EMPLOYEE               = 1;    // Discord Employee
    const USER_FLAG_PARTNER                = 2;    // Discord Partner
    const USER_FLAG_HYPESQUAD              = 4;    // HypeSquad Events
    const USER_FLAG_BUG_HUNTER1            = 8;    // Bug Hunter Level 1
    const USER_FLAG_HOUSE_BRAVERY          = 16;   // House Bravery
    const USER_FLAG_HOUSE_BRILLIANCE       = 32;   // House Brilliance
    const USER_FLAG_HOUSE_BALANCE          = 64;   // House Balance
    const USER_FLAG_EARLY_SUPPORTER        = 128;  // Early Supporter
    const USER_FLAG_TEAM                   = 256;  // Team User
    const USER_FLAG_SYSTEM                 = 512;  // System
    const USER_FLAG_BUG_HUNTER2            = 1024; // Bug Hunter Level 2
    const USER_FLAG_VERIFIED_BOT           = 2048; // Verified Bot
    const USER_FLAG_VERIFIED_BOT_DEVELOPER = 4096; // Verified Bot Developer

    /**
     * Message type.
     *
     * @const int
     */
    const MESSAGE_DEFAULT                                = 0;
    const MESSAGE_RECIPIENT_ADD                          = 1;
    const MESSAGE_RECIPIENT_REMOVE                       = 2;
    const MESSAGE_CALL                                   = 3;
    const MESSAGE_CHANNEL_NAME_CHANGE                    = 4;
    const MESSAGE_CHANNEL_ICON_CHANGE                    = 5;
    const MESSAGE_CHANNEL_PINNED_MESSAGE                 = 6;
    const MESSAGE_GUILD_MEMBER_JOIN                      = 7;
    const MESSAGE_USER_PREMIUM_GUILD_SUBSCRIPTION        = 8;
    const MESSAGE_USER_PREMIUM_GUILD_SUBSCRIPTION_TIER_1 = 9;
    const MESSAGE_USER_PREMIUM_GUILD_SUBSCRIPTION_TIER_2 = 10;
    const MESSAGE_USER_PREMIUM_GUILD_SUBSCRIPTION_TIER_3 = 11;
    const MESSAGE_CHANNEL_FOLLOW_ADD                     = 12;
    const MESSAGE_GUILD_DISCOVERY_DISQUALIFIED           = 14;
    const MESSAGE_GUILD_DISCOVERY_REQUALIFIED            = 15;

    /**
     * Message flags.
     *
     * @const int
     */
    const MESSAGE_CROSSPOSTED            = 1;  // this message has been published to subscribed channels (via Channel Following)
    const MESSAGE_IS_CROSSPOST           = 2;  // this message originated from a message in another channel (via Channel Following)
    const MESSAGE_SUPPRESS_EMBEDS        = 4;  // do not include any embeds when serializing this message
    const MESSAGE_SOURCE_MESSAGE_DELETED = 8;  // the source message for this crosspost has been deleted (via Channel Following)
    const MESSAGE_URGENT                 = 16; // this message came from the urgent message system

    /**
     * Client name.
     *
     * @const string
     */
    const APP_NAME = 'https://github.com/Xeloses/discord-web-client';

    /**
     * Client version.
     *
     * @const array
     */
    const APP_VERSION = [
        'major' => 1,
        'minor' => 0
    ];

    /**
     * Models namespace.
     *
     * @const string
     */
    const MODELS_NAMESPACE = '\\Xeloses\\DiscordWebClient\\Models\\';

    /**
     * Classes namespace.
     *
     * @const string
     */
    const CLASSES_NAMESPACE = '\\Xeloses\\DiscordWebClient\\Classes\\';

    /**
     * Discord Web API base URL.
     *
     * @var string
     */
    private static $WEB_API_BASE_URL = 'https://discord.com/api/';

    /**
     * Discord Web API version.
     *
     * @var string
     */
    private static $WEB_API_VERSION = 6;

    /**
     * Discord CDN base URL.
     *
     * @var string
     */
    private static $CDN_URL = 'https://cdn.discordapp.com/';

    /**
     * REST API client instance.
     *
     * @var REST_Client
     */
    private static $WEB_API_CLIENT;

    /**
     * Discord application token.
     *
     * @var string
     */
    private static $TOKEN;

    /**
     * Object initialization.
     *
     * @param string $token
     *
     * @return void
     *
     * @throws DiscordException
     */
    public static function init(string $token = ''): void
    {
        if(empty($token))
        {
            throw new DiscordException('Discord application token required.');
        }

        self::$TOKEN = $token;

        self::$WEB_API_CLIENT = new REST_Client(self::getApiUrl());
        self::$WEB_API_CLIENT->addHeaders([
            'Authorization' => 'Bot '.$token,
            'User-Agent'    => 'DiscordBot ('.self::APP_NAME.', v'.implode('.',self::APP_VERSION).')',
        ]);
    }

    /**
     * Get Discord Web API URL.
     *
     * @return string
     */
    public static function getApiUrl(): string
    {
        return self::$WEB_API_BASE_URL.'v'.self::$WEB_API_VERSION.'/';
    }

    /**
     * Set Discord Web API base URL.
     *
     * @param string $url
     *
     * @return void
     *
     * @throws InvalidArgumentException
     */
    public static function setApiBaseUrl(string $url): void
    {
        if(!filter_var($url,FILTER_VALIDATE_URL,FILTER_FLAG_SCHEME_REQUIRED|FILTER_FLAG_HOST_REQUIRED))
        {
            throw new \InvalidArgumentException('Invalid URL.');
        }
        self::$WEB_API_BASE_URL = rtrim($url,'/').'/';
    }

    /**
     * Set Discord Web API version.
     *
     * @param int $version
     *
     * @return void
     *
     * @throws InvalidArgumentException
     */
    public static function setApiVersion(int $version): void
    {
        if(!$version)
        {
            throw new \InvalidArgumentException('Invalid version.');
        }
        self::$WEB_API_VERSION = $version;
    }

    /**
     * Get Discord CDN URL.
     *
     * @return string
     */
    public static function getCdnUrl(): string
    {
        return self::$CDN_URL;
    }

    /**
     * Set Discord CDN URL.
     *
     * @param string $url
     *
     * @return void
     *
     * @throws InvalidArgumentException
     */
    public static function setCdnUrl(string $url): void
    {
        if(!filter_var($url,FILTER_VALIDATE_URL,FILTER_FLAG_SCHEME_REQUIRED|FILTER_FLAG_HOST_REQUIRED))
        {
            throw new \InvalidArgumentException('Invalid URL.');
        }
        self::$CDN_URL = rtrim($url,'/').'/';
    }

    /**
     * Get REST API client instance.
     *
     * @internal
     *
     * @return REST_Client
     *
     * @throws DiscordException
     */
    public static function getApiClient(): REST_Client
    {
        if(!self::$WEB_API_CLIENT)
        {
            throw new DiscordException('Client wasn\'t initialized.');
        }

        return self::$WEB_API_CLIENT;
    }

    /**
     * Class factory.
     * Create and return DiscordEntity descendant class instance.
     *
     * @param string $entity_name
     * @param object $data
     *
     * @return DiscordEntity
     *
     * @throws InvalidArgumentException
     */
    public static function create(string $entity_name, \stdClass $data = null): DiscordEntity
    {
        $entity_name = self::validateClassname($entity_name);

        if(!is_subclass_of($entity_name,self::CLASSES_NAMESPACE.'DiscordEntity'))
        {
            throw new \InvalidArgumentException('Wrong factory for class "'.$entity_name.'", DiscordEntity descendant class required.');
        }
        if(is_subclass_of($entity_name,self::CLASSES_NAMESPACE.'DiscordModel'))
        {
            throw new \InvalidArgumentException('Wrong factory for class "'.$entity_name.'", to create DiscordModel use ::connect() method.');
        }

        return new $entity_name($data);
    }

    /**
     * Class factory.
     * Create and return DiscordModel descendant class instance.
     *
     * @param string $entity_name
     * @param string $id
     *
     * @return DiscordModel
     *
     * @throws InvalidArgumentException
     */
    public static function connect(string $model_name, string $id): DiscordModel
    {
        $model_name = self::validateClassname($model_name);

        if(!is_subclass_of($model_name,self::CLASSES_NAMESPACE.'DiscordModel'))
        {
            throw new \InvalidArgumentException('Wrong factory for class "'.$model_name.'", DiscordModel descendant class required.');
        }

        return new $model_name($id);
    }

    /**
     * Connect to Discord bots Gateway and identify bot at Gateway (identification required for posting messages from bot).
     *
     * @IMPORTAMT Identification should be done once per bot (not per session!) before first attempt to post message.
     *
     * @param string $client_name
     *
     * @return object Bot and Session associated data from Discord Gateway response for "Identify" request (https://discord.com/developers/docs/topics/gateway#ready).
     *
     * @throws GatewayException
     */
    public static function identify(string $client_name = ''): object
    {
        if(!$client_name){
            $client_name = str_replace('\\','.',__NAMESPACE__);
        }

        // Discord Web API request to get Discord Gateway websocket address:
        $gateway = self::getApiClient()->get('gateway/bot');
        if($gateway && isset($gateway->url))
        {
            // assemble Discord Gateway websocket address:
            $gateway_uri = $gateway->url.'?v='.self::$WEB_API_VERSION.'&encoding=json';

            // create context for websocket connection:
            $context = stream_context_create();
            stream_context_set_option($context,'ssl','verify_peer',false);
            stream_context_set_option($context,'ssl','verify_peer_name',false);

            // connect to Discord Gateway websocket:
            $ws_client = new WebsocketClient($gateway_uri,[
                                'timeout'       => 10,
                                'fragment_size' => 1024,
                                'context'       => $context
                            ]);

            // read response from Discord Gateway:
            $response = self::processGatewayResponse($ws_client->receive());

            // check response opcode:
            if($response->op != 10)
            {
                throw new GatewayException('Gateway server does not return required opcode with response: '.PHP_EOL.print_r($response,true));
            }

            // prepare "Identification" data for Discord Gateway:
            $identify = '{'.PHP_EOL.
                            '"op": 2,'.PHP_EOL.
                            '"d": {'.PHP_EOL.
                                '"token": "'.self::$TOKEN.'",'.PHP_EOL.
                                '"properties": {'.PHP_EOL.
                                    '"$os": "'.strtolower(PHP_OS).'",'.PHP_EOL.
                                    '"$browser": "'.$client_name.'",'.PHP_EOL.
                                    '"$device": "'.$client_name.'"'.PHP_EOL.
                                '}'.PHP_EOL.
                            '}'.PHP_EOL.
                        '}';

            // send "Identify" request to Discord Gateway:
            $ws_client->send($identify);

            // read response from Discord Gateway:
            $response = self::processGatewayResponse($ws_client->receive());

            // check response state and data:
            if(strtolower($response->t) != 'ready' || !isset($response->d) || !isset($response->d->session_id))
            {
                throw new GatewayException('Gateway server does not return required state with response: '.PHP_EOL.print_r($response,true));
            }

            // return response object:
            unset($response->d->_trace);
            return $response->d;
        }
    }

    /**
     * Validate class name and check class is exists.
     *
     * @internal
     *
     * @param string $classname
     *
     * @return string
     */
    private static function validateClassname(string $classname): string
    {
        if(strpos($classname,'Discord') === false)
        {
            $classname = 'Discord'.ucfirst(strtolower($classname));
        }

        $classname = self::MODELS_NAMESPACE.$classname;

        return $classname;
    }

    /**
     * Check Gateway response and parse it into object.
     *
     * @internal
     *
     * @param string $response
     *
     * @return object
     *
     * @throws GatewayException
     */
    private static function processGatewayResponse(string $response = ''): object
    {
        if(preg_match('/^\s*({\s*})?\s*$/',$response))
        {
            throw new GatewayException('Gateway server does not responding.');
        }

        $result = json_decode($response,false,512,JSON_BIGINT_AS_STRING);

        if(!is_object($result) && !is_array($result))
        {
            throw new GatewayException('Bad response from Gateway server: '.PHP_EOL.$response);
        }

        if(!isset($result->op))
        {
            throw new GatewayException('Unexpected response from Gateway server: '.PHP_EOL.$response);
        }

        return $result;
    }

    /**
     * Handles dynamic attempts to convert object to string.
     *
     * @return string
     */
    public function __toString(): string
    {
        return self::APP_NAME.', v'.implode('.',self::APP_VERSION);
    }

    /**
     * Block constructor/cloning/serialization.
     */
    private function __construct(){}
    private function __clone(){}
    private function __sleep(){}
    private function __wakeup(){}
}
?>

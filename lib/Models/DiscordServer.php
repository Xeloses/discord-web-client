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

namespace Xeloses\DiscordWebClient\Models;

use Xeloses\DiscordWebClient\Discord;
use Xeloses\DiscordWebClient\Classes\DiscordModel;

/**
 * DiscordServer class
 *
 * @package    DiscordWebClient
 * @subpackage DiscordModels
 *
 * @property string id
 * @property string name
 * @property string description                // (nullable)
 * @property string vanity_url_code            // (nullable)
 * @property int    owner_id
 * @property string icon                       // (nullable)
 * @property string splash                     // (nullable)
 * @property string banner                     // (nullable)
 * @property string region
 * @property string preferred_locale
 * @property int    verification_level
 * @property int    premium_tier
 * @property int    premium_subscription_count // (optional)
 * @property int    max_members                // (optional)
 * @property int    max_presences              // (optional,nullable)
 * @property array  roles                      //                     -> object[]
 * @property array  emojis                     //                     -> object[]
 * @property array  features                   //                     -> string[]
 * @property string rules_channel_id            // (nullable)
 * @property string system_channel_id           // (nullable)
 * @property string public_updates_channel_id   // (nullable)
 * @property string afk_channel_id              // (nullable)
 * @property int    afk_timeout
 * @property bool   widget_enabled              // (optional)
 * @property string widget_channel_id           // (optional,nullable)
 * @property int    explicit_content_filter
 */

class DiscordServer extends DiscordModel
{
    /**
     * REST API endpoint.
     *
     * @var string
     */
    protected $endpoint = '/guilds/';

    /**
     * Properties to be hidden.
     *
     * @var array
     */
    protected $hidden = [
        'owner',
        'permissions',
        'embed_enabled',   // @deprecated
        'embed_channel_id' // @deprecated
    ];
}
?>

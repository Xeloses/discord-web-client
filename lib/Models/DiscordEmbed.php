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

use Xeloses\DiscordWebClient\Classes\DiscordEntity;

/**
 * DiscordEmbed class
 *
 * @package    DiscordWebClient
 * @subpackage DiscordModels
 *
 * @property string   title        // (optional)
 * @property string   type         // (optional)
 * @property string   description  // (optional)
 * @property string   url          // (optional)
 * @property DateTime timestamp    // (optional)
 * @property int      color        // (optional) color code
 * @property object   author       // (optional)
 * @property object   provider     // (optional)
 * @property object   image        // (optional)
 * @property object   video        // (optional)
 * @property object   thumbnail    // (optional)
 * @property object   footer       // (optional)
 * @property array    fields       // (optional) -> object[]
 */

class DiscordEmbed extends DiscordEntity
{
    /**
     * Properties to be converted to DateTime.
     *
     * @var array
     */
    protected $timestamps = [
        'timestamp'
    ];
}
?>

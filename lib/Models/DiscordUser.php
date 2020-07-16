<?php

/*
 * Discord Web API client.
 *
 * @author     Xeloses (https://github.com/Xeloses)
 * @package    DiscordMiniClient (https://github.com/Xeloses/discord-web-client)
 * @version    1.0
 * @copyright  Xeloses 2020
 * @license    GNU GPL v3 (https://www.gnu.org/licenses/gpl-3.0.html)
 */

namespace Xeloses\DiscordWebClient\Models;

use Xeloses\DiscordWebClient\Classes\DiscordEntity;

/**
 * DiscordUser class
 *
 * @package    DiscordWebClient
 * @subpackage DiscordModels
 *
 * @property string id
 * @property string username       //            username, not unique across the platform
 * @property string discriminator  //            user's 4-digit discord-tag
 * @property string avatar         // (nullable)
 * @property string locale         // (optional) user's chosen language option
 * @property bool   verified       // (optional) email on this account has been verified
 * @property bool   bot            // (optional)
 * @property bool   system         // (optional) user is an Official Discord System user (part of the urgent message system)
 * @property int    flags          // (optional)
 * @property int    public_flags   // (optional)
 *
 * @method string name()
 */

class DiscordUser extends DiscordEntity
{
    /**
     * Properties to be hidden.
     *
     * @var array
     */
    protected $hidden = [
        'email',
        'mfa_enabled',
        'premium_type'
    ];

    /**
     * Get full username in Discord format: username#discriminator
     *
     * @return string|null
     */
    public function name(): ?string
    {
        if($this->username && $this->discriminator)
        {
            return $this->username.'#'.$this->discriminator;
        }
        return null;
    }
}
?>

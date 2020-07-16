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
 * DiscordMember class
 *
 * @package    DiscordWebClient
 * @subpackage DiscordModels
 *
 * @property DiscordUser user           // (optional)
 * @property string      nick           // (nullable)          user's nickname on current server
 * @property DateTime    joined_at
 * @property DateTime    premium_since  // (optional,nullable) when the user started boosting the guild
 * @property array       roles          //                     -> string[]
 * @property bool        deaf           //                     user is deafened in voice channels
 * @property bool        mute           //                     user is muted in voice channels
 *
 * @method DiscordUser|null account()
 */

class DiscordMember extends DiscordEntity
{
    /**
     * Properties to be converted to DateTime.
     *
     * @var array
     */
    protected $timestamps = [
        'joined_at',
        'premium_since'
    ];

    /**
     * Properties to be converted to DiscordEntity descendant classes.
     *
     * @var array
     */
    protected $cast = [
        'DiscordUser' => ['user']
    ];

    /**
     * Get member's Discord account.
     *
     * @return DiscordUser|null
     */
    public function account()
    {
        if($this->user)
        {
            return $this->user;
        }
        return null;
    }
}
?>

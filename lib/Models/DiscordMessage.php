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
 * DiscordMessage class
 *
 * @package    DiscordWebClient
 * @subpackage DiscordModels
 *
 * @property string        id
 * @property string        channel_id
 * @property string        guild_id          // (optional) Server ID
 * @property int           type
 * @property DiscordUser   author
 * @property DiscordMember member            // (optional)
 * @property string        content
 * @property DateTime      timestamp
 * @property DateTime      edited_timestamp  // (nullable)
 * @property array         attachments       //            -> object[]
 * @property array         embeds            //            -> DiscordEmbed[]
 * @property array         reactions         // (optional) -> object[]
 * @property object        mentions
 * @property object        mention_roles
 * @property object        mention_channels  // (optional)
 * @property bool          pinned
 * @property int           flags             // (optional)
 *
 * @method DiscordMessage send(DiscordChannel $channel)
 * @method void           addEmbed(DiscordEmbed $embed)
 */

class DiscordMessage extends DiscordEntity
{
    /**
     * Read only properties (all IDs and Dates are read only by default).
     *
     * @var array
     */
    protected $locked = [
        'author',
        'member',
        'type',
        'nonce',
        'pinned',
        'flags'
    ];

    /**
     * Properties to be converted to DateTime.
     *
     * @var array
     */
    protected $timestamps = [
        'timestamp',
        'edited_timestamp'
    ];

    /**
     * Properties to be converted to DiscordEntity descendant classes.
     *
     * @var array
     */
    protected $cast = [
        'DiscordUser'   => ['author'],
        'DiscordMember' => ['member'],
        'DiscordEmbed'  => ['embeds']
    ];

    /**
     * Send message to Discord channel.
     *
     * @param DiscordChannel $channel
     *
     * @return DiscordMessage
     */
    public function send(DiscordChannel $channel): DiscordMessage
    {
        return $channel->sendMessage($this);
    }

    /**
     * Add embed to message.
     *
     * @param DiscordEmbed $embed
     *
     * @return void
     */
    public function addEmbed(DiscordEmbed $embed): void
    {
        $this->data->embeds[] = $embed;
    }
}
?>

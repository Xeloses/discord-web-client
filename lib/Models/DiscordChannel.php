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
 * DiscordChannel class
 *
 * @package    DiscordWebClient
 * @subpackage DiscordModels
 *
 * @property string id
 * @property string guild_id             // (optional)           Server ID
 * @property string parent_id            // (optional, nullable) Parent category ID (for nested channels)
 * @property int    type
 * @property string name                 // (optional)
 * @property string topic                // (optional, nullable)
 * @property string icon                 // (optional)
 * @property string last_message_id      // (optional, nullable) - only for Text/DM channels
 * @property bool   nsfw                 // (optional)           - only for Text channels
 * @property int    rate_limit_per_user  // (optional)           - only for Text channels
 * @property int    bitrate              // (optional)           - only for Voice channels
 * @property int    user_limit           // (optional)           - only for Voice channels
 * @property string owner_id             // (optional)           - only for DM chnnels
 * @property string application_id       // (optional)           - only for DM chnnels
 * @property array  recipients           // (optional)           - only for DM chnnels        -> DiscordUser[]
 *
 * @method int             loadMessages(?int $count, ?string $lastMessageID)
 * @method array           getMessages(?string $lastMessageID)
 * @method DiscordMessage  sendMessage(DiscordMessage $message)
 */

class DiscordChannel extends DiscordModel
{
    /**
     * REST API endpoint.
     *
     * @var string
     */
    protected $endpoint = '/channels/';

    /**
     * Properties to be hidden.
     *
     * @var array
     */
    protected $hidden = [
        'permission_overwrites'
    ];

    /**
     * Properties to be converted to objects.
     *
     * @var array
     */
    protected $cast = [
        'DiscordUser' => ['recipients']
    ];

    /**
     * Messages in channel.
     *
     * @var array
     */
    protected $messages = [];

    /**
     * ID of last loaded message.
     *
     * @var int
     */
    protected $last_message_id;

    /**
     * Load messages from channel and return count of loaded messages.
     *
     * @param int    $count          (optional; default: 10; max: 50; min: 5)
     * @param string $lastMessageID  (optional) load last messages if this param is empty and no messages was loaded before;
     *                                          load messages after last loaded message if this param is empty and any messages was loaded before.
     *
     * @return int
     */
    public function loadMessages(int $count = 10, string $lastMessageID = ''): int
    {
        $params = [
            'limit' => ($count > 50) ? 50 : (($count < 5) ? 5 : $count),
        ];

        if(!empty($lastMessageID))
        {
            $params['after'] = $lastMessageID;
        }
        elseif($this->last_message_id)
        {
            $params['after'] = $this->last_message_id;
        }

        $messages = array_reverse(Discord::getApiClient()->get('channels/'.$this->id.'/messages',$params));

        foreach($messages as $msg)
        {
            $message = Discord::create('message',$msg);
            if(!array_key_exists($message->id,$this->messages))
            {
                $this->messages[$message->id] = $message;
            }
            $this->last_message_id = $message->id;
        }

        return count($messages);
    }

    /**
     * Get loaded messages.
     * Load messages from channel if no messages was loaded before.
     * Loads all messages after $lastMessageID if specified or last 10 messages.
     *
     * @param string $lastMessageID (optional; default: empty)
     *
     * @return array
     */
    public function getMessages(string $lastMessageID = ''): array
    {
        if(!count($this->messages))
        {
            if($lastMessageID)
            {
                $count = 0;
                $limit = 50;
                do
                {
                    $count = $this->loadMessages($limit,$lastMessageID);
                }
                while($count == $limit);
            }
            else
            {
                $this->loadMessages(10);
            }
        }

        return $this->messages;
    }

    /**
     * Send message to current channel.
     *
     * @param DiscordMessage $message
     *
     * @return DiscordMessage|object|string
     */
    public function sendMessage(DiscordMessage $message)
    {
        $message = Discord::getApiClient()->customRequest(
            'channels/'.$this->id.'/messages',
            'POST',
            $message->toJson(),
            [
                'Content-Type' => 'application/json'
            ]
        );

        if(is_object($message) && isset($message->id))
        {
            return Discord::create('message',$message);
        }
        return $message;
    }
}
?>

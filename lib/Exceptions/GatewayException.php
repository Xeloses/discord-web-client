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

namespace Xeloses\DiscordWebClient\Exceptions;

/**
 * GatewayException.
 *
 * @package    DiscordWebClient
 * @subpackage DiscordExceptions
 */

class GatewayException extends DiscordBaseException
{
    /**
     * Default Exception message.
     *
     * @var string
     */
    protected $defaultMessage = 'Unknown Discord Gateway exception';
}
?>

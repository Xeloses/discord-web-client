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
 * IException interface.
 *
 * @package    DiscordWebClient
 * @subpackage DiscordExceptions
 */

interface IException
{
    public function __construct(string $message = '', int $code = 0);
    public function __toString(): string;

    public function getMessage();
    public function getCode();
    public function getFile();
    public function getLine();
    public function getTrace();
    public function getTraceAsString();
}
?>

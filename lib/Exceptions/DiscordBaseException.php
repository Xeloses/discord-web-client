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
 * DiscordBaseException abstract class.
 *
 * @package    DiscordWebClient
 * @subpackage DiscordExceptions
 */

abstract class DiscordBaseException extends \Exception implements IException
{
    /**
     * Exception message.
     *
     * @var string
     */
    protected $message;

    /**
     * Default Exception message.
     *
     * @var string
     */
    protected $defaultMessage = 'Unknown Discord exception';

    /**
     * Exception code.
     *
     * @var int
     */
    protected $code;

    /**
     * Source filename of exception.
     *
     * @var string
     */
    protected $file;

    /**
     * Source line of exception.
     *
     * @var int
     */
    protected $line;


    private   $trace;
    private   $string;

    /**
     * Constructor.
     *
     * @param string $message
     * @param int    $code
     */
    public function __construct(string $message = '', int $code = 0)
    {
        parent::__construct($message?:$this->defaultMessage,$code);
    }

    /**
     * Returns formatted Exception info.
     *
     * @return string
     */
    public function __toString(): string
    {
        return get_class($this).' "'.$this->message.'" in '.$this->file.':'.$this->line.PHP_EOL.$this->getTraceAsString();
    }
}
?>

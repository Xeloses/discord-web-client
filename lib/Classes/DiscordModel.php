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

namespace Xeloses\DiscordWebClient\Classes;

use Xeloses\DiscordWebClient\Discord;
use Xeloses\DiscordWebClient\Classes\DiscordEntity;

/**
 * DiscordModel abstract class
 *
 * @package    DiscordWebClient
 * @subpackage DiscordClasses
 *
 */
abstract class DiscordModel extends DiscordEntity
{

    /**
     * REST API endpoint.
     *
     * @var string
     */
    protected $endpoint;

    /**
     * Indicates object data to be loaded on request or in class constructor.
     *
     * @var bool
     */
    protected $lazy_load = true;

    /**
     * Indicates object data was loaded from Web API.
     *
     * @var bool
     */
    protected $loaded = false;

    /**
     * Indicates object properties are read only.
     *
     * @var bool
     */
    protected $readonly = true;

    /**
     * Constructor.
     *
     * @param string $id
     *
     * @throws InvalidArgumentException
     */
    public function __construct(string $id)
    {
        if(empty($id))
        {
            throw new \InvalidArgumentException('ID required.');
        }
        elseif(!preg_match('/^[\d]+$/',$id))
        {
            throw new \InvalidArgumentException('Invalid ID.');
        }

        parent::__construct();

        $this->data->id = $id;

        if(!$this->lazy_load){
            $this->load();
        }
    }

    /**
     * Load data from Discord Web API.
     *
     * @return void
     */
    protected function load(): void
    {
        $this->setData(Discord::getApiClient()->get(trim($this->endpoint,'/').'/'.$this->data->id));
        $this->loaded = true;
    }

    /**
     * Handles dynamic get calls to the object and load data from Discord Web API on request if "Lazy load" is TRUE.
     *
     * @param string $name
     *
     * @return mixed
     */
    public function __get(string $name)
    {
        $value = parent::__get($name);

        if(!$value && !$this->loaded)
        {
            $this->load();
            $value = parent::__get($name);
        }

        return $value;
    }
}
?>

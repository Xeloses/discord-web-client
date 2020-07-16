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

use JsonSerializable;
use Xeloses\DiscordWebClient\Discord;

/**
 * DiscordEntity abstract class
 *
 * @package    DiscordWebClient
 * @subpackage DiscordClasses
 */
abstract class DiscordEntity implements JsonSerializable
{
    /**
     * Discord object data.
     *
     * @var object
     */
    protected $data;

    /**
     * Indicates object properties are read only.
     *
     * @var bool
     */
    protected $readonly = false;

    /**
     * Properties to be hidden.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * Properties to be locked for modify (when "readonly" set to FALSE).
     * By default all IDs ("id" and "*_id"), Dates and object properties are locked.
     *
     * @var array
     */
    protected $locked = [];

    /**
     * Properties to be converted to DateTime.
     *
     * @var array
     */
    protected $timestamps = [];

    /**
     * Properties to be converted to DiscordEntity descendant classes.
     *
     * @var array
     */
    protected $cast = [];

    /**
     * Constructor.
     *
     * @param object $data Data to initialize object properties.
     */
    public function __construct(\stdClass $data = null)
    {
        if(is_object($data))
        {
            $this->setData($data);
        }
        else
        {
            $this->data = new \stdClass();
        }
    }

    /**
     * Return JSON encoded string with entity's data.
     *
     * @return string
     */
    public function toJson(): string
    {
        return empty($this->data)?'{}':json_encode($this->data,JSON_NUMERIC_CHECK|JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
    }

    /**
     * Set object data, process "hidden" properties, timestamps and cast nested objects.
     *
     * @internal
     *
     * @param object $data
     *
     * @return void
     */
    protected function setData(\stdClass $data): void
    {
        $this->data = $data;
        if(!empty($this->data))
        {
            $this->filterData();
            if(!empty($this->cast))
            {
                $this->castObjects();
            }
        }
    }

    /**
     * Remove hidden properties from object and convert timestamps to DateTime.
     *
     * @internal
     *
     * @return void
     */
    protected function filterData(): void
    {
        if(!empty($this->hidden))
        {
            foreach($this->hidden as $property)
            {
                if(isset($this->data->{$property}))
                {
                    unset($this->data->{$property});
                }
            }
        }

        if(!empty($this->timestamps))
        {
            foreach($this->timestamps as $property)
            {
                if($this->{$property})
                {
                    if(is_object($this->data->{$property}))
                    {
                        if(isset($this->data->{$property}->date))
                        {
                            $this->data->{$property} = new \DateTime($this->data->{$property}->date.((isset($this->data->{$property}->timezone) && !empty($this->data->{$property}->timezone))?$this->data->{$property}->timezone:''));
                        }
                    }
                    else
                    {
                        $this->data->{$property} = new \DateTime($this->data->{$property});
                    }
                }
            }
        }
    }

    /**
     * Convert object's properties to related DiscordEntity descendant class.
     *
     * @internal
     *
     * @return void
     */
    protected function castObjects(): void
    {
        foreach($this->cast as $classname => $properties)
        {
            if(class_exists('\\Xeloses\\DiscordWebClient\\Models\\'.$classname))
            {
                foreach($properties as $property)
                {
                    if(isset($this->data->{$property}))
                    {
                        if(is_object($this->data->{$property}))
                        {
                            // Property is Object:
                            $this->data->{$property} = Discord::create($classname,$this->data->{$property});
                        }
                        elseif(is_array($this->data->{$property}) && !empty($this->data->{$property}))
                        {
                            // Property is Array of Objects:
                            array_walk(
                                $this->data->{$property},
                                function(&$item,$key,$classname)
                                {
                                    if(is_object($item))
                                    {
                                        $item = Discord::create($classname,$item);
                                    }
                                },
                                $classname
                            );
                        }
                    }
                }
            }
        }
    }

    /**
     * Handles dynamic get calls to the object.
     *
     * @param string $name
     *
     * @return mixed
     */
    public function __get(string $name)
    {
        if(!$this->data)
        {
            return null;
        }

        $name = strtolower($name);
        if(array_key_exists($name,$this->data))
        {
            return $this->data->{$name};
        }

        return null;
    }

    /**
     * Handles dynamic set calls to the object.
     *
     * @param string $name
     * @param mixed  $value
     *
     * @return void
     *
     * @throws InvalidArgumentException
     */
    public function __set(string $name, $value): void
    {
        if($this->readonly)
        {
            throw new \InvalidArgumentException('Objects of class "'.substr(strrchr(get_class($this),'\\'),1).'" are read only.');
        }

        $name = strtolower($name);
        if($name == 'id' || substr($name,-3) == '_id' || in_array($name,$this->locked) || is_object($this->{$name}))
        {
            throw new \InvalidArgumentException('Property "'.$name.'" is read only.');
        }

        $this->data->{$name} = $value;
    }

    /**
     * Handles dynamic methods calls to the object.
     * Allows to get object properties values by call property name as method: $entity->property_name() or $entity->getProperty_name()
     * Returns NULL if property doesn't exists.
     *
     * @param string $name
     * @param array  $arguments
     *
     * @return mixed
     */
    public function __call(string $name, ?array $arguments)
    {
        $name = strtolower($name);
        if(substr($name,0,3) == 'get')
        {
            $name = substr($name,4);
        }

        return $this->{$name};
    }

    /**
     * Handles dynamic attempts to convert object to string.
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }

    /**
     * Specify data to be serialized when call json_encode on this object.
     *
     * @return mixed
     */
    public function jsonSerialize()
    {
        return $this->data;
    }
}
?>

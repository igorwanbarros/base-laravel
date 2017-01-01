<?php

namespace Igorwanbarros\BaseLaravel\Widgets;


class TabsManager implements \ArrayAccess, \IteratorAggregate
{

    protected $lists = [];


    public function add($name, $options = null) {
        if (is_array($name)) {
            $this->lists += $name;

            return $this;
        }

        if (!is_null($options)) {
            $this->lists[$name] += $options;
        }

        return $this;
    }

    /**
     * @param mixed $offset
     *
     * @return bool
     */
    public function offsetExists($offset)
    {
        return array_has($this->lists, $offset);
    }


    /**
     * @param mixed $offset
     *
     * @return null
     */
    public function offsetGet($offset)
    {
        return array_get($this->lists, $offset, []);
    }


    /**
     * @param mixed $offset
     * @param mixed $value
     *
     * @return $this
     */
    public function offsetSet($offset, $value)
    {
        array_set($this->lists, $offset, $value);

        return $this;
    }


    /**
     * @param mixed $offset
     *
     * @return $this
     */
    public function offsetUnset($offset)
    {
        array_forget($this->lists, $offset);

        return $this;
    }


    public function getIterator()
    {
        return new \ArrayIterator($this->lists);
    }
}

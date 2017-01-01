<?php

namespace Igorwanbarros\BaseLaravel\Widgets;

class AclsManager implements \ArrayAccess, \IteratorAggregate
{
    protected $acls = [];


    public function add(array $name)
    {
        $this->acls = array_merge($this->acls, $name);

        return $this;
    }

    public function toArray()
    {
        return $this->acls;
    }


    /**
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->acls);
    }



    /**
     * @param mixed $offset
     *
     * @return bool
     */
    public function offsetExists($offset)
    {
        return array_has($this->acls, $offset);
    }


    /**
     * @param mixed $offset
     *
     * @return null
     */
    public function offsetGet($offset)
    {
        return array_get($this->acls, $offset, []);
    }


    /**
     * @param mixed $offset
     * @param mixed $value
     *
     * @return $this
     */
    public function offsetSet($offset, $value)
    {
        array_set($this->acls, $offset, $value);

        return $this;
    }


    /**
     * @param mixed $offset
     *
     * @return $this
     */
    public function offsetUnset($offset)
    {
        array_forget($this->acls, $offset);

        return $this;
    }
}

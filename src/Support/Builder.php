<?php

namespace Unitable\Graham\Support;

abstract class Builder implements BuilderInterface {

    /**
     * @var array
     */
    public array $created;

    /**
     * Execute when an object is created.
     *
     * @param \Closure $closure
     * @return void
     */
    public function created(\Closure $closure) {
        if (!isset($this->created))
            $this->created = [];

        $this->created[] = $closure;
    }

    /**
     * Call the 'created' event listeners.
     *
     * @param $object
     */
    protected function dispatchCreated($object) {
        if (!isset($this->created)) return;

        foreach ($this->created as $listener) {
            call_user_func($listener, $object);
        }
    }

}

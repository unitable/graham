<?php

namespace Unitable\Graham\Support;

interface BuilderInterface {

    /**
     * Create the object.
     *
     * @return mixed
     */
    public function create();

    /**
     * Execute when an object is created.
     *
     * @param \Closure $closure
     * @return void
     */
    public function created(\Closure $closure);

}

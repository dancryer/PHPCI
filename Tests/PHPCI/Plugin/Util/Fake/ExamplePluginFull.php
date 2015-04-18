<?php

namespace Tests\PHPCI\Plugin\Util\Fixtures;

use PHPCI\Builder;
use PHPCI\Model\Build;
use PHPCI\Plugin;

class ExamplePluginFull implements Plugin {

    public $Options;

    public function __construct(
        Builder $phpci,
        Build $build,
        array $options = array()
    )
    {
        $this->Options = $options;
    }

    public function execute()
    {

    }

}

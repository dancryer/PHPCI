<?php

namespace Kiboko\Component\ContinuousIntegration;

use Kiboko\Component\ContinuousIntegration\Docker\Image;

interface Package
{
    /**
     * @param Image $image
     */
    public function register(Image $image);
}

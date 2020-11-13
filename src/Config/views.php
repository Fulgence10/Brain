<?php

use Brain\Renderer\PHPRenderer;
use Brain\Renderer\Renderer;
use Brain\Renderer\TintinRenderer;

return [
    IoRenderer::class => DI\create(TintinRenderer::class),

    PHPRenderer::class => DI\create(PHPRenderer::class)
                                ->constructor(\DI\get("path")),
    Renderer::class => DI\create(Renderer::class)
];
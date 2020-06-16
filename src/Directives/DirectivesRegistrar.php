<?php

namespace HuangYi\Rbac\Directives;

use Illuminate\View\Compilers\BladeCompiler;

abstract class DirectivesRegistrar
{
    /**
     * The blade compiler instance.
     *
     * @var \Illuminate\View\Compilers\BladeCompiler
     */
    protected $compiler;

    /**
     * The list of directives.
     *
     * @var array
     */
    protected $directives = [];

    /**
     * Create a new RoleRegistrar instance.
     *
     * @param  \Illuminate\View\Compilers\BladeCompiler  $compiler
     * @return void
     */
    public function __construct(BladeCompiler $compiler)
    {
        $this->compiler = $compiler;
    }

    /**
     * Register the blade directives.
     *
     * @return void
     */
    public function register()
    {
        foreach ($this->directives as $directive) {
            $this->compiler->directive($directive, [$this, $directive]);
        }
    }
}

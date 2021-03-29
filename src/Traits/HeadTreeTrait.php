<?php

declare(strict_types=1);

namespace App\Test\DataStructure\Traits;

use App\Test\DataStructure\Interfaces\HeadInterface;
use App\Test\DataStructure\Tree\Interfaces\TreeNodeInterface;

/**
 * Trait HeadTreeTrait
 */
trait HeadTreeTrait
{
    /**
     * @var TreeNodeInterface
     */
    protected $head;

    /**
     * {@inheritDoc}
     *
     * @return TreeNodeInterface
     */
    public function head(): TreeNodeInterface
    {
        return $this->head;
    }

    /**
     * {@inheritDoc}
     *
     * @param TreeNodeInterface|null $node
     *
     * @return static
     */
    public function setHead(TreeNodeInterface $node = null): HeadInterface
    {
        $node = $node ?? $this->last();

        $node->setParent();

        $this->head = $node;

        return $this->rewind()->free();
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function rewind(): HeadInterface
    {
        return $this->setCurrent($this->head());
    }

    /**
     * {@inheritDoc}
     *
     * @return bool
     */
    public function isHead(): bool
    {
        return $this->current() === $this->head();
    }

    /**
     * {@inheritDoc}
     *
     * @return bool
     */
    public function isEmpty(): bool
    {
        return $this->head === null;
    }
}

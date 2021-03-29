<?php

declare(strict_types=1);

namespace Voxonics\Data\Structure\Traits;

use Voxonics\Data\Structure\Tree\Interfaces\TreeNodeInterface;

/**
 * Trait StackTreeTrait
 */
trait StackTreeTrait
{
    /**
     * Stack of current level node elements.
     *
     * @var TreeNodeInterface[]
     */
    protected $stack = [];

    /**
     * Push node to current node stack list
     *
     * @param TreeNodeInterface $node
     */
    protected function push(TreeNodeInterface $node): void
    {
        $this->stack[] = $node;
    }

    protected function last(): TreeNodeInterface
    {
        return \end($this->stack);
    }

    /**
     * Free stack
     *
     * @param TreeNodeInterface|null $node
     *
     * @return static
     */
    protected function free(TreeNodeInterface $node = null): self
    {
        if ($node === null ||
            ($nodeIndex = \array_search($node, $this->stack)) === false
        ) {
            $this->stack = [];
        }
        elseif($node->getParent() !== $this->head()) {
            $this->stack = \array_slice($this->stack, 0, $nodeIndex);
        }

        return $this;
    }
}

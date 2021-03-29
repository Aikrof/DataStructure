<?php

declare(strict_types=1);

namespace App\Test\DataStructure\Interfaces;

use App\Test\DataStructure\Tree\Interfaces\TreeNodeInterface;

/**
 * Interface HeadInterface
 */
interface HeadInterface
{
    /**
     * Go to head of the tree list.
     *
     * @return TreeNodeInterface
     */
    public function head(): TreeNodeInterface;

    /**
     * Set head.
     *
     * @param TreeNodeInterface|null $node
     *
     * @return static
     */
    public function setHead(TreeNodeInterface $node = null): self;

    /**
     * Set current node to head.
     *
     * @return static
     */
    public function rewind(): self;

    /**
     * Check if current node element is head node element.
     *
     * @return bool
     */
    public function isHead(): bool;

    /**
     * Check if head is empty.
     *
     * @return bool
     */
    public function isEmpty(): bool;
}

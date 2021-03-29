<?php

declare(strict_types=1);

namespace App\Test\DataStructure\Interfaces;

use App\Test\DataStructure\Tree\Interfaces\TreeNodeInterface;

/**
 * Interface TreeBuilderInterface
 */
interface TreeBuilderInterface extends HeadInterface
{
    /**
     * Add new node to tree list.
     *
     * If value is node element and prefix is not null,
     * then node prefix will be changed to prefix that was added.
     *
     * @param TreeNodeInterface|mixed|null $value
     * @param string|null                  $prefix
     *
     * @return static;
     */
    public function add($value = null, string $prefix = null): self;

    /**
     * Add all of given values
     *
     * With value and prefix
     * @example [['value', 'prefix'], ['value', 'prefix']]
     *
     * Or only with values:
     * @example ['value', 'value']
     *
     * Or with both
     * @example ['value', ['value', 'prefix'], 'value]
     *
     * @param TreeNodeInterface[]|array $data
     *
     * @return static
     */
    public function addAll(array $data): self;

    /**
     * Get current node.
     *
     * @return TreeNodeInterface
     */
    public function current(): TreeNodeInterface;

    /**
     * Go to element, search will be by value or prefix or hash
     *
     * @param string $search
     *
     * @return TreeNodeInterface|null
     */
    public function goTo(string $search): ?TreeNodeInterface;

//    /**
//     * Remove current tree node with all of his child nodes
//     *
//     * @return bool
//     */
//    public function remove(): bool;
//
//    /**
//     * Remove child nodes from current node.
//     * If child is not set, we will remove all child nodes from current node
//     *
//     * @param TreeNodeInterface|null $child
//     *
//     * @return bool
//     */
//    public function removeChild($child = null): bool;

    /**
     * Child count of current node.
     *
     * @return int
     */
    public function childCount(): int;

    /**
     * Get first child
     *
     * @return TreeNodeInterface|null
     */
    public function getFirstChild(): ?TreeNodeInterface;

    /**
     * Get last child
     *
     * @return TreeNodeInterface|null
     */
    public function getLastChild(): ?TreeNodeInterface;
}

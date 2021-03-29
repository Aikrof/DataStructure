<?php

declare(strict_types=1);

namespace Voxonics\Data\Structure\Tree\Interfaces;

/**
 * Interface TreeNodeInterface
 */
interface TreeNodeInterface
{
    /**
     * Get the current tree node value.
     *
     * @return mixed
     */
    public function getValue();

    /**
     * Set the value of the current tree node.
     *
     * @param mixed $value
     *
     * @return static
     */
    public function setValue($value): self;

    /**
     * Set node prefix
     *
     * @return string
     */
    public function getPrefix(): ?string;

    /**
     * Set node prefix
     *
     * @param string|null $prefix
     *
     * @return static
     */
    public function setPrefix(string $prefix = null): self;

    /**
     * Get hash node value
     *
     * @return string
     */
    public function getHash(): string;

    /**
     * Generate node hash
     *
     * @return static
     */
    public function generateHash(): self;

    /**
     * Add a child.
     *
     * @param TreeNodeInterface $child
     *
     * @return static
     */
    public function addChild(self $child): self;

    /**
     * @param TreeNodeInterface[] $children
     *
     * @return static
     */
    public function addChildren(array $children): self;

    /**
     * Remove a node from children.
     *
     * @param TreeNodeInterface $child
     *
     * @return static
     */
    public function removeChild(self $child): self;

    /**
     * Remove children that set in children array.
     * Or all children of current node if children is not set
     *
     * @param TreeNodeInterface[] $children
     *
     * @return static
     */
    public function removeChildren(array $children = []): self;

    /**
     * Return the array of children.
     *
     * @return static[]
     */
    public function getChildren(): array;

    /**
     * Check if node has child nodes.
     *
     * @return bool
     */
    public function hasChildren(): bool;

    /**
     * Count of child nodes
     *
     * @return int
     */
    public function count(): int;

    /**
     * Set the parent node.
     *
     * @param TreeNodeInterface|null $parent
     *
     * @return static current node
     */
    public function setParent(self $parent = null): self;

    /**
     * Return the parent node.
     *
     * @return static|null parent node
     */
    public function getParent(): ?self;
}

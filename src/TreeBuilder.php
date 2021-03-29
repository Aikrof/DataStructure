<?php

declare(strict_types=1);

namespace Voxonics\Data\Structure;

use Voxonics\Data\Structure\Interfaces\TreeBuilderInterface;
use Voxonics\Data\Structure\Traits\HeadTreeTrait;
use Voxonics\Data\Structure\Traits\StackTreeTrait;
use Voxonics\Data\Structure\Tree\Interfaces\TreeNodeInterface;
use Voxonics\Data\Structure\Tree\TreeNode;

/**
 * Class TreeBuilder
 *
 * Tree builder
 */
class TreeBuilder implements TreeBuilderInterface
{
    use HeadTreeTrait, StackTreeTrait;

    /**
     * @var TreeNodeInterface
     */
    protected $current;

    /**
     * TreeBuilder constructor.
     *
     * @param TreeNodeInterface|null $node
     */
    public function __construct(TreeNodeInterface $node = null)
    {
        if (!empty($node)) {
            $this->add($node);
        }
    }

    /**
     * {@inheritDoc}
     *
     * @param TreeNodeInterface|mixed|null $value
     * @param string|null                  $prefix
     *
     * @return static
     */
    public function add($value = null, string $prefix = null): self
    {
        $node = $this->initTreeNode($value);

        if ($prefix !== null) {
            $node->setPrefix($prefix);
        }

        if ($this->isEmpty()) {
            $this->setHead($node);
        }
        else {
            $this->current->addChild($node);
        }
        
        $this->push($node);

        return $this;
    }

    /**
     * {@inheritDoc}
     *
     * @param array $data
     *
     * @return static
     */
    public function addAll(array $data): self
    {
        foreach ($data as $item) {
            if (\is_array($item)) {
                $this->add($item[0] ?? null, $item[1] ?? null);
                continue;
            }

            $this->add($item);
        }

        return $this;
    }

    /**
     * Build tree element, add a child to the node enter in its scope.
     *
     *
     * @param TreeNodeInterface|mixed|null $value
     * @param string|null                  $prefix
     *
     * @return static A TreeBuilderInterface instance linked to the child node
     */
    public function addTree($value = null, $prefix = null): self
    {
        $node = $this->add($value, $prefix)->last();
        $this->current = $node;

        return $this;
    }

    /**
     * Goes up to the parent node context.
     *
     * @return static
     */
    public function up(): self
    {
        return $this
            ->free($this->current)
            ->setCurrent($this->current()->getParent() ?? $this->head());
    }

    /**
     * {@inheritDoc}
     *
     * @return TreeNodeInterface
     */
    public function current(): TreeNodeInterface
    {
        return $this->current;
    }

    /**
     * Set current element to select node
     *
     * @param TreeNodeInterface $node
     *
     * @return static
     */
    public function setCurrent(TreeNodeInterface $node): self
    {
        if ($this->exists($node)) {
            $this->current = $node;
        }

        return $this;
    }

    /**
     * Init tree node
     *
     * @param TreeNodeInterface|mixed|null $value
     *
     * @return TreeNodeInterface
     */
    protected function initTreeNode($value = null): TreeNodeInterface
    {
        if ($value instanceof TreeNodeInterface) {
            $node = clone $value;
            /** Regenerate hash of node object */
            $node->generateHash();
            return $node;
        }

        return new TreeNode($value);
    }

    /**
     * Check if current value is tree node element.
     *
     * @param TreeNodeInterface|mixed|null $value
     *
     * @return bool
     */
    protected function isNode($value = null): bool
    {
        return $value instanceof TreeNodeInterface;
    }

    /**
     * Go to next element in the same level
     *
     * @return static|null
     */
    public function next(): ?self
    {
       return $this->move('next');
    }

    /**
     * @return static|null
     */
    public function prev(): ?self
    {
        return $this->move('prev');
    }

    /**
     * {@inheritDoc}
     *
     * @return int
     */
    public function childCount(): int
    {
        return $this->current->count();
    }

    /**
     * {@inheritDoc}
     *
     * @return TreeNodeInterface|null
     */
    public function getFirstChild(): ?TreeNodeInterface
    {
        $children = $this->current->getChildren();

        return $this->current->hasChildren() === true
            ? $children[\array_key_first($children)]
            : null;
    }

    /**
     * {@inheritDoc}
     *
     * @return TreeNodeInterface|null
     */
    public function getLastChild(): ?TreeNodeInterface
    {
        $children = $this->current->getChildren();

        return $this->current->hasChildren() === true
            ? $children[\array_key_last($children)]
            : null;
    }

    /**
     * Move to next or previous node.
     *
     * @param string $way
     *
     * @return static|null
     */
    protected function move(string $way): ?self
    {
        /** If node have not parent node, then he is head */
        if (!$this->current->getParent()) {
            return null;
        }

        $parentChildren = $this->current->getParent()->getChildren();
        $index = \array_search(
                $this->current,
                $parentChildren,
                true
            );

        $index = $way === 'next' ? $index + 1 : $index - 1;

        if (!isset($parentChildren[$index])) {
            return null;
        }

        $this->current = $parentChildren[$index];
        return $this;
    }

    /**
     * Check if node exists.
     *
     * @param TreeNodeInterface $node
     *
     * @return bool
     */
    public function exists(TreeNodeInterface $node): bool
    {
        return (bool) $this->goTo($node);
    }

    /**
     * {@inheritDoc}
     *
     * @param TreeNodeInterface|mixed $search
     *
     * @return TreeNodeInterface|null
     */
    public function goTo($search): ?TreeNodeInterface
    {
       return $this->search($search, [$this->head()]);
    }

    /**
     * Search node.
     *
     * @param TreeNodeInterface|mixed   $search
     * @param TreeNodeInterface[]       $nodes
     *
     * @return TreeNodeInterface|null
     */
    protected function search($search, array $nodes = [], array $valueResult = []): ?TreeNodeInterface
    {
        if ($search instanceof TreeNodeInterface &&
            ($index = \array_search($search, $nodes)) !== false
        ) {
            return $nodes[$index];
        }

        foreach ($nodes as $node) {
            if ($this->compareFields($search, $node, $valueResult) === true) {
                return $node;
            }

            if ($node->hasChildren()) {
                $result = $this->search($search, $node->getChildren(), $valueResult);
                if ($result !== null) {
                    return $result;
                }
            }
        }

        return $valueResult[0] ?? null;
    }

    /**
     * Compare fields
     *
     * @param mixed $field
     * @param TreeNodeInterface $node
     * @param array $valueResult
     *
     * @return bool
     */
    protected function compareFields($field, TreeNodeInterface $node, array &$valueResult): bool
    {
        if ($field === $node->getPrefix() ||
            $field === $node->getHash()
        ) {
            return true;
        }

        if ($field === $node->getValue()) {
            $valueResult[] = $node;
        }

        return false;
    }

    /**
     * @param \Closure $callback
     */
    public function each(\Closure $callback): void
    {
        $this->recursiveIterate($callback, [$this->head()]);
    }

    /**
     * @param \Closure $callback
     * @param TreeNodeInterface[] $nodes
     */
    protected function recursiveIterate(\Closure $callback, array $nodes = []): void
    {
        if (empty($nodes)) {
            return;
        }

        foreach ($nodes as $node) {
            $callback($node);

            $this->recursiveIterate($callback, $node->getChildren());
        }
    }
}

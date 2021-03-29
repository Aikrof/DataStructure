<?php

declare(strict_types=1);

namespace Voxonics\Data\Structure\Tree;

use Voxonics\Data\Structure\Tree\Interfaces\TreeNodeInterface;

/**
 * Class TreeNode
 */
class TreeNode implements TreeNodeInterface
{
    /**
     * @var mixed
     */
    protected $value;

    /**
     * @var string
     */
    protected $prefix;

    /**
     * @var string
     */
    protected $hash;

    /**
     * @var static
     */
    protected $parent;

    /**
     * @var TreeNodeInterface []
     */
    protected $children = [];

    /**
     * TreeNode constructor.
     *
     * @param mixed $value
     * @param TreeNodeInterface|TreeNodeInterface[]|null
     * @param string|null $prefix
     */
    public function __construct($value = null, $children = null, string $prefix = null)
    {
        $this
            ->setValue($value)
            ->generateHash()
            ->setPrefix($prefix);

        $children instanceof TreeNodeInterface
            ? $this->addChild($children)
            : $this->addChildren($children ?? []);
    }

    /**
     * {@inheritDoc}
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * {@inheritDoc}
     *
     * @param mixed $value
     *
     * @return static
     */
    public function setValue($value): self
    {
        $this->value = $value;

        return $this;
    }

    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public function getPrefix(): ?string
    {
        return $this->prefix;
    }

    /**
     * {@inheritDoc}
     *
     * @param string|null $prefix
     *
     * @return static
     */
    public function setPrefix(string $prefix = null): self
    {
        $this->prefix = $prefix;

        return $this;
    }

    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public function getHash(): string
    {
        return $this->hash;
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function generateHash(): self
    {
        $this->hash = \bin2hex(\random_bytes(24));

        return $this;
    }

    /**
     * @param TreeNodeInterface $child
     *
     * @return static
     */
    public function addChild(TreeNodeInterface $child): self
    {
        $child->setParent($this);

        return $this->pushChild($child);
    }

    /**
     * Push node child to children array
     *
     * @param TreeNodeInterface $child
     *
     * @return static
     */
    protected function pushChild(TreeNodeInterface $child): self
    {
        $this->children[] = $child;

        return $this;
    }

    /**
     * @param  TreeNodeInterface[] $children
     *
     * @return static
     */
    public function addChildren(array $children): self
    {
        foreach ($children as $child) {
            $this->addChild($child);
        }

        return $this;
    }

    /**
     * {@inheritDoc}
     *
     * @param TreeNodeInterface $child
     *
     * @return static
     */
    public function removeChild(TreeNodeInterface $child): self
    {
        foreach ($this->children as $childKey => $childNode) {
            if ($child->getHash() === $childNode->getHash()) {
                unset($this->children[$childKey]);
                /** Normalize array keys */
                $this->children = \array_values($this->children);
            }
        }

        /** Remove parent node from child node that we are removing */
        $child->setParent();

        return $this;
    }

    /**
     * {@inheritDoc}
     *
     * @param TreeNodeInterface[] $children
     *
     * @return static
     */
    public function removeChildren(array $children = []): self
    {
        if (empty($children)) {
            $this->children = [];
        }
        else {
            $removeHash = \array_map(static function ($child) {
                return $child->getHash();
            }, $children);

            \array_walk( $this->children,
                function ($child, $key) use ($removeHash) {
                    if (\in_array($child->getHash(), $removeHash, false)) {
                        unset($this->children[$key]);

                        /** Remove parent node from child node that we are removing */
                        $child->setParent();

                    }
                }
            );

            /** Normalize array keys */
            $this->children = \array_values($this->children);
        }

        return $this;
    }

    /**
     * {@inheritDoc}
     *
     * @return TreeNodeInterface[]
     */
    public function getChildren(): array
    {
        return $this->children;
    }

    /**
     * {@inheritDoc}
     *
     * @return bool
     */
    public function hasChildren(): bool
    {
        return !empty($this->getChildren());
    }

    /**
     * {@inheritDoc}
     *
     * @return int
     */
    public function count(): int
    {
        return \count($this->getChildren());
    }

    /**
     * {@inheritDoc}
     *
     * @param TreeNodeInterface|null $parent
     *
     * @return static
     */
    public function setParent(TreeNodeInterface $parent = null): self
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * {@inheritDoc}
     *
     * @return static|null
     */
    public function getParent(): ?self
    {
        return $this->parent;
    }
}

<?php

namespace Bundles\PegassCrawlerBundle\Entity;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Throwable;

/**
 * @ORM\Entity(repositoryClass="Bundles\PegassCrawlerBundle\Repository\PegassRepository")
 * @ORM\Table(
 * uniqueConstraints={
 *     @ORM\UniqueConstraint(name="type_identifier_idx", columns={"type", "identifier"})
 * },
 * indexes={
 *    @ORM\Index(name="type_update_idx", columns={"type", "updated_at"}),
 *    @ORM\Index(name="typ_ide_par_idx", columns={"type", "identifier", "parent_identifier"}),
 *    @ORM\Index(name="enabled_idx", columns={"enabled"})
 * })
 * @ORM\ChangeTrackingPolicy("DEFERRED_EXPLICIT")
 */
class Pegass
{
    const TYPE_AREA       = 'area';
    const TYPE_DEPARTMENT = 'department';
    const TYPE_STRUCTURE  = 'structure';
    const TYPE_VOLUNTEER  = 'volunteer';

    const TYPES = [
        self::TYPE_AREA,
        self::TYPE_DEPARTMENT,
        self::TYPE_STRUCTURE,
        self::TYPE_VOLUNTEER,
    ];

    const TTL = [
        self::TYPE_AREA       => 365 * 24 * 60 * 60, // 1 year
        self::TYPE_DEPARTMENT => 90 * 24 * 60 * 60, // 3 months
        self::TYPE_STRUCTURE  => 7 * 24 * 60 * 60, // 1 week
        self::TYPE_VOLUNTEER  => 30 * 24 * 60 * 60, // 1 month
    ];

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=64, nullable=true)
     */
    private $identifier;

    /**
     * @ORM\Column(type="string", length=64, nullable=true)
     */
    private $parentIdentifier;

    /**
     * @ORM\Column(type="string", length=24)
     */
    private $type;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $content;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="boolean")
     */
    private $enabled = true;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdentifier(): ?string
    {
        return $this->identifier;
    }

    public function setIdentifier(string $identifier): self
    {
        $this->identifier = $identifier;

        return $this;
    }

    public function getParentIdentifier(): ?string
    {
        return $this->parentIdentifier;
    }

    public function setParentIdentifier(?string $parentIdentifier): self
    {
        $this->parentIdentifier = $parentIdentifier;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @param string $expression
     *
     * @return array|string|null
     */
    public function evaluate(string $expression)
    {
        $content = $this->getContent();

        if (!$content) {
            return null;
        }

        try {
            $object = json_decode(json_encode($content));

            $accessed = PropertyAccess::createPropertyAccessorBuilder()
                                      ->disableExceptionOnInvalidPropertyPath()
                                      ->getPropertyAccessor()
                                      ->getValue($object, $expression);

            return json_decode(json_encode($accessed), true);
        } catch (Throwable $e) {
            return null;
        }
    }

    public function getContent(): ?array
    {
        if ($this->content) {
            return json_decode($this->content, true);
        }

        return null;
    }

    public function setContent(?array $content): self
    {
        $this->content = json_encode($content, JSON_PRETTY_PRINT);

        return $this;
    }

    public function getEnabled(): ?bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;

        return $this;
    }
}

<?php

namespace Magic;

abstract class Card
{
    /**
     * @var bool
     */
    private bool $isSummoningSick = true;

    /**
     * @var bool
     */
    private bool $isTapped = false;

    /**
     * Individual card's casting cost.
     *
     * @return int
     */
    abstract public function getCastingCost(): int;

    /**
     * Card returns it's type or name.
     *
     * @return string
     */
    abstract public function __toString(): string;

    /**
     * Performs card actions.
     */
    abstract public function play(Player $player): void;

    /**
     * Mark the card as tapped and not usable this turn.
     */
    public function tap(): void
    {
        $this->isTapped = true;
    }

    /**
     * Mark the card as untapped and ready to use.
     */
    public function untap(): void
    {
        $this->isTapped = false;
        $this->isSummoningSick = false;
    }

    /**
     * @return bool
     */
    public function getIsSummoningSick(): bool
    {
        return $this->isSummoningSick;
    }

    /**
     * @return bool
     */
    public function getIsTapped(): bool
    {
        return $this->isTapped;
    }

    /**
     * @return int
     */
    public function getPower(): int
    {
        return 0;
    }

    /**
     * @return int
     */
    public function getToughness(): int
    {
        return 0;
    }

    /**
     * Overridden by sub-classes.
     *
     * @return bool
     */
    public function isBurn(): bool
    {
        return false;
    }

    /**
     * Overridden by sub-classes.
     *
     * @return bool
     */
    public function isCreature(): bool
    {
        return false;
    }

    /**
     * Overridden by sub-classes.
     *
     * @return bool
     */
    public function isLand(): bool
    {
        return false;
    }

    /**
     * Overridden by sub-classes.
     *
     * @return bool
     */
    public function isRemoval(): bool
    {
        return false;
    }
}

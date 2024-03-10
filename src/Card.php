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
     */
    abstract public function getCastingCost(): int;

    /**
     * Performs card actions.
     */
    abstract public function play(Board $board): void;

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
    public function isInstant(): bool
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
    public function isSorcery(): bool
    {
        return false;
    }
}

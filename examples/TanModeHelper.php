<?php

declare(strict_types=1);

namespace Fhp\Examples;

use Fhp\FinTs;
use Fhp\Model\TanMedium;
use Fhp\Model\TanMode;

/**
 * Helper class for TAN mode and TAN medium selection.
 * This class provides functionality for discovering and selecting TAN modes and media.
 */
class TanModeHelper
{
    private FinTs $fints;

    public function __construct(FinTs $fints)
    {
        $this->fints = $fints;
    }

    /**
     * Get all available TAN modes from the bank.
     *
     * @return TanMode[]
     */
    public function getAvailableTanModes(): array
    {
        return $this->fints->getTanModes();
    }

    /**
     * Get all available TAN media for a given TAN mode.
     *
     * @param TanMode|int $tanMode The TAN mode or its ID
     * @return TanMedium[]
     */
    public function getAvailableTanMedia(TanMode|int $tanMode): array
    {
        $tanModeId = $tanMode instanceof TanMode ? $tanMode->getId() : $tanMode;
        return $this->fints->getTanMedia($tanModeId);
    }

    /**
     * Select a TAN mode and optionally a TAN medium.
     *
     * @param TanMode|int $tanMode The TAN mode or its ID
     * @param TanMedium|string|null $tanMedium The TAN medium, its name, or null
     */
    public function selectTanMode(TanMode|int $tanMode, TanMedium|string|null $tanMedium = null): void
    {
        $tanModeId = $tanMode instanceof TanMode ? $tanMode->getId() : $tanMode;
        $tanMediumName = match (true) {
            $tanMedium === null => null,
            $tanMedium instanceof TanMedium => $tanMedium->getName(),
            default => $tanMedium,
        };
        $this->fints->selectTanMode($tanModeId, $tanMediumName);
    }

    /**
     * Display available TAN modes and let user select one interactively.
     *
     * @return TanMode The selected TAN mode
     */
    public function selectTanModeInteractively(): TanMode
    {
        $tanModes = $this->getAvailableTanModes();
        if (empty($tanModes)) {
            throw new \RuntimeException('Your bank does not support any TAN modes!');
        }

        echo "Here are the available TAN modes:\n";
        foreach ($tanModes as $index => $tanMode) {
            echo "[$index] " . $tanMode->getName() . "\n";
        }

        echo "Which one do you want to use? Index:\n";
        $tanModeIndex = trim(fgets(STDIN));
        if (!is_numeric($tanModeIndex) || !array_key_exists((int) $tanModeIndex, $tanModes)) {
            throw new \InvalidArgumentException('Invalid TAN mode index!');
        }

        $tanMode = $tanModes[(int) $tanModeIndex];
        echo 'You selected ' . $tanMode->getName() . "\n";

        // Handle TAN medium if needed
        if ($tanMode->needsTanMedium()) {
            $tanMedium = $this->selectTanMediumInteractively($tanMode);
            $this->selectTanMode($tanMode, $tanMedium);
        } else {
            $this->selectTanMode($tanMode, null);
        }

        return $tanMode;
    }

    /**
     * Display available TAN media and let user select one interactively.
     *
     * @param TanMode $tanMode The TAN mode requiring a medium
     * @return TanMedium The selected TAN medium
     */
    public function selectTanMediumInteractively(TanMode $tanMode): TanMedium
    {
        $tanMedia = $this->getAvailableTanMedia($tanMode);
        if (empty($tanMedia)) {
            throw new \RuntimeException(
                'Your bank did not provide any TAN media, even though it requires selecting one!'
            );
        }

        echo "Here are the available TAN media:\n";
        foreach ($tanMedia as $index => $tanMedium) {
            $name = $tanMedium->getName();
            if ($tanMedium->getPhoneNumber() !== null) {
                $name .= ' (' . $tanMedium->getPhoneNumber() . ')';
            }
            echo "[$index] $name\n";
        }

        echo "Which one do you want to use? Index:\n";
        $tanMediumIndex = trim(fgets(STDIN));
        if (!is_numeric($tanMediumIndex) || !array_key_exists((int) $tanMediumIndex, $tanMedia)) {
            throw new \InvalidArgumentException('Invalid TAN medium index!');
        }

        $tanMedium = $tanMedia[(int) $tanMediumIndex];
        echo 'You selected ' . $tanMedium->getName() . "\n";

        return $tanMedium;
    }

    /**
     * Get the currently selected TAN mode.
     */
    public function getSelectedTanMode(): TanMode
    {
        return $this->fints->getSelectedTanMode();
    }
}
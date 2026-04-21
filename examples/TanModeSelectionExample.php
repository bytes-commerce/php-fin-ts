<?php

declare(strict_types=1);

namespace Fhp\Examples;

/**
 * Example: TAN mode selection.
 * Demonstrates how to discover and select TAN modes and TAN media using phpFinTS.
 *
 * This replaces the procedural tanModesAndMedia.php with an object-oriented approach.
 */
class TanModeSelectionExample
{
    private FinTsConnection $connection;

    public function __construct(FinTsConnection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * Get all available TAN modes.
     *
     * @return \Fhp\Model\TanMode[]
     */
    public function getAvailableTanModes(): array
    {
        return $this->connection->getTanModeHelper()->getAvailableTanModes();
    }

    /**
     * Get all available TAN media for a given TAN mode.
     *
     * @param \Fhp\Model\TanMode|int $tanMode The TAN mode or its ID
     * @return \Fhp\Model\TanMedium[]
     */
    public function getAvailableTanMedia(\Fhp\Model\TanMode|int $tanMode): array
    {
        return $this->connection->getTanModeHelper()->getAvailableTanMedia($tanMode);
    }

    /**
     * Select a TAN mode and optionally a TAN medium.
     *
     * @param \Fhp\Model\TanMode|int $tanMode The TAN mode or its ID
     * @param \Fhp\Model\TanMedium|string|null $tanMedium The TAN medium, its name, or null
     */
    public function selectTanMode(\Fhp\Model\TanMode|int $tanMode, \Fhp\Model\TanMedium|string|null $tanMedium = null): void
    {
        $this->connection->getTanModeHelper()->selectTanMode($tanMode, $tanMedium);
    }

    /**
     * Display available TAN modes and let the user select one interactively.
     *
     * @return \Fhp\Model\TanMode The selected TAN mode
     */
    public function selectTanModeInteractively(): \Fhp\Model\TanMode
    {
        return $this->connection->getTanModeHelper()->selectTanModeInteractively();
    }

    /**
     * Display all available TAN modes and their details.
     */
    public function displayTanModes(): void
    {
        $tanModes = $this->getAvailableTanModes();

        if (empty($tanModes)) {
            echo 'Your bank does not support any TAN modes!' . PHP_EOL;
            return;
        }

        echo "Available TAN modes:" . PHP_EOL;
        echo str_repeat('=', 60) . PHP_EOL;

        foreach ($tanModes as $index => $tanMode) {
            echo "[$index] " . $tanMode->getName() . PHP_EOL;
            echo "    ID: " . $tanMode->getId() . PHP_EOL;
            echo "    Decoupled: " . ($tanMode->isDecoupled() ? 'Yes' : 'No') . PHP_EOL;
            echo "    Needs TAN Medium: " . ($tanMode->needsTanMedium() ? 'Yes' : 'No') . PHP_EOL;

            if ($tanMode->needsTanMedium()) {
                $tanMedia = $this->getAvailableTanMedia($tanMode);
                echo "    Available TAN Media:" . PHP_EOL;
                foreach ($tanMedia as $mIndex => $medium) {
                    $name = "    [$mIndex] " . $medium->getName();
                    if ($medium->getPhoneNumber() !== null) {
                        $name .= " (Phone: " . $medium->getPhoneNumber() . ")";
                    }
                    echo $name . PHP_EOL;
                }
            }
            echo PHP_EOL;
        }
    }

    /**
     * Run this example interactively.
     */
    public function run(): void
    {
        $this->displayTanModes();

        $tanModes = $this->getAvailableTanModes();
        if (empty($tanModes)) {
            return;
        }

        echo "Which one do you want to use? Index (or 'q' to quit):" . PHP_EOL;
        $input = trim(fgets(STDIN));

        if (strtolower($input) === 'q') {
            echo "Cancelled." . PHP_EOL;
            return;
        }

        if (!is_numeric($input) || !array_key_exists((int) $input, $tanModes)) {
            echo "Invalid index!" . PHP_EOL;
            return;
        }

        $tanMode = $tanModes[(int) $input];
        echo 'You selected ' . $tanMode->getName() . PHP_EOL;

        // Handle TAN medium if needed
        if ($tanMode->needsTanMedium()) {
            $this->connection->getTanModeHelper()->selectTanMediumInteractively($tanMode);
        } else {
            $this->selectTanMode($tanMode, null);
        }

        echo "TAN mode selection complete." . PHP_EOL;

        // Now you could do $connection->connect() to login
        // See login.php for that part of the workflow.
        $this->connection->close();
        echo 'Done' . PHP_EOL;
    }
}
<?php

namespace Ulco\Tests;

class GameRunnerTest extends \PHPUnit\Framework\TestCase
{
    private int $goldenTests = 500;

    /**
     * @dataProvider getSeeds
     */
    public function testGamePathsUsingGoldenMaster($seed): void
    {
        ob_start();
        mt_srand($seed);
        require(__DIR__ . '/../src/GameRunner.php');
        $output = ob_get_clean();
        $file = __DIR__ . "/results/{$seed}.txt";

        if (!file_exists($file)) {
            file_put_contents($file, $output);
        }

        $this->assertEquals(file_get_contents($file), $output);
    }

    public function getSeeds(): array
    {
        $data = [];

        for ($i = 1; $i <= $this->goldenTests; $i++) {
            $data[] = [$i];
        }

        return $data;
    }
}
<?php

namespace bugfree\test\integration;


/**
 * @group integration
 */
class AutoFixIntegrationTest extends IntegrationTestCase
{

    public static function autoFixUnusedProvider()
    {
        return [
            ["test/autofix/unused/OnlyUnused.php"],
            ["test/autofix/unused/MultipleOnlyUnused.php"],
            ["test/autofix/unused/Unused.php"]
        ];
    }

    /**
     * @dataProvider autoFixUnusedProvider
     */
    public function testAutoFixUnused($path)
    {
        $exitCode = $this->lintPath($path);
        $this->assertNotEquals(0, $exitCode);

        $this->autofixPath($path);

        $exitCode = $this->lintPath($path);
        $this->assertEquals(0, $exitCode);
    }
}

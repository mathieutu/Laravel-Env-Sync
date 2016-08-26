<?php
/**
 * Laravel-Env-Sync
 *
 * @author Julien Tant - Craftyx <julien@craftyx.fr>
 */

namespace Jtant\LaravelEnvSync;


use Artisan;
use Orchestra\Testbench\TestCase;
use org\bovigo\vfs\vfsStream;

class SyncCommandTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [EnvSyncServiceProvider::class];
    }

    /** @test */
    public function it_should_fill_the_env_file_from_env_example()
    {
        // Arrange
        $root = vfsStream::setup();
        $example = "FOO=BAR\nBAR=BAZ\nBAZ=FOO";
        $env = "FOO=BAR\nBAZ=FOO";

        file_put_contents($root->url() . '/.env.example', $example);
        file_put_contents($root->url() . '/.env', $env);

        $this->app->setBasePath($root->url());

        // Act
        Artisan::call('env:sync', [
            '--no-interaction' => true,
        ]);

        // Assert
        $expected = "FOO=BAR\nBAZ=FOO\nBAR=BAZ";

        $this->assertEquals($expected, file_get_contents($root->url() . '/.env'));
    }
}
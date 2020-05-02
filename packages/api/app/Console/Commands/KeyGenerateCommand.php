<?php

namespace App\Console\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;

class KeyGenerateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'key:generate';

    /**
     * The console command descryption.
     *
     * @var string
     */
    protected $description = "Set the application key";

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $key = $this->getRandomKey();

        if ($this->option('show')) {
            $this->line('<comment>'.$key.'</comment>');
            return;
        }

        $path = base_path('.env');

        if (file_exists($path)) {
            $envContent = file_get_contents($path);
            if ($envContent) {
                file_put_contents(
                    $path,
                    str_replace('APP_KEY='.env('APP_KEY'), 'APP_KEY='.$key, $envContent)
                );
            }
        }

        $this->info("Application key [$key] set successfully.");
    }

    /**
     * Generate a random key for the application.
     *
     * @return string
     */
    protected function getRandomKey()
    {
        return Str::random(32);
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return array(
            array('show', null, InputOption::VALUE_NONE, 'Simply display the key instead of modifying files.'),
        );
    }
}

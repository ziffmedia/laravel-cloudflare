<?php

namespace ZiffDavis\Laravel\Cloudflare\Console\Commands;

use App\Services\Cloudflare;
use Illuminate\Console\Command;

class CloudflarePurge extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cloudflare-purge {tags*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Purges content from Cloudflare tagged with given tag(s)';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (config('cloudflare.purge_enabled')) {
            $tags = $this->argument('tags');

            try {
                app(Cloudflare::class)
                    ->setTags($tags)
                    ->purgeCacheByTags();
                $this->output->writeln('Purged cached content with tag(s) [' . implode(', ', $tags) . ']');
            } catch (\Exception $e) {
                $this->output->error('Purge failed with message: ' . $e->getMessage());
            }
        } else {
            $this->output->writeln('Purging disabled by config `cloudflare.purge_enabled = false`');
        }
    }
}

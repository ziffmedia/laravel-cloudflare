<?php

namespace ZiffDavis\Laravel\Cloudflare\Jobs;

use App\Services\Cloudflare;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PurgeCloudflareCacheByTags implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;

    /** @var array $tagsToPurge */
    protected $tagsToPurge = [];

    public function __construct(array $tags)
    {
        $this->tagsToPurge = $tags;
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws \Exception
     */
    public function handle()
    {
        app(Cloudflare::class)
           ->setTags($this->tagsToPurge)
           ->purgeCacheByTags();
    }
}

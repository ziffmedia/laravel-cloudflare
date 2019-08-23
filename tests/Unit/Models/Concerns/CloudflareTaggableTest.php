<?php

namespace Tests\Unit\Models\Concerns;

use Illuminate\Database\Eloquent\Model;
use Tests\TestCase;
use ZiffDavis\Laravel\Cloudflare\Models\Concerns\CloudflareTaggable;

class CloudflareTaggableTest extends TestCase
{
    /** @return Model */
    protected function getModelWithCloudflareTaggable($attributes = [])
    {
        return new class($attributes) extends Model {
            use CloudflareTaggable;

            protected $fillable = ['id'];

            protected $table = 'fake_models';
        };
    }

    public function testCloudflareTagUsesTableNameAndId()
    {
        $model = $this->getModelWithCloudflareTaggable(['id' => 1234]);
        $this->assertEquals('fake-models_1234', $model->cloudflareTag());
    }
}

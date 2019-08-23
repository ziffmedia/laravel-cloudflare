<?php

namespace Tests\Unit\Http\Controllers\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Routing\Controller;
use Tests\TestCase;
use ZiffDavis\Laravel\Cloudflare\Http\Controllers\Concerns\CloudflareTaggable;
use ZiffDavis\Laravel\Cloudflare\Services\Cloudflare;

class CloudflareTaggableTest extends TestCase
{
    //addCloudflareTag($tag)
    public function testAddCloudflareTagAddsStringTagToCloudflareService()
    {
        app()->singleton(Cloudflare::class, function () {
            $cloudflare = $this->getMockBuilder(Cloudflare::class)
                ->disableOriginalConstructor()
                ->getMock();

            $cloudflare->expects($this->once())
                ->method('addTag')
                ->with($this->equalTo('test-tag_1234'));

            return $cloudflare;
        });

        $controller = new class extends Controller {
            use CloudflareTaggable;

            public function index()
            {
                $this->addCloudflareTag('test-tag_1234');
            }
        };

        $controller->index();
    }

    //addCloudflareTagFromModel(Model $model)
    public function testAddCloudflareTagFromModelUsesModelCloudflareTaggableTag()
    {
        app()->singleton(Cloudflare::class, function () {
            $cloudflare = $this->getMockBuilder(Cloudflare::class)
                ->disableOriginalConstructor()
                ->getMock();

            $cloudflare->expects($this->once())
                ->method('addTag')
                ->with($this->equalTo('fake-models_3322'));

            return $cloudflare;
        });

        $controller = new class extends Controller {
            use CloudflareTaggable;

            public function index()
            {
                $model = new class(['id' => 3322]) extends Model {
                    use \ZiffDavis\Laravel\Cloudflare\Models\Concerns\CloudflareTaggable;

                    protected $table = 'fake_models';

                    protected $fillable = ['id'];
                };

                $this->addCloudflareTagFromModel($model);
            }
        };

        $controller->index();
    }

    //addCloudflareTagsFromCollection(Collection $collection)
    public function testAddCloudflareTagsFromCollectionOfModelsAddsModelTagsForAllModels()
    {
        app()->singleton(Cloudflare::class, function () {
            $cloudflare = $this->getMockBuilder(Cloudflare::class)
                ->disableOriginalConstructor()
                ->getMock();

            $cloudflare->expects($this->exactly(2))
                ->method('addTag')
                ->withConsecutive(
                    $this->equalTo('fake-models_1'),
                    $this->equalTo('fake-models_2')
                );

            return $cloudflare;
        });

        $controller = new class extends Controller {
            use CloudflareTaggable;

            public function index()
            {
                $models = collect([
                    $this->getCloudflareTaggableModel(1),
                    $this->getCloudflareTaggableModel(2),
                ]);

                $this->addCloudflareTagsFromCollection($models);
            }

            protected function getCloudflareTaggableModel($id)
            {
                return new class(['id' => $id]) extends Model {
                    use \ZiffDavis\Laravel\Cloudflare\Models\Concerns\CloudflareTaggable;

                    protected $table = 'fake_models';

                    protected $fillable = ['id'];
                };
            }
        };

        $controller->index();
    }
}

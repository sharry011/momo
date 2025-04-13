<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\SitemapIndex;
use Spatie\Sitemap\Tags\Url;
use App\Models\Post;
use App\Models\Season;
use App\Models\Serie;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate';
    protected $description = 'Generate sitemap index and split URLs into multiple sitemaps';

    public function handle()
    {
        $chunkSize = 10000; // Maximum URLs per sitemap
        $sitemapIndex = SitemapIndex::create();
        $sitemapPath = public_path('/'); // Store all sitemaps in /public/sitemaps

        // Ensure directory exists
        if (!file_exists($sitemapPath)) {
            mkdir($sitemapPath, 0777, true);
        }

        $models = [
            Post::class,
            Season::class,
            Serie::class,
            Category::class,
        ];

        $sitemapCount = 1;

        foreach ($models as $model) {
            $query = $model::query();

            $query->orderBy('id')->chunk($chunkSize, function ($items) use (&$sitemapCount, $sitemapPath, &$sitemapIndex, $model) {
                $sitemap = Sitemap::create();
                $sitemapFile = "sitemap-{$sitemapCount}.xml";

                foreach ($items as $item) {
                    $routeName = match ($model) {
                        Post::class => $item->opt == 1 ? 'film' : 'episode',
                        Season::class => 'season',
                        Serie::class => 'series',
                        Category::class => 'category',
                        default => null,
                    };

                    if ($routeName) {
                        $sitemap->add(
                            Url::create(url("/{$routeName}/" . rawurlencode($item->slug)))
                            ->setLastModificationDate($item->updated_at ?? now())
                            ->setPriority(1.0)
                            ->setChangeFrequency('always')
                        );
                    }
                }

                // Save the sitemap file
                $sitemap->writeToFile("$sitemapPath/$sitemapFile");
                $sitemapIndex->add(url("$sitemapFile"));

                $sitemapCount++;
            });
        }

        // Generate the main sitemap index
        $sitemapIndex->writeToFile("$sitemapPath/sitemap-index.xml");

        $this->info('Sitemap index and sitemaps generated successfully!');
    }
}


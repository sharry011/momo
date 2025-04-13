<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use App\Models\Post;
use App\Models\Season;
use App\Models\Serie;
use App\Models\Category;
use Carbon\Carbon;

class UpdateSitemap extends Command
{
    protected $signature = 'sitemap:Update {model} {oldSlug?} {slug?} {action=create}';
    protected $description = 'Update sitemap without removing existing URLs';

    public function handle()
    {
        $modelName = $this->argument('model');
        $oldSlug = $this->argument('oldSlug');
        $slug = $this->argument('slug');
        $action = $this->argument('action');


        

        if ($modelName && $slug) {
            if ($action === 'remove') {
                $this->removeEntry($modelName, $slug);
            } else {
                $this->updateSingleEntry($modelName, $oldSlug, $slug);
            }
        } else {
            $this->error('Model and slug are required.');
        }
    }

    private function updateSingleEntry($modelName, $oldSlug, $newSlug)
    {
        if (!class_exists($modelName)) {
            return;
        }
    
        $item = $modelName::where('slug', $newSlug)->first();
        if (!$item) {
            return;
        }
    
        $routeName = match ($modelName) {
            Post::class => $item->opt == 1 ? 'film' : 'episode',
            Season::class => 'season',
            Serie::class => 'serie',
            Category::class => 'category',
            default => null,
        };
    
        if (!$routeName) {
            return;
        }
    
        $oldUrl = $oldSlug ? route($routeName, ['slug' => $oldSlug]) : null;
        $newUrl = route($routeName, ['slug' => $newSlug]);
    
        $sitemapFiles = glob(public_path('sitemap-*.xml'));
        $targetSitemap = null;
    
        if (!$sitemapFiles) {
            Log::warning("No sitemap files found in public directory.");
        }
    
        foreach ($sitemapFiles as $sitemapFile) {
            $fileContents = file_get_contents($sitemapFile);
            
            if ($oldUrl && strpos($fileContents, $oldUrl) !== false) {
                $targetSitemap = $sitemapFile;
                break;
            }
    
            // If no old URL, find any sitemap to append a new entry
            if (!$targetSitemap) {
                $targetSitemap = $sitemapFile;
            }
        }
    
        if (!$targetSitemap) {
            return;
        }
    
        $xmlContent = file_get_contents($targetSitemap);

        $lastmod = $item->updated_at ?? $item->created_at ?? now();
        $lastmod = \Carbon\Carbon::parse($lastmod)->toDateTimeImmutable()->format('Y-m-d\TH:i:sP');
    
        // ✅ Remove old entry if exists
        if ($oldUrl) {
            $pattern = '/<url>\s*<loc>' . preg_quote($oldUrl, '/') . '<\/loc>.*?<\/url>/s';
            if (preg_match($pattern, $xmlContent)) {
                $xmlContent = preg_replace($pattern, '', $xmlContent);
            ;
            }
        }
    
        // ✅ Add new entry if not already in sitemap
        if (!strpos($xmlContent, $newUrl)) {
 
            
            $newEntry = "
            <url>
                <loc>{$newUrl}</loc>
                <lastmod>{$lastmod}</lastmod>
                <changefreq>always</changefreq>
                <priority>1.0</priority>
            </url>";
    
            $xmlContent = str_replace('</urlset>', $newEntry . "\n</urlset>", $xmlContent);
            file_put_contents($targetSitemap, $xmlContent);
            
   ;
        } else {
         
        }
    
        // ✅ Save the updated sitemap
        file_put_contents($targetSitemap, $xmlContent);
    
        Log::info("Sitemap updated successfully: {$targetSitemap}");
    }
    
    private function removeEntry($modelName, $slug)
    {
        if (!class_exists($modelName)) {
           
            return;
        }

        $item = $modelName::where('slug', $slug)->first();
        if (!$item) {
     
            return;
        }

        $routeName = match ($modelName) {
            Post::class => $item->opt == 1 ? 'film' : 'episode',
            Season::class => 'season',
            Serie::class => 'serie',
            Category::class => 'category',
            default => null,
        };

        if (!$routeName) {
           
            return;
        }

        $urlToRemove = route($routeName, ['slug' => $slug]);

        $sitemapFiles = glob(public_path('sitemap-*.xml'));
        if (!$sitemapFiles) {
          
            return;
        }

        foreach ($sitemapFiles as $sitemapFile) {
            $xmlContent = file_get_contents($sitemapFile);
            if (strpos($xmlContent, $urlToRemove) !== false) {
              

                $pattern = '/<url>\s*<loc>' . preg_quote($urlToRemove, '/') . '<\/loc>.*?<\/url>/s';
                $updatedXmlContent = preg_replace($pattern, '', $xmlContent);

                file_put_contents($sitemapFile, $updatedXmlContent);
             
                break;
            }
        }
    }
}

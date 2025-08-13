<?php

namespace App\Console\Commands;

use App\Models\Book;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CleanupOrphanedImages extends Command
{
    protected $signature = 'images:cleanup {--dry-run : Show what would be deleted without actually deleting}';
    protected $description = 'Clean up orphaned book images that are no longer referenced in database';

    public function handle(): int
    {
        $this->info('🔍 Scanning for orphaned images...');

        $allImages = collect(Storage::disk('public')->files('books'))
            ->filter(fn($file) => in_array(pathinfo($file, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png']));

        $usedImages = Book::whereNotNull('image_path')
            ->pluck('image_path')
            ->toArray();

        $orphanedImages = $allImages->diff($usedImages);

        if ($orphanedImages->isEmpty()) {
            $this->info('✅ No orphaned images found!');
            return self::SUCCESS;
        }

        $this->warn("🗑️  Found {$orphanedImages->count()} orphaned images:");
        
        foreach ($orphanedImages as $image) {
            $size = Storage::disk('public')->size($image);
            $sizeKb = round($size / 1024, 2);
            
            if ($this->option('dry-run')) {
                $this->line("   Would delete: {$image} ({$sizeKb} KB)");
            } else {
                Storage::disk('public')->delete($image);
                $this->line("   ✅ Deleted: {$image} ({$sizeKb} KB)");
            }
        }

        if ($this->option('dry-run')) {
            $this->info('🔄 Run without --dry-run to actually delete the files');
        } else {
            $this->info("✅ Cleanup completed! Deleted {$orphanedImages->count()} orphaned images");
        }

        return self::SUCCESS;
    }
}

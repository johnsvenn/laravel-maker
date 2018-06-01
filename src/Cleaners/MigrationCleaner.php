<?php

namespace AbCreative\LaravelMaker\Cleaners;

use AbCreative\LaravelMaker\Builders\MigrationBuilder;
use Illuminate\Support\Facades\DB;

class MigrationCleaner extends MigrationBuilder {
    
    use CleanerTrait;
    
    public function init()
    {
    
        $this->cleanMigrations();

    }
    
    public function cleanMigrations()
    {
        
        $file = base_path('database/migrations');

        $migrations = $this->command->filesystem->files($file);

        if (!empty($migrations)) {
            
            foreach ($migrations as $migration) {
                
                $filename = $migration->getFileName();
                
                if (strpos($filename, $this->getMigrationName()) !== false) {

                    $migration_name = str_replace('.php', '', $filename);
                  
                    
                    if (DB::table('migrations')->where('migration', $migration_name)->exists()) {
                        
                        $this->command->info('Unable to delete migration: ' . $filename . ' it has already been migrated!');
                        
                    } else {
                    
                        $this->command->filesystem->delete($migration);
                    
                        $this->command->info('Deleted migration: ' . $filename);
                        
                    }
                    
                }

            }
        }
    }
}
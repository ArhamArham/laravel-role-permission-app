<?php

namespace App\Console\Commands;

use App\Models\Permission;
use App\Providers\RoleServiceProvider;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class PermissionSync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permission:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will sync the permission';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info("Syncing permissions");

        foreach ($this->getPermissions() as $name => $slug) {
            Permission::updateOrCreate([
                'name' => $name
            ], [
                'slug' => $slug
            ]);
        }

        Cache::forget('roles');

        RoleServiceProvider::define();

        $this->comment("Permission has been synced");
    }

    private function getPermissions()
    {
        return [
            'Product Management'  => 'product.*',
            'Category Management' => 'category.*',
            'Brand Management'    => 'brand.*'
        ];
    }
}

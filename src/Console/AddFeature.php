<?php

namespace DenCreative\Featuretoggle\Console;

use DenCreative\FeatureToggle\Models\Feature;
use Illuminate\Console\Command;

class AddFeature extends Command
{
    protected $signature = 'feature:add {feature} {enabled=true}';

    protected $description = 'Add a new feature toggle';

    public function handle()
    {
        $feature = $this->argument('feature');
        $enabled = $this->argument('enabled') == 'true';
        $featureModel = new Feature;
        $featureModel->name = $feature;
        $featureModel->enabled = $enabled;
        $featureModel->on(config('features.connection', config('database.default')))
                    ->save();

        $state = $enabled ? 'enabled' : 'disabled';

        $this->info("Feature ({$feature}) created and {$state}");
    }
}

<?php

return [

    /*
     * The model which handles the plans tables.
     */

    'models' => [

        'plan' => App\Models\Plan::class,
        'subscription' => App\Models\PlanUser::class,
        // 'feature' => \Rennokki\Plans\Models\FeatureModel::class,
        // 'usage' => \Rennokki\Plans\Models\PlanSubscriptionUsageModel::class,

    ],

];
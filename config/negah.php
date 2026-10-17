<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Branded public-link prefix
    |--------------------------------------------------------------------------
    |
    | The public brochure links are published as {base_display_url}{slug},
    | e.g. negahm.ir/B/my-event. The /B/ prefix is fixed; the client only
    | supplies the {slug} part in the submission form.
    |
    */

    'base_display_url' => env('NEGAH_BASE_URL', 'negahm.ir/B/'),

    /*
    |--------------------------------------------------------------------------
    | Slug constraints
    |--------------------------------------------------------------------------
    */

    'slug_min' => 3,
    'slug_max' => 40,

    /*
    |--------------------------------------------------------------------------
    | Preview link lifetime (hours)
    |--------------------------------------------------------------------------
    |
    | How long the temporary, signed "Preview Ready" link stays valid while
    | the submission is waiting for admin review / telephone consultation.
    |
    */

    'preview_ttl_hours' => env('NEGAH_PREVIEW_TTL_HOURS', 48),

    /*
    |--------------------------------------------------------------------------
    | Admin panel credentials (concierge / review team)
    |--------------------------------------------------------------------------
    */

    'admin' => [
        'username' => env('NEGAH_ADMIN_USERNAME', 'admin'),
        'password' => env('NEGAH_ADMIN_PASSWORD', 'negah-admin'),
    ],

];

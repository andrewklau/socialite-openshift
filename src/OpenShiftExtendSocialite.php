<?php

namespace Andrewklau\Socialite\OpenShift;

use SocialiteProviders\Manager\SocialiteWasCalled;

class OpenShiftExtendSocialite
{
    /**
     * Execute the provider.
     */
    public function handle(SocialiteWasCalled $socialiteWasCalled)
    {
        $socialiteWasCalled->extendSocialite(
          'openshift', __NAMESPACE__.'\Provider'
        );
    }
}

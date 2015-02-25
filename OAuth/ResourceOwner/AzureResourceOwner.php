<?php

/*
 * This file is part of the HWIOAuthBundle package.
 *
 * (c) Hardware.Info <opensource@hardware.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HWI\Bundle\OAuthBundle\OAuth\ResourceOwner;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use HWI\Bundle\OAuthBundle\OAuth\ResourceOwner\GenericOAuth2ResourceOwner;

/**
 * AzureResourceOwner
 *
 * @author Baptiste Clavié <clavie.b@gmail.com>
 */
class AzureResourceOwner extends GenericOAuth2ResourceOwner
{
    /**
     * {@inheritDoc}
     */
    protected $paths = array(
        'identifier'     => 'MailboxGuid',
        'nickname'       => 'Alias',
        'realname'       => 'DisplayName',
        'email'          => 'Id',
        'profilepicture' => null,
    );

    /**
     * {@inheritDoc}
     */
    public function configure()
    {
        $this->options['access_token_url'] = sprintf($this->options['access_token_url'], $this->options['application']);
        $this->options['authorization_url'] = sprintf($this->options['authorization_url'], $this->options['application']);
        $this->options['infos_url'] = sprintf($this->options['infos_url'], $this->options['resource'], $this->options['api_version']);
    }

    /**
     * {@inheritDoc}
     */
    public function getAuthorizationUrl($redirectUri, array $extraParameters = array())
    {
        return parent::getAuthorizationUrl($redirectUri, $extraParameters + array('resource' => $this->options['resource']));
    }

    /**
     * {@inheritDoc}
     */
    public function refreshAccessToken($refreshToken, array $extraParameters = array())
    {
        return parent::refreshAccessToken($refreshToken, $extraParameters + array('resource' => $this->options['resource']));
    }

    /**
     * {@inheritDoc}
     */
    protected function configureOptions(OptionsResolverInterface $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setRequired(array('resource'));

        $resolver->setDefaults(array(
            'authorization_url' => 'https://login.windows.net/%s/oauth2/authorize',
            'infos_url' => '%s/api/%s/me',
            'access_token_url' => 'https://login.windows.net/%s/oauth2/token',

            'application' => 'common',
            'api_version' => 'v1.0',
            'csrf' => true
        ));
    }
}


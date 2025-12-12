<?php
/**
 *
 * SantaCloud APP (Nextcloud)
 *
 * @author Wolfgang Tödt <wtoedt@gmail.com>
 *
 * @copyright Copyright (c) 2025 Wolfgang Tödt
 *
 * @license GNU AGPL version 3 or any later version
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */
declare(strict_types=1);

namespace OCA\SantaCloud\Settings;

use OCP\AppFramework\Http\TemplateResponse;
use OCA\SantaCloud\AppInfo\Application;
use OCP\IConfig;
use OCP\IL10N;
use OCP\Settings\ISettings;
use OCP\Security\IContentSecurityPolicyManager;
use OCP\AppFramework\Http\ContentSecurityPolicy;
use OCP\AppFramework\Http\Attribute\OpenAPI;
use OCP\AppFramework\Http\Attribute\NoCSRFRequired;

class SantaCloudAdmin implements ISettings {
    private IL10N $l;
    private IConfig $config;

    public function __construct(IConfig $config, IL10N $l) {
        $this->config = $config;
        $this->l = $l;
    }

    /**
     * @return TemplateResponse
     */
     #[NoCSRFRequired]
     #[OpenAPI(OpenAPI::SCOPE_IGNORE)]
    public function getForm(): TemplateResponse {
        $wtpara_test = (int)$this->config->getAppValue('santacloud', 'wtpara_test', '');
        $wtpara_last = (int)$this->config->getAppValue('santacloud', 'wtpara_last', '');
        $wtpara_lock = (int)$this->config->getAppValue('santacloud', 'wtpara_lock', '');
        $wtpara_own = (int)$this->config->getAppValue('santacloud', 'wtpara_own', '');
		if (!isset($wtpara_test) or ($wtpara_test === 0)) {
			 $wtpara_test = 1;
			 $this->config->setAppValue('santacloud', 'wtpara_test', 1);
		}
		if (!isset($wtpara_last) or ($wtpara_last === 0)) {
			 $wtpara_last = 2;
			 $this->config->setAppValue('santacloud', 'wtpara_last', 2);
		}
		if (!isset($wtpara_lock) or ($wtpara_lock === 0)) {
			 $wtpara_lock = 1;
			 $this->config->setAppValue('santacloud', 'wtpara_lock', 1);
		}
		if (!isset($wtpara_own) or ($wtpara_own === 0)) {
			 $wtpara_own = 1;
			 $this->config->setAppValue('santacloud', 'wtpara_own', 2);
		}
        
        
        
        
        $csp = new ContentSecurityPolicy();
        $csp->addAllowedImageDomain('*');
        $csp->addAllowedMediaDomain('*');
        $parameters = [
          'wtpara_test' => $wtpara_test,
          'wtpara_last' => $wtpara_last,
          'wtpara_lock' => $wtpara_lock,
          'wtpara_own' => $wtpara_own,
    		];
        ($this->config->getSystemValue('version') < 32 ? \OC::$server->getContentSecurityPolicyManager()->addDefaultPolicy($csp) : \OCP\Server::get(IContentSecurityPolicyManager::class)->addDefaultPolicy($csp));
        $response = new TemplateResponse(Application::APP_ID, 'settings/admin', $parameters, '');
        return $response;
    }

    public function getSection() {
        return 'santacloud';
    }

    /**
     * @return int whether the form should be rather on the top or bottom of
     * the admin section. The forms are arranged in ascending order of the
     * priority values. It is required to return a value between 0 and 100.
     *
     * E.g.: 70
     */
    public function getPriority() {
        return 100;
    }
}

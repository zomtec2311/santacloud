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

namespace OCA\SantaCloud\Controller;

use OCA\SantaCloud\AppInfo\Application;
use OCP\AppFramework\Controller;
use OCP\AppFramework\Http\Attribute\FrontpageRoute;
use OCP\AppFramework\Http\Attribute\NoAdminRequired;
use OCP\AppFramework\Http\Attribute\NoCSRFRequired;
use OCP\AppFramework\Http\Attribute\OpenAPI;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\AppFramework\Http\ContentSecurityPolicy;

use OCP\IUserSession;
use OCP\IGroupManager;
use OCP\IConfig;

/**
 * @psalm-suppress UnusedClass
 */
class PageController extends Controller {

	public function __construct(private IConfig $config, IGroupManager $groupManager, IUserSession $userSession)
	{
			$this->config = $config;
			$this->userSession = $userSession;
			$this->groupManager = $groupManager;
	}

	#[NoCSRFRequired]
	#[NoAdminRequired]
	#[OpenAPI(OpenAPI::SCOPE_IGNORE)]
	#[FrontpageRoute(verb: 'POST', url: '/')]
	public function index(): TemplateResponse {
		$wtpara_test = (int)$this->config->getAppValue('santacloud', 'wtpara_test');
		if (!isset($wtpara_test) or ($wtpara_test === 0)) {
			 $wtpara_test = 1;
			 $this->config->setAppValue('santacloud', 'wtpara_test', 1);
		}
		$wtpara_last = (int)$this->config->getAppValue('santacloud', 'wtpara_last');
		if (!isset($wtpara_last) or ($wtpara_last === 0)) {
			 $wtpara_last = 2;
			 $this->config->setAppValue('santacloud', 'wtpara_last', 2);
		}
		$wtpara_lock = (int)$this->config->getAppValue('santacloud', 'wtpara_lock');
		if (!isset($wtpara_lock) or ($wtpara_lock === 0)) {
			 $wtpara_lock = 1;
			 $this->config->setAppValue('santacloud', 'wtpara_lock', 1);
		}
		$allowed = $this->isallowed($wtpara_lock);
		$response = new TemplateResponse(
			Application::APP_ID,
			$allowed,
		);
		$csp = new ContentSecurityPolicy();
		$csp->addAllowedImageDomain('*');
		$csp->addAllowedMediaDomain('*');
		$response->setContentSecurityPolicy($csp);
		return $response;
	}

	private function isallowed($wtpara_lock) {
		$user = $this->userSession->getUser();
		if ($this->groupManager->isAdmin($user->getUID())) {return 'index';}
		else {
			if( $wtpara_lock === 2 ) { return 'index'; }
			else { return 'wait'; }
		}
	}
}

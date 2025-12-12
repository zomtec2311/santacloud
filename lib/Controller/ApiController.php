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

use OCP\AppFramework\Http;
use OCP\AppFramework\Http\Attribute\ApiRoute;
use OCP\AppFramework\Http\Attribute\NoAdminRequired;
use OCP\AppFramework\Http\Attribute\NoCSRFRequired;
use OCP\IURLGenerator;
use OCP\AppFramework\Controller;
use OCP\IRequest;
use OCP\Files\IAppData;
use OCP\Files\NotFoundException;
use OCP\Files\NotPermittedException;
use OCP\AppFramework\Http\DataResponse;
use OCP\Files\AppData\IAppDataFactory; 
use OCP\Files\SimpleFS\ISimpleRoot;
use OCP\Files\SimpleFS\ISimpleFolder; 
use OCP\Files\SimpleFS\ISimpleFile;
use OCP\IConfig;
use Psr\Log\LoggerInterface;


/**
 * @psalm-suppress UnusedClass
 */
class ApiController extends Controller {
    
    protected $request;
    private IConfig $config;
    private IURLGenerator $urlGenerator;
    private $appData;

    public function __construct(
        $appName,
        IRequest $request,
        IConfig $config,
		IURLGenerator $urlGenerator,
        IAppData $appData,
        LoggerInterface $logger,
    ) {
        parent::__construct($appName, $request);
        $this->request = $request;
        $this->config = $config;
		$this->urlGenerator = $urlGenerator;
        $this->appData = $appData;
        $this->logger = $logger;
    }

	#[NoCSRFRequired]
    public function uploadFile(): DataResponse {
        $uploadFieldName = $this->config_['uploadFieldName'] ?? 'file'; 
        $fileData = $this->request->getUploadedFile($uploadFieldName); 
        
        if (empty($fileData) || $fileData['error'] !== UPLOAD_ERR_OK) {
            $error = $fileData['error'] ?? UPLOAD_ERR_NO_FILE;
            
            return new DataResponse(['error' => 'SantaCloud failed to store: ' . $e->getMessage() . $php_error_message], 500);
        
        }

        $tempPath = $fileData['tmp_name'];
        $originalName = $fileData['name'];

        try {
            $allowedMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            $detectedMime = @mime_content_type($tempPath);

            if ($detectedMime === false || !in_array($detectedMime, $allowedMimes)) {
                throw new \Exception('SantaCloud: Wrong type extension of file.');
            }
            $sanitizedName = preg_replace('/[^a-zA-Z0-9\-\._]/', '_', basename($originalName));
            if (empty($sanitizedName)) {
                throw new \Exception('SantaCloud: Invalid file name after cleanup.');
            }
            $targetDir = $this->config->getSystemValue('datadirectory') . '/appdata_' . $this->config->getSystemValue('instanceid') . '/santacloud/img';
            //$targetDir = $dataDir . '/' . $this->appName . '/img';
            $targetFile = $targetDir . '/' . $sanitizedName;
            if (!is_writable($targetDir)) {
                 throw new \Exception('SantaCloud Error: Target folder (' . $targetDir . ') is not writable .');
            }
            if (!copy($tempPath, $targetFile)) {
                throw new \Exception('SantaCloud Error: Unable to copy file.');
            }
            $internalFilePath = $this->appName . '/image/' . $sanitizedName;
            
            $finalImageUrl = $this->urlGenerator->getAbsoluteURL('/apps/') . $internalFilePath;

            return new DataResponse([
                'filePath' => $finalImageUrl,
                'name' => $sanitizedName,
            ]);

        } catch (\Exception $e) {
            return new DataResponse(['error' => 'SantaCloud memory error: ' . $e->getMessage() . $php_error_message], 500);
        }
    }
}

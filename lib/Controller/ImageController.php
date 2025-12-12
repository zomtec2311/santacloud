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

use OCP\AppFramework\Controller;
use OCP\AppFramework\Http\StreamResponse;
use OCP\AppFramework\Http\DataResponse;
use OCP\AppFramework\Http\JSONResponse;
use OCP\IRequest;
use OCP\Files\IAppData;
use OCP\Files\SimpleFS\ISimpleFile;
use OCP\Files\SimpleFS\ISimpleFolder;
use OCP\IURLGenerator;
use OCP\IConfig;
use OCP\AppFramework\Http\Attribute\NoAdminRequired;
use OCP\AppFramework\Http\Attribute\NoCSRFRequired;
use Psr\Log\LoggerInterface;
use OCP\AppFramework\Http\ContentSecurityPolicy;

class ImageController extends Controller {
    /** @var IAppData */
    private $appData;
    
    public function __construct(
        string $AppName, 
        IRequest $request, 
        IConfig $config,
        IURLGenerator $urlGenerator,
        IAppData $appData,
        LoggerInterface $logger,
    ) {
        parent::__construct($AppName, $request);
        $this->appData = $appData;
        $this->urlGenerator = $urlGenerator;
        $this->config = $config;
        $this->logger = $logger;
    }   
    
    #[NoCSRFRequired]
    public function get(string $filename): StreamResponse {
    $cleanFilename = str_replace('..', '', $filename);
    $imagePath = $this->config->getSystemValue('datadirectory') . '/appdata_' . $this->config->getSystemValue('instanceid') . '/santacloud/img/' . $cleanFilename;
    $extension = pathinfo($imagePath, PATHINFO_EXTENSION);
    $mimeType = match (strtolower($extension)) {
        'png' => 'image/png',
        'jpg', 'jpeg' => 'image/jpeg',
        'gif' => 'image/gif',
        default => 'application/octet-stream',
    };

    if (file_exists($imagePath)) {        
        $response =  new StreamResponse(
            $imagePath,
            200, 
            [
                'Content-Type' => $mimeType,
            ]
        );
        $csp = new ContentSecurityPolicy();
		$csp->addAllowedImageDomain('*');
		$csp->addAllowedMediaDomain('*');
		$response->setContentSecurityPolicy($csp);
        return $response;
    } else {
        return new StreamResponse(
            $imagePath,
            404, 
            [
                'Content-Type' => $mimeType,
            ]
        );
    }
    }
    
    #[NoCSRFRequired]
    public function bgget(string $filename): StreamResponse {
    $cleanFilename = str_replace('..', '', $filename);
    $imagePath = $this->config->getSystemValue('datadirectory') . '/appdata_' . $this->config->getSystemValue('instanceid') . '/santacloud/backgroundimg/' . $cleanFilename;
    $extension = pathinfo($imagePath, PATHINFO_EXTENSION);
    $mimeType = match (strtolower($extension)) {
        'png' => 'image/png',
        'jpg', 'jpeg' => 'image/jpeg',
        'gif' => 'image/gif',
        default => 'application/octet-stream',
    };

    if (file_exists($imagePath)) {
        $response =  new StreamResponse(
            $imagePath,
            200, 
            [
                'Content-Type' => $mimeType,
            ]
        );
        $csp = new ContentSecurityPolicy();
		$csp->addAllowedImageDomain('*');
		$csp->addAllowedMediaDomain('*');
		$response->setContentSecurityPolicy($csp);
        return $response;
    } else {
        return new StreamResponse(
            $imagePath,
            404, 
            [
                'Content-Type' => $mimeType,
            ]
        );
    }
    }
    
    public function getimages(): DataResponse {
        
        $folder = $this->config->getSystemValue('datadirectory') . '/appdata_' . $this->config->getSystemValue('instanceid') . '/santacloud/img';
        $dateien_gefiltert = array_diff(scandir($folder), array('.', '..'));
		
		return new DataResponse([
                'images' => $dateien_gefiltert,
                'folder' => $folder,
            ]);
	}
	
	public function getbgimages(): DataResponse {
        
        $folder = $this->config->getSystemValue('datadirectory') . '/appdata_' . $this->config->getSystemValue('instanceid') . '/santacloud/backgroundimg';
        $dateien_gefiltert = array_diff(scandir($folder), array('.', '..'));
		
		return new DataResponse([
                'images' => $dateien_gefiltert,
                'folder' => $folder,
            ]);
	}
	
	#[NoCSRFRequired]
    public function deleteimage(): JSONResponse {

        $rawBody = file_get_contents('php://input');
        $data = json_decode($rawBody, true);
        
        if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
             return new JSONResponse(['error' => 'SantaCloud: wrong JSON format.'], 400);
        }
        
        $fileName = $data['filePath'] ?? null;
        
        if (empty($fileName)) {
            return new JSONResponse(['error' => 'SantaCloud: no path defined.'], 400);
        }

        $fullPathRelativeToRoot = $this->config->getSystemValue('datadirectory') . '/appdata_' . $this->config->getSystemValue('instanceid') . '/santacloud/img/' . $fileName; 
        
        try {

            $file = $fullPathRelativeToRoot;

            if (!file_exists($file)) {
                return new JSONResponse(['error' => 'SantaCloud: could not find file.'], 404);
            }

            unlink($file);
            $this->setbg('background.jpg');
            
            return new JSONResponse([
                'message' => 'SantaCloud: file successful feleted: ' . $fileName,
                'deletedFile' => $fileName,
            ], 200);

        } catch (\OCP\Files\NotFoundException $e) {
            return new JSONResponse([
                'error' => 'SantaCloud: could not find file.',
                'gesuchter_pfad_relativ_zur_root' => $fullPathRelativeToRoot,
            ], 404);
        } catch (\Throwable $e) {
            return new JSONResponse(['error' => 'SantaCloud: storage failure.', 'details' => $e->getMessage()], 500);
        }
    }
    
	#[NoCSRFRequired]
    public function deletebgimage(): JSONResponse {

        $rawBody = file_get_contents('php://input');
        $data = json_decode($rawBody, true);
        
        if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
             return new JSONResponse(['error' => 'SantaCloud: wrong JSON format.'], 400);
        }
        
        $fileName = $data['filePath'] ?? null;
        
        if (empty($fileName)) {
            return new JSONResponse(['error' => 'SantaCloud: no path defined.'], 400);
        }

        $fullPathRelativeToRoot = $this->config->getSystemValue('datadirectory') . '/appdata_' . $this->config->getSystemValue('instanceid') . '/santacloud/backgroundimg/' . $fileName; 
        
        try {

            $file = $fullPathRelativeToRoot;

            if (!file_exists($file)) {
                return new JSONResponse(['error' => 'SantaCloud: could not find file.'], 404);
            }

            unlink($file);
            $this->setbg('background.jpg');
            
            return new JSONResponse([
                'message' => 'SantaCloud: file successful feleted: ' . $fileName,
                'deletedFile' => $fileName,
            ], 200);

        } catch (\OCP\Files\NotFoundException $e) {
            return new JSONResponse([
                'error' => 'SantaCloud: could not find file.',
                'gesuchter_pfad_relativ_zur_root' => $fullPathRelativeToRoot,
            ], 404);
        } catch (\Throwable $e) {
            return new JSONResponse(['error' => 'SantaCloud: storage failure.', 'details' => $e->getMessage()], 500);
        }
    }
    
    #[NoAdminRequired]
   public function getbg() {
     $wtpara_background_image = (string)$this->config->getAppValue('santacloud', 'wtpara_background_image');
     if (!isset($wtpara_background_image) or ($wtpara_background_image === "") or (!file_exists($this->config->getSystemValue('datadirectory') . '/appdata_' . $this->config->getSystemValue('instanceid') . '/santacloud/backgroundimg/' . $wtpara_background_image))) {
            if (!file_exists($this->config->getSystemValue('datadirectory') . '/appdata_' . $this->config->getSystemValue('instanceid') . '/santacloud/backgroundimg/background.jpg')) {
                $backgroundimg = __DIR__ . '/../../img/background.jpg';
                $newbackgroundimg = $this->config->getSystemValue('datadirectory') . '/appdata_' . $this->config->getSystemValue('instanceid') . '/santacloud/backgroundimg/background.jpg';
                if (!copy($backgroundimg, $newbackgroundimg)) {
                    $this->logger->warning("SantaCloud: failed to copy $backgroundimg... ");
                }
                else {
                    $this->logger->debug("SantaCloud: success copy $backgroundimg... ");
                }
            }
 			 $wtpara_background_image = 'background.jpg';
             $this->config->setAppValue('santacloud', 'wtpara_background_image', $wtpara_background_image);
 		}
     return '/apps/santacloud/bgimage/' . $wtpara_background_image;
   }
   
   public function setbg($imagePath) {
        if (file_exists($this->config->getSystemValue('datadirectory') . '/appdata_' . $this->config->getSystemValue('instanceid') . '/santacloud/backgroundimg/' . $imagePath)) {
            $this->config->setAppValue('santacloud', 'wtpara_background_image', $imagePath);
        }
        else {
            $this->config->setAppValue('santacloud', 'wtpara_background_image', 'background.jpg');
        }
        return;
   }
   
   #[NoCSRFRequired]
    public function uploadFile(): DataResponse {
        $uploadedFileArray = $this->request->getUploadedFile('file'); 

        if (empty($uploadedFileArray) || $uploadedFileArray['error'] !== UPLOAD_ERR_OK) {
            $errorCode = $uploadedFileArray['error'] ?? UPLOAD_ERR_NO_FILE;
            return new DataResponse(['error' => 'SantaCloud failed to store. Error code: ' . $errorCode], 500);
        }

        $tempPath = $uploadedFileArray['tmp_name'];
        $originalName = $uploadedFileArray['name'];

        try {
            $allowedMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            $detectedMime = @mime_content_type($tempPath);

            if ($detectedMime === false || !in_array($detectedMime, $allowedMimes)) {
                unlink($tempPath); 
                throw new \Exception('SantaCloud: Wrong type extension of file.');
            }
            
            $sanitizedName = preg_replace('/[^a-zA-Z0-9\-\._]/', '_', basename($originalName));
            if (empty($sanitizedName)) {
                throw new \Exception('SantaCloud: Invalid file name after cleanup.');
            }
            
            $targetDir = $this->config->getSystemValue('datadirectory') . '/appdata_' . $this->config->getSystemValue('instanceid') . '/santacloud/backgroundimg';
            $targetFile = $targetDir . '/' . $sanitizedName;
            
            if (!is_writable($targetDir)) {
                 throw new \Exception('SantaCloud Error: Target folder (' . $targetDir . ') is not writable .');
            }
            
            if (!copy($tempPath, $targetFile)) {
                unlink($tempPath);
                throw new \Exception('SantaCloud Error: Unable to copy file.');
            }
            
            unlink($tempPath);
            
            $internalFilePath = $this->appName . '/bgimage/' . $sanitizedName;
            
            $finalImageUrl = $this->urlGenerator->getAbsoluteURL('/apps/') . $internalFilePath;
            //$this->config->setAppValue('santacloud', 'wtpara_background_image', $sanitizedName);
            
            return new DataResponse([
                'filePath' => $finalImageUrl,
                'name' => $sanitizedName,
            ]);

        } catch (\Exception $e) {
            return new DataResponse(['error' => 'SantaCloud memory error: ' . $e->getMessage()], 500);
        }        
    }
}

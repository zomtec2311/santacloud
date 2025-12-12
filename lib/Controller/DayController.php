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
use OCP\AppFramework\Http\Response;
use OCP\AppFramework\Http\JSONResponse;
use OCP\IL10N;
use OCP\IConfig;
use OCP\IRequest;
use OCP\AppFramework\Services\IInitialState;
use OCP\AppFramework\Http\Attribute\FrontpageRoute;
use OCP\AppFramework\Http\Attribute\NoAdminRequired;
use OCP\AppFramework\Http\Attribute\NoCSRFRequired;
use OCP\AppFramework\Http\Attribute\OpenAPI;
use OCP\AppFramework\Http\DataResponse;
use OCP\AppFramework\Http\Attribute\AuthorizedAdminSetting;
use OCP\Files\IAppData;
use OCP\Files\SimpleFS\ISimpleFile;
use OCP\Files\SimpleFS\ISimpleFolder;
use Psr\Log\LoggerInterface;

class DayController extends Controller {
  #[NoCSRFRequired]
  #[NoAdminRequired]
  #[OpenAPI(OpenAPI::SCOPE_IGNORE)]
  #[FrontpageRoute(verb: 'POST', url: '/')]
	/**
	 * @param string $query
	 * @param int $count
	 * @param int $offset
	 * @return JSONResponse
	 */
   /** @var IL10N */
   private $config;
   private $l;
   private $appData;
   public function __construct(
     IL10N $l,
     IConfig $config,
     IRequest $request,
     IAppData $appData,
     LoggerInterface $logger,
     private IInitialState $initialState
   ) {
     parent::__construct('santacloud', $request);
     $this->l = $l;
     $this->config = $config;
     $this->request = $request;
     $this->appData = $appData;
     $this->logger = $logger;
   }

   public function getParam($who): DataResponse {
     return new DataResponse([
                'value' => $this->config->getAppValue('santacloud', $who),
            ]);
   }
   
   #[AuthorizedAdminSetting(settings: Admin::class)]
   public function setParam($who,$wert) {
     $this->config->setAppValue('santacloud', $who, $wert);
 		 return;
 	 }

   public function saveowntext($text) {
     $this->config->setAppValue('santacloud', 'owntext', $text);
 		 return;
 	 }
/*
   #[NoAdminRequired]
   public function getxml(): DataResponse {
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
 		 $this->checkdatafolder();
         
 		$wtdayfile = $this->config->getSystemValue('datadirectory') . '/days.xml';
        $newwtdayfile = $this->config->getSystemValue('datadirectory') . '/santacloud/days.xml';
        if (!file_exists($wtdayfile) AND !file_exists($newwtdayfile)) {
          if (!is_dir($this->config->getSystemValue('datadirectory') . '/santacloud')) {
            if (!mkdir($this->config->getSystemValue('datadirectory') . '/santacloud/', 0777, true)) {
              return new DataResponse([ 'msg' => "failed to create directory... ", ]);
            }
          }
          $file = __DIR__ . '/../../data/days_example.xml';
          if (!copy($file, $newwtdayfile)) {
            return new DataResponse([
								'msg' => "failed to copy $file... ",
            ]);
          }
          return new DataResponse([
								'msg' => $this->l->t('No days.xml found. %1$s copied to %2$s', ['days_example', $newwtdayfile]),
          ]);
        }
        elseif (file_exists($wtdayfile)) {
          if (!is_dir($this->config->getSystemValue('datadirectory') . '/santacloud')) {
            if (!mkdir($this->config->getSystemValue('datadirectory') . '/santacloud/', 0777, true)) {
              return new DataResponse([ 'msg' => "failed to create directory... ", ]);
            }
          }
          if (!rename($wtdayfile, $newwtdayfile)) {
            return new DataResponse([
								'msg' => "failed to copy $wtdayfile... ",
            ]);
          }
          return new DataResponse([
								'msg' => $this->l->t('No days.xml found. %1$s copied to %2$s', ['existing days.xml', $newwtdayfile]),
          ]);
        }
        else {
          return new DataResponse([
								'msg' => "",
          ]);
        }
 	 }
*/
   public function xmlcontent(): DataResponse {
     //$this->genfile();
     $this->checkdatafolder();
     //$wtdayfile = $this->config->getSystemValue('datadirectory') . '/santacloud/days.xml';
     $wtdayfile = $this->config->getSystemValue('datadirectory') . '/appdata_' . $this->config->getSystemValue('instanceid') . '/santacloud/xml/days.xml';
     $out = '';
     $arr = array();
       $xmlStr = file_get_contents($wtdayfile);
       $xml = simplexml_load_string($xmlStr);
       for ($i = 0; $i <= 23; $i++) {
         $xml->days->day[$i]->title = strval($xml->days->day[$i]->title);
         $xml->days->day[$i]->description = strval($xml->days->day[$i]->description);
       }
       $xmlcontent = $xml->days;
     return new DataResponse([
								'xmlcontent' => $xmlcontent,
            ]);
    }

    public function dayxmlcontent($day) {
      $day = intval($day);
      //$this->genfile();
      $this->checkdatafolder();
      //$wtdayfile = $this->config->getSystemValue('datadirectory') . '/santacloud/days.xml';
      $wtdayfile = $this->config->getSystemValue('datadirectory') . '/appdata_' . $this->config->getSystemValue('instanceid') . '/santacloud/xml/days.xml';
      $out = '';
      $arr = array();
        $xmlStr = file_get_contents($wtdayfile);
        $xml = simplexml_load_string($xmlStr);
        $obja = new \stdClass();
        $obja->day = $day;
        $obja->date = strval($xml->days->day[$day-1]->date);
        $obja->title = strval($xml->days->day[$day-1]->title);
        $obja->description = strval($xml->days->day[$day-1]->description);
        return $obja;
     }

     public function savedayxmlcontent($day, $date, $title, $description): JSONResponse {
       //$wtdayfile = $this->config->getSystemValue('datadirectory') . '/santacloud/days.xml';
       $wtdayfile = $this->config->getSystemValue('datadirectory') . '/appdata_' . $this->config->getSystemValue('instanceid') . '/santacloud/xml/days.xml';
       $xmlStr = file_get_contents($wtdayfile);
       $xml = simplexml_load_string($xmlStr);
       $xml->days->day[$day-1]->date = $date;
       $xml->days->day[$day-1]->title = '<![CDATA[' . $title . ']]>';
       $xml->days->day[$day-1]->description = '<![CDATA[' . $description . ']]>';
       file_put_contents($wtdayfile, html_entity_decode($xml->asXML()),LOCK_EX);
       return new JSONResponse([
         'day' => $day,
         'date' => $date,
         'title' => $title,
        'description' => $description,
		   ]);
     }

   #[NoAdminRequired]
   public function getday(string $day) {
     $wtpara_test = (int)$this->config->getAppValue('santacloud', 'wtpara_test');
     $wtpara_last = (int)$this->config->getAppValue('santacloud', 'wtpara_last');
     $day = intval($day);
     $today = intval(date("j"));
     $thismonth = intval(date("n"));
     $out = "";
     //$wtdayfile = $this->config->getSystemValue('datadirectory') . '/santacloud/days.xml';
     $wtdayfile = $this->config->getSystemValue('datadirectory') . '/appdata_' . $this->config->getSystemValue('instanceid') . '/santacloud/xml/days.xml';
     if( $wtpara_test === 1) {
       if (!file_exists($wtdayfile)) { return; }
       else {
         $xmlStr = file_get_contents($wtdayfile);
         $xml = simplexml_load_string($xmlStr);
         $out .= '<br><h1 style="font-size: 2em;color:red;">' . $this->l->t('Attention - test mode is ON') . '</h1><br><h1 style="font-size: 1.3em;">' . $xml->days->day[$day-1]->title . '</h1>';
         $out .= '<br>' . $xml->days->day[$day-1]->description;
         return $out;
       }
     }
     else {
       if ($day > $today) { return '<br><b>' . $this->l->t('Unfortunately, you are too early, because you are only allowed to open this door on the right day.') . '</b>'; }
       if (!file_exists($wtdayfile)) {
         return;
       }
       else {
         $xmlStr = file_get_contents($wtdayfile);
         $xml = simplexml_load_string($xmlStr);
         $datexml  = (string) $xml->days->day[$day-1]->date[0];
         $pieces = explode("-", $datexml);
         $xmlmonth = intval($pieces[1]);
         if ($xmlmonth !== $thismonth) { return $this->l->t('Unfortunately, you are too early, because you are only allowed to open this door on the right day.'); }
         if ( $day === $today ) {
           $out .= '<br><h1 style="font-size: 1.3em;">' . $xml->days->day[$day-1]->title . '</h1>';
           $out .= '<br>' . $xml->days->day[$day-1]->description;
           return $out;
         }
         if ( ($day < $today) and ($wtpara_last === 2)) { return '<br><b>' . $this->l->t('Unfortunately, you are too late, because this door is no longer available.') . '</b>'; }
         else {
           $out .= '<br><h1 style="font-size: 1.3em;">' . $xml->days->day[$day-1]->title . '</h1>';
           $out .= '<br>' . $xml->days->day[$day-1]->description;
           return $out;
         }
       }
     }
   }

   public function previewday(string $day) {
     $day = intval($day);
     $out = "";
     //$wtdayfile = $this->config->getSystemValue('datadirectory') . '/santacloud/days.xml';
     $wtdayfile = $this->config->getSystemValue('datadirectory') . '/appdata_' . $this->config->getSystemValue('instanceid') . '/santacloud/xml/days.xml';
       if (!file_exists($wtdayfile)) { return; }
       else {
         $xmlStr = file_get_contents($wtdayfile);
         $xml = simplexml_load_string($xmlStr);
         $out .= '<h1 style="font-size: 1.3em;">' . $xml->days->day[$day-1]->title . '</h1>';
         $out .= '<br>' . $xml->days->day[$day-1]->description;
         return $out;
       }
   }
 /*  
   public function genfile() {
      $wtdayfile = $this->config->getSystemValue('datadirectory') . '/days.xml';
      $newwtdayfile = $this->config->getSystemValue('datadirectory') . '/santacloud/days.xml';
      $wtimg = $this->config->getSystemValue('datadirectory') . '/santacloud/img/xmas-cookies.png';
      if (!file_exists($wtdayfile) AND !file_exists($newwtdayfile)) {
        if (!is_dir($this->config->getSystemValue('datadirectory') . '/santacloud')) {
          mkdir($this->config->getSystemValue('datadirectory') . '/santacloud/', 0777, true);
        }
        $file = __DIR__ . '/../../data/days_example.xml';
        copy($file, $newwtdayfile);
      }
      elseif (file_exists($wtdayfile)) {
        if (!is_dir($this->config->getSystemValue('datadirectory') . '/santacloud')) {
          mkdir($this->config->getSystemValue('datadirectory') . '/santacloud/', 0777, true);
        }
        rename($wtdayfile, $newwtdayfile);
      }
      if (!file_exists($wtimg)) {
        $file = __DIR__ . '/../../data/img/xmas-cookies.png';
        if (!is_dir($this->config->getSystemValue('datadirectory') . '/santacloud/img')) {
          mkdir($this->config->getSystemValue('datadirectory') . '/santacloud/img/', 0777, true);
        }
        copy($file, $wtimg);
        $this->savedayxmlcontent(2, "2025-12-02", '<center>Today a Reciep for X-Mas Cookies</center>', '<center><img src="'.$wtimg.'" width="500" /></center>
              <div style="text-align: justify; margin: auto; width: 50%; border: 3px solid green; padding: 10px;">
                <b>You need</b><br>
              <p>For the dough:
                <ul>
                  <li>4 cups (500g) all-purpose flour</li>
                  <li>1 1/2 tsp. baking powder</li>
                  <li>3/4 cup (150g) sugar</li>
                  <li>1 pinch of salt</li>
                  <li>1 cup (250g) butter</li>
                  <li>1 tsp. vanilla extract</li>
                  <li>2 medium eggs</li>

                  <li>2 egg yolks for brushing the cookies</li>
                  <li>1 tbsp. water</li>
                </ul>
              </p>
              <br>
              <p>For the decoration:
                <ul>
                  <li>1 1/2 cups (200g) confectioner’s sugar</li>
                  <li>2-3 tbsp. lemon juice (or rum if you are not baking for kids)</li>
                  <li>food color (optional)</li>
                  <li>sugar pearls/sprinkles</li>
                </ul>
              </p>
            </div>
            <br>
            <div style="text-align: justify; margin: auto; width: 50%; border: 3px solid green; padding: 10px;">
              <ul>
                <li>1. In a large bowl, mix flour with baking powder, sugar, and salt until well combined. Add the butter in small pieces, vanilla extract and the eggs and knead until you get a nice smooth dough (best to do it first with the machine and then with your hands). Wrap in plastic wrap and let rest for 30 minutes in the fridge.</li>
                <li>2. Preheat the oven to 350˚F (180°C). Line several baking sheets with baking parchment. Roll out the dough on a floured surface to a thickness of 0.08 inch (2mm). Use cookie cutters in any shape you want and place the cookies on the prepared baking sheets. Knead the remaining dough and roll out again to cut out more cookies until all dough is used.</li>
                <li>3. Whisk the egg yolks with the water and brush the cookies. Bake one baking sheet at a time for 10-12 minutes. Let the cookies cool down on a wire rack.</li>
                <li>4. For the glaze, sift the confectioner’s sugar in a bowl, add lemon juice (or rum) and mix until you get a smooth mixture. Add now some drops of food color if you like (optional). Glaze the cookies and sprinkle with sugar pearls or sprinkles. Let the glaze dry completely and store the cookies in a tin box in a cool place.</li>
              </ul>
            </div>');
      }
  }
 */ 
  public function checkdatafolder() {
        $appdataroot = $this->config->getSystemValue('datadirectory') . '/appdata_' . $this->config->getSystemValue('instanceid') . '/santacloud';
 		if (!is_dir($appdataroot . '/img')) $this->appData->newFolder('img');
        if (!is_dir($appdataroot . '/backgroundimg')) $this->appData->newFolder('backgroundimg');
        if (!is_dir($appdataroot . '/xml')) $this->appData->newFolder('xml');
 		
 		$wtdayfile = $this->config->getSystemValue('datadirectory') . '/days.xml';
        $newwtdayfile = $appdataroot . '/xml/days.xml';
        $file = __DIR__ . '/../../data/days_example.xml';
        $backgroundimg = __DIR__ . '/../../img/background.jpg';
        $newbackgroundimg = $appdataroot . '/backgroundimg/background.jpg';
        $exampleimg = __DIR__ . '/../../data/img/xmas-cookies.png';
        $newexampleimg = $appdataroot . '/img/xmas-cookies.png';
        
        if (!file_exists($wtdayfile) AND !$this->appData->getFolder('xml')->fileExists('days.xml')) {
          if (!copy($file, $newwtdayfile)) {
            $this->logger->warning("SantaCloud: failed to copy $file... ");
          }
          else {
            $this->logger->debug("SantaCloud: success copy $file... ");
          }
        }
        elseif (file_exists($wtdayfile) AND !$this->appData->getFolder('xml')->fileExists('days.xml')) {
          if (!rename($wtdayfile, $newwtdayfile)) {
              $this->logger->warning("SantaCloud: failed to move existing $wtdayfile... ");
          }
          else {
            $this->logger->debug("SantaCloud: success moving $wtdayfile... ");
          }
        }
        if (!$this->appData->getFolder('backgroundimg')->fileExists('background.jpg')) {
          if (!copy($backgroundimg, $newbackgroundimg)) {
            $this->logger->warning("SantaCloud: failed to copy $backgroundimg... ");
          }
          else {
            $this->logger->debug("SantaCloud: success copy $backgroundimg... ");
          }
        }
        if (!$this->appData->getFolder('img')->fileExists('xmas-cookies.png')) {          
          if (!copy($exampleimg, $newexampleimg)) {
            $this->logger->warning("SantaCloud: failed to copy $exampleimg... ");
          }
          else {
            $this->logger->debug("SantaCloud: success copy $exampleimg... ");
          }
        }                
         return;
       }
   }

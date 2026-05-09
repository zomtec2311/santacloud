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
return [
  'routes' => [
      ['name' => 'page#index', 'url' => '/', 'verb' => 'GET'],
      ['name' => 'Day#getday', 'url' => '/day/{day}', 'verb' => 'GET'],
      ['name' => 'Day#previewday', 'url' => '/previewday/{day}', 'verb' => 'GET'],
      ['name' => 'Day#setParam', 'url' => '/ajax/{who}/{wert}', 'verb' => 'GET'],
      ['name' => 'Day#xmlcontent', 'url' => '/ajax/xmlcontent', 'verb' => 'GET'],
      ['name' => 'Day#dayxmlcontent', 'url' => '/editday/{day}', 'verb' => 'GET'],
      ['name' => 'Day#savedayxmlcontent', 'url' => '/saveday', 'verb' => 'POST'],
      ['name' => 'Day#saveowntext', 'url' => '/saveowntext', 'verb' => 'POST'],
      ['name' => 'Day#getParam', 'url' => '/getparam/{who}', 'verb' => 'GET'],
      ['name' => 'Day#isAdmin', 'url' => '/isadmin', 'verb' => 'GET'],
      ['name' => 'image#get', 'url' => '/image/{filename}', 'verb' => 'GET'],
      ['name' => 'image#bgget', 'url' => '/bgimage/{filename}', 'verb' => 'GET'],
      ['name' => 'image#dbget', 'url' => '/dbimage/{filename}', 'verb' => 'GET'],
      ['name' => 'image#getimages', 'url' => '/getimages', 'verb' => 'GET'],
      ['name' => 'image#getbgimages', 'url' => '/getbgimages', 'verb' => 'GET'],
      ['name' => 'image#getdbimages', 'url' => '/getdbimages', 'verb' => 'GET'],
      ['name' => 'image#getbg', 'url' => '/ajax/getbg', 'verb' => 'GET'],
      ['name' => 'image#getdb', 'url' => '/ajax/getdb', 'verb' => 'GET'],
      ['name' => 'image#deleteimage', 'url' => '/deleteimage', 'verb' => 'DELETE'],
      ['name' => 'image#deletebgimage', 'url' => '/deletebgimage', 'verb' => 'DELETE'],
      ['name' => 'image#deletedbimage', 'url' => '/deletedbimage', 'verb' => 'DELETE'],
      ['name' => 'image#setbg', 'url' => '/setbgimage/{imagePath}', 'verb' => 'GET'],
      ['name' => 'image#setdb', 'url' => '/setdbimage/{imagePath}', 'verb' => 'GET'],
      ['name' => 'image#uploadFile', 'url' => '/upload-bg-image', 'verb' => 'POST', 'defaults' => ['action' => 'uploadFile', 'insecure' => true,],],
      ['name' => 'image#uploadFileDB', 'url' => '/upload-db-image', 'verb' => 'POST', 'defaults' => ['action' => 'uploadFile', 'insecure' => true,],],
      ['name' => 'Api#uploadFile', 'url' => '/upload', 'verb' => 'POST', 'defaults' => ['action' => 'uploadFile', 'insecure' => true,],],
  ]
];


    
        
    


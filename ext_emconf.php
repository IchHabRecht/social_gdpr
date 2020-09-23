<?php

/***************************************************************
 * Extension Manager/Repository config file for ext "social_gdpr".
 *
 * Auto generated 23-09-2020 15:44
 *
 * Manual updates:
 * Only the data in the array - everything else is removed by next
 * writing. "version" and "dependencies" must not be touched!
 ***************************************************************/

$EM_CONF[$_EXTKEY] = array (
  'title' => 'Social GDPR',
  'description' => 'Show social media after user confirmation',
  'category' => 'fe',
  'author' => 'Nicole Cordes',
  'author_email' => 'typo3@cordes.co',
  'author_company' => 'biz-design',
  'state' => 'stable',
  'uploadfolder' => 0,
  'createDirs' => '',
  'clearcacheonload' => 0,
  'version' => '2.0.2',
  'constraints' => 
  array (
    'depends' => 
    array (
      'typo3' => '8.7.0-10.4.99',
    ),
    'conflicts' => 
    array (
    ),
    'suggests' => 
    array (
    ),
  ),
  '_md5_values_when_last_written' => 'a:41:{s:9:"ChangeLog";s:4:"3c09";s:7:"LICENSE";s:4:"b234";s:9:"README.md";s:4:"f2f3";s:13:"composer.json";s:4:"a7a8";s:13:"composer.lock";s:4:"97bd";s:12:"ext_icon.png";s:4:"e7f1";s:17:"ext_localconf.php";s:4:"9cf2";s:16:"phpunit.xml.dist";s:4:"041c";s:24:"sonar-project.properties";s:4:"854f";s:43:"Classes/Handler/GoogleMapsIframeHandler.php";s:4:"1c5d";s:36:"Classes/Handler/HandlerInterface.php";s:4:"c0d1";s:25:"Classes/Handler/Match.php";s:4:"789f";s:32:"Classes/Handler/VimeoHandler.php";s:4:"48f0";s:34:"Classes/Handler/YoutubeHandler.php";s:4:"a557";s:40:"Classes/Hooks/ContentPostProcessHook.php";s:4:"2197";s:37:"Classes/Service/VimeoImageService.php";s:4:"b8aa";s:39:"Classes/Service/YoutubeImageService.php";s:4:"ee7c";s:42:"Configuration/TCA/Overrides/tt_content.php";s:4:"3141";s:38:"Configuration/TypoScript/constants.txt";s:4:"b455";s:34:"Configuration/TypoScript/setup.txt";s:4:"8bf4";s:27:"Resources/Private/.htaccess";s:4:"4adb";s:43:"Resources/Private/Language/de.locallang.xlf";s:4:"64eb";s:46:"Resources/Private/Language/de.locallang_be.xlf";s:4:"83f8";s:40:"Resources/Private/Language/locallang.xlf";s:4:"7c6a";s:43:"Resources/Private/Language/locallang_be.xlf";s:4:"c3d2";s:49:"Resources/Private/Templates/GoogleMapsIframe.html";s:4:"2328";s:38:"Resources/Private/Templates/Vimeo.html";s:4:"9a89";s:40:"Resources/Private/Templates/Youtube.html";s:4:"02fc";s:31:"Resources/Public/Css/styles.css";s:4:"4dc9";s:36:"Resources/Public/Icons/Extension.svg";s:4:"9f9a";s:39:"Resources/Public/Images/maps_button.svg";s:4:"d27b";s:39:"Resources/Public/Images/play_button.svg";s:4:"b4f5";s:47:"Resources/Public/Images/youtube_play_button.svg";s:4:"2aed";s:37:"Resources/Public/JavaScript/decode.js";s:4:"921d";s:27:"Tests/Fixtures/Content.html";s:4:"81eb";s:52:"Tests/Functional/Fixtures/TypoScript/social_gdpr.txt";s:4:"6f43";s:53:"Tests/Functional/Hooks/ContentPostProcessHookTest.php";s:4:"81df";s:40:"Tests/Unit/Handler/AbstractVideoTest.php";s:4:"ff7a";s:50:"Tests/Unit/Handler/GoogleMapsIframeHandlerTest.php";s:4:"e360";s:39:"Tests/Unit/Handler/VimeoHandlerTest.php";s:4:"be55";s:41:"Tests/Unit/Handler/YoutubeHandlerTest.php";s:4:"6fd3";}',
);


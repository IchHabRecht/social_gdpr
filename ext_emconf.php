<?php

/***************************************************************
 * Extension Manager/Repository config file for ext "social_gdpr".
 *
 * Auto generated 06-05-2020 13:09
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
  'version' => '1.0.1',
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
  '_md5_values_when_last_written' => 'a:34:{s:9:"ChangeLog";s:4:"8bfb";s:7:"LICENSE";s:4:"b234";s:9:"README.md";s:4:"f2f3";s:13:"composer.json";s:4:"0063";s:13:"composer.lock";s:4:"97bd";s:12:"ext_icon.png";s:4:"e7f1";s:17:"ext_localconf.php";s:4:"3137";s:16:"phpunit.xml.dist";s:4:"041c";s:24:"sonar-project.properties";s:4:"ceba";s:36:"Classes/Handler/HandlerInterface.php";s:4:"49a6";s:25:"Classes/Handler/Match.php";s:4:"6f99";s:32:"Classes/Handler/VimeoHandler.php";s:4:"e242";s:34:"Classes/Handler/YoutubeHandler.php";s:4:"45da";s:40:"Classes/Hooks/ContentPostProcessHook.php";s:4:"8801";s:37:"Classes/Service/VimeoImageService.php";s:4:"c0b4";s:39:"Classes/Service/YoutubeImageService.php";s:4:"d011";s:42:"Configuration/TCA/Overrides/tt_content.php";s:4:"ad18";s:38:"Configuration/TypoScript/constants.txt";s:4:"b455";s:34:"Configuration/TypoScript/setup.txt";s:4:"8bf4";s:27:"Resources/Private/.htaccess";s:4:"4adb";s:43:"Resources/Private/Language/de.locallang.xlf";s:4:"013f";s:46:"Resources/Private/Language/de.locallang_be.xlf";s:4:"83f8";s:40:"Resources/Private/Language/locallang.xlf";s:4:"dedf";s:43:"Resources/Private/Language/locallang_be.xlf";s:4:"c3d2";s:38:"Resources/Private/Templates/Vimeo.html";s:4:"d0e5";s:40:"Resources/Private/Templates/Youtube.html";s:4:"c847";s:31:"Resources/Public/Css/styles.css";s:4:"2c06";s:36:"Resources/Public/Icons/Extension.svg";s:4:"9f9a";s:39:"Resources/Public/Images/play_button.svg";s:4:"b4f5";s:47:"Resources/Public/Images/youtube_play_button.svg";s:4:"2aed";s:37:"Resources/Public/JavaScript/decode.js";s:4:"ba1a";s:40:"Tests/Unit/Handler/AbstractVideoTest.php";s:4:"8b48";s:39:"Tests/Unit/Handler/VimeoHandlerTest.php";s:4:"39da";s:41:"Tests/Unit/Handler/YoutubeHandlerTest.php";s:4:"7fdc";}',
);

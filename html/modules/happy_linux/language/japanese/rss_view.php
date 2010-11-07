<?php
// $Id: rss_view.php,v 1.1 2010/11/07 14:59:15 ohwada Exp $

// 2007-05-12 K.OHWADA
// this is new file
// move from rssc module

//=========================================================
// Happy Linux Framework Module
// 2007-05-06 K.OHWADA
// 有朋自遠方来
//=========================================================

// common
define('_HAPPY_LINUX_VIEW_SITE_TITLE','サイト名');
define('_HAPPY_LINUX_VIEW_SITE_LINK', 'サイトURL');
define('_HAPPY_LINUX_VIEW_SITE_DESCRIPTION', 'サイトの説明');
define('_HAPPY_LINUX_VIEW_SITE_PUBLISHED', 'サイト公開日');
define('_HAPPY_LINUX_VIEW_SITE_UPDATED',   'サイト更新日');
define('_HAPPY_LINUX_VIEW_SITE_DATE',      'サイト作成日');
define('_HAPPY_LINUX_VIEW_SITE_COPYRIGHT', 'サイト著作権');
define('_HAPPY_LINUX_VIEW_SITE_GENERATOR', 'サイト生成元');
define('_HAPPY_LINUX_VIEW_SITE_CATEGORY',  'サイト・カテゴリ');
define('_HAPPY_LINUX_VIEW_SITE_WEBMASTER', 'サイト管理者');
define('_HAPPY_LINUX_VIEW_SITE_LANGUAGE',  'サイト言語');
define('_HAPPY_LINUX_VIEW_TITLE', 'タイトル');
define('_HAPPY_LINUX_VIEW_LINK',  'URL');
define('_HAPPY_LINUX_VIEW_DESCRIPTION', '説明'); 
define('_HAPPY_LINUX_VIEW_SUMMARY', '要約'); 
define('_HAPPY_LINUX_VIEW_CONTENT', '内容');
define('_HAPPY_LINUX_VIEW_PUBLISHED', '公開日');
define('_HAPPY_LINUX_VIEW_UPDATED',   '更新日');
define('_HAPPY_LINUX_VIEW_CREATED', '作成日');
define('_HAPPY_LINUX_VIEW_CATEGORY',  'カテゴリ');
define('_HAPPY_LINUX_VIEW_RIGHTS', '著作権');
define('_HAPPY_LINUX_VIEW_SOURCE', '情報源');
define('_HAPPY_LINUX_VIEW_AUTHOR_NAME', '作者名');
define('_HAPPY_LINUX_VIEW_AUTHOR_URI',  '作者URL');
define('_HAPPY_LINUX_VIEW_AUTHOR_EMAIL','作者メール');
define('_HAPPY_LINUX_VIEW_IMAGE_TITLE',  '画像タイトル');
define('_HAPPY_LINUX_VIEW_IMAGE_URL',    '画像URL');
define('_HAPPY_LINUX_VIEW_ENCLOSURE_URL',    '同封ファイル Url');
define('_HAPPY_LINUX_VIEW_ENCLOSURE_TYPE',   '同封ファイル Type');
define('_HAPPY_LINUX_VIEW_ENCLOSURE_LENGTH', '同封ファイル Length');

// RSS
define('_HAPPY_LINUX_VIEW_RSS_SITE_TITLE', _HAPPY_LINUX_VIEW_SITE_TITLE);
define('_HAPPY_LINUX_VIEW_RSS_SITE_LINK',  _HAPPY_LINUX_VIEW_SITE_LINK);
define('_HAPPY_LINUX_VIEW_RSS_SITE_DESCRIPTION',   _HAPPY_LINUX_VIEW_SITE_DESCRIPTION);
define('_HAPPY_LINUX_VIEW_RSS_SITE_LASTBUILDDATE', _HAPPY_LINUX_VIEW_SITE_UPDATED);
define('_HAPPY_LINUX_VIEW_RSS_SITE_PUBDATE',       _HAPPY_LINUX_VIEW_SITE_PUBLISHED);
define('_HAPPY_LINUX_VIEW_RSS_SITE_GENERATOR', _HAPPY_LINUX_VIEW_SITE_GENERATOR);
define('_HAPPY_LINUX_VIEW_RSS_SITE_CATEGORY',  _HAPPY_LINUX_VIEW_SITE_CATEGORY);
define('_HAPPY_LINUX_VIEW_RSS_SITE_WEBMASTER', _HAPPY_LINUX_VIEW_SITE_WEBMASTER);
define('_HAPPY_LINUX_VIEW_RSS_SITE_LANGUAGE',  _HAPPY_LINUX_VIEW_SITE_LANGUAGE);
define('_HAPPY_LINUX_VIEW_RSS_SITE_COPYRIGHT', _HAPPY_LINUX_VIEW_SITE_COPYRIGHT);
define('_HAPPY_LINUX_VIEW_RSS_SITE_MANAGINGEDITOR', 'サイト編集者');
define('_HAPPY_LINUX_VIEW_RSS_SITE_DOCS','サイト文書');
define('_HAPPY_LINUX_VIEW_RSS_SITE_CLOUD', 'サイト・クラウド');
define('_HAPPY_LINUX_VIEW_RSS_SITE_TTL', 'サイト生存時間');
define('_HAPPY_LINUX_VIEW_RSS_SITE_RATING', 'サイト評価');
define('_HAPPY_LINUX_VIEW_RSS_SITE_TEXTINPUT', 'サイト・テキスト入力');
define('_HAPPY_LINUX_VIEW_RSS_SITE_SKIPHOURS', 'サイト・スキップ時間');
define('_HAPPY_LINUX_VIEW_RSS_SITE_SKIPDAYS',  'サイト・スキップ日数');
define('_HAPPY_LINUX_VIEW_RSS_IMAGE_TITLE',  _HAPPY_LINUX_VIEW_IMAGE_TITLE);
define('_HAPPY_LINUX_VIEW_RSS_IMAGE_URL',    _HAPPY_LINUX_VIEW_IMAGE_URL);
define('_HAPPY_LINUX_VIEW_RSS_IMAGE_WIDTH',  '画像の幅');
define('_HAPPY_LINUX_VIEW_RSS_IMAGE_HEIGHT', '画像の高さ');
define('_HAPPY_LINUX_VIEW_RSS_IMAGE_LINK',  _HAPPY_LINUX_VIEW_SITE_LINK);
define('_HAPPY_LINUX_VIEW_RSS_TITLE',_HAPPY_LINUX_VIEW_TITLE);
define('_HAPPY_LINUX_VIEW_RSS_LINK', _HAPPY_LINUX_VIEW_LINK);
define('_HAPPY_LINUX_VIEW_RSS_DESCRIPTION', _HAPPY_LINUX_VIEW_DESCRIPTION); 
define('_HAPPY_LINUX_VIEW_RSS_PUBDATE',  _HAPPY_LINUX_VIEW_PUBLISHED);
define('_HAPPY_LINUX_VIEW_RSS_CATEGORY', _HAPPY_LINUX_VIEW_CATEGORY);
define('_HAPPY_LINUX_VIEW_RSS_SOURCE',   _HAPPY_LINUX_VIEW_SOURCE);
define('_HAPPY_LINUX_VIEW_RSS_GUID',   'RSS guid');
define('_HAPPY_LINUX_VIEW_RSS_AUTHOR', '作者');
define('_HAPPY_LINUX_VIEW_RSS_COMMENTS','コメント');
define('_HAPPY_LINUX_VIEW_RSS_ENCLOSURE', '同封');

// RDF
define('_HAPPY_LINUX_VIEW_RDF_SITE_TITLE', _HAPPY_LINUX_VIEW_SITE_TITLE);
define('_HAPPY_LINUX_VIEW_RDF_SITE_LINK',  _HAPPY_LINUX_VIEW_SITE_LINK);
define('_HAPPY_LINUX_VIEW_RDF_SITE_DESCRIPTION', _HAPPY_LINUX_VIEW_SITE_DESCRIPTION);
define('_HAPPY_LINUX_VIEW_RDF_SITE_PUBLISHER',   _HAPPY_LINUX_VIEW_SITE_WEBMASTER);
define('_HAPPY_LINUX_VIEW_RDF_SITE_RIGHT', _HAPPY_LINUX_VIEW_SITE_COPYRIGHT);
define('_HAPPY_LINUX_VIEW_RDF_SITE_DATE',  _HAPPY_LINUX_VIEW_SITE_PUBLISHED );
define('_HAPPY_LINUX_VIEW_RDF_SITE_TEXTINPUT', 'サイト・テキスト入力');
define('_HAPPY_LINUX_VIEW_RDF_SITE_IMAGE',  'サイト画像');
define('_HAPPY_LINUX_VIEW_RDF_IMAGE_TITLE', _HAPPY_LINUX_VIEW_IMAGE_TITLE);
define('_HAPPY_LINUX_VIEW_RDF_IMAGE_URL',   _HAPPY_LINUX_VIEW_IMAGE_URL);
define('_HAPPY_LINUX_VIEW_RDF_IMAGE_LINK',  _HAPPY_LINUX_VIEW_SITE_LINK);
define('_HAPPY_LINUX_VIEW_RDF_TITLE',_HAPPY_LINUX_VIEW_TITLE);
define('_HAPPY_LINUX_VIEW_RDF_LINK', _HAPPY_LINUX_VIEW_LINK);
define('_HAPPY_LINUX_VIEW_RDF_DESCRIPTION', _HAPPY_LINUX_VIEW_DESCRIPTION); 
define('_HAPPY_LINUX_VIEW_RDF_TEXTINPUT', 'テキスト入力');

// ATOM
define('_HAPPY_LINUX_VIEW_ATOM_SITE_TITLE', _HAPPY_LINUX_VIEW_SITE_TITLE);
define('_HAPPY_LINUX_VIEW_ATOM_SITE_LINK',  _HAPPY_LINUX_VIEW_SITE_LINK);
define('_HAPPY_LINUX_VIEW_ATOM_SITE_PUBLISHED', _HAPPY_LINUX_VIEW_SITE_PUBLISHED);
define('_HAPPY_LINUX_VIEW_ATOM_SITE_UPDATED',   _HAPPY_LINUX_VIEW_SITE_UPDATED);
define('_HAPPY_LINUX_VIEW_ATOM_SITE_RIGHTS',    _HAPPY_LINUX_VIEW_SITE_COPYRIGHT);
define('_HAPPY_LINUX_VIEW_ATOM_SITE_GENERATOR', _HAPPY_LINUX_VIEW_SITE_GENERATOR);
define('_HAPPY_LINUX_VIEW_ATOM_SITE_CATEGORY',  _HAPPY_LINUX_VIEW_SITE_CATEGORY);
define('_HAPPY_LINUX_VIEW_ATOM_SITE_LINK_ALTERNATE', _HAPPY_LINUX_VIEW_SITE_LINK);
define('_HAPPY_LINUX_VIEW_ATOM_SITE_LINK_SELF', 'ATOM自身のURL');
define('_HAPPY_LINUX_VIEW_ATOM_SITE_ID','サイトID');
define('_HAPPY_LINUX_VIEW_ATOM_SITE_CONTRIBUTOR','サイト貢献者');
define('_HAPPY_LINUX_VIEW_ATOM_SITE_SUBTITLE','サイト副題');
define('_HAPPY_LINUX_VIEW_ATOM_SITE_ICON', 'サイト・アイコン');
define('_HAPPY_LINUX_VIEW_ATOM_SITE_LOGO', 'サイト・ロゴ');
define('_HAPPY_LINUX_VIEW_ATOM_SITE_SOURCE', 'サイト情報源');
define('_HAPPY_LINUX_VIEW_ATOM_SITE_AUTHOR_NAME', _HAPPY_LINUX_VIEW_SITE_WEBMASTER);
define('_HAPPY_LINUX_VIEW_ATOM_SITE_AUTHOR_URI',  '管理者URL');
define('_HAPPY_LINUX_VIEW_ATOM_SITE_AUTHOR_EMAIL','管理者メール');
define('_HAPPY_LINUX_VIEW_ATOM_TITLE', _HAPPY_LINUX_VIEW_TITLE);
define('_HAPPY_LINUX_VIEW_ATOM_LINK',  _HAPPY_LINUX_VIEW_LINK);
define('_HAPPY_LINUX_VIEW_ATOM_PUBLISHED', _HAPPY_LINUX_VIEW_PUBLISHED);
define('_HAPPY_LINUX_VIEW_ATOM_UPDATED',   _HAPPY_LINUX_VIEW_UPDATED);
define('_HAPPY_LINUX_VIEW_ATOM_SUMMARY',  _HAPPY_LINUX_VIEW_SUMMARY); 
define('_HAPPY_LINUX_VIEW_ATOM_CONTENT',  _HAPPY_LINUX_VIEW_CONTENT);
define('_HAPPY_LINUX_VIEW_ATOM_CATEGORY', _HAPPY_LINUX_VIEW_CATEGORY);
define('_HAPPY_LINUX_VIEW_ATOM_RIGHTS',   _HAPPY_LINUX_VIEW_RIGHTS);
define('_HAPPY_LINUX_VIEW_ATOM_SOURCE',   _HAPPY_LINUX_VIEW_SOURCE);
define('_HAPPY_LINUX_VIEW_ATOM_ID','ATOM id');
define('_HAPPY_LINUX_VIEW_ATOM_CONTRIBUTOR','貢献者');
define('_HAPPY_LINUX_VIEW_ATOM_AUTHOR_NAME', _HAPPY_LINUX_VIEW_AUTHOR_NAME);
define('_HAPPY_LINUX_VIEW_ATOM_AUTHOR_URI',  _HAPPY_LINUX_VIEW_AUTHOR_URI);
define('_HAPPY_LINUX_VIEW_ATOM_AUTHOR_EMAIL',_HAPPY_LINUX_VIEW_AUTHOR_EMAIL);
define('_HAPPY_LINUX_VIEW_ATOM_CONTRIBUTOR_NAME', '貢献者');
define('_HAPPY_LINUX_VIEW_ATOM_CONTRIBUTOR_URI',  '貢献者URL');
define('_HAPPY_LINUX_VIEW_ATOM_CONTRIBUTOR_EMAIL','貢献者メール');

// Dublin Core
define('_HAPPY_LINUX_VIEW_DC_TITLE',_HAPPY_LINUX_VIEW_TITLE);
define('_HAPPY_LINUX_VIEW_DC_DESCRIPTION', _HAPPY_LINUX_VIEW_DESCRIPTION); 
define('_HAPPY_LINUX_VIEW_DC_RIGHTS', _HAPPY_LINUX_VIEW_RIGHTS);
define('_HAPPY_LINUX_VIEW_DC_PUBLISHER', '発行者');
define('_HAPPY_LINUX_VIEW_DC_CREATOR', '著者');
define('_HAPPY_LINUX_VIEW_DC_DATE', '作成日');
define('_HAPPY_LINUX_VIEW_DC_FORMAT', '形式');
define('_HAPPY_LINUX_VIEW_DC_RELATION', '関係');
define('_HAPPY_LINUX_VIEW_DC_IDENTIFIER', 'ID');
define('_HAPPY_LINUX_VIEW_DC_COVERAGE', '範囲');
define('_HAPPY_LINUX_VIEW_DC_AUDIENCE', '観客');
define('_HAPPY_LINUX_VIEW_DC_SUBJECT', '主題');
define('_HAPPY_LINUX_VIEW_CONTENT_ENCODED', _HAPPY_LINUX_VIEW_CONTENT);

// require / option
define('_HAPPY_LINUX_VIEW_SITE_TAG','サイト タグ');
define('_HAPPY_LINUX_VIEW_SITE_LOGO','サイトのロゴ画像');
define('_HAPPY_LINUX_VIEW_RSS_ATOM_REQUIRE', 'RSS/ATOMの必須項目です');
define('_HAPPY_LINUX_VIEW_RSS_REQUIRE','RSSの必須項目です');
define('_HAPPY_LINUX_VIEW_ATOM_REQUIRE','ATOMの必須項目です');
define('_HAPPY_LINUX_VIEW_OPTION','任意項目です');
define('_HAPPY_LINUX_VIEW_IMAGE_TOO_BIG','画像サイズが規格よりも大きい');
define('_HAPPY_LINUX_VIEW_IMAGE_MAX',    '画像サイズの最大値');

?>
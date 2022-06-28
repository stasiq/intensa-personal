<?php
$_SERVER["DOCUMENT_ROOT"] = realpath(dirname(__FILE__) . "/..");
$DOCUMENT_ROOT = $_SERVER["DOCUMENT_ROOT"];

define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
define("BX_CRONTAB", true);
define('BX_NO_ACCELERATOR_RESET', true);

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

class imageMapGen
{
    public static $google_link;
    public static $google_img;
    public static $google_desc;
    public static $iblockId;

    public static function xmlGen()
    {
        if (file_exists('../upload/sitemap-image1.xml')) {
            unlink('../upload/sitemap-image1.xml');
        }

        self::$iblockId = \COption::GetOptionString('main', 'iblock_id_catalog', false);
        $dom = new domDocument('1.0', 'utf-8');
        $xml = $dom->createElement('xml');
        $xml->setAttributeNS(null, 'version', '1.0');
        $xml->setAttributeNS(null, 'encoding', 'utf-8');
        $dom->appendChild($xml);
        $urlset = $dom->createElement('urlset');
        $urlset->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
        $urlset->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:image', 'http://www.google.com/schemas/sitemap-image/1.1');

        $arSelect = ['ID', 'NAME', 'DETAIL_PAGE_URL', 'PREVIEW_PICTURE', 'DETAIL_PICTURE'];
        $arFilter = ['IBLOCK_ID' => self::$iblockId, 'INCLUDE_SUBSECTIONS' => 'Y']; //Выбор инфоблока
        $rsElement = CIBlockElement::GetList(['NAME' => 'ASC'], $arFilter, false, [], $arSelect);

        while ($obElement = $rsElement->GetNextElement()) {

            $arItem = $obElement->GetFields();
            $arItem['PROPERTIES'] = $obElement->GetProperties();

            if ($arItem['DETAIL_PICTURE']) {
                $arItem['PROPERTIES'] = $obElement->GetProperties();
                self::$google_link = 'https://' . SITE_SERVER_NAME . $arItem['DETAIL_PAGE_URL'];
                self::$google_desc = $arItem['PROPERTIES']['CML2_ARTICLE']['VALUE'] . ' ' . $arItem['PROPERTIES']['NAIMENOVANIE_DLYA_SAYTA']['VALUE'];


                if ($arItem['PROPERTIES']['MORE_PHOTO']['VALUE']) {
                    foreach ($arItem['PROPERTIES']['MORE_PHOTO']['VALUE'] as $img) {

                        if (CFile::GetPath($img)) {
                            self::$google_img[] = 'https://' . SITE_SERVER_NAME . CFile::GetPath($img);

                        }
                    }
                };

                if (CFile::GetPath($arItem['DETAIL_PICTURE'])) {
                    self::$google_img[] = 'https://' . SITE_SERVER_NAME . CFile::GetPath($arItem['DETAIL_PICTURE']);

                };

                $url = $dom->createElement('url');

                foreach (self::$google_img as $key => $img) {
                    $image_loc = $dom->createElement('image:loc', $img);
                    $loc = $dom->createElement('loc', self::$google_link);
                    $url->appendChild($loc);
                    $image = $dom->createElement('image:image');
                    $image->appendChild($image_loc);
                    $image_cap = $dom->createElement('image:caption', self::$google_desc);
                    $image->appendChild($image_cap);
                    $image_title = $dom->createElement('image:title', self::$google_desc);
                    $image->appendChild($image_title);
                    $url->appendChild($image);
                    unset(self::$google_img[$key]);
                }

            }
            $urlset->appendChild($url);

        }

        $xml->appendChild($urlset);
        $dom->save('../upload/sitemap-image1.xml');

        if (file_exists('../upload/sitemap-image2.xml')) {
            unlink('../upload/sitemap-image2.xml');
        }
        self::getImages();
    }

    public
    static function getImages()
    {
        $dom = new domDocument("1.0", 'utf-8');
        $xml = $dom->createElement("xml");
        $xml->setAttributeNS(null, 'version', '1.0');
        $xml->setAttributeNS(null, 'encoding', 'utf-8');
        $dom->appendChild($xml);
        $urlset = $dom->createElement("urlset");
        $urlset->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
        $urlset->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:image', 'http://www.google.com/schemas/sitemap-image/1.1');

        // Изображения инфоблоков.
        $arSelect = ["ID", "NAME", "DETAIL_PAGE_URL", "PREVIEW_PICTURE", "DETAIL_PICTURE"];
        $arFilter = ["!IBLOCK_ID" => self::$iblockId, "INCLUDE_SUBSECTIONS" => "Y"]; //Исключение инфоблока каталога
        $rsElement = CIBlockElement::GetList(["NAME" => "ASC"], $arFilter, false, [], $arSelect);
        $arResult["ITEMS"] = [];
        while ($obElement = $rsElement->GetNextElement()) {

            $arItem = $obElement->GetFields();
            if ($arItem['PREVIEW_PICTURE'] || $arItem['DETAIL_PICTURE']) {
                $arItem["PROPERTIES"] = $obElement->GetProperties();
                self::$google_link = 'https://' . SITE_SERVER_NAME;
                self::$google_desc = str_replace('&nbsp;', ' ', $arItem['NAME']);
                self::$google_desc = str_replace(['&mdash;'], '', self::$google_desc);

                if (CFile::GetPath($arItem['PREVIEW_PICTURE'])) {
                    self::$google_img[] = 'https://' . SITE_SERVER_NAME . CFile::GetPath($arItem['PREVIEW_PICTURE']);

                }

                if ($arItem['PROPERTIES']['MORE_PHOTO']['VALUE']) {
                    foreach ($arItem['PROPERTIES']['MORE_PHOTO']['VALUE'] as $img) {
                        if (CFile::GetPath($img)) {
                            self::$google_img[] = 'https://' . SITE_SERVER_NAME . CFile::GetPath($img);
                        }
                    }
                };

                if (CFile::GetPath($arItem['DETAIL_PICTURE'])) {
                    self::$google_img[] = 'https://' . SITE_SERVER_NAME . CFile::GetPath($arItem['DETAIL_PICTURE']);
                };

                foreach (self::$google_img as $key => $img) {
                    $url = $dom->createElement("url");
                    $loc = $dom->createElement("loc", self::$google_link);
                    $url->appendChild($loc);
                    $image = $dom->createElement("image:image");
                    $image_loc = $dom->createElement('image:loc', $img);
                    $image->appendChild($image_loc);
                    $image_cap = $dom->createElement("image:caption", self::$google_desc);
                    $image->appendChild($image_cap);
                    $image_title = $dom->createElement("image:title", self::$google_desc);
                    $image->appendChild($image_title);
                    $url->appendChild($image);
                    unset(self::$google_img[$key]);
                }

                $urlset->appendChild($url);
            };
        };

        $xml->appendChild($urlset);

        //Изображения категорий товаров.
        $arSelect = ['ID', 'NAME', 'DETAIL_PAGE_URL', 'PREVIEW_PICTURE'];
        $arFilter = ['IBLOCK_ID' => self::$iblockId, 'INCLUDE_SUBSECTIONS' => 'Y']; //Выбор инфоблока
        $rsElement = CIBlockElement::GetList(['NAME' => 'ASC'], $arFilter, false, [], $arSelect);

        while ($obElement = $rsElement->GetNextElement()) {

            $arItem = $obElement->GetFields();
            $arItem['PROPERTIES'] = $obElement->GetProperties();
            if ($arItem['PREVIEW_PICTURE']) {
                $arItem['PROPERTIES'] = $obElement->GetProperties();
                self::$google_desc = $arItem['PROPERTIES']['CML2_ARTICLE']['VALUE'] . ' ' . $arItem['PROPERTIES']['NAIMENOVANIE_DLYA_SAYTA']['VALUE'];
                $section = CIBlockSection::GetByID($arItem['IBLOCK_SECTION_ID']);

                if ($sec = $section->GetNext())

                    self::$google_link = 'https://' . SITE_SERVER_NAME . $sec['SECTION_PAGE_URL'];
                self::$google_desc = $arItem['PROPERTIES']['CML2_ARTICLE']['VALUE'] . ' ' . $arItem['PROPERTIES']['NAIMENOVANIE_DLYA_SAYTA']['VALUE'];

                if (CFile::GetPath($arItem['PREVIEW_PICTURE'])) {
                    self::$google_img[] = 'https://' . SITE_SERVER_NAME . CFile::GetPath($arItem['PREVIEW_PICTURE']);

                }

                $url = $dom->createElement('url');

                foreach (self::$google_img as $key => $img) {
                    $image_loc = $dom->createElement('image:loc', $img);
                    $loc = $dom->createElement('loc', self::$google_link);
                    $url->appendChild($loc);
                    $image = $dom->createElement('image:image');
                    $image->appendChild($image_loc);
                    $image_cap = $dom->createElement('image:caption', self::$google_desc);
                    $image->appendChild($image_cap);
                    $image_title = $dom->createElement('image:title', self::$google_desc);
                    $image->appendChild($image_title);
                    $url->appendChild($image);
                    unset(self::$google_img[$key]);

                }

                $urlset->appendChild($url);
            };
        };

        $xml->appendChild($urlset);

        //Изображения статических страниц
        $menu[] = "/.company_bottom.menu.php";
        $menu[] = "/.customers_bottom.menu.php";
        $menu[] = "/.footer1.menu.php";
        $menu[] = "/.footer3.menu.php";
        $menu[] = "/.left.menu.php";
        $menu[] = "/.partners_bottom.menu.php";

        function setStatic($menu)
        {
            $static = [];
            foreach ($menu as $manuItem) {
                require($_SERVER["DOCUMENT_ROOT"] . $manuItem);
                if ($aMenuLinks) {
                    foreach ($aMenuLinks as $menuItem) {
                        if ($menuItem[1]) {
                            $static[] = $menuItem[1];
                        }
                    }

                }
            };
            return $static;
        }

        $static = setStatic($menu);

        foreach ($static as $page) {
            self::$google_link = 'https://' . SITE_SERVER_NAME . $page;
            self::$google_desc = '';
            $page_url = '..' . $page . 'index.php';
            $content = file_get_contents($page_url);
            preg_match_all('/< *img[^>]*src *= *["\']?([^"\']*)/i', $content, $srcs);
            preg_match_all('/< *img[^>]*alt *= *["\']?([^"\']*)/i', $content, $alts);

            foreach ($srcs[1] as $k => $str) {
                $str = str_replace('<?=SITE_TEMPLATE_PATH?>', SITE_TEMPLATE_PATH, $str);
                self::$google_img[] = 'https://' . SITE_SERVER_NAME . $str;
            }
            foreach ($alts[1] as $k => $str) {
                $str = str_replace('<?=SITE_TEMPLATE_PATH?>', SITE_TEMPLATE_PATH, $str);
                self::$google_desc = $str;
            }

            foreach (self::$google_img as $key => $img) {

                $loc = $dom->createElement("loc", self::$google_link);
                $image = $dom->createElement("image:image");
                $image_loc = $dom->createElement('image:loc', $img);
                $url = $dom->createElement("url");
                if (self::$google_desc) {
                    $image_cap = $dom->createElement("image:caption", self::$google_desc);
                    $image->appendChild($image_cap);
                    $image_title = $dom->createElement("image:title", self::$google_desc);
                    $image->appendChild($image_title);
                }
                $url->appendChild($loc);
                $url->appendChild($image);
                $urlset->appendChild($url);
                $image->appendChild($image_loc);

                unset(self::$google_img[$key]);
            }

        }
        $xml->appendChild($urlset);
        $dom->save("../upload/sitemap-image2.xml");
    }
}

imageMapGen::xmlGen();
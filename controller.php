<?php

// see https://documentation.concrete5.org/developers/working-with-pages/single-pages/including-single-pages-and-controllers-in-packages

namespace Concrete\Package\Exporter; //<--must match package name
use Concrete\Core\Page\Single as SinglePage;

defined('C5_EXECUTE') or die('Access Denied.');

use \Concrete\Core\Package\Package;
use \Concrete\Core\Block\BlockType\BlockType;
use Concrete\Core\Page\Theme\Theme;
use Concrete\Core\Page\Page;
use PageType;
use PageTemplate;



class Controller extends Package
{
    protected $pkgHandle = 'exporter'; //<--must match package name
    protected $appVersionRequired = '8.3.2';
    protected $pkgVersion = '0.2.0';

    public function getPackageDescription()
    {
        return t('Experimental XML/CIF exporter');
    }

    public function getPackageName()
    {
        return t('Exporter');
    }

    public function install()
    {
        $pkg = parent::install();
        $this->installPage($pkg);
    }

    public function upgrade () {

      $pkg = Package::getByHandle($this->pkgHandle);

      $exportPage=Page::getByPath('/dashboard/system/export');
      if ($exportPage) {
        $exportPage->delete(); // this works
      }

      $this->installPage($pkg);

      parent::upgrade();

    }

    private function installPage(&$pkg) {
      $rval = SinglePage::add('/dashboard/system/export', $pkg);
    }

}

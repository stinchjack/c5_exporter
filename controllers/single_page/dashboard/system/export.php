<?php

namespace Concrete\Package\Exporter\Controller\SinglePage\Dashboard\System;
use \Concrete\Core\Page\Controller\DashboardPageController;
use Package;
use View;
use Loader;
use Log;
use Concrete\Core\Backup\ContentImporter;
use \Concrete\Core\Page\Template;
use \Concrete\Core\Page\Feed;
use \Concrete\Core\Page\Type\Type;
use \Concrete\Core\Tree\Type\Topic;
use \Concrete\Core\Attribute\Key\CollectionKey as CollectionAttributeKey;
use PageType;
use Page;
use Concrete\Core\Tree\Tree;
// not to be confused with Concrete\Core\Entity\Site\Tree
use Concrete\Core\Support\Facade\Application;

use Concrete\Core\Page\Theme\Theme;
use Concrete\Core\Permission\Access\Entity\Type as AccessEntityType;
use Concrete\Core\User\Group\Group;

class Export extends DashboardPageController
{

    public function on_start() {
      // will be run prior to any URL-based methods.
      parent::on_start();
    }

    public function on_before_render() {
      // will be run after any URL-based methods, but before the page is delivered for rendering.
      parent::on_before_render();
    }

    public function view()
    {

        $this -> setSelectData();

        // dislays a message as part of the internal dashboard template
        /*$this->set('success', 'My success message');*/

    }



    private function getTrees() {
      $app = Application::getFacadeApplication();
      $db = $app->make('database')->connection();
      $r = $db->executeQuery('select treeID from Trees order by treeID asc');
      $trees = [];
      while ($row = $r->fetch()) {
          $tree = Tree::getByID($row['treeID']);
          $trees[] = $tree;
      }
      return $trees;
    }

    private function getGroups() {

      $app = Application::getFacadeApplication();
      $db = $app->make('database')->connection();
      $r = $db->executeQuery('select gID from Groups order by gID asc');
      $groups = [];
      while ($row = $r->fetch()) {
          $group = Group::getByID($row['gID']);
          $groups[] = $group;
      }
      return $groups;
    }

    private function getSiteTree() {
      return Page::getHomePageID()->getSiteTreeObject();
    }


    private function setSelectData() {
      $this->set('accessEntities', AccessEntityType::getList());
      $this->set('groups', $this->getGroups());
      $this->set('pageTypes', PageType::getList());
      $this->set('themes', Theme::getList());
      $this->set('trees', $this->getTrees());


    }

    public function allAccessEntityTypes() {
      $this -> setSelectData();
      $xmlObj = new \SimpleXMLElement('<?xml version="1.0"?><concrete5-cif version="1.0"></concrete5-cif>');
      AccessEntityType::exportList($xmlObj);
      $this->set('xml', $this->formatXML($xmlObj));

    }


    public function allPageTypes () {
      $this -> setSelectData();
      $xmlObj = new \SimpleXMLElement('<?xml version="1.0"?><concrete5-cif version="1.0"></concrete5-cif>');
      PageType::exportList($xmlObj);
      $this->set('xml', $this->formatXML($xmlObj));
    }

    public function group ($id) {
      $this -> setSelectData();
      $group = Group::getByID((int)$id);
      $this->exportObjectAsXML($group);
    }

    public function pageType ($id) {
      $this -> setSelectData();
      $pageType = PageType::getByID((int)$id);
      $this->exportObjectAsXML($pageType);
    }

    public function theme ($id) {
      $this -> setSelectData();
      $tree = Theme::getByID((int)$id);
      $this->exportObjectAsXML($tree);
    }

    public function tree ($id) {
      $this -> setSelectData();
      $tree = Tree::getByID((int)$id);
      $this->exportObjectAsXML($tree);
    }




    /*
      Page::getHomePageID() // <-- get home page ID
    */

    private function exportObjectAsXML($obj) {
      $xmlObj = new \SimpleXMLElement('<?xml version="1.0"?><concrete5-cif version="1.0"></concrete5-cif>');
      $obj->export($xmlObj);
      $this->set('xml', $this->formatXML($xmlObj));
    }

    private function formatXML($xmlObj) {
      /*
      C5's export functions only take SimpleXMLElement as an argument,
      but SimpleXMLElement doesnt do pretty formatting. this method uses
      DOMDocument to convert the XML to a human-readable format.

      see https://stackoverflow.com/questions/1840148/php-simplexml-new-line
      */

      $xml = $xmlObj->asXML();
      $dom = new \DOMDocument();
      $dom->loadXML($xml);
      $dom->formatOutput = true;
      $formattedXML = $dom->saveXML();
      return htmlspecialchars($formattedXML);
    }

}

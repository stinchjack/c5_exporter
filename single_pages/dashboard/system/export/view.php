<!--
There is only one template file for the single pagecontroller
Any call to different methods e.g. edit(), add() from the Controller
all end up rendering here.

see https://www.concrete5.org/community/forums/customizing_c5/single-pages-and-controller-methods
-->

<?php

defined('C5_EXECUTE') or die('Access Denied.');


// $view is of tpye Concrete\Core\Page\View\PageView
?>
<ul>



  <li>
    <h3>Access Entities</h3>
    <ul>
      <li>
          <a href="<?=$view->action('allAccessEntityTypes');?>">Export all AccessEntityTypes</a>
      </li>
    </ul>
  </li>

  <li>
    <h3>Groups</h3>
    <ul>
<?php
foreach ($groups as $group) {
  print '<li>Name: '.$group->getGroupName().' ID: '.$group->getGroupID().'
    <a href="'.$view->action('group', $group->getGroupID()) .'" >Export </a> </li>';
}
?>

    </ul>
  </li>


  <li>
    <h3>Page Types</h3>
    <ul>
      <li>
          <a href="<?=$view->action('allPageTypes');?>">Export all PageTypes</a>
      </li>
<?php
foreach ($pageTypes as $pageType) {
  print '<li>Name: '.$pageType->getPageTypeName().' Handle: '.$pageType->getPageTypeHandle().'  ID: '.$pageType->getPageTypeID().'
    <a href="'.$view->action('pageType', $pageType->getPageTypeID()) .'" >Export </a> </li>';
}
?>

    </ul>
  </li>

  <li> <h3>Trees</h3>
  <ul>
<?php
foreach ($trees as $tree) {
  print '<li>Name: '.$tree->getTreeName().' Handle: '.$tree->getTreeTypeHandle().'  ID: '.$tree->getTreeTypeID() .'
    <a href="'.$view->action('tree', $tree->getTreeTypeID()) .'" >Export </a> </li>';
}
?>

    </ul>
  </li>

  <li> <h3>Themes</h3>
  <ul>
<?php
foreach ($themes as $theme) {
  print '<li>Name: '.$theme->getThemeName().' Handle: '.$theme->getThemeHandle().'  ID: '.$theme->getThemeID() .'
    <a href="'.$view->action('theme', $theme->getThemeID()) .'" >Export </a> </li>';
} ?>

   </ul>
  </li>
</ul>

<?php
if (isset($xml)) {
  print "<pre>$xml</pre>";
}


?>

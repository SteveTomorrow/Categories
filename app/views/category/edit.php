<?php 
function populateParentCategories($categories, $parent_id, $prefix = '') {
    $options = '';
    foreach ($categories as $category) {
      if ($category['id'] !== $parent_id && !isDescendant($category['id'], $parent_id, $categories)) {
        $options .= '<option value="' . $category['id'] . '">' . $prefix . $category['name'] . '</option>';
        $options .= populateParentCategories($categories, $parent_id, $prefix . '--');
      }
    }
    return $options;
  }
  
  function isDescendant($child_id, $parent_id, $categories) {
    $category = getCategoryById($child_id, $categories);
    if ($category['parent_id'] === $parent_id) {
      return true;
    }
    if ($category['parent_id']) {
      return isDescendant($category['parent_id'], $parent_id, $categories);
    }
    return false;
  }
  
  function getCategoryById($id, $categories) {
    foreach ($categories as $category) {
      if ($category['id'] === $id) {
        return $category;
      }
    }
    return null;
  }

  $categories_json = json_encode($categories);

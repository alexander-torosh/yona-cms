'use strict';

var TreeCategories = function () {
  var self = this;

  self.init = function () {
    $('.sortable').nestedSortable({
      handle: 'div',
      items: 'li',
      toleranceElement: '> div'
    });
  }
};

$(function () {

  var treeCategoriesElement = document.getElementById('tree-categories');
  if (treeCategoriesElement) {
    console.log(treeCategoriesElement);

    var treeCategories = new TreeCategories();
    treeCategories.init();
  }

});
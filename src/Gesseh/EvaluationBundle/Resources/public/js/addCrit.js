$(document).ready(function() {
  var $container = $('#gesseh_evaluationbundle_evalformtype_criterias');

  function add_crit() {
    index = $container.children().length;
    $container.append(
      $($container.attr('data-prototype').replace(/\$\$name\$\$/g, index))
    );
  }

  $('#add_crit').click(function() {
    add_crit();
  });
});

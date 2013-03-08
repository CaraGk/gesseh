$(document).ready(function() {

  function add_sub($container_id) {
    var $container = $($container_id);
    index = $container.children().length;
    $container.append(
      $($container.attr('data-prototype').replace(/__name__/g, index))
    );
  }

  function rm_sub($container_id) {
    var $container = $($container_id);
    index = $container.children().length;
    $container.children().last().remove();
  }

  $('#add_dpt').click(function() {
    add_sub('#gesseh_corebundle_hospitaltype_departments');
  });
  $('#rm_dpt').click(function() {
    rm_sub('#gesseh_corebundle_hospitaltype_departments');
  })

  $('#add_crit').click(function() {
    add_sub('#gesseh_evaluationbundle_evalformtype_criterias');
  });
  $('#rm_crit').click(function() {
    rm_sub('#gesseh_evaluationbundle_evalformtype_criterias');
  });
});

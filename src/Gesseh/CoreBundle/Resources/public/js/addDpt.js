$(document).ready(function() {
  var $container = $('#gesseh_corebundle_hospitaltype_departments');
  
  function add_dpt() {
    index = $container.children().length;
    $container.append(
      $($container.attr('data-prototype').replace(/\$\$name\$\$/g, index))
    );
  }
  
  $('#add_dpt').click(function() {
    add_dpt();
  });
});
